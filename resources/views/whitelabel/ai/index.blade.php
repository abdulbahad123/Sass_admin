@extends('layouts.whitelabel')

@section('title', 'AI Engine & API Key Access')

@section('content')
<div class="space-y-6 max-w-4xl">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900 font-heading">AI Engine & API Key Access Management</h2>
            <p class="text-xs text-slate-500">Provide white-label agency API keys or configure AI access per registered customer</p>
        </div>

        <!-- Client Selector Dropdown -->
        <div class="flex items-center space-x-2">
            <label class="text-xs font-bold text-slate-700 whitespace-nowrap">Configure Target:</label>
            <select onchange="window.location.href='{{ route('whitelabel.ai-settings.index') }}?client_id=' + this.value"
                    class="bg-white border-2 border-indigo-200 rounded-xl px-3.5 py-2 text-xs font-bold text-indigo-900 shadow-sm focus:outline-none focus:border-indigo-600 cursor-pointer">
                <option value="global" {{ $selectedClientId === 'global' ? 'selected' : '' }}>
                    🌐 All Clients (Agency Global Default)
                </option>
                <optgroup label="Registered Customers (Real Users)">
                    @foreach($clients as $c)
                        <option value="{{ $c['id'] }}" {{ (string)$selectedClientId === (string)$c['id'] ? 'selected' : '' }}>
                            👤 {{ $c['name'] }} ({{ $c['email'] ?: $c['username'] }})
                        </option>
                    @endforeach
                </optgroup>
            </select>
        </div>
    </div>

    <!-- Active Target Banner -->
    <div class="p-4 rounded-2xl border {{ $selectedClientId !== 'global' ? 'bg-indigo-50/80 border-indigo-200 text-indigo-950' : 'bg-slate-100 border-slate-200 text-slate-800' }} flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl {{ $selectedClientId !== 'global' ? 'bg-indigo-600 text-white' : 'bg-slate-700 text-white' }} flex items-center justify-center font-bold">
                <i data-lucide="{{ $selectedClientId !== 'global' ? 'user-check' : 'globe' }}" class="w-5 h-5"></i>
            </div>
            <div>
                <h3 class="font-bold text-xs uppercase tracking-wider">
                    {{ $selectedClientId !== 'global' ? 'Specific Client Configuration Mode' : 'Global Agency Default Mode' }}
                </h3>
                <p class="text-xs font-medium">
                    @if($selectedClientId !== 'global' && isset($selectedClient))
                        Editing AI access and custom keys specifically for <strong>{{ $selectedClient['name'] }}</strong> ({{ $selectedClient['email'] }}).
                    @else
                        Editing default AI access and fallback API keys for all clients in <strong>{{ $agency->name }}</strong> network.
                    @endif
                </p>
            </div>
        </div>
        @if($selectedClientId !== 'global')
            <a href="{{ route('whitelabel.ai-settings.index', ['client_id' => 'global']) }}" class="px-3 py-1.5 bg-white border border-indigo-200 hover:bg-indigo-50 text-indigo-700 rounded-xl text-xs font-bold shadow-sm">
                Switch to Global Default
            </a>
        @endif
    </div>

    <form action="{{ route('whitelabel.ai-settings.update') }}" method="POST" class="card-white rounded-2xl p-6 space-y-6">
        @csrf
        <input type="hidden" name="client_id" value="{{ $selectedClientId }}">
        
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
                        <option value="1" {{ ($isGeminiActive ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ ($isGeminiActive ?? 1) == 0 ? 'selected' : '' }}>Deactive</option>
                    </select>
                </div>
            </div>
            
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">
                    {{ $selectedClientId !== 'global' ? 'Client Gemini API Key' : 'Agency Gemini API Key' }}
                </label>
                <input type="text" name="gemini_api_key" value="{{ $geminiApiKey ?? '' }}" placeholder="AIzaSy..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-mono text-slate-900 focus:outline-none focus:border-blue-500 focus:bg-white">
                <p class="text-[11px] text-amber-600 mt-1">
                    @if($selectedClientId !== 'global')
                        Set a custom Gemini API key for this specific client to override agency and system defaults.
                    @else
                        Enter your Google Gemini API key to override the system key for all clients registered under your white-label agency.
                    @endif
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
                        <option value="1" {{ ($isOpenaiActive ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ ($isOpenaiActive ?? 1) == 0 ? 'selected' : '' }}>Deactive</option>
                    </select>
                </div>
            </div>
            
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">
                    {{ $selectedClientId !== 'global' ? 'Client OpenAI API Key' : 'Agency OpenAI API Key' }}
                </label>
                <input type="text" name="openai_api_key" value="{{ $openaiApiKey ?? '' }}" placeholder="sk-proj-..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-mono text-slate-900 focus:outline-none focus:border-emerald-500 focus:bg-white">
                <p class="text-[11px] text-amber-600 mt-1">
                    @if($selectedClientId !== 'global')
                        Set a custom OpenAI API key for this specific client to override agency and system defaults.
                    @else
                        Enter your OpenAI API key to provide text & DALL-E image generation capabilities for your end-clients.
                    @endif
                </p>
            </div>
        </div>

        <!-- Info Card -->
        <div class="p-4 rounded-xl bg-indigo-50/70 border border-indigo-100 text-xs text-indigo-950 space-y-1">
            <h4 class="font-bold flex items-center space-x-1 text-indigo-900">
                <i data-lucide="info" class="w-4 h-4 text-indigo-600 mr-1"></i>
                <span>Registered Customer Client Filtering Note</span>
            </h4>
            <p class="text-[11px] text-indigo-800">
                The client dropdown exclusively lists real registered customers (excluding demo/theme preview templates where <code>preview_template = 1</code>).
            </p>
        </div>

        <div class="pt-2 flex justify-end">
            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs shadow-md shadow-blue-600/30 flex items-center space-x-2">
                <i data-lucide="save" class="w-4 h-4"></i>
                <span>Save {{ $selectedClientId !== 'global' ? 'Client' : 'Agency Global' }} AI Settings</span>
            </button>
        </div>
    </form>
</div>
@endsection
