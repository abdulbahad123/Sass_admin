@extends('layouts.admin')

@section('title', 'Staff Management')

@section('page_title', 'Super Admin Staff Management')

@section('header_actions')
<button onclick="document.getElementById('addStaffModal').classList.remove('hidden')" 
        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-600/30 transition flex items-center space-x-2">
    <i data-lucide="user-plus" class="w-4 h-4"></i>
    <span>Add Staff Member</span>
</button>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Top Banner & Subtitle -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-extrabold text-slate-900 font-heading">Support Team & Staff Roster</h2>
            <p class="text-xs text-slate-500 mt-1">Manage Super Admin support personnel, assign designations, view ticket workloads, and control team access.</p>
        </div>
    </div>

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Total Staff -->
        <div class="card-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Super Admin Staff</p>
                <h3 class="text-2xl font-black text-slate-900 font-heading mt-1">{{ $stats['total_staff'] }}</h3>
                <p class="text-[10px] text-slate-500 mt-0.5">Platform Team Members</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100">
                <i data-lucide="users" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Active Agents -->
        <div class="card-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Active Support Agents</p>
                <h3 class="text-2xl font-black text-emerald-600 font-heading mt-1">{{ $stats['active_staff'] }}</h3>
                <p class="text-[10px] text-emerald-600 font-medium mt-0.5">Ready for Ticket Assignment</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100">
                <i data-lucide="user-check" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Assigned Tickets -->
        <div class="card-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Assigned Tickets</p>
                <h3 class="text-2xl font-black text-blue-600 font-heading mt-1">{{ $stats['total_assigned'] }}</h3>
                <p class="text-[10px] text-slate-500 mt-0.5">Active Ticket Workload</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100">
                <i data-lucide="headset" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Unassigned Queue -->
        <div class="card-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Unassigned Tickets</p>
                <h3 class="text-2xl font-black text-amber-600 font-heading mt-1">{{ $stats['unassigned_tickets'] }}</h3>
                <p class="text-[10px] text-amber-600 font-medium mt-0.5">Needs Agent Assignment</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100">
                <i data-lucide="help-circle" class="w-6 h-6"></i>
            </div>
        </div>

    </div>

    <!-- Filter & Search Bar -->
    <div class="card-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
        <form action="{{ route('admin.staff.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 items-center justify-between">
            <div class="relative w-full sm:w-80">
                <i data-lucide="search" class="w-4 h-4 absolute left-3.5 top-3 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search staff name, email, designation..." 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 transition">
            </div>

            <div class="flex items-center space-x-2 w-full sm:w-auto justify-end">
                <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition">
                    Filter Staff
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.staff.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Staff Members List Table -->
    <div class="card-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-400">
                        <th class="py-4 px-6">Staff Member</th>
                        <th class="py-4 px-6">Role & Designation</th>
                        <th class="py-4 px-6 text-center">Active Workload</th>
                        <th class="py-4 px-6 text-center">Total Resolved</th>
                        <th class="py-4 px-6 text-center">Status</th>
                        <th class="py-4 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($staffMembers as $staff)
                        <tr class="hover:bg-slate-50/50 transition">
                            <!-- Staff Member Info -->
                            <td class="py-4 px-6">
                                <div class="flex items-center space-x-3">
                                    @if($staff->avatar)
                                        <img src="{{ $staff->avatar }}" alt="{{ $staff->name }}" class="w-10 h-10 rounded-2xl object-cover ring-2 ring-indigo-500/20">
                                    @else
                                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-indigo-600 to-violet-600 text-white font-bold text-xs flex items-center justify-center shadow-md">
                                            {{ substr($staff->name, 0, 2) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="flex items-center space-x-1.5">
                                            <span class="font-extrabold text-slate-900 font-heading text-sm">{{ $staff->name }}</span>
                                            @if($staff->id === auth()->id())
                                                <span class="px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700 text-[9px] font-extrabold">You</span>
                                            @endif
                                        </div>
                                        <p class="text-[11px] text-slate-400 font-medium">{{ $staff->email }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Role & Designation -->
                            <td class="py-4 px-6">
                                <div>
                                    <span class="px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-200/80 text-[11px] font-bold inline-flex items-center space-x-1">
                                        <i data-lucide="shield-check" class="w-3 h-3 text-indigo-600"></i>
                                        <span>{{ $staff->designation ?: 'Super Admin Staff' }}</span>
                                    </span>
                                </div>
                            </td>

                            <!-- Active Workload -->
                            <td class="py-4 px-6 text-center">
                                <a href="{{ route('admin.tickets.index', ['assigned_to' => $staff->id]) }}" 
                                   class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full {{ $staff->active_tickets_count > 0 ? 'bg-amber-50 text-amber-700 border border-amber-200 font-extrabold' : 'bg-slate-100 text-slate-500 font-semibold' }} text-xs hover:opacity-80 transition">
                                    <i data-lucide="life-buoy" class="w-3.5 h-3.5"></i>
                                    <span>{{ $staff->active_tickets_count }} Open Tickets</span>
                                </a>
                            </td>

                            <!-- Total Resolved -->
                            <td class="py-4 px-6 text-center">
                                <span class="font-extrabold text-emerald-600 bg-emerald-50 border border-emerald-200/80 px-2.5 py-1 rounded-lg">
                                    {{ $staff->resolved_tickets_count }} Resolved
                                </span>
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-6 text-center">
                                @if($staff->status === 'inactive')
                                    <span class="px-2.5 py-1 rounded-full bg-rose-50 text-rose-600 border border-rose-200 font-bold text-[10px] uppercase tracking-wider">
                                        Inactive
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-200 font-bold text-[10px] uppercase tracking-wider inline-flex items-center space-x-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        <span>Active</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-6 text-right space-x-2">
                                <button onclick="openEditStaffModal({{ json_encode($staff) }})" 
                                        class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition" title="Edit Staff">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </button>
                                
                                @if($staff->id !== auth()->id())
                                    <form action="{{ route('admin.staff.destroy', $staff->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to remove this staff member?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 transition" title="Delete Staff">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <i data-lucide="users" class="w-10 h-10 mx-auto text-slate-300 mb-2"></i>
                                <p class="font-bold text-slate-700">No staff members found</p>
                                <p class="text-xs text-slate-400 mt-0.5">Click 'Add Staff Member' above to create support team agents.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal: Add New Staff Member -->
<div id="addStaffModal" class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="card-white w-full max-w-lg rounded-3xl p-6 shadow-2xl space-y-5 border border-slate-200">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <h3 class="text-base font-extrabold text-slate-900 font-heading flex items-center space-x-2">
                <i data-lucide="user-plus" class="w-5 h-5 text-indigo-600"></i>
                <span>Add Super Admin Support Staff</span>
            </h3>
            <button onclick="document.getElementById('addStaffModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('admin.staff.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Full Name</label>
                <input type="text" name="name" required placeholder="e.g. Sarah Jenkins" 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Email Address</label>
                <input type="email" name="email" required placeholder="e.g. sarah@platform.com" 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Staff Designation / Job Title</label>
                <input type="text" name="designation" placeholder="e.g. Senior Support Engineer / Tier-1 Lead" 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-indigo-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Password</label>
                    <input type="password" name="password" required placeholder="Minimum 8 characters" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Account Status</label>
                    <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-bold text-slate-800 focus:outline-none focus:border-indigo-500">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('addStaffModal').classList.add('hidden')" 
                        class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-extrabold shadow-lg shadow-indigo-600/30 transition">
                    Create Staff Member
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Edit Staff Member -->
<div id="editStaffModal" class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="card-white w-full max-w-lg rounded-3xl p-6 shadow-2xl space-y-5 border border-slate-200">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <h3 class="text-base font-extrabold text-slate-900 font-heading flex items-center space-x-2">
                <i data-lucide="edit" class="w-5 h-5 text-indigo-600"></i>
                <span>Edit Support Staff Member</span>
            </h3>
            <button onclick="document.getElementById('editStaffModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="editStaffForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Full Name</label>
                <input type="text" id="edit_name" name="name" required 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Email Address</label>
                <input type="email" id="edit_email" name="email" required 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Staff Designation / Job Title</label>
                <input type="text" id="edit_designation" name="designation" placeholder="e.g. Senior Support Specialist" 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-indigo-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Password (Leave blank to keep)</label>
                    <input type="password" name="password" placeholder="New password" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Account Status</label>
                    <select id="edit_status" name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-bold text-slate-800 focus:outline-none focus:border-indigo-500">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('editStaffModal').classList.add('hidden')" 
                        class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-extrabold shadow-lg shadow-indigo-600/30 transition">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openEditStaffModal(staff) {
        const form = document.getElementById('editStaffForm');
        form.action = `/admin/staff/${staff.id}`;
        document.getElementById('edit_name').value = staff.name;
        document.getElementById('edit_email').value = staff.email;
        document.getElementById('edit_designation').value = staff.designation || '';
        document.getElementById('edit_status').value = staff.status || 'active';
        document.getElementById('editStaffModal').classList.remove('hidden');
    }
</script>
@endpush
@endsection
