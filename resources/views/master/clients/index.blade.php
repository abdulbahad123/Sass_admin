@extends('layouts.master')

@section('title', 'Network Clients Management')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-3">
                <h2 class="text-xl font-bold text-slate-900 font-heading">Network Clients Directory</h2>
                <span class="px-2.5 py-1 rounded-full text-xs font-extrabold bg-indigo-100 text-indigo-700">
                    {{ count($clients) }} Total Network Clients
                </span>
            </div>
            <div class="flex items-center space-x-2 mt-1.5 flex-wrap gap-y-1">
                <span class="text-xs text-slate-500 font-medium">Product Allocation:</span>
                @forelse($productCounts as $pName => $pCount)
                    <span class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-violet-50 text-violet-700 border border-violet-100">
                        {{ $pName }}: {{ $pCount }}
                    </span>
                @empty
                    <span class="text-xs text-slate-400">No active products yet</span>
                @endforelse
            </div>
        </div>
        <button onclick="document.getElementById('createClientModal').classList.remove('hidden')" 
                class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-600/30 flex items-center space-x-2 transition-all self-start sm:self-auto">
            <i data-lucide="user-plus" class="w-4 h-4"></i>
            <span>Add End Client</span>
        </button>
    </div>

    <div class="card-white rounded-2xl p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        <th class="pb-3">Client Business</th>
                        <th class="pb-3">Contact Email</th>
                        <th class="pb-3">Managing Sub-Agency</th>
                        <th class="pb-3">Product Allocated</th>
                        <th class="pb-3">Client Tier</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3 text-right">Joined Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($clients as $c)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-4 font-bold text-slate-900">
                                {{ $c['name'] }}
                                <span class="block text-[10px] text-slate-400 font-medium">Owner: {{ $c['owner'] }}</span>
                            </td>
                            <td class="py-4 text-indigo-600 font-mono text-[11px]">{{ $c['email'] }}</td>
                            <td class="py-4 font-medium text-slate-700">{{ $c['sub_agency'] }}</td>
                            <td class="py-4">
                                <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-violet-50 text-violet-700 border border-violet-100 inline-flex items-center space-x-1">
                                    <i data-lucide="box" class="w-3 h-3 text-violet-500 mr-1"></i>
                                    <span>{{ $c['product'] }}</span>
                                </span>
                            </td>
                            <td class="py-4 text-slate-500">{{ $c['plan'] }}</td>
                            <td class="py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">
                                    {{ $c['status'] }}
                                </span>
                            </td>
                            <td class="py-4 text-right text-slate-400 font-mono text-[11px]">{{ $c['joined'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400 text-xs font-medium">
                                No network end-clients found. Click <strong>Add End Client</strong> to create one!
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
<div id="createClientModal" class="fixed inset-0 z-[100] hidden bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-slate-900 font-heading">Add End Client Account</h3>
            <button onclick="document.getElementById('createClientModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('master.clients.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Business Name</label>
                <input type="text" name="name" placeholder="FashionBoutique Online" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Owner Name</label>
                    <input type="text" name="owner" placeholder="Karan Malhotra" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Email</label>
                    <input type="email" name="email" placeholder="owner@client.com" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Managing Sub-Agency</label>
                    <select name="sub_agency_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                        <option value="">Direct Master Client</option>
                        @foreach($subAgencies as $sub)
                            <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Product Allocated</label>
                    <select name="product_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                        @foreach($products as $p)
                            <option value="{{ $p->id }}" {{ $p->slug === 'launchshop' ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3">
                <button type="button" onclick="document.getElementById('createClientModal').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-slate-500 hover:text-slate-800">Cancel</button>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-600/30">Save Client</button>
            </div>
        </form>
    </div>
</div>
@endpush
@endsection
