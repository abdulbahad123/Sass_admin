@extends('layouts.whitelabel')

@section('title', 'Branding & Domain Settings')

@section('content')
<div class="space-y-6 max-w-4xl">
    <div>
        <h2 class="text-xl font-bold text-slate-900 font-heading">Custom Branding & Domain Configuration</h2>
        <p class="text-xs text-slate-500">Personalize your agency branding, logos, color scheme, and custom CNAME domain</p>
    </div>

    <form action="{{ route('whitelabel.branding.update') }}" method="POST" class="card-white rounded-2xl p-6 space-y-6">
        @csrf
        <div class="space-y-4">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2">Agency Identity</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Agency Name</label>
                    <input type="text" name="agency_name" value="{{ $agency->name ?? 'Apex Digital Agency' }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-xs font-bold text-slate-900 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Custom CNAME Domain</label>
                    <input type="text" name="custom_domain" value="{{ $agency->custom_domain ?? 'app.apexdigital.com' }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-xs font-mono font-bold text-blue-600 focus:outline-none">
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2">Brand Palette</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Primary Color Accent</label>
                    <input type="color" name="primary_color" value="{{ $agency->primary_color ?? '#3b82f6' }}" class="w-full h-10 rounded-xl cursor-pointer">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Secondary Color Accent</label>
                    <input type="color" name="secondary_color" value="#8b5cf6" class="w-full h-10 rounded-xl cursor-pointer">
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2">White-Label AI Engine API Keys</h3>
            <p class="text-xs text-slate-500">Provide default Gemini and OpenAI API keys for your white-label clients</p>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Agency Gemini API Key</label>
                    <input type="text" name="gemini_api_key" value="{{ $agency->gemini_api_key ?? '' }}" placeholder="AIzaSy..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-xs font-mono text-slate-900 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Agency OpenAI API Key</label>
                    <input type="text" name="openai_api_key" value="{{ $agency->openai_api_key ?? '' }}" placeholder="sk-proj-..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-xs font-mono text-slate-900 focus:outline-none">
                </div>
            </div>
        </div>

        <div class="pt-4 flex justify-end">
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs shadow-md shadow-indigo-600/30">Save Branding Settings</button>
        </div>
    </form>
</div>
@endsection
