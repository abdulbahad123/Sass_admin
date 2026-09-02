@extends('layouts.admin')

@section('title', 'Staff & Roles Management')

@section('page_title', 'Super Admin Staff & Roles Management')

@section('header_actions')
<div class="flex items-center space-x-2">
    <button onclick="document.getElementById('createRoleModal').classList.remove('hidden')" 
            class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition flex items-center space-x-2 shadow-sm">
        <i data-lucide="shield-plus" class="w-4 h-4 text-indigo-400"></i>
        <span>Create New Role</span>
    </button>
    <button onclick="document.getElementById('addStaffModal').classList.remove('hidden')" 
            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-600/30 transition flex items-center space-x-2">
        <i data-lucide="user-plus" class="w-4 h-4"></i>
        <span>Add Staff Member</span>
    </button>
</div>
@endsection

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'staff' }">

    <!-- Top Navigation & Tabs Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200/80 pb-4">
        <div>
            <h2 class="text-lg font-extrabold text-slate-900 font-heading">Team Control & Menu Permissions</h2>
            <p class="text-xs text-slate-500 mt-1">Manage Super Admin support staff roster, define custom roles, and configure menu access permissions via checkboxes.</p>
        </div>

        <!-- Tab Switcher Buttons -->
        <div class="flex items-center space-x-2 bg-slate-100 p-1.5 rounded-2xl border border-slate-200/80">
            <button @click="activeTab = 'staff'" 
                    :class="activeTab === 'staff' ? 'bg-white text-indigo-600 shadow-md font-extrabold' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                    class="px-4 py-2 rounded-xl text-xs transition-all flex items-center space-x-2">
                <i data-lucide="users" class="w-4 h-4"></i>
                <span>Support Staff Roster ({{ $stats['total_staff'] }})</span>
            </button>
            <button @click="activeTab = 'roles'" 
                    :class="activeTab === 'roles' ? 'bg-white text-indigo-600 shadow-md font-extrabold' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                    class="px-4 py-2 rounded-xl text-xs transition-all flex items-center space-x-2">
                <i data-lucide="shield-check" class="w-4 h-4"></i>
                <span>Roles & Menu Permissions ({{ $stats['total_roles'] }})</span>
            </button>
        </div>
    </div>

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Total Staff -->
        <div class="card-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Staff Members</p>
                <h3 class="text-2xl font-black text-slate-900 font-heading mt-1">{{ $stats['total_staff'] }}</h3>
                <p class="text-[10px] text-slate-500 mt-0.5">Platform Team Roster</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100">
                <i data-lucide="users" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Custom Roles -->
        <div class="card-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Custom Roles Configured</p>
                <h3 class="text-2xl font-black text-purple-600 font-heading mt-1">{{ $stats['total_roles'] }}</h3>
                <p class="text-[10px] text-purple-600 font-medium mt-0.5">Menu Permissions Defined</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center border border-purple-100">
                <i data-lucide="shield-check" class="w-6 h-6"></i>
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

    <!-- TAB 1: STAFF ROSTER -->
    <div x-show="activeTab === 'staff'" class="space-y-6">
        
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
                            <th class="py-4 px-6">Role & Menu Access</th>
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

                                <!-- Role & Menu Access -->
                                <td class="py-4 px-6">
                                    <div>
                                        @php $assignedRole = $staff->roles->first(); @endphp
                                        <span class="px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-200/80 text-[11px] font-bold inline-flex items-center space-x-1">
                                            <i data-lucide="shield-check" class="w-3 h-3 text-indigo-600"></i>
                                            <span>{{ $assignedRole ? $assignedRole->name : ($staff->designation ?: 'Super Admin Staff') }}</span>
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
                                    <button onclick="openEditStaffModal({{ json_encode($staff) }}, {{ json_encode($staff->roles->first()?->id) }})" 
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

    <!-- TAB 2: ROLES & MENU PERMISSIONS -->
    <div x-show="activeTab === 'roles'" class="space-y-6" style="display: none;">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($roles as $role)
                <div class="card-white p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-4 flex flex-col justify-between hover:border-indigo-300 transition">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                                </div>
                                <h3 class="font-extrabold text-slate-900 font-heading text-sm">{{ $role->name }}</h3>
                            </div>
                            @if($role->is_system)
                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[10px] font-extrabold uppercase">System Role</span>
                            @else
                                <span class="px-2 py-0.5 rounded bg-purple-50 text-purple-700 border border-purple-200 text-[10px] font-bold">Custom Role</span>
                            @endif
                        </div>

                        <p class="text-xs text-slate-500 leading-relaxed">{{ $role->description ?: 'No role description provided.' }}</p>

                        <!-- Assigned Users & Permissions Badges -->
                        <div class="pt-2 border-t border-slate-100 space-y-2">
                            <div class="flex justify-between text-[11px] font-semibold text-slate-600">
                                <span>Assigned Staff:</span>
                                <span class="font-bold text-slate-900">{{ $role->users->count() }} Members</span>
                            </div>
                            <div class="flex justify-between text-[11px] font-semibold text-slate-600">
                                <span>Enabled Menu Access:</span>
                                <span class="font-bold text-indigo-600">{{ $role->permissions->count() }} Permissions Checked</span>
                            </div>
                        </div>

                        <!-- Menu Permission Badges List Preview -->
                        <div class="flex flex-wrap gap-1.5 pt-1">
                            @foreach($role->permissions->take(6) as $perm)
                                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[10px] font-medium border border-slate-200/60">
                                    ✓ {{ $perm->name }}
                                </span>
                            @endforeach
                            @if($role->permissions->count() > 6)
                                <span class="px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-600 text-[10px] font-bold">
                                    +{{ $role->permissions->count() - 6 }} more
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Role Action Buttons -->
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between space-x-2">
                        <button onclick="openEditRoleModal({{ json_encode($role) }}, {{ json_encode($role->permissions->pluck('id')) }})" 
                                class="flex-1 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-xl text-xs font-bold transition inline-flex items-center justify-center space-x-1.5">
                            <i data-lucide="check-square" class="w-3.5 h-3.5 text-indigo-600"></i>
                            <span>Configure Permissions</span>
                        </button>

                        @if(!$role->is_system)
                            <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this role?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 transition" title="Delete Role">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full card-white p-12 text-center text-slate-400 rounded-3xl">
                    <i data-lucide="shield-off" class="w-10 h-10 mx-auto text-slate-300 mb-2"></i>
                    <p class="font-bold text-slate-700">No Custom Roles Configured</p>
                    <p class="text-xs text-slate-400 mt-0.5">Click 'Create New Role' above to add custom menu permissions.</p>
                </div>
            @endforelse
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

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Staff Designation</label>
                    <input type="text" name="designation" placeholder="e.g. Senior Support Lead" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Assign Role</label>
                    <select name="role_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-bold text-slate-800 focus:outline-none focus:border-indigo-500">
                        <option value="">-- No Specific Role --</option>
                        @foreach($roles as $roleOption)
                            <option value="{{ $roleOption->id }}">{{ $roleOption->name }}</option>
                        @endforeach
                    </select>
                </div>
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

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Staff Designation</label>
                    <input type="text" id="edit_designation" name="designation" placeholder="e.g. Senior Support Specialist" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Assign Role</label>
                    <select id="edit_role_id" name="role_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-bold text-slate-800 focus:outline-none focus:border-indigo-500">
                        <option value="">-- No Specific Role --</option>
                        @foreach($roles as $roleOption)
                            <option value="{{ $roleOption->id }}">{{ $roleOption->name }}</option>
                        @endforeach
                    </select>
                </div>
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

<!-- Modal: Create New Role & Menu Checkboxes -->
<div id="createRoleModal" class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="card-white w-full max-w-3xl rounded-3xl p-6 shadow-2xl space-y-5 border border-slate-200 max-h-[90vh] flex flex-col">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 shrink-0">
            <h3 class="text-base font-extrabold text-slate-900 font-heading flex items-center space-x-2">
                <i data-lucide="shield-plus" class="w-5 h-5 text-indigo-600"></i>
                <span>Create Role & Assign Menu Permissions</span>
            </h3>
            <button onclick="document.getElementById('createRoleModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('admin.roles.store') }}" method="POST" class="space-y-5 overflow-y-auto pr-1 flex-1">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Role Title / Name</label>
                    <input type="text" name="name" required placeholder="e.g. Support Ticket Specialist" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Description</label>
                    <input type="text" name="description" placeholder="e.g. Can manage tickets and view agency catalog" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-indigo-500">
                </div>
            </div>

            <!-- Menu Permissions Checkbox Grid -->
            <div class="space-y-4 pt-2 border-t border-slate-100">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-800 font-heading">Menu Access & Feature Permissions (Check to Enable)</label>
                    <span class="text-[11px] text-slate-400">Tick checkboxes for menu access</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($permissions as $category => $categoryPermissions)
                        <div class="p-4 bg-slate-50/80 rounded-2xl border border-slate-200/70 space-y-2.5">
                            <div class="flex items-center justify-between pb-2 border-b border-slate-200/60">
                                <span class="font-extrabold text-slate-900 text-xs uppercase tracking-wider font-heading flex items-center space-x-1.5">
                                    <i data-lucide="check-square" class="w-3.5 h-3.5 text-indigo-600"></i>
                                    <span>{{ ucfirst(str_replace('_', ' ', $category)) }} Menu</span>
                                </span>
                            </div>

                            <div class="space-y-2 pt-1">
                                @foreach($categoryPermissions as $perm)
                                    <label class="flex items-start space-x-2.5 text-xs text-slate-700 font-semibold cursor-pointer hover:text-indigo-600 transition">
                                        <input type="checkbox" name="permissions[]" value="{{ $perm->id }}" class="mt-0.5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                        <div>
                                            <span class="block font-bold text-slate-800 text-[11px]">{{ $perm->name }}</span>
                                            <span class="block text-[10px] text-slate-400 font-normal">{{ $perm->description }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100 shrink-0">
                <button type="button" onclick="document.getElementById('createRoleModal').classList.add('hidden')" 
                        class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-extrabold shadow-lg shadow-indigo-600/30 transition">
                    Save New Role & Permissions
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Edit Role & Menu Checkboxes -->
<div id="editRoleModal" class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="card-white w-full max-w-3xl rounded-3xl p-6 shadow-2xl space-y-5 border border-slate-200 max-h-[90vh] flex flex-col">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 shrink-0">
            <h3 class="text-base font-extrabold text-slate-900 font-heading flex items-center space-x-2">
                <i data-lucide="edit-3" class="w-5 h-5 text-indigo-600"></i>
                <span>Configure Role & Menu Checkboxes</span>
            </h3>
            <button onclick="document.getElementById('editRoleModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="editRoleForm" method="POST" class="space-y-5 overflow-y-auto pr-1 flex-1">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Role Title / Name</label>
                    <input type="text" id="edit_role_name" name="name" required 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Description</label>
                    <input type="text" id="edit_role_description" name="description" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-indigo-500">
                </div>
            </div>

            <!-- Menu Permissions Checkbox Grid -->
            <div class="space-y-4 pt-2 border-t border-slate-100">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-800 font-heading">Menu Access & Feature Permissions (Check to Enable)</label>
                    <span class="text-[11px] text-slate-400">Tick checkboxes to update permissions</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($permissions as $category => $categoryPermissions)
                        <div class="p-4 bg-slate-50/80 rounded-2xl border border-slate-200/70 space-y-2.5">
                            <div class="flex items-center justify-between pb-2 border-b border-slate-200/60">
                                <span class="font-extrabold text-slate-900 text-xs uppercase tracking-wider font-heading flex items-center space-x-1.5">
                                    <i data-lucide="check-square" class="w-3.5 h-3.5 text-indigo-600"></i>
                                    <span>{{ ucfirst(str_replace('_', ' ', $category)) }} Menu</span>
                                </span>
                            </div>

                            <div class="space-y-2 pt-1">
                                @foreach($categoryPermissions as $perm)
                                    <label class="flex items-start space-x-2.5 text-xs text-slate-700 font-semibold cursor-pointer hover:text-indigo-600 transition">
                                        <input type="checkbox" name="permissions[]" value="{{ $perm->id }}" class="role-perm-checkbox mt-0.5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" id="edit_perm_{{ $perm->id }}">
                                        <div>
                                            <span class="block font-bold text-slate-800 text-[11px]">{{ $perm->name }}</span>
                                            <span class="block text-[10px] text-slate-400 font-normal">{{ $perm->description }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100 shrink-0">
                <button type="button" onclick="document.getElementById('editRoleModal').classList.add('hidden')" 
                        class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-extrabold shadow-lg shadow-indigo-600/30 transition">
                    Update Role Permissions
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openEditStaffModal(staff, roleId) {
        const form = document.getElementById('editStaffForm');
        form.action = `/admin/staff/${staff.id}`;
        document.getElementById('edit_name').value = staff.name;
        document.getElementById('edit_email').value = staff.email;
        document.getElementById('edit_designation').value = staff.designation || '';
        document.getElementById('edit_role_id').value = roleId || '';
        document.getElementById('edit_status').value = staff.status || 'active';
        document.getElementById('editStaffModal').classList.remove('hidden');
    }

    function openEditRoleModal(role, permissionIds) {
        const form = document.getElementById('editRoleForm');
        form.action = `/admin/roles/${role.id}`;
        document.getElementById('edit_role_name').value = role.name;
        document.getElementById('edit_role_description').value = role.description || '';
        
        // Reset all checkboxes
        document.querySelectorAll('.role-perm-checkbox').forEach(cb => cb.checked = false);

        // Check assigned permissions
        if (permissionIds && Array.isArray(permissionIds)) {
            permissionIds.forEach(id => {
                const cb = document.getElementById(`edit_perm_${id}`);
                if (cb) cb.checked = true;
            });
        }

        document.getElementById('editRoleModal').classList.remove('hidden');
    }
</script>
@endpush
@endsection
