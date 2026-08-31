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
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs gap-2">
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

        <!-- AI Engine & API Key Access Card -->
        <div class="card-white rounded-2xl p-6 flex flex-col justify-between space-y-4 hover:shadow-md transition border-2 border-purple-100 bg-gradient-to-br from-white to-purple-50/30">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 rounded-2xl bg-purple-100 text-purple-600 flex items-center justify-center font-bold text-lg">
                    <i data-lucide="cpu" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 font-heading text-base">AI Engines & API Keys</h3>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-700">Gemini & OpenAI</span>
                </div>
            </div>
            <p class="text-xs text-slate-500">Configure your agency's Gemini and OpenAI API keys for white-label client stores.</p>
            <div class="space-y-1 bg-white p-2.5 rounded-xl border border-slate-200">
                <span class="text-[10px] text-slate-400 font-semibold block">Configured API Keys:</span>
                <span class="text-xs font-mono font-bold text-purple-600 flex items-center">
                    <i data-lucide="key" class="w-3.5 h-3.5 mr-1 text-purple-500"></i>
                    Gemini: {{ !empty($agency->gemini_api_key) ? 'Custom Key Set' : 'Default System Key' }} | OpenAI: {{ !empty($agency->openai_api_key) ? 'Custom Key Set' : 'Default System Key' }}
                </span>
            </div>
            <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs gap-2">
                <a href="{{ route('whitelabel.ai-settings.index') }}" 
                   class="w-full py-2 px-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl flex items-center justify-center space-x-1.5 shadow-sm">
                    <i data-lucide="settings" class="w-3.5 h-3.5"></i>
                    <span>Configure AI API Keys</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
