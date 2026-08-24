@extends('layouts.master')

@section('title', 'Products & Access Management')

@section('content')
<div class="space-y-6">

    <div>
        <h2 class="text-xl font-bold text-slate-900 font-heading">SaaS Products & Entitlements</h2>
        <p class="text-xs text-slate-500">Products licensed to Master Agency for sub-agency distribution</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($products as $prod)
            @php
                $subdomainUrl = $prod->getSubdomainPreviewUrl();
            @endphp
            <div class="card-white rounded-2xl p-6 flex flex-col justify-between space-y-4 hover:shadow-md transition-all">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-bold">
                            <i data-lucide="{{ $prod->icon ?? 'box' }}" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 font-heading">{{ $prod->name }}</h3>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">Licensed</span>
                        </div>
                    </div>

                    <p class="text-xs font-semibold text-slate-800 mb-1">{{ $prod->tagline }}</p>
                    <p class="text-xs text-slate-500 line-clamp-3 leading-relaxed mb-3">{{ $prod->description }}</p>

                    <div class="p-2 bg-slate-50 border border-slate-100 rounded-xl">
                        <span class="text-[10px] text-slate-400 font-semibold block">Subdomain App Preview:</span>
                        <a href="{{ $subdomainUrl }}" target="_blank" class="text-xs font-mono font-bold text-indigo-600 hover:underline flex items-center truncate">
                            <i data-lucide="globe" class="w-3.5 h-3.5 mr-1 flex-shrink-0 text-indigo-500"></i>
                            <span class="truncate">{{ $subdomainUrl }}</span>
                        </a>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs gap-2">
                    <a href="{{ $subdomainUrl }}" target="_blank" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl flex items-center space-x-1 border border-slate-200">
                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                        <span>Live Preview</span>
                    </a>

                    <a href="{{ route('admin.products.admin-launch', $prod) }}" target="_blank" 
                       class="py-2 px-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl flex items-center space-x-1.5 shadow-sm">
                        <i data-lucide="key" class="w-3.5 h-3.5"></i>
                        <span>Admin Access</span>
                    </a>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection
