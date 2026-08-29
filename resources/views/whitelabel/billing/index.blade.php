@extends('layouts.whitelabel')

@section('title', 'Billing & Invoices')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900 font-heading">Billing & Client Invoices</h2>
            <p class="text-xs text-slate-500">Automated recurring invoices issued under {{ $agency->name ?? 'Apex Digital Agency' }}</p>
        </div>
        <button class="px-4 py-2.5 bg-indigo-600 text-white rounded-xl text-xs font-bold shadow-md shadow-indigo-600/30">Create Custom Invoice</button>
    </div>

    <div class="card-white rounded-2xl p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-[11px] font-bold uppercase text-slate-400">
                        <th class="pb-3">Invoice Number</th>
                        <th class="pb-3">Client</th>
                        <th class="pb-3">Amount</th>
                        <th class="pb-3">Issued Date</th>
                        <th class="pb-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @foreach($invoices as $inv)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-4 font-mono font-bold text-indigo-600">{{ $inv['id'] }}</td>
                            <td class="py-4 font-bold text-slate-900">{{ $inv['client'] }}</td>
                            <td class="py-4 font-extrabold text-emerald-600">{{ $inv['amount'] }}</td>
                            <td class="py-4 text-slate-500">{{ $inv['date'] }}</td>
                            <td class="py-4"><span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">{{ $inv['status'] }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
