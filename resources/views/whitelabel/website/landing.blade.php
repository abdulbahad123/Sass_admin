@extends('layouts.whitelabel')

@section('title', 'Website - Landing Page')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 font-heading">Landing Page Configuration</h1>
            <p class="text-xs text-slate-500">Manage every section of your dynamic agency landing page — hero, stats, model cards, products, how it works, growth path, CTA and more.</p>
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
                        <input type="color" name="primary_color" id="primaryColorPicker" value="{{ old('primary_color', $agency->primary_color ?? '#2563eb') }}" class="w-10 h-10 rounded-xl border border-slate-200 p-1 cursor-pointer" oninput="document.getElementById('primaryColorText').value=this.value">
                        <input type="text" id="primaryColorText" value="{{ old('primary_color', $agency->primary_color ?? '#2563eb') }}" readonly class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-mono">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Secondary Color</label>
                    <div class="flex items-center space-x-2">
                        <input type="color" name="secondary_color" id="secondaryColorPicker" value="{{ old('secondary_color', $agency->secondary_color ?? '#1d4ed8') }}" class="w-10 h-10 rounded-xl border border-slate-200 p-1 cursor-pointer" oninput="document.getElementById('secondaryColorText').value=this.value">
                        <input type="text" id="secondaryColorText" value="{{ old('secondary_color', $agency->secondary_color ?? '#1d4ed8') }}" readonly class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-mono">
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
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Announcement Bar Text</label>
                    <input type="text" name="announcement_bar_text" value="{{ old('announcement_bar_text', $agency->announcement_bar_text ?? 'YOUR BRAND. OUR TECHNOLOGY. UNLIMITED GROWTH.') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold focus:outline-none focus:border-blue-500">
                    <p class="text-[10px] text-slate-400 mt-1">Shown in the thin bar above the navbar.</p>
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
                    <input type="text" name="hero_title" value="{{ old('hero_title', $agency->hero_title ?? 'Launch Your Own SaaS Business Under Your Brand') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Hero Subheading / Description</label>
                    <textarea name="hero_subtitle" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:border-blue-500">{{ old('hero_subtitle', $agency->hero_subtitle ?? $agency->name . ' is an All-in-One White Label SaaS Platform that helps agencies, freelancers, IT companies and entrepreneurs who want to launch their own SaaS business under their own brand.') }}</textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Primary CTA Button Text</label>
                        <input type="text" name="cta_text" value="{{ old('cta_text', $agency->cta_text ?? 'Start Your SaaS Business') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Primary CTA Button URL</label>
                        <input type="text" name="cta_url" value="{{ old('cta_url', $agency->cta_url ?? '/login') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Secondary CTA Button Text (Book Demo)</label>
                        <input type="text" name="cta2_text" value="{{ old('cta2_text', $agency->cta2_text ?? 'Book a Demo') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Secondary CTA Button URL</label>
                        <input type="text" name="cta2_url" value="{{ old('cta2_url', $agency->cta2_url ?? '/login') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold">
                    </div>
                </div>

                {{-- 3 Image Upload Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
                    <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 space-y-2">
                        <label class="block text-xs font-bold text-slate-800">1. Hero Dashboard Image</label>
                        <p class="text-[10px] text-slate-400">Recommended: 900×600px PNG/JPG</p>
                        <input type="file" name="hero_image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <img src="{{ asset(ltrim($agency->hero_image ?? 'assets/landing_page/herobanner_dashboard.png', '/')) }}" alt="Hero" class="h-20 w-full object-cover rounded-xl border border-slate-200 mt-2">
                    </div>
                    <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 space-y-2">
                        <label class="block text-xs font-bold text-slate-800">2. About / Features Image</label>
                        <p class="text-[10px] text-slate-400">Recommended: 800×600px PNG/JPG</p>
                        <input type="file" name="about_image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <img src="{{ asset(ltrim($agency->about_image ?? 'assets/landing_page/features_leftside.png', '/')) }}" alt="About" class="h-20 w-full object-cover rounded-xl border border-slate-200 mt-2">
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

        {{-- ─── 3. STATS BAR ──────────────────────────────────────── --}}
        <div class="card-white rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 font-heading border-b border-slate-100 pb-3 flex items-center space-x-2">
                <i data-lucide="bar-chart-2" class="w-4 h-4 text-indigo-600"></i>
                <span>Stats Bar (5 horizontal stats below hero)</span>
            </h3>
            <p class="text-[11px] text-slate-400">Icon name must be a valid FontAwesome icon slug (e.g. <code>users</code>, <code>briefcase</code>, <code>layers</code>, <code>shield-check</code>, <code>headphones</code>).</p>
            @php
                $statsBar = $agency->parsed_stats_bar;
            @endphp
            <div class="space-y-3">
                @foreach($statsBar as $si => $stat)
                    <div class="flex gap-3 items-center bg-slate-50 rounded-xl px-4 py-3">
                        <span class="text-xs font-bold text-slate-400 w-5">{{ $si+1 }}.</span>
                        <div class="flex-1 grid grid-cols-3 gap-3">
                            <div>
                                <label class="text-[10px] text-slate-500 font-bold">Value</label>
                                <input type="text" name="stats_bar_value[]" value="{{ old('stats_bar_value.' . $si, $stat['value']) }}" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs font-bold focus:outline-none focus:border-blue-500">
                            </div>
                            <div>
                                <label class="text-[10px] text-slate-500 font-bold">Label</label>
                                <input type="text" name="stats_bar_label[]" value="{{ old('stats_bar_label.' . $si, $stat['label']) }}" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs focus:outline-none focus:border-blue-500">
                            </div>
                            <div>
                                <label class="text-[10px] text-slate-500 font-bold">Icon (FA slug)</label>
                                <input type="text" name="stats_bar_icon[]" value="{{ old('stats_bar_icon.' . $si, $stat['icon']) }}" placeholder="e.g. users" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs focus:outline-none focus:border-blue-500">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ─── 4. ABOUT / PARTNER SECTION ──────────────────────── --}}
        <div class="card-white rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 font-heading border-b border-slate-100 pb-3 flex items-center space-x-2">
                <i data-lucide="info" class="w-4 h-4 text-teal-600"></i>
                <span>About / Partner Section</span>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">About Section Heading</label>
                    <input type="text" name="about_title" value="{{ old('about_title', $agency->about_title ?? 'Your Partner in SaaS Success') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Footer Tagline</label>
                    <textarea name="footer_content" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:border-blue-500">{{ old('footer_content', $agency->footer_content ?? 'Your technology partner in building profitable SaaS businesses worldwide.') }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">About Mission / Description</label>
                    <textarea name="about_mission" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:border-blue-500">{{ old('about_mission', $agency->about_mission ?? '') }}</textarea>
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

        {{-- ─── 5. MODEL CARDS ─────────────────────────────────── --}}
        <div class="card-white rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 font-heading border-b border-slate-100 pb-3 flex items-center space-x-2">
                <i data-lucide="layers" class="w-4 h-4 text-blue-600"></i>
                <span>Business Model Cards (Model 01 & Model 02)</span>
            </h3>
            @php
                $modelCards = $agency->parsed_model_cards;
            @endphp
            <div class="space-y-6">
                @foreach($modelCards as $mi => $mc)
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                        <div class="font-bold text-xs text-slate-700 mb-3">Model Card {{ $mi + 1 }}</div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="text-[10px] text-slate-500 font-bold">Badge Label</label>
                                <input type="text" name="model_badge[]" value="{{ old('model_badge.' . $mi, $mc['badge']) }}" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs font-semibold focus:outline-none focus:border-blue-500">
                            </div>
                            <div>
                                <label class="text-[10px] text-slate-500 font-bold">Card Color</label>
                                <input type="color" name="model_color[]" value="{{ old('model_color.' . $mi, $mc['color'] ?? '#2563eb') }}" class="w-full h-9 rounded-lg border border-slate-200 p-1 cursor-pointer">
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-[10px] text-slate-500 font-bold">Card Title</label>
                                <input type="text" name="model_title[]" value="{{ old('model_title.' . $mi, $mc['title']) }}" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs font-semibold focus:outline-none focus:border-blue-500">
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-[10px] text-slate-500 font-bold">Card Description</label>
                                <textarea name="model_description[]" rows="2" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs focus:outline-none focus:border-blue-500">{{ old('model_description.' . $mi, $mc['description'] ?? '') }}</textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-[10px] text-slate-500 font-bold">Feature List (one per line)</label>
                                <textarea name="model_features_raw[]" rows="5" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs font-mono focus:outline-none focus:border-blue-500">{{ old('model_features_raw.' . $mi, implode("\n", $mc['features'] ?? [])) }}</textarea>
                            </div>
                            <div>
                                <label class="text-[10px] text-slate-500 font-bold">CTA Button Text</label>
                                <input type="text" name="model_cta_text[]" value="{{ old('model_cta_text.' . $mi, $mc['cta_text'] ?? 'Get Started') }}" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs focus:outline-none focus:border-blue-500">
                            </div>
                            <div>
                                <label class="text-[10px] text-slate-500 font-bold">CTA Button URL</label>
                                <input type="text" name="model_cta_url[]" value="{{ old('model_cta_url.' . $mi, $mc['cta_url'] ?? $agency->cta_url ?? '/login') }}" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs focus:outline-none focus:border-blue-500">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ─── 6. PRODUCTS SECTION HEADING ──────────────────────── --}}
        <div class="card-white rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 font-heading border-b border-slate-100 pb-3 flex items-center space-x-2">
                <i data-lucide="box" class="w-4 h-4 text-orange-600"></i>
                <span>Products / Services Section</span>
            </h3>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Products Section Heading</label>
                <input type="text" name="products_section_title" value="{{ old('products_section_title', $agency->products_section_title ?? 'Everything You Need to Build & Scale Your SaaS Business') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold focus:outline-none focus:border-blue-500">
            </div>
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-3 flex items-center gap-2">
                <i data-lucide="info" class="w-4 h-4 text-blue-600 flex-shrink-0"></i>
                <p class="text-xs text-blue-700">To edit individual products/services, go to <a href="{{ route('whitelabel.website.services') }}" class="font-bold underline">Website → Services</a>.</p>
            </div>
        </div>

        {{-- ─── 7. REVENUE CALCULATOR ─────────────────────────────── --}}
        <div class="card-white rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 font-heading border-b border-slate-100 pb-3 flex items-center space-x-2">
                <i data-lucide="calculator" class="w-4 h-4 text-green-600"></i>
                <span>Revenue Opportunity Calculator</span>
            </h3>
            @php
                $revCalc = $agency->parsed_revenue_calculator;
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Calculator Title</label>
                    <input type="text" name="rev_calc_title" value="{{ old('rev_calc_title', $revCalc['title'] ?? 'Revenue Opportunity Calculator') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Price per Customer (₹/month)</label>
                    <input type="number" name="rev_calc_price_per_customer" value="{{ old('rev_calc_price_per_customer', $revCalc['price_per_customer'] ?? 999) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Default Customer Count</label>
                    <input type="number" name="rev_calc_default_count" value="{{ old('rev_calc_default_count', $revCalc['default_count'] ?? 100) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Max Slider Count</label>
                    <input type="number" name="rev_calc_max_count" value="{{ old('rev_calc_max_count', $revCalc['max_count'] ?? 500) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Currency Symbol</label>
                    <input type="text" name="rev_calc_currency_symbol" value="{{ old('rev_calc_currency_symbol', $revCalc['currency_symbol'] ?? '₹') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">CTA Button Text</label>
                    <input type="text" name="rev_calc_cta_text" value="{{ old('rev_calc_cta_text', $revCalc['cta_text'] ?? 'Start Building Your Revenue') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold focus:outline-none focus:border-blue-500">
                </div>
            </div>
        </div>

        {{-- ─── 8. HOW IT WORKS ────────────────────────────────────── --}}
        <div class="card-white rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 font-heading border-b border-slate-100 pb-3 flex items-center space-x-2">
                <i data-lucide="git-branch" class="w-4 h-4 text-purple-600"></i>
                <span>How It Works (4-step process)</span>
            </h3>
            @php
                $howItWorks = $agency->parsed_how_it_works;
            @endphp
            <div class="space-y-3">
                @foreach($howItWorks as $hi => $step)
                    <div class="bg-slate-50 rounded-xl px-4 py-3 border border-slate-200">
                        <div class="text-[10px] text-slate-400 font-bold mb-2">Step {{ $hi + 1 }}</div>
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="text-[10px] text-slate-500 font-bold">Icon (FA slug)</label>
                                <input type="text" name="hiw_icon[]" value="{{ old('hiw_icon.' . $hi, $step['icon'] ?? 'check') }}" placeholder="e.g. rocket" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs focus:outline-none focus:border-blue-500">
                            </div>
                            <div>
                                <label class="text-[10px] text-slate-500 font-bold">Step Title</label>
                                <input type="text" name="hiw_title[]" value="{{ old('hiw_title.' . $hi, $step['title']) }}" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs focus:outline-none focus:border-blue-500">
                            </div>
                            <div>
                                <label class="text-[10px] text-slate-500 font-bold">Step Description</label>
                                <input type="text" name="hiw_desc[]" value="{{ old('hiw_desc.' . $hi, $step['desc']) }}" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs focus:outline-none focus:border-blue-500">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ─── 9. WHY CHOOSE SECTION HEADING ───────────────────── --}}
        <div class="card-white rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 font-heading border-b border-slate-100 pb-3 flex items-center space-x-2">
                <i data-lucide="star" class="w-4 h-4 text-yellow-500"></i>
                <span>Why Choose Section</span>
            </h3>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Section Heading</label>
                <input type="text" name="why_choose_title" value="{{ old('why_choose_title', $agency->why_choose_title ?? 'Built for Ambitious Entrepreneurs') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold focus:outline-none focus:border-blue-500">
            </div>
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-3 flex items-center gap-2">
                <i data-lucide="info" class="w-4 h-4 text-blue-600 flex-shrink-0"></i>
                <p class="text-xs text-blue-700">To edit individual features/benefits cards, go to <a href="{{ route('whitelabel.website.services') }}" class="font-bold underline">Website → Services</a>.</p>
            </div>
        </div>

        {{-- ─── 10. GROWTH PATH ─────────────────────────────────── --}}
        <div class="card-white rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 font-heading border-b border-slate-100 pb-3 flex items-center space-x-2">
                <i data-lucide="map" class="w-4 h-4 text-emerald-600"></i>
                <span>Growth Path (Start → Grow → Expand → Global)</span>
            </h3>
            @php
                $growthPath = $agency->parsed_growth_path;
            @endphp
            <div class="space-y-3">
                @foreach($growthPath as $gi => $gStep)
                    <div class="bg-slate-50 rounded-xl px-4 py-3 border border-slate-200">
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="text-[10px] text-slate-500 font-bold">Stage Label</label>
                                <input type="text" name="growth_label[]" value="{{ old('growth_label.' . $gi, $gStep['label']) }}" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs font-bold focus:outline-none focus:border-blue-500">
                            </div>
                            <div>
                                <label class="text-[10px] text-slate-500 font-bold">Icon (FA slug)</label>
                                <input type="text" name="growth_icon[]" value="{{ old('growth_icon.' . $gi, $gStep['icon'] ?? 'flag') }}" placeholder="e.g. flag, bar-chart-2" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs focus:outline-none focus:border-blue-500">
                            </div>
                            <div>
                                <label class="text-[10px] text-slate-500 font-bold">Stage Description</label>
                                <input type="text" name="growth_desc[]" value="{{ old('growth_desc.' . $gi, $gStep['desc']) }}" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs focus:outline-none focus:border-blue-500">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ─── 11. CTA BANNER ──────────────────────────────────── --}}
        <div class="card-white rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 font-heading border-b border-slate-100 pb-3 flex items-center space-x-2">
                <i data-lucide="megaphone" class="w-4 h-4 text-red-600"></i>
                <span>CTA Banner (Bottom Section)</span>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Banner Heading</label>
                    <input type="text" name="cta_banner_heading" value="{{ old('cta_banner_heading', $agency->cta_banner_heading ?? 'Ready to Launch Your ' . $agency->name . ' Business?') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Banner Subtext</label>
                    <input type="text" name="cta_banner_subtext" value="{{ old('cta_banner_subtext', $agency->cta_banner_subtext ?? 'Your Success Starts Here!') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold focus:outline-none focus:border-blue-500">
                </div>
            </div>
        </div>

        {{-- ─── 12. SOCIAL MEDIA LINKS ──────────────────────────── --}}
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

        {{-- ─── 13. SEO METADATA ────────────────────────────────── --}}
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
                Save All Landing Page Changes
            </button>
        </div>
    </form>
</div>
@endsection
