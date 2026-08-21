@extends('layouts.master')

@section('title', 'Reports & Analytics')

@section('content')
<div class="space-y-6">

    <div>
        <h2 class="text-xl font-bold text-slate-900 font-heading">Network Reports & Growth Analytics</h2>
        <p class="text-xs text-slate-500">Comprehensive performance analysis for sub-agencies and end-client expansion</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card-white rounded-2xl p-6">
            <span class="text-xs text-slate-400 font-bold uppercase">Network Adoption Rate</span>
            <h3 class="text-3xl font-extrabold text-indigo-600 font-heading mt-2">{{ $adoptionRate }}%</h3>
            <p class="text-xs text-slate-500 mt-1">{{ $activeCount }} of {{ $totalSubAgencies }} active resellers</p>
        </div>
        <div class="card-white rounded-2xl p-6">
            <span class="text-xs text-slate-400 font-bold uppercase">Average Sub-Agency MRR</span>
            <h3 class="text-3xl font-extrabold text-emerald-600 font-heading mt-2">₹{{ number_format($avgMrr) }}</h3>
            <p class="text-xs text-slate-500 mt-1">Per reseller agency</p>
        </div>
        <div class="card-white rounded-2xl p-6">
            <span class="text-xs text-slate-400 font-bold uppercase">Client Retention</span>
            <h3 class="text-3xl font-extrabold text-sky-600 font-heading mt-2">{{ $retentionRate }}%</h3>
            <p class="text-xs text-slate-500 mt-1">Active subscriptions ratio</p>
        </div>
    </div>

</div>
@endsection
