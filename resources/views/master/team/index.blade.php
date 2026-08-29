@extends('layouts.master')

@section('title', 'Team Members')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900 font-heading">Team Members & Staff Access</h2>
            <p class="text-xs text-slate-500">Manage administrative team access with roles and permissions</p>
        </div>
        <div class="flex items-center space-x-3">
            <button onclick="document.getElementById('createRoleModal').classList.remove('hidden')" 
                    class="px-4 py-2.5 bg-slate-600 hover:bg-slate-700 text-white rounded-xl text-xs font-bold shadow-lg flex items-center space-x-2 transition-all">
                <i data-lucide="shield" class="w-4 h-4"></i>
                <span>Create Role</span>
            </button>
            <button onclick="document.getElementById('createTeamModal').classList.remove('hidden')" 
                    class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-600/30 flex items-center space-x-2 transition-all">
                <i data-lucide="user-plus" class="w-4 h-4"></i>
                <span>Invite Member</span>
            </button>
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex items-center space-x-2 border-b border-slate-200">
        <button onclick="switchTab('members')" id="membersTab" class="px-4 py-2.5 text-sm font-bold border-b-2 border-indigo-600 text-indigo-600">
            Team Members
        </button>
        <button onclick="switchTab('roles')" id="rolesTab" class="px-4 py-2.5 text-sm font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-700">
            Roles & Permissions
        </button>
    </div>

    <!-- Members Tab -->
    <div id="membersView">
        <div class="card-white rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr class="text-[11px] font-bold uppercase tracking-wider text-slate-600">
                            <th class="px-6 py-4">Member Name</th>
                            <th class="px-6 py-4">Email Address</th>
                            <th class="px-6 py-4">Role</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @forelse($members as $member)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4 font-bold text-slate-900">{{ $member->name }}</td>
                                <td class="px-6 py-4 text-indigo-600 font-mono text-[11px]">{{ $member->email }}</td>
                                <td class="px-6 py-4">
                                    @if($member->roles->isNotEmpty())
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($member->roles as $role)
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-700">
                                                    {{ $role->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-slate-400">No Role</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">
                                        {{ ucfirst($member->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('master.team.destroy', $member) }}" method="POST" onsubmit="return confirm('Remove team member?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-rose-100 hover:bg-rose-200 text-rose-700 font-semibold text-xs">
                                            Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                                    No team members found. Invite members to get started.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Roles Tab -->
    <div id="rolesView" class="hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($roles as $role)
                <div class="card-white rounded-2xl p-6 space-y-4">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-base font-bold text-slate-900 font-heading">{{ $role->name }}</h3>
                            <p class="text-xs text-slate-500 mt-1">{{ $role->description ?? 'No description' }}</p>
                            @if($role->is_system)
                                <span class="inline-block mt-2 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700">
                                    System Role
                                </span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <span class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">Permissions ({{ $role->permissions->count() }})</span>
                        <div class="flex flex-wrap gap-1.5">
                            @forelse($role->permissions->take(5) as $perm)
                                <span class="px-2 py-0.5 rounded-lg text-[10px] font-semibold bg-slate-100 text-slate-700">
                                    {{ $perm->name }}
                                </span>
                            @empty
                                <span class="text-xs text-slate-400">No permissions</span>
                            @endforelse
                            @if($role->permissions->count() > 5)
                                <span class="px-2 py-0.5 rounded-lg text-[10px] font-semibold bg-slate-100 text-slate-700">
                                    +{{ $role->permissions->count() - 5 }} more
                                </span>
                            @endif
                        </div>
                    </div>

                    @if(!$role->is_system)
                        <div class="pt-4 border-t border-slate-100 flex items-center space-x-2">
                            <button onclick="openEditRoleModal({{ json_encode($role) }})" class="px-3 py-1.5 rounded-lg bg-indigo-100 hover:bg-indigo-200 text-indigo-700 font-semibold text-xs flex-1">
                                Edit
                            </button>
                            <form action="{{ route('master.team.roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Delete role {{ $role->name }}?');" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full px-3 py-1.5 rounded-lg bg-rose-100 hover:bg-rose-200 text-rose-700 font-semibold text-xs">
                                    Delete
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @empty
                <div class="col-span-full text-center py-12 text-slate-500">
                    No roles found. Create a role to get started.
                </div>
            @endforelse
        </div>
    </div>

</div>

@push('modals')
<!-- Invite Member Modal -->
<div id="createTeamModal" class="fixed inset-0 z-[100] hidden bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-slate-900 font-heading">Invite Team Member</h3>
            <button onclick="document.getElementById('createTeamModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('master.team.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Full Name</label>
                <input type="text" name="name" placeholder="John Doe" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Email Address</label>
                <input type="email" name="email" placeholder="john@example.com" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Password</label>
                <input type="password" name="password" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Assign Role</label>
                <select name="role_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                    <option value="">Select a role...</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3">
                <button type="button" onclick="document.getElementById('createTeamModal').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-slate-500 hover:text-slate-800">Cancel</button>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-600/30">Send Invitation</button>
            </div>
        </form>
    </div>
</div>

<!-- Create Role Modal -->
<div id="createRoleModal" class="fixed inset-0 z-[100] hidden bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 max-w-2xl w-full shadow-2xl overflow-y-auto max-h-[90vh]">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-slate-900 font-heading">Create Role</h3>
            <button onclick="document.getElementById('createRoleModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('master.team.roles.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Role Name</label>
                <input type="text" name="name" placeholder="e.g. Billing Manager" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Description</label>
                <textarea name="description" rows="2" placeholder="Brief description of this role..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900"></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">Permissions</label>
                <div class="space-y-3 max-h-60 overflow-y-auto border border-slate-200 rounded-xl p-4 bg-slate-50">
                    @foreach($permissions as $category => $perms)
                        <div>
                            <span class="block text-xs font-bold text-slate-700 mb-2 uppercase">{{ ucfirst($category) }}</span>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($perms as $perm)
                                    <label class="flex items-center space-x-2 text-xs text-slate-700 cursor-pointer">
                                        <input type="checkbox" name="permissions[]" value="{{ $perm->id }}" class="rounded bg-white border-slate-300 text-indigo-600">
                                        <span>{{ $perm->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3">
                <button type="button" onclick="document.getElementById('createRoleModal').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-slate-500 hover:text-slate-800">Cancel</button>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-600/30">Create Role</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Role Modal -->
<div id="editRoleModal" class="fixed inset-0 z-[100] hidden bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 max-w-2xl w-full shadow-2xl overflow-y-auto max-h-[90vh]">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-slate-900 font-heading">Edit Role</h3>
            <button onclick="document.getElementById('editRoleModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="editRoleForm" action="" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Role Name</label>
                <input type="text" id="edit_role_name" name="name" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Description</label>
                <textarea id="edit_role_description" name="description" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900"></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">Permissions</label>
                <div id="edit_permissions_container" class="space-y-3 max-h-60 overflow-y-auto border border-slate-200 rounded-xl p-4 bg-slate-50">
                    @foreach($permissions as $category => $perms)
                        <div>
                            <span class="block text-xs font-bold text-slate-700 mb-2 uppercase">{{ ucfirst($category) }}</span>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($perms as $perm)
                                    <label class="flex items-center space-x-2 text-xs text-slate-700 cursor-pointer">
                                        <input type="checkbox" name="permissions[]" value="{{ $perm->id }}" class="edit-perm-checkbox rounded bg-white border-slate-300 text-indigo-600" data-perm-id="{{ $perm->id }}">
                                        <span>{{ $perm->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3">
                <button type="button" onclick="document.getElementById('editRoleModal').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-slate-500 hover:text-slate-800">Cancel</button>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-600/30">Update Role</button>
            </div>
        </form>
    </div>
</div>
@endpush

@push('scripts')
<script>
    function switchTab(tab) {
        const membersView = document.getElementById('membersView');
        const rolesView = document.getElementById('rolesView');
        const membersTab = document.getElementById('membersTab');
        const rolesTab = document.getElementById('rolesTab');

        if (tab === 'members') {
            membersView.classList.remove('hidden');
            rolesView.classList.add('hidden');
            membersTab.classList.add('border-indigo-600', 'text-indigo-600');
            membersTab.classList.remove('border-transparent', 'text-slate-500');
            rolesTab.classList.remove('border-indigo-600', 'text-indigo-600');
            rolesTab.classList.add('border-transparent', 'text-slate-500');
        } else {
            membersView.classList.add('hidden');
            rolesView.classList.remove('hidden');
            rolesTab.classList.add('border-indigo-600', 'text-indigo-600');
            rolesTab.classList.remove('border-transparent', 'text-slate-500');
            membersTab.classList.remove('border-indigo-600', 'text-indigo-600');
            membersTab.classList.add('border-transparent', 'text-slate-500');
        }
        lucide.createIcons();
    }

    function openEditRoleModal(role) {
        document.getElementById('editRoleForm').action = `/master/team/roles/${role.id}`;
        document.getElementById('edit_role_name').value = role.name || '';
        document.getElementById('edit_role_description').value = role.description || '';

        // Uncheck all permissions first
        document.querySelectorAll('.edit-perm-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });

        // Check permissions that belong to this role
        if (role.permissions) {
            role.permissions.forEach(perm => {
                const checkbox = document.querySelector(`.edit-perm-checkbox[data-perm-id="${perm.id}"]`);
                if (checkbox) {
                    checkbox.checked = true;
                }
            });
        }

        document.getElementById('editRoleModal').classList.remove('hidden');
        lucide.createIcons();
    }
</script>
@endpush
@endsection
