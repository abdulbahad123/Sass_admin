@extends('layouts.master')

@section('title', 'Products & Access Management')

@section('content')
<div class="space-y-6">

    <div>
        <h2 class="text-xl font-bold text-slate-900 font-heading">SaaS Products & Entitlements</h2>
        <p class="text-xs text-slate-500">Products licensed to Apex Master Ventures by Super Admin for sub-agency distribution</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($products as $prod)
            <div class="card-white rounded-2xl p-6 flex flex-col justify-between hover:shadow-md transition-all">
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
                    <p class="text-xs text-slate-500 line-clamp-3 leading-relaxed">{{ $prod->description }}</p>
                </div>

                <div class="pt-6 border-t border-slate-100 mt-6 flex items-center justify-between text-xs">
                    <span class="text-slate-400 font-mono text-[11px]">slug: {{ $prod->slug }}</span>

                    <a href="https://launchshop.in/X9_AdMiN-Portal_V7" target="_blank" 
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
