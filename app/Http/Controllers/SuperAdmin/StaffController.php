<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'super_admin')
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

        $stats = [
            'total_staff' => User::where('role', 'super_admin')->count(),
            'active_staff' => User::where('role', 'super_admin')->where(function($q){
                $q->whereNull('status')->orWhere('status', 'active');
            })->count(),
            'total_assigned' => \App\Models\Ticket::whereNotNull('assigned_to')->count(),
            'unassigned_tickets' => \App\Models\Ticket::whereNull('assigned_to')->whereIn('status', ['open', 'in_progress'])->count(),
        ];

        return view('admin.staff.index', compact('staffMembers', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'designation' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'super_admin',
            'designation' => $request->designation ?: 'Support Specialist',
            'status' => $request->status,
        ]);

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
}
