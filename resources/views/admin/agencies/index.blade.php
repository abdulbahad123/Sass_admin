@extends('layouts.admin')

@section('title', 'Agencies Directory')
@section('page_title', 'Master Agencies & White-Label Agencies Management')

@section('header_actions')
<div class="flex items-center space-x-2">
<button onclick="document.getElementById('broadcastModal').classList.remove('hidden')" class="px-2.5 py-1.5 sm:px-4 sm:py-2.5 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-amber-500/25 flex items-center space-x-1.5 sm:space-x-2 transition-all whitespace-nowrap">
    <i data-lucide="megaphone" class="w-4 h-4"></i>
    <span>Broadcast Notice</span>
</button>
@if(auth()->user()->hasPermission('create-agencies'))
<button onclick="document.getElementById('createAgencyModal').classList.remove('hidden')" 
        class="px-2.5 py-1.5 sm:px-4 sm:py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-600/30 flex items-center space-x-1.5 sm:space-x-2 transition-all whitespace-nowrap">
    <i data-lucide="building" class="w-4 h-4"></i>
    <span>Onboard Agency</span>
</button>
@endif
</div>
@endsection

@section('content')
<div class="space-y-6">

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1 shadow-sm">
            <p class="font-bold flex items-center text-rose-900"><i data-lucide="alert-triangle" class="w-4 h-4 mr-1.5 text-rose-600"></i> Onboarding / Update action failed:</p>
            <ul class="list-disc list-inside space-y-0.5 pl-2 text-rose-700 font-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Tabs & Filter Header + View Mode Switcher (Task 2) -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
        <div class="flex items-center space-x-2 overflow-x-auto w-full sm:w-auto pb-2 sm:pb-0">
            <a href="{{ route('admin.agencies.index') }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-all {{ !$type ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'text-slate-600 hover:text-slate-900 bg-white border border-slate-200' }}">
                All Agencies
            </a>
            <a href="{{ route('admin.agencies.index', ['type' => 'white_label']) }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-all {{ $type === 'white_label' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'text-slate-600 hover:text-slate-900 bg-white border border-slate-200' }}">
                White Label Resellers
            </a>
        </div>

        <!-- Grid / List Switcher Button Group (Task 2) -->
        <div class="flex items-center space-x-1.5 bg-slate-100 p-1 rounded-xl border border-slate-200 self-end sm:self-auto">
            <button type="button" id="btnGridView" onclick="switchView('grid')" title="Grid View"
                    class="px-3 py-1.5 rounded-lg text-xs font-bold flex items-center space-x-1.5 transition-all bg-white text-indigo-600 shadow-sm">
                <i data-lucide="layout-grid" class="w-4 h-4"></i>
                <span class="hidden sm:inline">Grid</span>
            </button>
            <button type="button" id="btnListView" onclick="switchView('list')" title="List View"
                    class="px-3 py-1.5 rounded-lg text-xs font-bold flex items-center space-x-1.5 transition-all text-slate-500 hover:text-slate-800">
                <i data-lucide="list" class="w-4 h-4"></i>
                <span class="hidden sm:inline">List</span>
            </button>
        </div>
    </div>

    <!-- Agencies Grid View -->
    <div id="agenciesGridView" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($agencies as $agency)
            <div class="card-white rounded-2xl p-6 flex flex-col justify-between space-y-4 hover:shadow-md transition-all">
                
                <!-- Agency Header Info -->
                <div class="flex items-start justify-between">
                    <div class="flex items-center space-x-3.5">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 font-bold text-lg flex items-center justify-center border border-indigo-100">
                            {{ substr($agency->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="flex items-center space-x-2 flex-wrap">
                                <h3 class="text-base font-bold text-slate-900 font-heading">{{ $agency->name }}</h3>
                                @if($agency->type === 'master')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-violet-100 text-violet-700 uppercase">Master Agency</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-sky-100 text-sky-700 uppercase">White Label Reseller</span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5">Owner: <span class="text-slate-800 font-medium">{{ $agency->owner_name }}</span> ({{ $agency->email }})</p>
                        </div>
                    </div>

                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">
                        {{ ucfirst($agency->status) }}
                    </span>
                </div>

                <!-- Hierarchy & Sub-Agency Info -->
                @if($agency->type === 'white_label' && $agency->parentAgency)
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs flex items-center justify-between">
                        <span class="text-slate-500">Master Partner Parent:</span>
                        <span class="font-semibold text-violet-600 flex items-center">
                            <i data-lucide="crown" class="w-3.5 h-3.5 mr-1 text-violet-600"></i>
                            {{ $agency->parentAgency->name }}
                        </span>
                    </div>
                @elseif($agency->type === 'master')
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs flex items-center justify-between">
                        <span class="text-slate-500">Sub-Agencies Managed:</span>
                        <span class="font-bold text-slate-900">{{ $agency->subAgencies->count() }} Agencies</span>
                    </div>
                @endif

                <!-- Quotas, Price & Domain -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                    <div class="p-3 rounded-xl bg-emerald-50/70 border border-emerald-100/80">
                        <span class="text-emerald-700/80 block text-[11px] font-bold uppercase tracking-wider">Clients Capacity</span>
                        <span class="font-extrabold text-emerald-800 text-xs mt-0.5 inline-flex items-center space-x-1">
                            @if($agency->max_clients >= 99999)
                                <i data-lucide="infinity" class="w-4 h-4 text-emerald-600"></i>
                                <span>Unlimited</span>
                            @else
                                <i data-lucide="users" class="w-4 h-4 text-emerald-600"></i>
                                <span>{{ number_format($agency->max_clients) }} Clients</span>
                            @endif
                        </span>
                    </div>
                    <div class="p-3 rounded-xl bg-violet-50/70 border border-violet-100/80">
                        <span class="text-violet-700/80 block text-[11px] font-bold uppercase tracking-wider">Agency Price</span>
                        <span class="font-extrabold text-violet-800 text-xs mt-0.5 inline-flex items-center space-x-1">
                            <i data-lucide="tag" class="w-3.5 h-3.5 text-violet-600"></i>
                            <span>{{ \App\Models\Setting::getCurrencySymbol() }}{{ number_format($agency->subscription?->amount ?? 0) }}/{{ $agency->subscription?->billing_cycle === 'yearly' ? 'yr' : 'mo' }}</span>
                        </span>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-slate-400 block text-[11px]">Custom Domain</span>
                        <span class="font-mono text-indigo-600 mt-0.5 block truncate">{{ $agency->custom_domain ?? 'Not configured' }}</span>
                    </div>
                </div>

                <!-- Product Entitlements Section (Launchshop, CRM, Builder) -->
                <div>
                    <span class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">Authorized SaaS Products @if(auth()->user()->hasPermission(['manage-product-access', 'edit-agencies']))(Click to Toggle Access)@endif</span>
                    <div class="flex flex-wrap gap-2">
                        @foreach($products as $prod)
                            @php
                                $assigned = $agency->products->firstWhere('id', $prod->id);
                                $isEnabled = $assigned && $assigned->pivot->status === 'enabled';
                            @endphp
                            @if(auth()->user()->hasPermission(['manage-product-access', 'edit-agencies']))
                            <form action="{{ route('admin.agencies.toggle-product', [$agency, $prod]) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="px-3 py-1.5 rounded-xl text-xs font-semibold flex items-center space-x-1.5 transition-all border {{ $isEnabled ? 'bg-indigo-50 text-indigo-700 border-indigo-200 hover:bg-indigo-100' : 'bg-slate-50 text-slate-400 border-slate-200 hover:text-slate-600' }}"
                                        title="{{ $isEnabled ? 'Disable access to ' . $prod->name : 'Grant access to ' . $prod->name }}">
                                    <i data-lucide="{{ $isEnabled ? 'check-circle-2' : 'circle' }}" class="w-3.5 h-3.5 {{ $isEnabled ? 'text-indigo-600' : 'text-slate-400' }}"></i>
                                    <span>{{ $prod->name }}</span>
                                </button>
                            </form>
                            @else
                            <div class="px-3 py-1.5 rounded-xl text-xs font-semibold flex items-center space-x-1.5 border {{ $isEnabled ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : 'bg-slate-50 text-slate-400 border-slate-200' }}">
                                <i data-lucide="{{ $isEnabled ? 'check-circle-2' : 'circle' }}" class="w-3.5 h-3.5 {{ $isEnabled ? 'text-indigo-600' : 'text-slate-400' }}"></i>
                                <span>{{ $prod->name }}</span>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Footer Actions: Edit Agency & Direct Admin Access -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs gap-2">
                    <a href="{{ route('admin.agencies.admin-login', $agency) }}" target="_blank"
                       class="py-2 px-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-center shadow-md shadow-indigo-600/20 transition-all flex items-center space-x-1.5">
                        <i data-lucide="key" class="w-3.5 h-3.5"></i>
                        <span>Admin Access (No Password)</span>
                    </a>

                    <div class="flex items-center space-x-2">
                        <!-- Edit Agency Button -->
                        @if(auth()->user()->hasPermission('edit-agencies'))
                        <button onclick="openEditAgencyModal({{ json_encode($agency) }})" 
                                class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-indigo-700 font-bold border border-slate-200 transition-colors flex items-center space-x-1">
                            <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                            <span>Edit</span>
                        </button>
                        @endif

                        @if(auth()->user()->hasPermission('delete-agencies'))
                        <button type="button" 
                                onclick="openDeleteAgencyModal({{ $agency->id }}, '{{ addslashes($agency->name) }}', '{{ route('admin.agencies.destroy', $agency) }}')"
                                class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-rose-100 text-slate-500 hover:text-rose-700 border border-slate-200 transition-colors flex items-center space-x-1"
                                title="Delete Whitelabel">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                            <span>Remove</span>
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Agencies List View (Task 2) -->
    <div id="agenciesListView" class="card-white rounded-2xl overflow-hidden border border-slate-200 hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-4">Whitelabel Agency</th>
                        <th class="py-3.5 px-4">Owner & Contact</th>
                        <th class="py-3.5 px-4">Plan & Capacity</th>
                        <th class="py-3.5 px-4">Authorized SaaS Products</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($agencies as $agency)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-4 px-4 align-top">
                                <div class="flex items-start space-x-3">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-700 font-extrabold flex items-center justify-center shrink-0 text-sm">
                                        {{ substr($agency->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="flex items-center space-x-1.5 flex-wrap">
                                            <span class="font-bold text-slate-900 text-sm leading-tight">{{ $agency->name }}</span>
                                            @if($agency->type === 'master')
                                                <span class="px-1.5 py-0.5 rounded text-[9px] font-extrabold bg-violet-100 text-violet-700 uppercase">Master</span>
                                            @else
                                                <span class="px-1.5 py-0.5 rounded text-[9px] font-extrabold bg-sky-100 text-sky-700 uppercase">White Label</span>
                                            @endif
                                        </div>
                                        @if(!empty($agency->custom_domain))
                                            <a href="https://{{ $agency->custom_domain }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 text-[11px] font-semibold mt-1 flex items-center gap-1">
                                                <span>{{ $agency->custom_domain }}</span>
                                                <i data-lucide="external-link" class="w-3 h-3"></i>
                                            </a>
                                        @else
                                            <span class="text-slate-400 text-[10px] block mt-0.5 font-mono">No custom domain</span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="py-4 px-4 align-top">
                                <div class="space-y-1">
                                    <div class="font-bold text-slate-800">{{ $agency->owner_name }}</div>
                                    <div class="text-slate-500 text-[11px] flex items-center gap-1">
                                        <i data-lucide="mail" class="w-3.5 h-3.5 text-slate-400"></i>
                                        <span>{{ $agency->email }}</span>
                                    </div>
                                    @if(!empty($agency->phone))
                                        <div class="text-slate-600 text-[11px] flex items-center gap-1">
                                            <i data-lucide="phone" class="w-3.5 h-3.5 text-slate-400"></i>
                                            <span>{{ $agency->phone }}</span>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <td class="py-4 px-4 align-top">
                                <div class="space-y-1">
                                    <div class="font-bold text-slate-900 flex items-center gap-1">
                                        <i data-lucide="tag" class="w-3.5 h-3.5 text-violet-600"></i>
                                        <span>{{ \App\Models\Setting::getCurrencySymbol() }}{{ number_format($agency->subscription?->amount ?? 0) }}/{{ $agency->subscription?->billing_cycle === 'yearly' ? 'yr' : 'mo' }}</span>
                                    </div>
                                    <div class="text-slate-600 text-[11px] flex items-center gap-1">
                                        <i data-lucide="users" class="w-3.5 h-3.5 text-emerald-600"></i>
                                        <span>{{ $agency->max_clients >= 99999 ? 'Unlimited' : number_format($agency->max_clients) . ' Clients' }}</span>
                                    </div>
                                </div>
                            </td>

                            <td class="py-4 px-4 align-top">
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($products as $prod)
                                        @php
                                            $assigned = $agency->products->firstWhere('id', $prod->id);
                                            $isEnabled = $assigned && $assigned->pivot->status === 'enabled';
                                        @endphp
                                        <span class="px-2 py-0.5 rounded-lg text-[10px] font-semibold flex items-center space-x-1 border {{ $isEnabled ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : 'bg-slate-50 text-slate-400 border-slate-200 opacity-60' }}">
                                            <i data-lucide="{{ $isEnabled ? 'check-circle-2' : 'circle' }}" class="w-3 h-3"></i>
                                            <span>{{ $prod->name }}</span>
                                        </span>
                                    @endforeach
                                </div>
                            </td>

                            <td class="py-4 px-4 align-top">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $agency->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ ucfirst($agency->status) }}
                                </span>
                            </td>

                            <td class="py-4 px-4 align-top text-right whitespace-nowrap">
                                <div class="flex items-center justify-end space-x-1.5">
                                    <a href="{{ route('admin.agencies.admin-login', $agency) }}" target="_blank"
                                       class="px-2.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-center shadow-sm text-xs transition-all flex items-center space-x-1"
                                       title="Login as Whitelabel Admin">
                                        <i data-lucide="key" class="w-3.5 h-3.5"></i>
                                        <span>Admin Access</span>
                                    </a>

                                    @if(auth()->user()->hasPermission('edit-agencies'))
                                    <button onclick="openEditAgencyModal({{ json_encode($agency) }})" 
                                            class="p-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-indigo-700 font-bold border border-slate-200 transition-colors"
                                            title="Edit Agency">
                                        <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                    </button>
                                    @endif

                                    @if(auth()->user()->hasPermission('delete-agencies'))
                                    <button type="button" 
                                            onclick="openDeleteAgencyModal({{ $agency->id }}, '{{ addslashes($agency->name) }}', '{{ route('admin.agencies.destroy', $agency) }}')"
                                            class="p-1.5 rounded-xl bg-slate-100 hover:bg-rose-100 text-slate-500 hover:text-rose-700 border border-slate-200 transition-colors"
                                            title="Delete Whitelabel">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">
                                No agencies found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination (Task 2) -->
    @if($agencies->hasPages())
        <div class="pt-4">
            {{ $agencies->links() }}
        </div>
    @endif
</div>
@endsection

@push('modals')
<!-- Onboard Agency Modal -->
<div id="createAgencyModal" class="fixed inset-0 z-[100] {{ $errors->any() ? '' : 'hidden' }} bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 max-w-xl w-full shadow-2xl overflow-y-auto max-h-[90vh]">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-slate-900 font-heading">Onboard Agency Account</h3>
            <button onclick="document.getElementById('createAgencyModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        @if($errors->any())
            <div class="mb-4 p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
                <p class="font-bold flex items-center text-rose-900"><i data-lucide="alert-circle" class="w-4 h-4 mr-1 text-rose-600 shrink-0"></i> Please fix the following errors:</p>
                <ul class="list-disc list-inside space-y-0.5 text-rose-700 font-medium pl-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div id="onboardClientErrors" class="hidden mb-4 p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
            <p class="font-bold flex items-center text-rose-900"><i data-lucide="alert-circle" class="w-4 h-4 mr-1 text-rose-600 shrink-0"></i> Please fix the following errors:</p>
            <ul id="onboardClientErrorsList" class="list-disc list-inside space-y-0.5 text-rose-700 font-medium pl-1"></ul>
        </div>

        <form id="createAgencyForm" action="{{ route('admin.agencies.store') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Agency Name</label>
                    <input type="text" name="name" placeholder="e.g. ABC Digital Agency" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Role Type</label>
                    <select name="type" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                        <option value="white_label">White Label Reseller Agency</option>
                        <option value="master">Master Plan Admin Agency</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Assign Master Agency Parent (Optional)</label>
                <select name="parent_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                    <option value="">-- Direct Super Admin Client --</option>
                    @foreach($masterAgencies as $master)
                        <option value="{{ $master->id }}">{{ $master->name }} (Master)</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Owner Full Name</label>
                    <input type="text" name="owner_name" placeholder="John Doe" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Owner Email</label>
                    <input type="email" name="email" placeholder="owner@agency.com" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Owner Mobile Number</label>
                    <input type="text" name="phone" placeholder="+91 9876543210" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Account Password</label>
                    <input type="password" name="password" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Custom Domain</label>
                    <input type="text" name="custom_domain" placeholder="app.agency.com" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Subscription Price (₹)</label>
                    <input type="number" name="price_monthly" placeholder="e.g. 2999" value="2999" step="1" min="0" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:bg-white focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Billing Cycle</label>
                    <select name="billing_cycle" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:bg-white focus:border-indigo-500">
                        <option value="monthly" selected>Per Month (/mo)</option>
                        <option value="yearly">Per Year (/yr)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Max End-Clients</label>
                    <input type="number" name="max_clients" placeholder="e.g. 100 or 999999" value="100" min="1" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:bg-white focus:border-indigo-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">Initial SaaS Product Access</label>
                <div class="grid grid-cols-2 gap-2">
                    @foreach($products as $product)
                        <label class="flex items-center space-x-2 text-xs text-slate-700 p-2 rounded-xl bg-slate-50 border border-slate-200 cursor-pointer">
                            <input type="checkbox" name="products[]" value="{{ $product->id }}" checked class="rounded bg-slate-100 border-slate-300 text-indigo-600">
                            <span>{{ $product->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl">
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="checkbox" name="hide_developer_attribution" value="1" class="rounded bg-slate-100 border-slate-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4">
                    <div>
                        <span class="text-xs font-bold text-slate-800 block">Hide "Developed by Nooryak Technologies"</span>
                        <span class="text-[11px] text-slate-500 block">Remove developer attribution and Nooryak link from this whitelabel agency's footer.</span>
                    </div>
                </label>
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3">
                <button type="button" onclick="document.getElementById('createAgencyModal').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-slate-500 hover:text-slate-800">Cancel</button>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-600/30">Onboard Agency</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Agency Modal -->
<div id="editAgencyModal" class="fixed inset-0 z-[100] hidden bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 max-w-xl w-full shadow-2xl overflow-y-auto max-h-[90vh]">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-slate-900 font-heading">Edit Agency Settings</h3>
            <button onclick="document.getElementById('editAgencyModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="editAgencyForm" action="" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Agency Name</label>
                    <input type="text" id="ea_name" name="name" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Role Type</label>
                    <select id="ea_type" name="type" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                        <option value="white_label">White Label Reseller Agency</option>
                        <option value="master">Master Plan Admin Agency</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Assign Master Agency Parent</label>
                <select id="ea_parent_id" name="parent_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                    <option value="">-- Direct Super Admin Client --</option>
                    @foreach($masterAgencies as $master)
                        <option value="{{ $master->id }}">{{ $master->name }} (Master)</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Owner Full Name</label>
                    <input type="text" id="ea_owner_name" name="owner_name" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Owner Mobile Number</label>
                    <input type="text" id="ea_phone" name="phone" placeholder="+91 9876543210" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Account Status</label>
                    <select id="ea_status" name="status" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                        <option value="active">Active</option>
                        <option value="pending">Pending</option>
                        <option value="suspended">Suspended</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Subscription Price (₹)</label>
                    <input type="number" id="ea_price_monthly" name="price_monthly" placeholder="e.g. 2999" step="1" min="0" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:bg-white focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Billing Cycle</label>
                    <select id="ea_billing_cycle" name="billing_cycle" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:bg-white focus:border-indigo-500">
                        <option value="monthly">Per Month (/mo)</option>
                        <option value="yearly">Per Year (/yr)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Max End-Clients</label>
                    <input type="number" id="ea_max_clients" name="max_clients" placeholder="e.g. 100 or 999999" min="1" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:bg-white focus:border-indigo-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Custom Domain</label>
                <input type="text" id="ea_custom_domain" name="custom_domain" placeholder="app.agency.com" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Reset Password (Leave blank to keep existing)</label>
                <input type="password" id="ea_password" name="password" placeholder="New Password (optional)" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
            </div>

            <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl">
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="checkbox" id="ea_hide_developer_attribution" name="hide_developer_attribution" value="1" class="rounded bg-slate-100 border-slate-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4">
                    <div>
                        <span class="text-xs font-bold text-slate-800 block">Hide "Developed by Nooryak Technologies"</span>
                        <span class="text-[11px] text-slate-500 block">Remove developer attribution and Nooryak link from this whitelabel agency's footer.</span>
                    </div>
                </label>
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3">
                <button type="button" onclick="document.getElementById('editAgencyModal').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-slate-500 hover:text-slate-800">Cancel</button>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-600/30">Update Agency</button>
            </div>
        </form>
    </div>
</div>

<!-- Broadcast Notice Modal -->
<div id="broadcastModal" class="fixed inset-0 z-[100] hidden bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 max-w-xl w-full shadow-2xl overflow-y-auto max-h-[90vh]">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-2xl bg-amber-500/10 text-amber-600 flex items-center justify-center">
                    <i data-lucide="megaphone" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900 font-heading">Broadcast Notice & Push Alert</h3>
                    <p class="text-xs text-slate-500">Send announcements to White-Label Agencies across all channels</p>
                </div>
            </div>
            <button onclick="document.getElementById('broadcastModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('admin.agencies.broadcast') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Target Recipients</label>
                    <select name="target_agency_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 font-semibold">
                        <option value="all">📢 All Active White-Label Agencies</option>
                        @foreach($agencies as $ag)
                            <option value="{{ $ag->id }}">{{ $ag->name }} ({{ $ag->owner_name }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Notice Priority / Type</label>
                    <select name="type" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 font-semibold">
                        <option value="info">🔵 General Info / Update</option>
                        <option value="warning" selected>🟡 High Priority Announcement</option>
                        <option value="critical">🔴 Critical Alert / Maintenance</option>
                        <option value="success">🟢 Feature Launch / Success</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Notice Title / Subject <span class="text-rose-500">*</span></label>
                <input type="text" name="title" placeholder="e.g. Scheduled System Maintenance / New AI Engine Update" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 font-medium">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Announcement Message <span class="text-rose-500">*</span></label>
                <textarea name="message" rows="4" placeholder="Type your broadcast announcement message here..." required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900"></textarea>
            </div>

            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                <p class="text-xs font-bold text-slate-800">Dispatch Channels:</p>
                <label class="flex items-center space-x-2 text-xs text-slate-700 cursor-pointer">
                    <input type="checkbox" checked disabled class="rounded bg-slate-200 border-slate-300 text-indigo-600">
                    <span class="font-semibold text-slate-900">📌 Top Header Notice Banner on Whitelabel Dashboard</span>
                    <span class="text-[10px] text-slate-400">(Agency can dismiss with close button)</span>
                </label>
                <label class="flex items-center space-x-2 text-xs text-slate-700 cursor-pointer">
                    <input type="checkbox" name="send_email" value="1" checked class="rounded bg-slate-100 border-slate-300 text-indigo-600">
                    <span>✉️ Send Email to Agency Owner Registered Email</span>
                </label>
                <label class="flex items-center space-x-2 text-xs text-slate-700 cursor-pointer">
                    <input type="checkbox" name="send_mobile" value="1" checked class="rounded bg-slate-100 border-slate-300 text-indigo-600">
                    <span>📱 Send WhatsApp / SMS Alert to Agency Owner Registered Mobile</span>
                </label>
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3">
                <button type="button" onclick="document.getElementById('broadcastModal').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-slate-500 hover:text-slate-800">Cancel</button>
                <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-amber-500/25 flex items-center space-x-2">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    <span>Send Broadcast Notice</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Full-Screen Onboarding & Cloud Provisioning Loader Modal (Task 1) -->
<div id="onboardingLoaderOverlay" class="fixed inset-0 z-[200] hidden bg-slate-950/85 backdrop-blur-md flex items-center justify-center p-4">
    <div class="bg-gradient-to-b from-slate-900 to-slate-950 border border-indigo-500/30 rounded-3xl p-6 sm:p-10 max-w-lg w-full shadow-2xl text-center relative overflow-hidden animate-in fade-in zoom-in duration-300">
        
        <!-- Glowing Ambient Lighting Effects -->
        <div class="absolute -top-24 -left-24 w-48 h-48 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-48 h-48 bg-cyan-500/20 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Animated Centerpiece: Orbital Spinner & Cloud DB Icon -->
        <div class="relative w-24 h-24 mx-auto mb-6 flex items-center justify-center">
            <!-- Pulsing ring -->
            <div class="absolute inset-0 rounded-full bg-gradient-to-tr from-indigo-500 to-cyan-400 opacity-25 animate-ping"></div>
            <!-- Outer spinning gradient border -->
            <div class="absolute inset-0 rounded-full border-2 border-transparent border-t-indigo-500 border-r-cyan-400 animate-spin"></div>
            <!-- Inner glowing circle -->
            <div class="w-16 h-16 rounded-full bg-slate-800 border border-indigo-500/40 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                <i data-lucide="database" class="w-8 h-8 text-indigo-400 animate-pulse"></i>
            </div>
        </div>

        <!-- Header Titles -->
        <h3 class="text-xl sm:text-2xl font-black text-white font-heading tracking-tight mb-2">
            Provisioning Agency Infrastructure
        </h3>
        <p class="text-xs text-slate-300 mb-6 leading-relaxed">
            Allocating cloud database schemas, creating tenant tables, and configuring security credentials.
        </p>

        <!-- Prominent Safety Warning Alert Banner -->
        <div class="mb-6 p-4 rounded-2xl bg-amber-500/15 border border-amber-500/30 text-amber-200 text-xs font-semibold flex items-start space-x-3 text-left">
            <i data-lucide="alert-triangle" class="w-5 h-5 text-amber-400 shrink-0 mt-0.5 animate-bounce"></i>
            <div>
                <span class="font-extrabold text-amber-300 block uppercase tracking-wider text-[11px]">⚠️ Critical Notice:</span>
                <span>Please <strong>do NOT close, refresh, or leave this page</strong>! Database provisioning is in progress and interrupting will result in incomplete schema setup.</span>
            </div>
        </div>

        <!-- Real-Time Progress Bar & Percentage Counter (Task 1) -->
        <div class="space-y-2 mb-6">
            <div class="flex items-center justify-between text-xs font-bold">
                <span id="onboardPhaseBadge" class="text-indigo-300 bg-indigo-950/80 px-2.5 py-1 rounded-lg border border-indigo-800/60 uppercase tracking-wider text-[10px]">
                    Phase 1 of 6
                </span>
                <span id="onboardPercentText" class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-sky-400 to-emerald-400">
                    0%
                </span>
            </div>

            <div class="w-full bg-slate-800 rounded-full h-3.5 overflow-hidden p-0.5 border border-slate-700/80 shadow-inner">
                <div id="onboardProgressBar" class="h-full rounded-full bg-gradient-to-r from-indigo-500 via-cyan-400 to-emerald-400 transition-all duration-300 ease-out" style="width: 2%;"></div>
            </div>
        </div>

        <!-- Dynamic Phase Step Indicator -->
        <div class="p-3.5 rounded-2xl bg-slate-800/60 border border-slate-700/50 flex items-center justify-center space-x-2.5">
            <div id="onboardPulseDot" class="w-2.5 h-2.5 rounded-full bg-cyan-400 animate-ping shrink-0"></div>
            <p id="onboardPhaseStatus" class="text-xs text-slate-200 font-medium truncate">
                Initializing agency configuration...
            </p>
        </div>
    </div>
</div>

<!-- Delete Whitelabel Confirmation Modal (Task 2) -->
<div id="deleteAgencyModal" class="fixed inset-0 z-[110] hidden bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-7 max-w-md w-full shadow-2xl animate-in fade-in zoom-in duration-200">
        <div class="flex items-center space-x-3 mb-4">
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0 border border-rose-100">
                <i data-lucide="alert-triangle" class="w-6 h-6"></i>
            </div>
            <div>
                <h3 class="text-base font-bold text-slate-900 font-heading">Delete Whitelabel Confirmation</h3>
                <p class="text-xs text-slate-500">Super Admin Whitelabel Management</p>
            </div>
        </div>

        <div class="p-4 rounded-2xl bg-rose-50/60 border border-rose-100 text-slate-700 text-xs leading-relaxed mb-6 space-y-2">
            <p class="font-semibold text-rose-900 text-sm">
                Do you want to delete that whitelabel?
            </p>
            <p class="text-slate-800">
                Target Whitelabel: <strong id="delete_agency_name" class="text-slate-950 font-bold underline"></strong>
            </p>
            <p class="text-[11px] text-slate-500">
                ⚠️ Warning: Removing this whitelabel will immediately terminate agency access, suspend client logins, unlink reseller domain routing, and permanently drop all dedicated MySQL tenant databases from the server to reclaim disk space.
            </p>
        </div>

        <form id="deleteAgencyForm" action="" method="POST" class="flex items-center justify-end space-x-3">
            @csrf
            @method('DELETE')
            <button type="button" onclick="closeDeleteAgencyModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition-colors">
                Cancel
            </button>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-lg shadow-rose-600/20 transition-all flex items-center space-x-1.5">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
                <span>Yes, Delete Whitelabel</span>
            </button>
        </form>
    </div>
</div>
@endpush

@push('scripts')
<script>
    // ── Task 2: View Switcher (Grid vs List) ──────────────────────
    function switchView(mode) {
        const gridView = document.getElementById('agenciesGridView');
        const listView = document.getElementById('agenciesListView');
        const btnGrid = document.getElementById('btnGridView');
        const btnList = document.getElementById('btnListView');

        if (!gridView || !listView) return;

        if (mode === 'list') {
            gridView.classList.add('hidden');
            listView.classList.remove('hidden');

            btnGrid.classList.remove('bg-white', 'text-indigo-600', 'shadow-sm');
            btnGrid.classList.add('text-slate-500');

            btnList.classList.add('bg-white', 'text-indigo-600', 'shadow-sm');
            btnList.classList.remove('text-slate-500');

            localStorage.setItem('agencies_view_mode', 'list');
        } else {
            listView.classList.add('hidden');
            gridView.classList.remove('hidden');

            btnList.classList.remove('bg-white', 'text-indigo-600', 'shadow-sm');
            btnList.classList.add('text-slate-500');

            btnGrid.classList.add('bg-white', 'text-indigo-600', 'shadow-sm');
            btnGrid.classList.remove('text-slate-500');

            localStorage.setItem('agencies_view_mode', 'grid');
        }

        if (window.lucide) lucide.createIcons();
    }

    // Restore saved view on page load
    document.addEventListener('DOMContentLoaded', function () {
        const savedView = localStorage.getItem('agencies_view_mode') || 'grid';
        switchView(savedView);
    });

    // ── Task 1: Onboarding Real-time Progress Loader ──────────────
    let beforeUnloadActive = false;
    function handleBeforeUnload(e) {
        if (!beforeUnloadActive) return;
        e.preventDefault();
        e.returnValue = 'Provisioning is in progress! Closing now may corrupt the agency database setup.';
        return e.returnValue;
    }

    const createAgencyForm = document.getElementById('createAgencyForm');
    if (createAgencyForm) {
        createAgencyForm.addEventListener('submit', function (e) {
            e.preventDefault();

            // Client-side HTML5 validity check
            if (!createAgencyForm.checkValidity()) {
                createAgencyForm.reportValidity();
                return;
            }

            const errorBox = document.getElementById('onboardClientErrors');
            const errorList = document.getElementById('onboardClientErrorsList');
            if (errorBox) errorBox.classList.add('hidden');
            if (errorList) errorList.innerHTML = '';

            // Hide form modal, show full screen provisioning loader
            const createModal = document.getElementById('createAgencyModal');
            if (createModal) createModal.classList.add('hidden');

            const loaderOverlay = document.getElementById('onboardingLoaderOverlay');
            if (loaderOverlay) loaderOverlay.classList.remove('hidden');

            // Activate beforeunload browser lock
            beforeUnloadActive = true;
            window.addEventListener('beforeunload', handleBeforeUnload);

            const percentText = document.getElementById('onboardPercentText');
            const progressBar = document.getElementById('onboardProgressBar');
            const phaseBadge = document.getElementById('onboardPhaseBadge');
            const phaseStatus = document.getElementById('onboardPhaseStatus');

            let currentProgress = 0;
            let targetProgress = 15;
            let currentPhaseIndex = 0;

            const phases = [
                { target: 15, badge: 'Phase 1 of 6', text: 'Phase 1: Validating agency credentials and DNS...' },
                { target: 35, badge: 'Phase 2 of 6', text: 'Phase 2: Connecting to cPanel API & allocating resources...' },
                { target: 60, badge: 'Phase 3 of 6', text: 'Phase 3: Creating dedicated MySQL tenant database...' },
                { target: 82, badge: 'Phase 4 of 6', text: 'Phase 4: Importing SaaS database tables & core schema...' },
                { target: 94, badge: 'Phase 5 of 6', text: 'Phase 5: Granting secure user privileges & configuring access...' },
                { target: 98, badge: 'Phase 6 of 6', text: 'Phase 6: Verifying schema integrity & finalizing agency setup...' },
            ];

            // Animate progress smoothly
            const progressInterval = setInterval(function () {
                if (currentProgress < targetProgress) {
                    currentProgress += Math.random() * 1.5 + 0.4;
                    if (currentProgress > targetProgress) currentProgress = targetProgress;
                    
                    const rounded = Math.floor(currentProgress);
                    if (percentText) percentText.textContent = rounded + '%';
                    if (progressBar) progressBar.style.width = Math.max(rounded, 3) + '%';
                }

                // Advance phases automatically over time if server is working
                if (currentProgress >= targetProgress && currentPhaseIndex < phases.length - 1) {
                    currentPhaseIndex++;
                    targetProgress = phases[currentPhaseIndex].target;
                    if (phaseBadge) phaseBadge.textContent = phases[currentPhaseIndex].badge;
                    if (phaseStatus) phaseStatus.textContent = phases[currentPhaseIndex].text;
                }
            }, 120);

            // Execute submission via fetch API
            const formData = new FormData(createAgencyForm);
            fetch(createAgencyForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(async function (response) {
                const isJson = response.headers.get('content-type')?.includes('application/json');
                const data = isJson ? await response.json() : null;

                if (!response.ok) {
                    throw { status: response.status, data: data };
                }

                return data;
            })
            .then(function (data) {
                clearInterval(progressInterval);

                // Smoothly finish to 100%
                let finishProgress = currentProgress;
                const finishInterval = setInterval(function () {
                    finishProgress += 3;
                    if (finishProgress >= 100) {
                        finishProgress = 100;
                        clearInterval(finishInterval);

                        if (percentText) percentText.textContent = '100%';
                        if (progressBar) progressBar.style.width = '100%';
                        if (phaseBadge) phaseBadge.textContent = 'Completed';
                        if (phaseStatus) phaseStatus.textContent = '✓ Agency Cloud Infrastructure Provisioned Successfully!';

                        beforeUnloadActive = false;
                        window.removeEventListener('beforeunload', handleBeforeUnload);

                        setTimeout(function () {
                            window.location.href = (data && data.redirect_url) ? data.redirect_url : window.location.href;
                        }, 800);
                    } else {
                        if (percentText) percentText.textContent = Math.floor(finishProgress) + '%';
                        if (progressBar) progressBar.style.width = finishProgress + '%';
                    }
                }, 30);
            })
            .catch(function (err) {
                clearInterval(progressInterval);
                beforeUnloadActive = false;
                window.removeEventListener('beforeunload', handleBeforeUnload);

                if (loaderOverlay) loaderOverlay.classList.add('hidden');
                if (createModal) createModal.classList.remove('hidden');

                // Render error messages
                if (errorBox && errorList) {
                    errorList.innerHTML = '';
                    if (err.data && err.data.errors) {
                        Object.values(err.data.errors).flat().forEach(function (msg) {
                            const li = document.createElement('li');
                            li.textContent = msg;
                            errorList.appendChild(li);
                        });
                    } else if (err.data && err.data.message) {
                        const li = document.createElement('li');
                        li.textContent = err.data.message;
                        errorList.appendChild(li);
                    } else {
                        const li = document.createElement('li');
                        li.textContent = 'An unexpected server error occurred during provisioning. Please check inputs and try again.';
                        errorList.appendChild(li);
                    }
                    errorBox.classList.remove('hidden');
                    if (window.lucide) lucide.createIcons();
                }
            });
        });
    }

    // ── Agency Modals (Delete & Edit) ─────────────────────────────
    function openDeleteAgencyModal(id, name, deleteUrl) {
        document.getElementById('delete_agency_name').textContent = name;
        document.getElementById('deleteAgencyForm').action = deleteUrl;
        document.getElementById('deleteAgencyModal').classList.remove('hidden');
        if (window.lucide) lucide.createIcons();
    }

    function closeDeleteAgencyModal() {
        document.getElementById('deleteAgencyModal').classList.add('hidden');
    }

    function openEditAgencyModal(agency) {
        document.getElementById('editAgencyForm').action = "{{ url(env('ADMIN_LOGIN_SLUG', 'F7_AdMn-GaTeWaY_9x')) }}/agencies/" + agency.id;
        document.getElementById('ea_name').value = agency.name || '';
        document.getElementById('ea_type').value = agency.type || 'white_label';
        document.getElementById('ea_parent_id').value = agency.parent_id || '';
        document.getElementById('ea_owner_name').value = agency.owner_name || '';
        document.getElementById('ea_phone').value = agency.phone || '';
        document.getElementById('ea_status').value = agency.status || 'active';
        document.getElementById('ea_custom_domain').value = agency.custom_domain || '';
        document.getElementById('ea_max_clients').value = agency.max_clients || 100;
        document.getElementById('ea_price_monthly').value = (agency.subscription && agency.subscription.amount != null) ? agency.subscription.amount : 2999;
        document.getElementById('ea_billing_cycle').value = (agency.subscription && agency.subscription.billing_cycle) ? agency.subscription.billing_cycle : 'monthly';
        var hideAttrEl = document.getElementById('ea_hide_developer_attribution');
        if (hideAttrEl) {
            hideAttrEl.checked = (agency.hide_developer_attribution == 1 || agency.hide_developer_attribution === true);
        }

        document.getElementById('editAgencyModal').classList.remove('hidden');
    }
</script>
@endpush
