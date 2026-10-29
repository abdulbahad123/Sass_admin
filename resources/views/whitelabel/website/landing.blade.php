@extends('layouts.whitelabel')

@section('title', 'Website - Landing Page')

@section('content')
@php
    $aboutFeatures = is_array($agency->about_features_data ?? null)
        ? $agency->about_features_data
        : (json_decode($agency->about_features_data ?? '[]', true) ?: [
            ['title' => 'Lightning Performance', 'desc' => 'Modern architecture delivering sub-second load times and 99.9% uptime.', 'icon' => 'zap'],
            ['title' => 'Enterprise Security',   'desc' => 'Bank-grade encryption and industry-leading security protocols to protect your data.', 'icon' => 'shield-check'],
            ['title' => 'Scale Without Limits',   'desc' => 'Infrastructure that grows with your business — from 10 to 10,000 clients.', 'icon' => 'trending-up'],
        ]);

    $whyChooseItems = is_array($agency->features_data ?? null)
        ? $agency->features_data
        : (json_decode($agency->features_data ?? '[]', true) ?: [
            ['title' => 'Get More Customers', 'desc' => 'Build trust with reviews, smart websites and digital presence.', 'icon' => 'rocket'],
            ['title' => 'Save Time & Effort',  'desc' => 'Automate repetitive tasks and focus on what matters most.',      'icon' => 'clock'],
            ['title' => 'Increase Revenue',    'desc' => 'Drive repeat business with loyalty programs & digital tools.',   'icon' => 'trending-up'],
            ['title' => 'Reliable & Secure',   'desc' => 'Your business data is safe with enterprise-grade security.',     'icon' => 'shield-check'],
        ]);

    $hiwSteps = is_array($agency->how_it_works_steps ?? null)
        ? $agency->how_it_works_steps
        : (json_decode($agency->how_it_works_steps ?? '[]', true) ?: [
            ['num' => '01', 'title' => 'Sign Up',              'desc' => 'Create your account in less than 2 minutes.',               'icon' => 'user-plus'],
            ['num' => '02', 'title' => 'Set Up Your Business', 'desc' => 'Choose the tools you need and customize in minutes.',      'icon' => 'monitor'],
            ['num' => '03', 'title' => 'Grow Faster',          'desc' => 'Get more customers, more reviews and more revenue.',         'icon' => 'bar-chart-2'],
        ]);

    $ctaCheckmarksRaw = is_array($agency->cta_features_data ?? null)
        ? implode(', ', $agency->cta_features_data)
        : (is_string($agency->cta_features_data ?? null) ? implode(', ', json_decode($agency->cta_features_data, true) ?: []) : 'Quick Setup, No Credit Card Required, 24/7 Support');
@endphp
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
                        <img src="{{ asset(ltrim($agency->logo, '/')) }}" alt="Logo" class="h-9 mt-2 object-contain">
                    @endif
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Favicon</label>
                    <input type="file" name="favicon" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    @if($agency->favicon)
                        <img src="{{ asset(ltrim($agency->favicon, '/')) }}" alt="Favicon" class="h-6 mt-2 object-contain">
                    @endif
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Dark Mode Logo (Optional)</label>
                    <input type="file" name="logo_dark" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    @if(!empty($agency->logo_dark))
                        <div class="bg-slate-900 p-2 rounded-xl mt-2 inline-block">
                            <img src="{{ asset(ltrim($agency->logo_dark, '/')) }}" alt="Dark Logo" class="h-8 object-contain">
                        </div>
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
                    <label class="block text-xs font-bold text-slate-700 mb-1">Hero Pill Badge</label>
                    <input type="text" name="hero_badge" value="{{ old('hero_badge', $agency->hero_badge ?? '⚡ All-in-One Growth Platform for Indian Businesses') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Hero Main Heading</label>
                    <input type="text" name="hero_title" value="{{ old('hero_title', $agency->hero_title ?? 'Grow, Manage & Automate Your Business — All in One Place') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold focus:outline-none focus:border-blue-500">
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
                        <img src="{{ asset(ltrim($agency->hero_image ?? 'assets/images/agency_herobanner.png', '/')) }}" alt="Hero" class="h-20 w-full object-cover rounded-xl border border-slate-200 mt-2">
                    </div>
                    <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 space-y-2">
                        <label class="block text-xs font-bold text-slate-800">2. About / Features Image</label>
                        <p class="text-[10px] text-slate-400">Recommended: 800×600px PNG/JPG</p>
                        <input type="file" name="about_image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <img src="{{ asset(ltrim($agency->about_image ?? 'assets/images/about_right.png', '/')) }}" alt="About" class="h-20 w-full object-cover rounded-xl border border-slate-200 mt-2">
                    </div>
                    <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 space-y-2">
                        <label class="block text-xs font-bold text-slate-800">3. CTA Banner Image</label>
                        <p class="text-[10px] text-slate-400">Recommended: 400×500px PNG/JPG</p>
                        <input type="file" name="cta_image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <img src="{{ asset(ltrim($agency->cta_image ?? 'assets/landing_page/footer_card.png', '/')) }}" alt="CTA" class="h-20 w-full object-cover rounded-xl border border-slate-200 mt-2">
                    </div>
                </div>
            </div>
        </div>

                {{-- ─── 2. TRUSTED BY / BUSINESS CATEGORIES BAR ──────────────────────── --}}
        @php
            $trustedCategories = $agency->parsed_categories ?? [
                ['label' => 'Restaurants',    'icon' => '🍽️'],
                ['label' => 'Clinics',        'icon' => '🏥'],
                ['label' => 'Salons & Spas',  'icon' => '💇'],
                ['label' => 'Retail Shops',   'icon' => '🛍️'],
                ['label' => 'Hotels',         'icon' => '🏨'],
                ['label' => 'Gyms & Fitness', 'icon' => '🏋️'],
                ['label' => 'Real Estate',    'icon' => '🏠'],
                ['label' => '& Many More',    'icon' => '✨'],
            ];
        @endphp
        <div class="card-white rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 font-heading border-b border-slate-100 pb-3 flex items-center justify-between">
                <span class="flex items-center space-x-2">
                    <i data-lucide="badge-check" class="w-4 h-4 text-emerald-600"></i>
                    <span>Trusted By / Business Categories Bar</span>
                </span>
                <span class="text-[11px] text-slate-400 font-normal">Controls "Trusted by 10,000+ Local Businesses Across India"</span>
            </h3>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Section Heading Text</label>
                <input type="text" name="trusted_businesses_title" value="{{ old('trusted_businesses_title', $agency->trusted_businesses_title ?? 'TRUSTED BY 10,000+ LOCAL BUSINESSES ACROSS INDIA') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold focus:outline-none focus:border-blue-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">Category Badges &amp; Icons (Pills)</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @foreach($trustedCategories as $cidx => $cat)
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-3 space-y-1.5">
                        <div class="flex items-center space-x-1.5">
                            <span class="text-[10px] font-bold text-slate-400 uppercase">Icon:</span>
                            <input type="text" name="category_icon[]" value="{{ $cat['icon'] ?? '✨' }}" class="w-12 text-center text-xs bg-white border border-slate-200 rounded-lg px-1 py-1 font-emoji">
                        </div>
                        <input type="text" name="category_label[]" value="{{ $cat['label'] ?? '' }}" placeholder="Category" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1 text-xs font-bold">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ─── 3. ABOUT US SECTION (CONTENT & 3 FEATURES) ─────── --}}
        <div class="card-white rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 font-heading border-b border-slate-100 pb-3 flex items-center justify-between">
                <span class="flex items-center space-x-2">
                    <i data-lucide="info" class="w-4 h-4 text-teal-600"></i>
                    <span>About Us Section</span>
                </span>
                <span class="text-[11px] text-slate-400 font-normal">Controls the dark "Built for entrepreneurs" card</span>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">About Section Badge / Top Pill</label>
                    <input type="text" name="about_section_badge" value="{{ old('about_section_badge', $agency->about_section_badge ?? 'About Us') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold focus:outline-none focus:border-blue-500" placeholder="e.g. About Us">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">About Main Heading</label>
                    <input type="text" name="about_title" value="{{ old('about_title', $agency->about_title ?? 'Built for entrepreneurs, by entrepreneurs.') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">About Mission Statement</label>
                    <textarea name="about_mission" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:border-blue-500">{{ old('about_mission', $agency->about_mission ?? 'Our mission is to help Indian entrepreneurs automate repetitive operations, boost sales revenue, build customer loyalty, and scale seamlessly without juggling multiple expensive tools.') }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">About Detailed Paragraph</label>
                    <textarea name="about_content" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:border-blue-500">{{ old('about_content', $agency->about_content ?? '') }}</textarea>
                </div>
            </div>

            {{-- 3 About Features Badges --}}
            <div class="pt-2 border-t border-slate-100 space-y-3">
                <label class="block text-xs font-bold text-slate-800">About Feature Highlights (3 Cards on Left)</label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    @foreach($aboutFeatures as $afi => $af)
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-3.5 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-bold text-teal-700 uppercase">Feature #{{ $afi + 1 }}</span>
                            <input type="text" name="about_feature_icon[]" value="{{ $af['icon'] ?? 'zap' }}" placeholder="zap / shield-check" class="w-24 text-[10px] bg-white border border-slate-200 rounded px-2 py-0.5 font-mono">
                        </div>
                        <input type="text" name="about_feature_title[]" value="{{ $af['title'] ?? '' }}" placeholder="Feature Title" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs font-bold">
                        <textarea name="about_feature_desc[]" rows="2" placeholder="Description" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-[11px]">{{ $af['desc'] ?? '' }}</textarea>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ─── 4. WHY CHOOSE US SECTION ───────────────────────── --}}
        <div class="card-white rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 font-heading border-b border-slate-100 pb-3 flex items-center justify-between">
                <span class="flex items-center space-x-2">
                    <i data-lucide="check-circle" class="w-4 h-4 text-purple-600"></i>
                    <span>Why Choose Us Section (4 Cards)</span>
                </span>
                <span class="text-[11px] text-slate-400 font-normal">Controls "Why Choose Youverse"</span>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Section Heading</label>
                    <input type="text" name="why_choose_title" value="{{ old('why_choose_title', $agency->why_choose_title ?? ('Why Choose ' . $agency->name . '?')) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Section Subtitle</label>
                    <input type="text" name="why_choose_subtitle" value="{{ old('why_choose_subtitle', $agency->why_choose_subtitle ?? 'Everything you need to run, grow and scale your business — without juggling multiple tools.') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 pt-2">
                @foreach($whyChooseItems as $wci => $wc)
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-3.5 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-purple-700 uppercase">Card #{{ $wci + 1 }}</span>
                        <input type="text" name="why_choose_item_icon[]" value="{{ $wc['icon'] ?? 'rocket' }}" placeholder="icon" class="w-20 text-[10px] bg-white border border-slate-200 rounded px-2 py-0.5 font-mono">
                    </div>
                    <input type="text" name="why_choose_item_title[]" value="{{ $wc['title'] ?? '' }}" placeholder="Card Title" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs font-bold">
                    <textarea name="why_choose_item_desc[]" rows="2" placeholder="Description" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-[11px]">{{ $wc['desc'] ?? '' }}</textarea>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ─── 5. OUR PRODUCTS SECTION ─────────────────────────── --}}
        <div class="card-white rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 font-heading border-b border-slate-100 pb-3 flex items-center justify-between">
                <span class="flex items-center space-x-2">
                    <i data-lucide="package" class="w-4 h-4 text-blue-600"></i>
                    <span>Our Products Section Header & CTA</span>
                </span>
                <span class="text-[11px] text-slate-400 font-normal">Controls "Smart Tools for Smarter Businesses"</span>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Section Badge</label>
                    <input type="text" name="products_section_badge" value="{{ old('products_section_badge', $agency->products_section_badge ?? 'Our Products') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Section Heading</label>
                    <input type="text" name="products_section_title" value="{{ old('products_section_title', $agency->products_section_title ?? 'Smart Tools for Smarter Businesses') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold">
                </div>
                <div class="md:col-span-3">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Section Subtitle</label>
                    <textarea name="products_section_subtitle" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs">{{ old('products_section_subtitle', $agency->products_section_subtitle ?? 'A complete suite of business growth tools designed for Indian entrepreneurs and local businesses.') }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Button Text</label>
                    <input type="text" name="products_cta_text" value="{{ old('products_cta_text', $agency->products_cta_text ?? 'Explore All Products') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Button URL</label>
                    <input type="text" name="products_cta_url" value="{{ old('products_cta_url', $agency->products_cta_url ?? 'https://wa.me/916374913298?text=I%20am%20interested%20on%20that%20business') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold">
                </div>
            </div>
        </div>

        {{-- ─── 6. HOW IT WORKS SECTION ─────────────────────────── --}}
        <div class="card-white rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 font-heading border-b border-slate-100 pb-3 flex items-center justify-between">
                <span class="flex items-center space-x-2">
                    <i data-lucide="layers" class="w-4 h-4 text-indigo-600"></i>
                    <span>How It Works Section</span>
                </span>
                <span class="text-[11px] text-slate-400 font-normal">Controls the 3-step setup guide</span>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Section Title</label>
                    <input type="text" name="how_it_works_title" value="{{ old('how_it_works_title', $agency->how_it_works_title ?? 'How It Works?') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Section Subtitle</label>
                    <input type="text" name="how_it_works_subtitle" value="{{ old('how_it_works_subtitle', $agency->how_it_works_subtitle ?? 'Get started in 3 simple steps and transform your business today.') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 pt-2">
                @foreach($hiwSteps as $hi => $step)
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-3.5 space-y-2">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-1.5">
                            <span class="text-[10px] font-bold text-indigo-600">STEP</span>
                            <input type="text" name="hiw_step_num[]" value="{{ $step['num'] ?? sprintf('%02d', $hi + 1) }}" class="w-10 text-[10px] text-center font-bold bg-white border border-slate-200 rounded px-1 py-0.5">
                        </div>
                        <input type="text" name="hiw_step_icon[]" value="{{ $step['icon'] ?? 'user-plus' }}" placeholder="icon" class="w-20 text-[10px] bg-white border border-slate-200 rounded px-2 py-0.5 font-mono">
                    </div>
                    <input type="text" name="hiw_step_title[]" value="{{ $step['title'] ?? '' }}" placeholder="Step Title" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs font-bold">
                    <textarea name="hiw_step_desc[]" rows="2" placeholder="Step Description" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-[11px]">{{ $step['desc'] ?? '' }}</textarea>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ─── 7. READY TO SCALE CTA BANNER ────────────────────── --}}
        <div class="card-white rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 font-heading border-b border-slate-100 pb-3 flex items-center justify-between">
                <span class="flex items-center space-x-2">
                    <i data-lucide="zap" class="w-4 h-4 text-amber-500"></i>
                    <span>Ready To Scale CTA Banner (Blue Card Above Footer)</span>
                </span>
                <span class="text-[11px] text-slate-400 font-normal">Controls "Ready to Take Your Business to the Next Level?"</span>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Banner Headline</label>
                    <input type="text" name="cta_section_title" value="{{ old('cta_section_title', $agency->cta_section_title ?? 'Ready to Take Your Business to the Next Level?') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Banner Subtitle</label>
                    <input type="text" name="cta_section_subtitle" value="{{ old('cta_section_subtitle', $agency->cta_section_subtitle ?? ('Join thousands of growing businesses with ' . $agency->name . ' today.')) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Trust Bullets (comma-separated, e.g. Quick Setup, No Credit Card Required, 24/7 Support)</label>
                    <input type="text" name="cta_feature_text" value="{{ old('cta_feature_text', $ctaCheckmarksRaw) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">CTA Button Text</label>
                    <input type="text" name="cta_button_text" value="{{ old('cta_button_text', $agency->cta_button_text ?? 'Get Started Free') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">CTA Button URL</label>
                    <input type="text" name="cta_button_url" value="{{ old('cta_button_url', $agency->cta_button_url ?? $agency->cta_url ?? 'https://wa.me/916374913298?text=I%20am%20interested%20on%20that%20business') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold">
                </div>
            </div>
        </div>

        {{-- ─── 8. FOOTER & CONTACT INFO ────────────────────────── --}}
        <div class="card-white rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 font-heading border-b border-slate-100 pb-3 flex items-center space-x-2">
                <i data-lucide="file-text" class="w-4 h-4 text-slate-600"></i>
                <span>Footer & Contact Information</span>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Footer Tagline</label>
                    <textarea name="footer_content" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:border-blue-500">{{ old('footer_content', $agency->footer_content ?? 'Powering the growth of Indian local businesses with smart digital solutions.') }}</textarea>
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
