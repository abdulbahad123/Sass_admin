@extends('layouts.whitelabel')

@section('title', 'AI Engine & API Key Access')

@section('content')
<div class="space-y-6 max-w-4xl">
    <div>
        <h2 class="text-xl font-bold text-slate-900 font-heading">AI Engine & API Key Access Management</h2>
        <p class="text-xs text-slate-500">Provide white-label agency API keys for Gemini and OpenAI to power content & image generation for your clients</p>
    </div>

    <form action="{{ route('whitelabel.ai-settings.update') }}" method="POST" class="card-white rounded-2xl p-6 space-y-6">
        @csrf
        
        <!-- Gemini AI Engine Section -->
        <div class="space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs">
                        <i data-lucide="cpu" class="w-4 h-4"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 font-heading">Google Gemini AI Engine</h3>
                </div>
                <div class="flex items-center space-x-2">
                    <label class="text-xs font-semibold text-slate-600">Engine Status:</label>
                    <select name="is_gemini_active" class="bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1 text-xs font-bold text-slate-800 focus:outline-none">
                        <option value="1" {{ ($agency->is_gemini_active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ ($agency->is_gemini_active ?? 1) == 0 ? 'selected' : '' }}>Deactive</option>
                    </select>
                </div>
            </div>
            
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Agency Gemini API Key</label>
                <input type="text" name="gemini_api_key" value="{{ $agency->gemini_api_key ?? '' }}" placeholder="AIzaSy..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-mono text-slate-900 focus:outline-none focus:border-blue-500 focus:bg-white">
                <p class="text-[11px] text-amber-600 mt-1">
                    Enter your Google Gemini API key to override the system key for all clients registered under your white-label agency.
                </p>
            </div>
        </div>

        <!-- OpenAI Engine Section -->
        <div class="space-y-4 pt-4 border-t border-slate-100">
            <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs">
                        <i data-lucide="bot" class="w-4 h-4"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 font-heading">OpenAI ChatGPT & DALL-E Engine</h3>
                </div>
                <div class="flex items-center space-x-2">
                    <label class="text-xs font-semibold text-slate-600">Engine Status:</label>
                    <select name="is_openai_active" class="bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1 text-xs font-bold text-slate-800 focus:outline-none">
                        <option value="1" {{ ($agency->is_openai_active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ ($agency->is_openai_active ?? 1) == 0 ? 'selected' : '' }}>Deactive</option>
                    </select>
                </div>
            </div>
            
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Agency OpenAI API Key</label>
                <input type="text" name="openai_api_key" value="{{ $agency->openai_api_key ?? '' }}" placeholder="sk-proj-..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-mono text-slate-900 focus:outline-none focus:border-emerald-500 focus:bg-white">
                <p class="text-[11px] text-amber-600 mt-1">
                    Enter your OpenAI API key to provide text & DALL-E image generation capabilities for your end-clients.
                </p>
            </div>
        </div>

        <!-- Info Card -->
        <div class="p-4 rounded-xl bg-indigo-50/70 border border-indigo-100 text-xs text-indigo-950 space-y-1">
            <h4 class="font-bold flex items-center space-x-1 text-indigo-900">
                <i data-lucide="info" class="w-4 h-4 text-indigo-600 mr-1"></i>
                <span>3-Tier AI API Key Fallback Rule</span>
            </h4>
            <p class="text-[11px] text-indigo-800">
                1. If an end-client enters their own custom API key on their store dashboard, their key is used first.<br>
                2. If the client has no custom key, your White-Label Agency API key configured here will be automatically used.<br>
                3. If neither is set, the SuperAdmin default system key will be used as the ultimate fallback.
            </p>
        </div>

        <div class="pt-2 flex justify-end">
            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs shadow-md shadow-blue-600/30 flex items-center space-x-2">
                <i data-lucide="save" class="w-4 h-4"></i>
                <span>Save AI API Access Settings</span>
            </button>
        </div>
    </form>
</div>
@endsection
