@extends('layouts.whitelabel')

@section('title', 'End-Clients Directory')

@section('header_actions')
    <button onclick="document.getElementById('addClientModal').classList.remove('hidden')" 
            class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-blue-600/30 flex items-center space-x-1.5 transition-all whitespace-nowrap">
        <i data-lucide="plus" class="w-4 h-4"></i>
        <span>Add New Client</span>
    </button>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-3">
                <h2 class="text-xl font-bold text-slate-900 font-heading">Client Management</h2>
                <span class="px-2.5 py-1 rounded-full text-xs font-extrabold bg-blue-100 text-blue-700">
                    {{ count($clients) }} Total Clients
                </span>
            </div>
            <div class="flex items-center space-x-2 mt-1.5 flex-wrap gap-y-1">
                <span class="text-xs text-slate-500 font-medium">Product Breakdown:</span>
                @forelse($productCounts as $pName => $pCount)
                    <span class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                        {{ $pName }}: {{ $pCount }}
                    </span>
                @empty
                    <span class="text-xs text-slate-400">No active products yet</span>
                @endforelse
            </div>
        </div>
        <button onclick="document.getElementById('addClientModal').classList.remove('hidden')" 
                class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md shadow-blue-600/30 flex items-center space-x-1.5 self-start sm:self-auto">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Add New Client</span>
        </button>
    </div>

    <div class="card-white rounded-2xl p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-[11px] font-bold uppercase text-slate-400">
                        <th class="pb-3">Client Name</th>
                        <th class="pb-3">Contact Person</th>
                        <th class="pb-3">Email</th>
                        <th class="pb-3">Registered Product</th>
                        <th class="pb-3">Plan</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3">Joined On</th>
                        <th class="pb-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($clients as $client)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-4 font-bold text-slate-900">{{ $client['name'] }}</td>
                            <td class="py-4 text-slate-600">{{ $client['contact'] }}</td>
                            <td class="py-4 text-slate-500 font-mono text-[11px]">{{ $client['email'] }}</td>
                            <td class="py-4">
                                <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100 inline-flex items-center space-x-1">
                                    <i data-lucide="box" class="w-3 h-3 text-indigo-500 mr-1"></i>
                                    <span>{{ $client['product_name'] }}</span>
                                </span>
                            </td>
                            <td class="py-4"><span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700">{{ $client['plan'] }}</span></td>
                            <td class="py-4"><span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">{{ $client['status'] }}</span></td>
                            <td class="py-4 text-slate-500">{{ $client['joined'] }}</td>
                            <td class="py-4 text-right">
                                <button class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[11px]">Manage</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-400 text-xs font-medium">
                                No end-clients onboarded yet. Click <strong>Add New Client</strong> to add one!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('modals')
<!-- Add Client Modal -->
<div id="addClientModal" class="fixed inset-0 z-[100] hidden bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-slate-900 font-heading">Onboard New End-Client</h3>
            <button onclick="document.getElementById('addClientModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('whitelabel.clients.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Client Full Name / Business Name</label>
                <input type="text" name="name" placeholder="John Doe / My Business Store" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Email Address</label>
                <input type="email" name="email" placeholder="client@example.com" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Select SaaS Product</label>
                    <select name="product_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                        @foreach($products as $p)
                            <option value="{{ $p->id }}" {{ $p->slug === 'launchshop' ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Subscription Plan</label>
                    <select name="plan" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                        <option value="Growth">Growth Plan</option>
                        <option value="Starter">Starter Plan</option>
                        <option value="Enterprise">Enterprise Plan</option>
                    </select>
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3">
                <button type="button" onclick="document.getElementById('addClientModal').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-slate-500 hover:text-slate-800">Cancel</button>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md shadow-blue-600/30">Onboard Client</button>
            </div>
        </form>
    </div>
</div>
@endpush
@endsection
