@extends('layouts.master')

@section('title', 'Agencies Management')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900 font-heading">Agencies Network</h2>
            <p class="text-xs text-slate-500">Manage reseller agencies operating under {{ $masterAgency->name ?? 'Apex Master Ventures' }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <!-- View Toggle -->
            <div class="flex items-center bg-slate-100 rounded-xl p-1 border border-slate-200">
                <button onclick="switchView('grid')" id="gridViewBtn" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-white text-slate-900 shadow-sm">
                    <i data-lucide="grid-3x3" class="w-4 h-4"></i>
                </button>
                <button onclick="switchView('list')" id="listViewBtn" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-500">
                    <i data-lucide="list" class="w-4 h-4"></i>
                </button>
            </div>

            <button onclick="document.getElementById('createSubAgencyModal').classList.remove('hidden')" 
                    class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-600/30 flex items-center space-x-2 transition-all">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Onboard Agency</span>
            </button>
        </div>
    </div>

    <!-- Grid View -->
    <div id="gridView" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($subAgencies as $sub)
            <div class="card-white rounded-2xl p-6 space-y-4 hover:shadow-md transition-all">
                
                <!-- Agency Header -->
                <div class="flex items-start justify-between">
                    <div class="flex items-center space-x-3.5">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 font-bold text-lg flex items-center justify-center border border-indigo-100">
                            {{ substr($sub->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="flex items-center space-x-2">
                                <h3 class="text-base font-bold text-slate-900 font-heading">{{ $sub->name }}</h3>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-sky-100 text-sky-700 uppercase">Agency</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5">Owner: <span class="text-slate-800 font-medium">{{ $sub->owner_name }}</span></p>
                        </div>
                    </div>

                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">
                        {{ ucfirst($sub->status) }}
                    </span>
                </div>

                <!-- Domain & Quota -->
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-slate-400 block text-[11px]">Client Quota</span>
                        <span class="font-bold text-slate-900 mt-0.5 block">{{ $sub->max_clients }} Allowed</span>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-slate-400 block text-[11px]">Branding Domain</span>
                        <span class="font-mono text-indigo-600 mt-0.5 block truncate">{{ $sub->custom_domain ?? 'app.subagency.com' }}</span>
                    </div>
                </div>

                <!-- Product Toggles -->
                <div>
                    <span class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">Product Access (Click to Toggle)</span>
                    <div class="flex flex-wrap gap-2">
                        @foreach($products as $prod)
                            @php
                                $assigned = $sub->products->firstWhere('id', $prod->id);
                                $isEnabled = $assigned && $assigned->pivot->status === 'enabled';
                            @endphp
                            <form action="{{ route('master.sub-agencies.toggle-product', [$sub, $prod]) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="px-2.5 py-1 rounded-xl text-xs font-semibold flex items-center space-x-1.5 border transition-all {{ $isEnabled ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : 'bg-slate-50 text-slate-400 border-slate-200' }}">
                                    <i data-lucide="{{ $isEnabled ? 'check-circle-2' : 'circle' }}" class="w-3.5 h-3.5"></i>
                                    <span>{{ $prod->name }}</span>
                                </button>
                            </form>
                        @endforeach
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                    <a href="https://launchshop.in/X9_AdMiN-Portal_V7" target="_blank" 
                       class="py-2 px-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl flex items-center space-x-1.5 shadow-sm">
                        <i data-lucide="key" class="w-3.5 h-3.5"></i>
                        <span>Admin Access</span>
                    </a>

                    <div class="flex items-center space-x-2">
                        <button onclick="openEditModal({{ json_encode($sub) }})" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-indigo-700 font-bold border border-slate-200">
                            Edit
                        </button>
                        <form action="{{ route('master.sub-agencies.destroy', $sub) }}" method="POST" onsubmit="return confirm('Remove agency {{ $sub->name }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-rose-100 text-slate-500 hover:text-rose-700 border border-slate-200">
                                Remove
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- List View -->
    <div id="listView" class="hidden">
        <div class="card-white rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr class="text-xs font-bold uppercase tracking-wider text-slate-600">
                            <th class="px-6 py-4 text-left">Agency Name</th>
                            <th class="px-6 py-4 text-left">Owner</th>
                            <th class="px-6 py-4 text-left">Status</th>
                            <th class="px-6 py-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($subAgencies as $sub)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 font-bold flex items-center justify-center border border-indigo-100">
                                            {{ substr($sub->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900 text-sm">{{ $sub->name }}</div>
                                            <div class="text-xs text-slate-500 font-mono">{{ $sub->custom_domain ?? 'app.subagency.com' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-slate-900">{{ $sub->owner_name }}</div>
                                    <div class="text-xs text-slate-500">{{ $sub->email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">
                                        {{ ucfirst($sub->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button onclick="openViewModal({{ json_encode($sub) }})" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs">
                                            View
                                        </button>
                                        <a href="https://launchshop.in/X9_AdMiN-Portal_V7" target="_blank" 
                                           class="px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs">
                                            Admin
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex items-center justify-between">
            {{ $subAgencies->links() }}
        </div>
    </div>
</div>

@push('modals')
<!-- Onboard Agency Modal -->
<div id="createSubAgencyModal" class="fixed inset-0 z-[100] hidden bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl overflow-y-auto max-h-[90vh]">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-slate-900 font-heading">Onboard Agency</h3>
            <button onclick="document.getElementById('createSubAgencyModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('master.sub-agencies.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Agency Name</label>
                <input type="text" name="name" placeholder="e.g. Digital Solutions Hub" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Owner Name</label>
                    <input type="text" name="owner_name" placeholder="Amit Verma" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Owner Email</label>
                    <input type="email" name="email" placeholder="owner@subagency.com" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Account Password</label>
                    <input type="password" name="password" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Custom Domain</label>
                    <input type="text" name="custom_domain" placeholder="app.subagency.com" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Assigned Pricing Plan</label>
                    <select name="plan_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}">{{ $plan->name }} ({{ \App\Models\Setting::getCurrencySymbol() }}{{ number_format($plan->price_monthly, 0) }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Max Client Quota</label>
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
                <button type="button" onclick="document.getElementById('createSubAgencyModal').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-slate-500 hover:text-slate-800">Cancel</button>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-600/30">Save Agency</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Agency Modal -->
<div id="editSubAgencyModal" class="fixed inset-0 z-[100] hidden bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-slate-900 font-heading">Edit Agency</h3>
            <button onclick="document.getElementById('editSubAgencyModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="editSubAgencyForm" action="" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Agency Name</label>
                <input type="text" id="es_name" name="name" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Owner Name</label>
                    <input type="text" id="es_owner_name" name="owner_name" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Status</label>
                    <select id="es_status" name="status" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                        <option value="active">Active</option>
                        <option value="pending">Pending</option>
                        <option value="suspended">Suspended</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Custom Domain</label>
                    <input type="text" id="es_custom_domain" name="custom_domain" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Max Client Quota</label>
                    <input type="number" id="es_max_clients" name="max_clients" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3">
                <button type="button" onclick="document.getElementById('editSubAgencyModal').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-slate-500 hover:text-slate-800">Cancel</button>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-600/30">Update Agency</button>
            </div>
        </form>
    </div>
</div>

<!-- View Agency Modal -->
<div id="viewSubAgencyModal" class="fixed inset-0 z-[100] hidden bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 max-w-2xl w-full shadow-2xl">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-slate-900 font-heading">Agency Details</h3>
            <button onclick="document.getElementById('viewSubAgencyModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                    <span class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Agency Name</span>
                    <span id="view_name" class="block text-sm font-bold text-slate-900">-</span>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                    <span class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Owner Name</span>
                    <span id="view_owner" class="block text-sm font-bold text-slate-900">-</span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                    <span class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Email Address</span>
                    <span id="view_email" class="block text-sm font-mono text-indigo-600">-</span>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                    <span class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Status</span>
                    <span id="view_status" class="block text-sm font-bold text-emerald-700">-</span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                    <span class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Custom Domain</span>
                    <span id="view_domain" class="block text-sm font-mono text-slate-900">-</span>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                    <span class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Max Clients</span>
                    <span id="view_clients" class="block text-sm font-bold text-slate-900">-</span>
                </div>
            </div>

            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                <span class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Agency Type</span>
                <span id="view_type" class="block text-sm font-bold text-slate-900">-</span>
            </div>
        </div>

        <div class="pt-6 flex items-center justify-end">
            <button onclick="document.getElementById('viewSubAgencyModal').classList.add('hidden')" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold">Close</button>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    function switchView(view) {
        const gridView = document.getElementById('gridView');
        const listView = document.getElementById('listView');
        const gridBtn = document.getElementById('gridViewBtn');
        const listBtn = document.getElementById('listViewBtn');

        if (view === 'grid') {
            gridView.classList.remove('hidden');
            listView.classList.add('hidden');
            gridBtn.classList.add('bg-white', 'text-slate-900', 'shadow-sm');
            gridBtn.classList.remove('text-slate-500');
            listBtn.classList.remove('bg-white', 'text-slate-900', 'shadow-sm');
            listBtn.classList.add('text-slate-500');
        } else {
            gridView.classList.add('hidden');
            listView.classList.remove('hidden');
            listBtn.classList.add('bg-white', 'text-slate-900', 'shadow-sm');
            listBtn.classList.remove('text-slate-500');
            gridBtn.classList.remove('bg-white', 'text-slate-900', 'shadow-sm');
            gridBtn.classList.add('text-slate-500');
        }
        lucide.createIcons();
    }

    function openEditModal(sub) {
        document.getElementById('editSubAgencyForm').action = "/master/sub-agencies/" + sub.id;
        document.getElementById('es_name').value = sub.name || '';
        document.getElementById('es_owner_name').value = sub.owner_name || '';
        document.getElementById('es_status').value = sub.status || 'active';
        document.getElementById('es_custom_domain').value = sub.custom_domain || '';
        document.getElementById('es_max_clients').value = sub.max_clients || 50;

        document.getElementById('editSubAgencyModal').classList.remove('hidden');
        lucide.createIcons();
    }

    function openViewModal(sub) {
        document.getElementById('view_name').textContent = sub.name || 'N/A';
        document.getElementById('view_owner').textContent = sub.owner_name || 'N/A';
        document.getElementById('view_email').textContent = sub.email || 'N/A';
        document.getElementById('view_status').textContent = sub.status ? sub.status.charAt(0).toUpperCase() + sub.status.slice(1) : 'N/A';
        document.getElementById('view_domain').textContent = sub.custom_domain || 'app.subagency.com';
        document.getElementById('view_clients').textContent = sub.max_clients || '0';
        document.getElementById('view_type').textContent = sub.type ? sub.type.replace('_', ' ').toUpperCase() : 'N/A';
        
        document.getElementById('viewSubAgencyModal').classList.remove('hidden');
        lucide.createIcons();
    }
</script>
@endpush
@endsection
