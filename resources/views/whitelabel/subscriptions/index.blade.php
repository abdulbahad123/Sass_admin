@extends('layouts.whitelabel')

@section('title', 'Subscriptions Management')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-xl font-bold text-slate-900 font-heading">Client Subscriptions</h2>
        <p class="text-xs text-slate-500">186 Active client recurring subscriptions</p>
    </div>

    <div class="card-white rounded-2xl p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-[11px] font-bold uppercase text-slate-400">
                        <th class="pb-3">Client</th>
                        <th class="pb-3">Plan</th>
                        <th class="pb-3">Cycle</th>
                        <th class="pb-3">Amount</th>
                        <th class="pb-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @foreach($subscriptions as $sub)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-4 font-bold text-slate-900">{{ $sub->agency->name ?? 'TechNova Solutions' }}</td>
                            <td class="py-4 text-slate-600">{{ $sub->plan->name ?? 'Growth Plan' }}</td>
                            <td class="py-4 uppercase text-indigo-600 font-bold">{{ $sub->billing_cycle }}</td>
                            <td class="py-4 font-extrabold text-emerald-600">₹{{ number_format($sub->amount, 2) }}</td>
                            <td class="py-4"><span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">Active</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
