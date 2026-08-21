@extends('layouts.master')

@section('title', 'Billing & Invoices')

@section('content')
<div class="space-y-6">

    <div>
        <h2 class="text-xl font-bold text-slate-900 font-heading">Billing & Invoice Management</h2>
        <p class="text-xs text-slate-500">Network revenue invoices and billing history</p>
    </div>

    <div class="card-white rounded-2xl p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        <th class="pb-3">Invoice Number</th>
                        <th class="pb-3">Sub-Agency</th>
                        <th class="pb-3">Amount</th>
                        <th class="pb-3">Invoice Date</th>
                        <th class="pb-3">Payment Status</th>
                        <th class="pb-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @foreach($invoices as $inv)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-4 font-mono font-bold text-indigo-600">{{ $inv['id'] }}</td>
                            <td class="py-4 font-bold text-slate-900">{{ $inv['agency'] }}</td>
                            <td class="py-4 font-extrabold text-emerald-600">₹{{ number_format($inv['amount']) }}</td>
                            <td class="py-4 text-slate-500">{{ $inv['date'] }}</td>
                            <td class="py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $inv['status'] === 'Paid' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $inv['status'] }}
                                </span>
                            </td>
                            <td class="py-4 text-right">
                                <button class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold border border-slate-200">
                                    Download PDF
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
