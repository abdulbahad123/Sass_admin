@extends('layouts.whitelabel')

@section('title', 'Authorized SaaS Products')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-xl font-bold text-slate-900 font-heading">Authorized SaaS Products</h2>
        <p class="text-xs text-slate-500">Enable or configure white-label products for your client network</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($products as $prod)
            @php
                $subdomainUrl = isset($agency) && $agency ? $agency->getProductSubdomainUrl($prod->slug ?? $prod->name) : "https://" . \Illuminate\Support\Str::slug($prod->name) . ".nooryak.in";
            @endphp
            <div class="card-white rounded-2xl p-6 flex flex-col justify-between space-y-4 hover:shadow-md transition">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-lg">
                        <i data-lucide="box" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 font-heading text-base">{{ $prod->name }}</h3>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">Active</span>
                    </div>
                </div>
                <p class="text-xs text-slate-500">{{ $prod->description ?? 'White-label SaaS product suite enabled for your end-clients.' }}</p>
                <div class="space-y-1 bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                    <span class="text-[10px] text-slate-400 font-semibold block">Subdomain App Launch URL:</span>
                    <a href="{{ $subdomainUrl }}" target="_blank" class="text-xs font-mono font-bold text-indigo-600 hover:text-indigo-800 flex items-center truncate">
                        <i data-lucide="globe" class="w-3.5 h-3.5 mr-1 flex-shrink-0 text-indigo-500"></i>
                        <span class="truncate">{{ $subdomainUrl }}</span>
                    </a>
                </div>
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-slate-400 font-medium">Monthly Cost: <span class="text-slate-800 font-bold">₹{{ number_format($prod->base_price ?? 99, 0) }}</span></span>
                    <a href="{{ $subdomainUrl }}" target="_blank" class="px-3 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs flex items-center space-x-1 shadow-sm">
                        <span>Launch Product</span>
                        <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
