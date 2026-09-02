<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $this->ensurePermissionsExist();

        $query = User::where('role', 'super_admin')
            ->with(['roles'])
            ->withCount([
                'assignedTickets as total_assigned_tickets',
                'assignedTickets as active_tickets_count' => function ($q) {
                    $q->whereIn('status', ['open', 'in_progress', 'pending_reply']);
                },
                'assignedTickets as resolved_tickets_count' => function ($q) {
                    $q->whereIn('status', ['resolved', 'closed']);
                }
            ]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('designation', 'LIKE', "%{$search}%");
            });
        }

        $staffMembers = $query->orderBy('id', 'asc')->get();

        $roles = Role::with(['permissions', 'users'])->orderBy('id', 'asc')->get();
        $permissions = Permission::all()->groupBy('category');

        $stats = [
            'total_staff' => User::where('role', 'super_admin')->count(),
            'active_staff' => User::where('role', 'super_admin')->where(function($q){
                $q->whereNull('status')->orWhere('status', 'active');
            })->count(),
            'total_roles' => Role::count(),
            'total_assigned' => \App\Models\Ticket::whereNotNull('assigned_to')->count(),
            'unassigned_tickets' => \App\Models\Ticket::whereNull('assigned_to')->whereIn('status', ['open', 'in_progress'])->count(),
        ];

        return view('admin.staff.index', compact('staffMembers', 'roles', 'permissions', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'designation' => 'nullable|string|max:255',
            'role_id' => 'nullable|exists:roles,id',
            'status' => 'required|in:active,inactive',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'super_admin',
            'designation' => $request->designation ?: 'Support Specialist',
            'status' => $request->status,
        ]);

        if ($request->filled('role_id')) {
            $user->roles()->sync([$request->role_id]);
        }

        return redirect()->route('admin.staff.index')->with('success', 'New Super Admin staff member created successfully.');
    }

    public function update(Request $request, User $staff)
    {
        if ($staff->role !== 'super_admin') {
            return redirect()->back()->with('error', 'Unauthorized staff modification.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $staff->id,
            'password' => 'nullable|string|min:8',
            'designation' => 'nullable|string|max:255',
            'role_id' => 'nullable|exists:roles,id',
            'status' => 'required|in:active,inactive',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'designation' => $request->designation ?: 'Support Specialist',
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $staff->update($data);

        if ($request->filled('role_id')) {
            $staff->roles()->sync([$request->role_id]);
        } else {
            $staff->roles()->detach();
        }

        return redirect()->route('admin.staff.index')->with('success', "Staff member '{$staff->name}' updated successfully.");
    }

    public function destroy(User $staff)
    {
        if ($staff->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete your own logged-in account.');
        }

        if ($staff->role !== 'super_admin') {
            return redirect()->back()->with('error', 'Invalid operation.');
        }

        // Unassign tickets assigned to this staff member
        \App\Models\Ticket::where('assigned_to', $staff->id)->update(['assigned_to' => null]);

        $name = $staff->name;
        $staff->delete();

        return redirect()->route('admin.staff.index')->with('success', "Staff member '{$name}' deleted successfully.");
    }

    public function storeRole(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'is_system' => false,
        ]);

        if (!empty($validated['permissions'])) {
            $role->permissions()->sync($validated['permissions']);
        }

        return redirect()->route('admin.staff.index')->with('success', "Role '{$role->name}' created successfully with menu permissions!");
    }

    public function updateRole(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
        ]);

        if (isset($validated['permissions'])) {
            $role->permissions()->sync($validated['permissions']);
        } else {
            $role->permissions()->detach();
        }

        return redirect()->route('admin.staff.index')->with('success', "Role '{$role->name}' menu permissions updated successfully!");
    }

    public function destroyRole(Role $role)
    {
        if ($role->is_system) {
            return redirect()->back()->with('error', 'System roles cannot be deleted.');
        }

        $name = $role->name;
        $role->delete();

        return redirect()->route('admin.staff.index')->with('success', "Role '{$name}' deleted successfully.");
    }

    public function managePermissions(Role $role)
    {
        $this->ensurePermissionsExist();

        $role->load('permissions');
        $permissions = Permission::all()->groupBy('category');
        $assignedPermissionIds = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.permissions', compact('role', 'permissions', 'assignedPermissionIds'));
    }

    public function updatePermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        if (isset($request->permissions)) {
            $role->permissions()->sync($request->permissions);
        } else {
            $role->permissions()->detach();
        }

        return redirect()->route('admin.roles.permissions', $role->id)
            ->with('success', "Permissions for role '{$role->name}' updated successfully!");
    }

    private function ensurePermissionsExist()
    {
        if (Permission::count() === 0) {
            (new \Database\Seeders\PermissionSeeder())->run();
        }
    }
}
