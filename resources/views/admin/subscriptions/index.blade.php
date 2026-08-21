@extends('layouts.admin')

@section('title', 'Subscriptions & Billing')
@section('page_title', 'Agency Subscriptions & Platform Billing Oversight')

@section('content')
<div class="space-y-6">

    <!-- Stat Header -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div class="card-white rounded-2xl p-6">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Active Revenue</p>
            <h3 class="text-3xl font-extrabold text-emerald-600 font-heading mt-2">{{ \App\Models\Setting::getCurrencySymbol() }}{{ number_format($totalRevenue, 2) }}</h3>
            <p class="text-xs text-slate-400 mt-1">Across all subscribed Master & White Label Agencies</p>
        </div>
        <div class="card-white rounded-2xl p-6">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Active Subscriptions</p>
            <h3 class="text-3xl font-extrabold text-slate-900 font-heading mt-2">{{ number_format($activeCount) }}</h3>
            <p class="text-xs text-slate-400 mt-1">Automatic recurring billing active</p>
        </div>
    </div>

    <!-- Table -->
    <div class="card-white rounded-2xl p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        <th class="pb-3">Agency</th>
                        <th class="pb-3">Assigned Plan</th>
                        <th class="pb-3">Cycle</th>
                        <th class="pb-3">Amount</th>
                        <th class="pb-3">Started Date</th>
                        <th class="pb-3">Billing Status</th>
                        <th class="pb-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @foreach($subscriptions as $sub)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-4 font-bold text-slate-900">
                                {{ $sub->agency->name ?? 'Unknown Agency' }}
                                <span class="block text-[10px] text-slate-400 font-mono">{{ $sub->agency->email ?? '' }}</span>
                            </td>
                            <td class="py-4 text-slate-600 font-medium">{{ $sub->plan->name ?? 'Standard' }}</td>
                            <td class="py-4 uppercase text-[10px] font-bold text-indigo-600">{{ $sub->billing_cycle }}</td>
                            <td class="py-4 font-extrabold text-emerald-600">{{ \App\Models\Setting::getCurrencySymbol() }}{{ number_format($sub->amount, 2) }}</td>
                            <td class="py-4 text-slate-500">{{ $sub->starts_at->format('M d, Y') }}</td>
                            <td class="py-4">
                                <form action="{{ route('admin.subscriptions.update-status', $sub) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" 
                                            class="bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold px-2.5 py-1 text-slate-800 focus:outline-none">
                                        <option value="active" {{ $sub->status === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="trial" {{ $sub->status === 'trial' ? 'selected' : '' }}>Trial</option>
                                        <option value="past_due" {{ $sub->status === 'past_due' ? 'selected' : '' }}>Past Due</option>
                                        <option value="cancelled" {{ $sub->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </form>
                            </td>
                            <td class="py-4 text-right text-slate-400">
                                <span class="text-[10px] font-mono font-semibold">SUB-{{ str_pad($sub->id, 5, '0', STR_PAD_LEFT) }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
