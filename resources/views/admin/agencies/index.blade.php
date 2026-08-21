@extends('layouts.admin')

@section('title', 'Agencies Directory')
@section('page_title', 'Master Agencies & White-Label Agencies Management')

@section('header_actions')
<button onclick="document.getElementById('createAgencyModal').classList.remove('hidden')" 
        class="px-2.5 py-1.5 sm:px-4 sm:py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-600/30 flex items-center space-x-1.5 sm:space-x-2 transition-all whitespace-nowrap">
    <i data-lucide="building" class="w-4 h-4"></i>
    <span>+ Onboard Agency</span>
</button>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Tabs & Filter Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
        <div class="flex items-center space-x-2 overflow-x-auto w-full sm:w-auto pb-2 sm:pb-0">
            <a href="{{ route('admin.agencies.index') }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-all {{ !$type ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'text-slate-600 hover:text-slate-900 bg-white border border-slate-200' }}">
                All Agencies
            </a>
            <a href="{{ route('admin.agencies.index', ['type' => 'master']) }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-all {{ $type === 'master' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'text-slate-600 hover:text-slate-900 bg-white border border-slate-200' }}">
                Master Agencies
            </a>
            <a href="{{ route('admin.agencies.index', ['type' => 'white_label']) }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-all {{ $type === 'white_label' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'text-slate-600 hover:text-slate-900 bg-white border border-slate-200' }}">
                White Label Resellers
            </a>
        </div>
    </div>

    <!-- Agencies Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

                <!-- Quotas & Domain -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-slate-400 block text-[11px]">Client Quota</span>
                        <span class="font-bold text-slate-900 mt-0.5 block">{{ $agency->max_clients }} Allowed</span>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-slate-400 block text-[11px]">Custom Branding Domain</span>
                        <span class="font-mono text-indigo-600 mt-0.5 block truncate">{{ $agency->custom_domain ?? 'Not configured' }}</span>
                    </div>
                </div>

                <!-- Product Entitlements Section (Launchshop, CRM, Builder) -->
                <div>
                    <span class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">Authorized SaaS Products (Click to Toggle Access)</span>
                    <div class="flex flex-wrap gap-2">
                        @foreach($products as $prod)
                            @php
                                $assigned = $agency->products->firstWhere('id', $prod->id);
                                $isEnabled = $assigned && $assigned->pivot->status === 'enabled';
                            @endphp
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
                        <!-- Edit Agency Button (Task 3) -->
                        <button onclick="openEditAgencyModal({{ json_encode($agency) }})" 
                                class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-indigo-700 font-bold border border-slate-200 transition-colors flex items-center space-x-1">
                            <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                            <span>Edit</span>
                        </button>

                        <form action="{{ route('admin.agencies.destroy', $agency) }}" method="POST" onsubmit="return confirm('Suspend/Remove agency {{ $agency->name }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-rose-100 text-slate-500 hover:text-rose-700 border border-slate-200 transition-colors">
                                Remove
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Onboard Agency Modal -->
<div id="createAgencyModal" class="fixed inset-0 z-50 hidden bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 max-w-xl w-full shadow-2xl overflow-y-auto max-h-[90vh]">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-slate-900 font-heading">Onboard Agency Account</h3>
            <button onclick="document.getElementById('createAgencyModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('admin.agencies.store') }}" method="POST" class="space-y-4">
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

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Owner Full Name</label>
                    <input type="text" name="owner_name" placeholder="John Doe" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Owner Email</label>
                    <input type="email" name="email" placeholder="owner@agency.com" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
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

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Select Subscription Plan</label>
                    <select name="plan_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}">{{ $plan->name }} ({{ \App\Models\Setting::getCurrencySymbol() }}{{ number_format($plan->price_monthly, 0) }}/mo)</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Max End-Clients Quota</label>
                    <input type="number" name="max_clients" value="50" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
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

            <div class="pt-4 flex items-center justify-end space-x-3">
                <button type="button" onclick="document.getElementById('createAgencyModal').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-slate-500 hover:text-slate-800">Cancel</button>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-600/30">Onboard Agency</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Agency Modal (Task 3 Implementation) -->
<div id="editAgencyModal" class="fixed inset-0 z-50 hidden bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
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

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Owner Full Name</label>
                    <input type="text" id="ea_owner_name" name="owner_name" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
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

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Custom Domain</label>
                    <input type="text" id="ea_custom_domain" name="custom_domain" placeholder="app.agency.com" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Max End-Clients Quota</label>
                    <input type="number" id="ea_max_clients" name="max_clients" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Reset Password (Leave blank to keep existing)</label>
                <input type="password" id="ea_password" name="password" placeholder="New Password (optional)" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3">
                <button type="button" onclick="document.getElementById('editAgencyModal').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-slate-500 hover:text-slate-800">Cancel</button>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-600/30">Update Agency</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openEditAgencyModal(agency) {
        document.getElementById('editAgencyForm').action = "/admin/agencies/" + agency.id;
        document.getElementById('ea_name').value = agency.name || '';
        document.getElementById('ea_type').value = agency.type || 'white_label';
        document.getElementById('ea_parent_id').value = agency.parent_id || '';
        document.getElementById('ea_owner_name').value = agency.owner_name || '';
        document.getElementById('ea_status').value = agency.status || 'active';
        document.getElementById('ea_custom_domain').value = agency.custom_domain || '';
        document.getElementById('ea_max_clients').value = agency.max_clients || 50;

        document.getElementById('editAgencyModal').classList.remove('hidden');
    }
</script>
@endpush
@endsection
