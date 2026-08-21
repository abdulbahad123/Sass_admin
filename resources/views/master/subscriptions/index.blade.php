@extends('layouts.master')

@section('title', 'Subscriptions Management')

@section('content')
<div class="space-y-6">

    <div>
        <h2 class="text-xl font-bold text-slate-900 font-heading">Sub-Agency Subscriptions</h2>
        <p class="text-xs text-slate-500">{{ $subscriptions->count() }} Subscriptions across sub-agency resellers</p>
    </div>

    <div class="card-white rounded-2xl p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        <th class="pb-3">Sub-Agency</th>
                        <th class="pb-3">Assigned Plan</th>
                        <th class="pb-3">Billing Cycle</th>
                        <th class="pb-3">Amount</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3 text-right">Subscription ID</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($subscriptions as $sub)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-4 font-bold text-slate-900">
                                {{ $sub->agency->name ?? 'Sub-Agency Partner' }}
                            </td>
                            <td class="py-4 text-slate-600 font-medium">{{ $sub->plan->name ?? 'Growth' }}</td>
                            <td class="py-4 uppercase text-[10px] font-bold text-indigo-600">{{ $sub->billing_cycle }}</td>
                            <td class="py-4 font-extrabold text-emerald-600">₹{{ number_format($sub->amount, 2) }}</td>
                            <td class="py-4">
                                <form action="{{ route('master.subscriptions.update-status', $sub) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" 
                                            class="bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold px-2 py-1 text-slate-800 focus:outline-none">
                                        <option value="active" {{ $sub->status === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="trial" {{ $sub->status === 'trial' ? 'selected' : '' }}>Trial</option>
                                        <option value="past_due" {{ $sub->status === 'past_due' ? 'selected' : '' }}>Past Due</option>
                                        <option value="cancelled" {{ $sub->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </form>
                            </td>
                            <td class="py-4 text-right font-mono text-[10px] text-slate-400">SUB-{{ str_pad($sub->id, 5, '0', STR_PAD_LEFT) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 text-xs font-medium">
                                No active sub-agency subscriptions found in network.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
