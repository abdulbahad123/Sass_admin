<?php

namespace App\Http\Controllers\MasterAgency;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MasterTeamController extends Controller
{
    public function index()
    {
        $agency = Auth::user()->agency;
        $members = User::where('agency_id', $agency->id)
            ->with('roles')
            ->get();
        
        $roles = Role::where('agency_id', $agency->id)
            ->orWhere('is_system', true)
            ->with('permissions')
            ->get();
        
        $permissions = Permission::all()->groupBy('category');

        return view('master.team.index', compact('members', 'roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id',
        ]);

        $agency = Auth::user()->agency;

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'team_member',
            'agency_id' => $agency->id,
            'status' => 'active',
        ]);

        $user->roles()->attach($validated['role_id']);

        return redirect()->route('master.team.index')->with('success', 'Team member invited successfully!');
    }

    public function storeRole(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
        ]);

        $agency = Auth::user()->agency;

        $role = Role::create([
            'name' => $validated['name'],
            'slug' => \Illuminate\Support\Str::slug($validated['name']),
            'description' => $validated['description'],
            'agency_id' => $agency->id,
            'is_system' => false,
        ]);

        if (!empty($validated['permissions'])) {
            $role->permissions()->sync($validated['permissions']);
        }

        return redirect()->route('master.team.index')->with('success', "Role '{$role->name}' created successfully!");
    }

    public function updateRole(Request $request, Role $role)
    {
        if ($role->is_system) {
            return redirect()->route('master.team.index')->with('error', 'Cannot edit system roles.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
        ]);

        $role->update([
            'name' => $validated['name'],
            'slug' => \Illuminate\Support\Str::slug($validated['name']),
            'description' => $validated['description'],
        ]);

        if (isset($validated['permissions'])) {
            $role->permissions()->sync($validated['permissions']);
        }

        return redirect()->route('master.team.index')->with('success', "Role '{$role->name}' updated successfully!");
    }

    public function destroyRole(Role $role)
    {
        if ($role->is_system) {
            return redirect()->route('master.team.index')->with('error', 'Cannot delete system roles.');
        }

        $role->delete();
        return redirect()->route('master.team.index')->with('success', 'Role deleted successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('master.team.index')->with('success', 'Team member removed successfully.');
    }
}
