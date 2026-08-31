@extends('layouts.whitelabel')

@section('title', 'Website - Landing Page')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 font-heading">Landing Page Configuration</h1>
            <p class="text-xs text-slate-500">Manage your dynamic agency landing page hero section, branding colors, and all section data</p>
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

        {{-- ─── 1. BRANDING & COLORS ─────────────────────────────── --}}
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
                    <input type="text" name="custom_domain" value="{{ old('custom_domain', $agency->custom_domain) }}" placeholder="e.g. checkout.yourdomain.com" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Primary Color</label>
                    <div class="flex items-center space-x-2">
                        <input type="color" name="primary_color" id="primaryColorPicker" value="{{ old('primary_color', $agency->primary_color ?? '#4f46e5') }}" class="w-10 h-10 rounded-xl border border-slate-200 p-1 cursor-pointer" oninput="document.getElementById('primaryColorText').value=this.value">
                        <input type="text" id="primaryColorText" value="{{ old('primary_color', $agency->primary_color ?? '#4f46e5') }}" readonly class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-mono">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Secondary Color</label>
                    <div class="flex items-center space-x-2">
                        <input type="color" name="secondary_color" id="secondaryColorPicker" value="{{ old('secondary_color', $agency->secondary_color ?? '#9333ea') }}" class="w-10 h-10 rounded-xl border border-slate-200 p-1 cursor-pointer" oninput="document.getElementById('secondaryColorText').value=this.value">
                        <input type="text" id="secondaryColorText" value="{{ old('secondary_color', $agency->secondary_color ?? '#9333ea') }}" readonly class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-mono">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Agency Logo</label>
                    <input type="file" name="logo" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    @if($agency->logo)
                        <img src="{{ asset($agency->logo) }}" alt="Logo" class="h-9 mt-2 object-contain">
                    @endif
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Favicon</label>
                    <input type="file" name="favicon" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    @if($agency->favicon)
                        <img src="{{ asset($agency->favicon) }}" alt="Favicon" class="h-6 mt-2 object-contain">
                    @endif
                </div>
            </div>
        </div>

        {{-- ─── 2. HERO SECTION ──────────────────────────────────── --}}
        <div class="card-white rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 font-heading border-b border-slate-100 pb-3 flex items-center space-x-2">
                <i data-lucide="sparkles" class="w-4 h-4 text-purple-600"></i>
                <span>Hero Section</span>
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Hero Main Heading</label>
                    <input type="text" name="hero_title" value="{{ old('hero_title', $agency->hero_title ?? 'Build. Automate. Scale. All in One') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Hero Subheading / Description</label>
                    <textarea name="hero_subtitle" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:border-blue-500">{{ old('hero_subtitle', $agency->hero_subtitle ?? 'The most powerful SaaS platform for local businesses to get more customers, save time and grow faster.') }}</textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">CTA Button Text</label>
                        <input type="text" name="cta_text" value="{{ old('cta_text', $agency->cta_text ?? 'Get Started Free') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">CTA Button URL</label>
                        <input type="text" name="cta_url" value="{{ old('cta_url', $agency->cta_url ?? '/login') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold">
                    </div>
                </div>

                {{-- 3 Image Upload Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
                    <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 space-y-2">
                        <label class="block text-xs font-bold text-slate-800">1. Hero Dashboard Image</label>
                        <p class="text-[10px] text-slate-400">Recommended: 900×600px PNG/JPG</p>
                        <input type="file" name="hero_image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <img src="{{ asset($agency->hero_image ?? 'assets/landing_page/herobanner_dashboard.png') }}" alt="Hero" class="h-20 w-full object-cover rounded-xl border border-slate-200 mt-2">
                    </div>
                    <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 space-y-2">
                        <label class="block text-xs font-bold text-slate-800">2. About / Features Image</label>
                        <p class="text-[10px] text-slate-400">Recommended: 800×600px PNG/JPG</p>
                        <input type="file" name="about_image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <img src="{{ asset($agency->about_image ?? 'assets/landing_page/features_leftside.png') }}" alt="About" class="h-20 w-full object-cover rounded-xl border border-slate-200 mt-2">
                    </div>
                    <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 space-y-2">
                        <label class="block text-xs font-bold text-slate-800">3. CTA Banner Image</label>
                        <p class="text-[10px] text-slate-400">Recommended: 400×500px PNG/JPG</p>
                        <input type="file" name="cta_image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <img src="{{ asset($agency->cta_image ?? 'assets/landing_page/footer_card.png') }}" alt="CTA" class="h-20 w-full object-cover rounded-xl border border-slate-200 mt-2">
                    </div>
                </div>
            </div>
        </div>

        {{-- ─── 3. FOOTER & ABOUT CONTENT ───────────────────────── --}}
        <div class="card-white rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 font-heading border-b border-slate-100 pb-3 flex items-center space-x-2">
                <i data-lucide="file-text" class="w-4 h-4 text-teal-600"></i>
                <span>Footer & About Content</span>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Footer Tagline</label>
                    <textarea name="footer_content" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:border-blue-500">{{ old('footer_content', $agency->footer_content ?? 'Powering the growth of Indian local businesses with smart digital solutions.') }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">About Us Content</label>
                    <textarea name="about_content" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:border-blue-500">{{ old('about_content', $agency->about_content ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Contact Email</label>
                    <input type="email" name="contact_email" value="{{ old('contact_email', $agency->contact_email ?? $agency->email) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Contact Phone</label>
                    <input type="text" name="contact_phone" value="{{ old('contact_phone', $agency->contact_phone ?? $agency->phone ?? '') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold">
                </div>
            </div>
        </div>

        {{-- ─── 4. SOCIAL MEDIA LINKS ───────────────────────────── --}}
        <div class="card-white rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 font-heading border-b border-slate-100 pb-3 flex items-center space-x-2">
                <i data-lucide="share-2" class="w-4 h-4 text-pink-600"></i>
                <span>Social Media Links (Footer)</span>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Facebook URL</label>
                    <input type="url" name="facebook_url" value="{{ old('facebook_url', $agency->facebook_url ?? '') }}" placeholder="https://facebook.com/yourpage" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Instagram URL</label>
                    <input type="url" name="instagram_url" value="{{ old('instagram_url', $agency->instagram_url ?? '') }}" placeholder="https://instagram.com/yourpage" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">YouTube URL</label>
                    <input type="url" name="youtube_url" value="{{ old('youtube_url', $agency->youtube_url ?? '') }}" placeholder="https://youtube.com/yourchannel" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">LinkedIn URL</label>
                    <input type="url" name="linkedin_url" value="{{ old('linkedin_url', $agency->linkedin_url ?? '') }}" placeholder="https://linkedin.com/company/yourco" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Twitter / X URL</label>
                    <input type="url" name="twitter_url" value="{{ old('twitter_url', $agency->twitter_url ?? '') }}" placeholder="https://twitter.com/yourhandle" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs">
                </div>
            </div>
        </div>

        {{-- ─── 5. SEO METADATA ─────────────────────────────────── --}}
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
            <button type="submit" class="px-7 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs shadow-lg shadow-blue-600/30 transition-all flex items-center gap-2">
                <i data-lucide="save" class="w-4 h-4"></i>
                Save Landing Page Changes
            </button>
        </div>
    </form>
</div>
@endsection
