@extends('layouts.whitelabel')

@section('title', 'Website - Landing Page')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 font-heading">Landing Page Configuration</h1>
            <p class="text-xs text-slate-500">Manage your dynamic agency landing page hero section, branding colors, and section visibility</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('whitelabel.website.preview') }}" target="_blank" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-md shadow-emerald-600/20 flex items-center space-x-2 transition-all">
                <i data-lucide="external-link" class="w-4 h-4"></i>
                <span>Preview Website Live</span>
            </a>
        </div>
    </div>

    <form action="{{ route('whitelabel.website.landing.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Branding & Themes -->
        <div class="card-white rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 font-heading border-b border-slate-100 pb-3 flex items-center space-x-2">
                <i data-lucide="palette" class="w-4 h-4 text-blue-600"></i>
                <span>Brand & Color Settings</span>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Agency Name</label>
                    <input type="text" name="name" value="{{ old('name', $agency->name) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Custom Domain</label>
                    <input type="text" name="custom_domain" value="{{ old('custom_domain', $agency->custom_domain) }}" placeholder="e.g. checkout.maturednature.com" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Primary Color (Hex)</label>
                    <div class="flex items-center space-x-2">
                        <input type="color" name="primary_color" value="{{ old('primary_color', $agency->primary_color ?? '#4f46e5') }}" class="w-10 h-10 rounded-xl border border-slate-200 p-1 cursor-pointer">
                        <input type="text" value="{{ old('primary_color', $agency->primary_color ?? '#4f46e5') }}" readonly class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-mono">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Secondary Color (Hex)</label>
                    <div class="flex items-center space-x-2">
                        <input type="color" name="secondary_color" value="{{ old('secondary_color', $agency->secondary_color ?? '#9333ea') }}" class="w-10 h-10 rounded-xl border border-slate-200 p-1 cursor-pointer">
                        <input type="text" value="{{ old('secondary_color', $agency->secondary_color ?? '#9333ea') }}" readonly class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-mono">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Agency Logo</label>
                    <input type="file" name="logo" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    @if($agency->logo)
                        <img src="{{ asset($agency->logo) }}" alt="Logo" class="h-8 mt-2 object-contain">
                    @endif
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Favicon</label>
                    <input type="file" name="favicon" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    @if($agency->favicon)
                        <img src="{{ asset($agency->favicon) }}" alt="Favicon" class="h-6 mt-2 object-contain">
                    @endif
                </div>
            </div>
        </div>

        <!-- Hero Section -->
        <div class="card-white rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 font-heading border-b border-slate-100 pb-3 flex items-center space-x-2">
                <i data-lucide="sparkles" class="w-4 h-4 text-purple-600"></i>
                <span>Hero Section Configuration</span>
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Hero Heading</label>
                    <input type="text" name="hero_title" value="{{ old('hero_title', $agency->hero_title ?? 'Grow, Manage & Automate Your Business — All in One Place') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Hero Subheading / Description</label>
                    <textarea name="hero_subtitle" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:border-blue-500">{{ old('hero_subtitle', $agency->hero_subtitle ?? 'The most powerful SaaS platform for local businesses to get more customers, save time and grow faster.') }}</textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Call To Action Button Text</label>
                        <input type="text" name="cta_text" value="{{ old('cta_text', $agency->cta_text ?? 'Start Free Today') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Call To Action URL</label>
                        <input type="text" name="cta_url" value="{{ old('cta_url', $agency->cta_url ?? '/login') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Hero Dashboard Preview Image</label>
                    <input type="file" name="hero_image" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    @if($agency->hero_image)
                        <img src="{{ asset($agency->hero_image) }}" alt="Hero Graphic" class="h-24 mt-2 rounded-xl border border-slate-200 object-cover">
                    @endif
                </div>
            </div>
        </div>

        <!-- SEO Metadata -->
        <div class="card-white rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 font-heading border-b border-slate-100 pb-3 flex items-center space-x-2">
                <i data-lucide="search" class="w-4 h-4 text-emerald-600"></i>
                <span>SEO & Meta Configuration</span>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Meta Title</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title', $agency->meta_title ?? $agency->name . ' — Growth Suite') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Meta Description</label>
                    <input type="text" name="meta_description" value="{{ old('meta_description', $agency->meta_description ?? $agency->hero_subtitle) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold">
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end space-x-3">
            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs shadow-lg shadow-blue-600/30 transition-all">
                Save Landing Page Changes
            </button>
        </div>
    </form>
</div>
@endsection
