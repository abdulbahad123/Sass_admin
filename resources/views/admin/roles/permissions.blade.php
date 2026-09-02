@extends('layouts.admin')

@section('title', '"' . $role->name . '" – Permissions Management')

@section('page_title', 'Role Permissions Configuration')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Breadcrumbs Navigation -->
    <div class="flex items-center space-x-2 text-xs font-semibold text-slate-400">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-600 transition">Home</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-300"></i>
        <a href="{{ route('admin.staff.index') }}" class="hover:text-slate-600 transition">Staff & Roles Management</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-300"></i>
        <span class="text-indigo-600 font-bold">Permissions Management</span>
    </div>

    <!-- Title & Back Button Bar -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex items-center justify-between">
        <div>
            <h1 class="text-xl font-black text-slate-900 font-heading">
                "{{ $role->name }}" – <span class="text-indigo-600">Permissions Management</span>
            </h1>
            <p class="text-xs text-slate-500 mt-1">Configure menu access and operational privileges for this role using category checkboxes below.</p>
        </div>

        <a href="{{ route('admin.staff.index') }}" class="px-5 py-2.5 bg-blue-500 hover:bg-blue-600 text-white rounded-xl text-xs font-extrabold shadow-md shadow-blue-500/20 transition flex items-center space-x-1.5">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Back to Staff & Roles</span>
        </a>
    </div>

    <!-- Permissions Form -->
    <form action="{{ route('admin.roles.permissions.update', $role->id) }}" method="POST" class="space-y-6">
        @csrf

        @php
            $categoryTitleMap = [
                'dashboard' => 'Dashboard & Platform Overview',
                'agencies' => 'Agencies Management & Submenus',
                'products' => 'Products Catalog & Access Control',
                'plans' => 'Package & Financial Plans Management',
                'subscriptions' => 'Subscriptions & Billing Management',
                'tickets' => 'Support Tickets & Customer Support',
                'team' => 'Staff Roster & Roles Administration',
                'audit_logs' => 'Audit Logs & Security Monitoring',
                'branding' => 'Branding & Whitelabel Configurations',
                'settings' => 'System & Platform Settings',
            ];
        @endphp

        @foreach($permissions as $category => $categoryPermissions)
            @php
                $catTitle = $categoryTitleMap[$category] ?? ucfirst(str_replace('_', ' ', $category)) . ' Management';
            @endphp
            
            <!-- Category Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm space-y-4">
                
                <!-- Category Card Header -->
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-extrabold text-blue-600 font-heading flex items-center space-x-2">
                        <i data-lucide="folder-key" class="w-4 h-4 text-blue-500"></i>
                        <span>{{ $catTitle }}</span>
                    </h3>

                    <!-- Select / Deselect Category Toggle -->
                    <button type="button" 
                            onclick="toggleCategoryCheckboxes('cat_{{ $category }}')" 
                            class="text-[11px] font-bold text-slate-500 hover:text-indigo-600 bg-slate-50 hover:bg-slate-100 px-3 py-1 rounded-lg border border-slate-200/80 transition">
                        Select / Deselect All
                    </button>
                </div>

                <!-- Checkboxes 3-Column Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-1 cat_{{ $category }}">
                    @foreach($categoryPermissions as $perm)
                        @php
                            $isChecked = in_array($perm->id, $assignedPermissionIds);
                        @endphp
                        <label class="flex items-start space-x-3 p-3 rounded-2xl hover:bg-slate-50 transition border border-transparent hover:border-slate-200/60 cursor-pointer">
                            <input type="checkbox" 
                                   name="permissions[]" 
                                   value="{{ $perm->id }}" 
                                   {{ $isChecked ? 'checked' : '' }}
                                   class="mt-1 w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <div>
                                <span class="block text-xs font-bold {{ $isChecked ? 'text-slate-900 font-extrabold' : 'text-slate-700' }}">
                                    {{ $perm->name }}
                                </span>
                                @if($perm->description)
                                    <span class="block text-[11px] text-slate-400 font-normal mt-0.5">
                                        {{ $perm->description }}
                                    </span>
                                @endif
                            </div>
                        </label>
                    @endforeach
                </div>

            </div>
        @endforeach

        <!-- Bottom Update Permissions Action Bar -->
        <div class="pt-4 pb-12 text-center">
            <button type="submit" class="px-10 py-3.5 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-black rounded-2xl shadow-xl shadow-emerald-500/30 hover:shadow-emerald-500/40 transition transform hover:-translate-y-0.5 inline-flex items-center space-x-2">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                <span>Update Permissions</span>
            </button>
        </div>

    </form>

</div>

@push('scripts')
<script>
    function toggleCategoryCheckboxes(categoryClass) {
        const checkboxes = document.querySelectorAll(`.${categoryClass} input[type="checkbox"]`);
        if (!checkboxes.length) return;

        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
        checkboxes.forEach(cb => cb.checked = !allChecked);
    }
</script>
@endpush
@endsection
