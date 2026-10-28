@extends('layouts.admin')

@section('title', 'Platform Landing Page Editor')
@section('page_title', 'SaaSReselling.com Landing Page Editor')

@section('content')
<div class="max-w-6xl mx-auto space-y-8" x-data="{ activeTab: 'hero' }">

    <!-- Top Banner & Actions -->
    <div class="card-white p-6 rounded-2xl flex flex-col md:flex-row md:items-center justify-between gap-4 border border-slate-200">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 font-heading tracking-tight">SaaSReselling Platform Landing Page Editor</h2>
            <p class="text-xs text-slate-500 mt-1">Manage all headlines, images, product features, model cards, calculator values, pricing, FAQs, and testimonials on <span class="font-bold text-blue-600">saasreselling.com</span>.</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ url('/') }}" target="_blank" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all flex items-center space-x-2">
                <i data-lucide="external-link" class="w-4 h-4"></i>
                <span>Preview Landing Page</span>
            </a>
            <button type="submit" form="landingEditorForm" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-lg shadow-indigo-600/30 transition-all flex items-center space-x-2">
                <i data-lucide="save" class="w-4 h-4"></i>
                <span>Save All Changes</span>
            </button>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex space-x-2 border-b border-slate-200 overflow-x-auto pb-1 text-xs font-bold text-slate-600">
        <button @click="activeTab = 'announcement'" :class="activeTab === 'announcement' ? 'border-indigo-600 text-indigo-600 bg-indigo-50/50' : 'border-transparent hover:text-slate-900 hover:bg-slate-100'" class="px-4 py-2.5 rounded-xl border-b-2 transition-all">
            Header & Navbar
        </button>
        <button @click="activeTab = 'hero'" :class="activeTab === 'hero' ? 'border-indigo-600 text-indigo-600 bg-indigo-50/50' : 'border-transparent hover:text-slate-900 hover:bg-slate-100'" class="px-4 py-2.5 rounded-xl border-b-2 transition-all">
            Hero Section
        </button>
        <button @click="activeTab = 'stats'" :class="activeTab === 'stats' ? 'border-indigo-600 text-indigo-600 bg-indigo-50/50' : 'border-transparent hover:text-slate-900 hover:bg-slate-100'" class="px-4 py-2.5 rounded-xl border-b-2 transition-all">
            Stats & About
        </button>
        <button @click="activeTab = 'models'" :class="activeTab === 'models' ? 'border-indigo-600 text-indigo-600 bg-indigo-50/50' : 'border-transparent hover:text-slate-900 hover:bg-slate-100'" class="px-4 py-2.5 rounded-xl border-b-2 transition-all">
            Model Cards
        </button>
        <button @click="activeTab = 'products'" :class="activeTab === 'products' ? 'border-indigo-600 text-indigo-600 bg-indigo-50/50' : 'border-transparent hover:text-slate-900 hover:bg-slate-100'" class="px-4 py-2.5 rounded-xl border-b-2 transition-all">
            Products & Calculator
        </button>
        <button @click="activeTab = 'workflow'" :class="activeTab === 'workflow' ? 'border-indigo-600 text-indigo-600 bg-indigo-50/50' : 'border-transparent hover:text-slate-900 hover:bg-slate-100'" class="px-4 py-2.5 rounded-xl border-b-2 transition-all">
            How It Works & Why Us
        </button>
        <button @click="activeTab = 'pricing'" :class="activeTab === 'pricing' ? 'border-indigo-600 text-indigo-600 bg-indigo-50/50' : 'border-transparent hover:text-slate-900 hover:bg-slate-100'" class="px-4 py-2.5 rounded-xl border-b-2 transition-all">
            Pricing & Growth
        </button>
        <button @click="activeTab = 'faqs'" :class="activeTab === 'faqs' ? 'border-indigo-600 text-indigo-600 bg-indigo-50/50' : 'border-transparent hover:text-slate-900 hover:bg-slate-100'" class="px-4 py-2.5 rounded-xl border-b-2 transition-all">
            Testimonials & FAQs
        </button>
        <button @click="activeTab = 'security'" :class="activeTab === 'security' ? 'border-indigo-600 text-indigo-600 bg-indigo-50/50' : 'border-transparent hover:text-slate-900 hover:bg-slate-100'" class="px-4 py-2.5 rounded-xl border-b-2 transition-all">
            Security & reCAPTCHA
        </button>
        <button @click="activeTab = 'footer'" :class="activeTab === 'footer' ? 'border-indigo-600 text-indigo-600 bg-indigo-50/50' : 'border-transparent hover:text-slate-900 hover:bg-slate-100'" class="px-4 py-2.5 rounded-xl border-b-2 transition-all">
            CTA Banner & Footer
        </button>
    </div>

    <!-- Master Form -->
    <form id="landingEditorForm" action="{{ route('admin.landing-page.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- TAB 1: HEADER & ANNOUNCEMENT -->
        <div x-show="activeTab === 'announcement'" class="card-white p-6 rounded-2xl space-y-6">
            <h3 class="font-bold text-slate-900 text-base border-b pb-3">Header & Top Announcement Bar</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Announcement Badge Text</label>
                    <input type="text" name="lp_announcement_badge" value="{{ $data['lp_announcement_badge'] }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Announcement Banner Message</label>
                    <input type="text" name="lp_announcement_text" value="{{ $data['lp_announcement_text'] }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-indigo-500">
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label class="block text-xs font-bold text-slate-800 mb-3">Navbar Logos (White Mode & Dark Mode)</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 p-4 rounded-2xl bg-slate-50 border border-slate-200/90">
                        <!-- White Mode (Light) Logo -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <label class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                    <i data-lucide="sun" class="w-4 h-4 text-amber-500"></i>
                                    <span>White Mode Logo (Light Theme)</span>
                                </label>
                                <span class="text-[10px] text-slate-400 font-medium">For Light Backgrounds</span>
                            </div>
                            @if(!empty($data['lp_header_logo']))
                                <div class="p-3 bg-white rounded-xl border border-slate-200 flex items-center justify-between shadow-sm">
                                    <img src="{{ asset($data['lp_header_logo']) }}" class="h-10 w-auto object-contain p-1">
                                    <button type="button" onclick="deleteLandingImage('lp_header_logo')" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg text-xs font-bold border border-rose-200 transition-colors flex items-center gap-1">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        <span>Delete Image</span>
                                    </button>
                                </div>
                            @endif
                            <input type="file" name="lp_header_logo" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-white file:text-indigo-700 hover:file:bg-indigo-50 border border-slate-200 rounded-xl bg-white">
                        </div>

                        <!-- Dark Mode Logo -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <label class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                    <i data-lucide="moon" class="w-4 h-4 text-indigo-500"></i>
                                    <span>Dark Mode Logo (Dark Theme)</span>
                                </label>
                                <span class="text-[10px] text-slate-400 font-medium">For Dark Mode Toggle</span>
                            </div>
                            @if(!empty($data['lp_header_logo_dark']))
                                <div class="p-3 bg-slate-900 rounded-xl border border-slate-800 flex items-center justify-between shadow-sm">
                                    <img src="{{ asset($data['lp_header_logo_dark']) }}" class="h-10 w-auto object-contain p-1">
                                    <button type="button" onclick="deleteLandingImage('lp_header_logo_dark')" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg text-xs font-bold border border-rose-200 transition-colors flex items-center gap-1">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        <span>Delete Image</span>
                                    </button>
                                </div>
                            @else
                                <div class="p-3 bg-slate-900/5 border border-dashed border-slate-300 rounded-xl text-center text-xs text-slate-400">
                                    No Dark Mode Logo uploaded (Will fallback to White Mode Logo)
                                </div>
                            @endif
                            <input type="file" name="lp_header_logo_dark" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-indigo-300 hover:file:bg-slate-800 border border-slate-200 rounded-xl bg-white">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Book Demo Button URL</label>
                    <input type="text" name="lp_book_demo_url" value="{{ $data['lp_book_demo_url'] }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Primary CTA Button Label</label>
                    <input type="text" name="lp_cta_button_text" value="{{ $data['lp_cta_button_text'] }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Primary CTA Button URL</label>
                    <input type="text" name="lp_cta_button_url" value="{{ $data['lp_cta_button_url'] }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
        </div>

        <!-- TAB 2: HERO SECTION -->
        <div x-show="activeTab === 'hero'" class="card-white p-6 rounded-2xl space-y-6">
            <h3 class="font-extrabold text-slate-900 text-base border-b pb-3 font-heading">Hero Banner Section (Matches Reference Design)</h3>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Hero Top Audience Badge</label>
                    <input type="text" name="lp_hero_announcement_badge" value="{{ $data['lp_hero_announcement_badge'] ?? 'BUILT FOR AGENCIES | FREELANCERS | RESELLERS | DIGITAL MARKETERS' }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-indigo-500 font-medium">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Hero Main Headline (HTML allowed e.g. &lt;span class="text-[#ff3d00]"&gt;...&lt;/span&gt;)</label>
                    <input type="text" name="lp_hero_title" value="{{ $data['lp_hero_title'] }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Hero Subtitle Paragraph</label>
                    <textarea name="lp_hero_subtitle" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-indigo-500">{{ $data['lp_hero_subtitle'] }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Hero CTA 1 Label</label>
                        <input type="text" name="lp_hero_cta1_text" value="{{ $data['lp_hero_cta1_text'] }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Hero CTA 1 Target URL</label>
                        <input type="text" name="lp_hero_cta1_url" value="{{ $data['lp_hero_cta1_url'] }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Hero CTA 2 Label</label>
                        <input type="text" name="lp_hero_cta2_text" value="{{ $data['lp_hero_cta2_text'] }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Hero CTA 2 Target URL</label>
                        <input type="text" name="lp_hero_cta2_url" value="{{ $data['lp_hero_cta2_url'] }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Hero Video URL ("See How It Works" Popup)</label>
                    <input type="text" name="hero_video_url" value="{{ $data['hero_video_url'] ?? 'https://www.youtube.com/embed/dQw4w9WgXcQ' }}" placeholder="https://www.youtube.com/embed/..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-indigo-500 font-mono">
                    <span class="text-[10px] text-slate-400 mt-1 block">Enter a YouTube embed URL (e.g. https://www.youtube.com/embed/VIDEO_ID).</span>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Hero Graphic Image (Replaces Mockup if Uploaded)</label>
                    @if($data['lp_hero_image'])
                        <div class="mb-3 flex items-center space-x-3">
                            <img src="{{ asset($data['lp_hero_image']) }}" class="h-20 w-auto rounded border p-1 bg-white">
                            <button type="button" onclick="deleteLandingImage('lp_hero_image')" class="px-3 py-1 bg-rose-50 text-rose-600 rounded-lg text-xs font-bold border border-rose-200">Delete Image</button>
                        </div>
                    @endif
                    <input type="file" name="lp_hero_image" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>
            </div>
        </div>

        <!-- TAB 3: STATS & ABOUT -->
        <div x-show="activeTab === 'stats'" class="card-white p-6 rounded-2xl space-y-6">
            <h3 class="font-bold text-slate-900 text-base border-b pb-3">Stats Bar & About Section</h3>

            <!-- About Section Fields -->
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">About Section Tag</label>
                        <input type="text" name="lp_about_tag" value="{{ $data['lp_about_tag'] }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">About Section Title</label>
                        <input type="text" name="lp_about_title" value="{{ $data['lp_about_title'] }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">About Description</label>
                    <textarea name="lp_about_desc" rows="4" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-indigo-500">{{ $data['lp_about_desc'] }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">About Graphic Image</label>
                    @if($data['lp_about_image'])
                        <div class="mb-3 flex items-center space-x-3">
                            <img src="{{ asset($data['lp_about_image']) }}" class="h-20 w-auto rounded border p-1 bg-white">
                            <button type="button" onclick="deleteLandingImage('lp_about_image')" class="px-3 py-1 bg-rose-50 text-rose-600 rounded-lg text-xs font-bold border border-rose-200">Delete Image</button>
                        </div>
                    @endif
                    <input type="file" name="lp_about_image" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>
            </div>
        </div>

        <!-- TAB 4: MODEL CARDS -->
        <div x-show="activeTab === 'models'" class="card-white p-6 rounded-2xl space-y-6">
            <h3 class="font-bold text-slate-900 text-base border-b pb-3">Model 01 & Model 02 Cards</h3>

            @if(is_array($data['lp_model_cards']))
                @foreach($data['lp_model_cards'] as $mIndex => $mCard)
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-4">
                    <h4 class="font-bold text-xs text-indigo-600 uppercase">Model {{ $mIndex + 1 }} Details</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Badge Text</label>
                            <input type="text" name="lp_model_cards[{{ $mIndex }}][badge]" value="{{ $mCard['badge'] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Title</label>
                            <input type="text" name="lp_model_cards[{{ $mIndex }}][title]" value="{{ $mCard['title'] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">CTA Text</label>
                            <input type="text" name="lp_model_cards[{{ $mIndex }}][cta_text]" value="{{ $mCard['cta_text'] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Description</label>
                        <input type="text" name="lp_model_cards[{{ $mIndex }}][desc]" value="{{ $mCard['desc'] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Features (One per line)</label>
                        @php
                            $featuresText = (isset($mCard['features']) && is_array($mCard['features']) && count($mCard['features']) > 0)
                                ? implode("\n", $mCard['features'])
                                : "Launch Your Own SaaS Brand\nSell Unlimited Subscriptions\n3 SaaS Products Included\nManage Your Customers & Business\nCustom Domain & Branding\nBuild Recurring Revenue";
                        @endphp
                        <textarea name="lp_model_cards[{{ $mIndex }}][features_raw]" rows="6" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs font-mono">{{ $featuresText }}</textarea>
                    </div>
                </div>
                @endforeach
            @endif
        </div>

        <!-- TAB 5: PRODUCTS & REVENUE CALCULATOR -->
        <div x-show="activeTab === 'products'" class="card-white p-6 rounded-2xl space-y-6">
            <h3 class="font-bold text-slate-900 text-base border-b pb-3">Products & Revenue Calculator</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Calculator Title</label>
                    <input type="text" name="lp_rev_calc_title" value="{{ $data['lp_rev_calc_title'] }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Calculator Subtitle</label>
                    <input type="text" name="lp_rev_calc_subtitle" value="{{ $data['lp_rev_calc_subtitle'] }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Price Per Customer (₹)</label>
                    <input type="number" name="lp_rev_calc_price_per_customer" value="{{ $data['lp_rev_calc_price_per_customer'] }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Default Slider Customer Count</label>
                    <input type="number" name="lp_rev_calc_default_customers" value="{{ $data['lp_rev_calc_default_customers'] }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs">
                </div>
            </div>
        </div>

        <!-- TAB 6: HOW IT WORKS & WHY CHOOSE SAASRESELLING -->
        <div x-show="activeTab === 'workflow'" class="card-white p-6 rounded-2xl space-y-6">
            <h3 class="font-bold text-slate-900 text-base border-b pb-3">How SaaSReselling Works Section</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Section Tag</label>
                    <input type="text" name="lp_how_works_tag" value="{{ $data['lp_how_works_tag'] }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Section Heading</label>
                    <input type="text" name="lp_how_works_title" value="{{ $data['lp_how_works_title'] }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs">
                </div>
            </div>
        </div>

        <!-- TAB 7: PRICING & GROWTH (Task 3: Full Dynamic Controls matching Reference Image 3) -->
        <div x-show="activeTab === 'pricing'" class="card-white p-6 rounded-2xl space-y-6">
            <h3 class="font-bold text-slate-900 text-base border-b pb-3 flex items-center justify-between">
                <span>Pricing Plan Card (Reference Design)</span>
                <span class="text-xs font-normal text-slate-500">Editable from Dashboard</span>
            </h3>

            <!-- Section Headings -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Section Pill Tag</label>
                    <input type="text" name="lp_pricing_tag" value="{{ $data['lp_pricing_tag'] ?? 'PRICING' }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Section Title</label>
                    <input type="text" name="lp_pricing_title" value="{{ $data['lp_pricing_title'] ?? 'Transparent Pricing for Every Stage' }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs">
                </div>
            </div>

            <!-- Plan Card Details -->
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/90 space-y-5">
                <h4 class="font-bold text-xs text-indigo-700 uppercase tracking-wider">Plan Card Core Information</h4>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Card Top Badge</label>
                        <input type="text" name="lp_pricing_badge" value="{{ $data['lp_pricing_badge'] ?? 'FOR AGENCIES & FREELANCERS' }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Plan Name</label>
                        <input type="text" name="lp_pricing_name" value="{{ $data['lp_pricing_name'] ?? 'White-Label Panel' }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Plan Subtitle</label>
                        <input type="text" name="lp_pricing_subtitle" value="{{ $data['lp_pricing_subtitle'] ?? 'One platform. Your brand. Unlimited growth.' }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Monthly Price (₹)</label>
                        <input type="text" name="lp_pricing_monthly" value="{{ (!empty($data['lp_pricing_monthly']) && $data['lp_pricing_monthly'] != '999') ? $data['lp_pricing_monthly'] : '4,000' }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-900">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Yearly Price (₹ / year)</label>
                        <input type="text" name="lp_pricing_yearly" value="{{ (!empty($data['lp_pricing_yearly']) && $data['lp_pricing_yearly'] != '799') ? $data['lp_pricing_yearly'] : '35,000' }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold text-orange-600">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Plan Description</label>
                    <textarea name="lp_pricing_desc" rows="2" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs">{{ $data['lp_pricing_desc'] ?? 'Get 3 core SaaS products, unlimited client subscriptions, and complete white-label control under your own domain. Best for solo marketers and growth agencies.' }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">CTA Button Text</label>
                        <input type="text" name="lp_pricing_cta_text" value="{{ $data['lp_pricing_cta_text'] ?? 'Get Started' }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">CTA Button URL</label>
                        <input type="text" name="lp_pricing_cta_url" value="{{ $data['lp_pricing_cta_url'] ?? 'https://wa.me/916374913298?text=I%20am%20interested%20in%20White-Label%20Panel' }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs">
                    </div>
                </div>

                <!-- Features & Badges -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Checklist Features (One per line)</label>
                        <span class="block text-[10px] text-slate-400 mb-1.5">Displayed in 2 columns under "Everything You Get"</span>
                        @php
                            $featuresText = (isset($data['lp_pricing_features']) && is_array($data['lp_pricing_features']))
                                ? implode("\n",  $data['lp_pricing_features'])
                                : "1 White-Label Platform\nUnlimited End-Customers\n3 White-Label SaaS Products Included\nFull Partner Dashboard Access\nCustom Domain & Logo\nLifetime Updates & Support";
                        @endphp
                        <textarea name="lp_pricing_features_raw" rows="6" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-mono">{{ $featuresText }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Trust Badges Below Button (One per line)</label>
                        <span class="block text-[10px] text-slate-400 mb-1.5">Displayed with icons below the Get Started button</span>
                        @php
                            $badgesText = (isset($data['lp_pricing_trust_badges']) && is_array($data['lp_pricing_trust_badges']))
                                ? implode("\n",  $data['lp_pricing_trust_badges'])
                                : "1:1 Demo on Google Meet\nNo Credit Card\n100% Free Demo";
                        @endphp
                        <textarea name="lp_pricing_trust_badges_raw" rows="6" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-mono">{{ $badgesText }}</textarea>
                    </div>
                </div>

                <!-- Dashboard Preview Mockup Image (Task 1: Live Preview & Reliable Upload) -->
                <div class="pt-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Dashboard Screen Preview Image</label>
                    <div class="mb-3 flex items-center space-x-3">
                        <img id="pricingImagePreview" src="{{ asset(ltrim($data['lp_pricing_image'] ?? 'assets/images/user_dashboard.png', '/')) }}" class="h-20 w-auto rounded-xl border border-slate-200 p-1 bg-white shadow-sm object-contain">
                        @if(!empty($data['has_custom_pricing_image']))
                        <button type="button" onclick="deleteLandingImage('lp_pricing_image')" class="px-3 py-1 bg-rose-50 text-rose-600 rounded-lg text-xs font-bold border border-rose-200 hover:bg-rose-100 transition">Delete Image</button>
                        @endif
                    </div>
                    <input type="file" name="lp_pricing_image" onchange="previewLandingImage(this, 'pricingImagePreview')" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                    <span class="text-[10px] text-slate-400 mt-1 block">Supports JPG, PNG, WEBP, SVG up to 5MB. Instant live preview on file selection.</span>
                </div>

                <!-- Trophy Box -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Bottom Trophy Title</label>
                        <input type="text" name="lp_pricing_trophy_title" value="{{ $data['lp_pricing_trophy_title'] ?? 'Most Popular Choice' }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Bottom Trophy Description</label>
                        <input type="text" name="lp_pricing_trophy_desc" value="{{ $data['lp_pricing_trophy_desc'] ?? 'Perfect for getting started and building your customer base.' }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs">
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 8: TESTIMONIALS & FAQS (Task 2: Manage White Label Partners & Clickable Domains) -->
        <div x-show="activeTab === 'faqs'" class="card-white p-6 rounded-2xl space-y-8">
            <div>
                <h3 class="font-extrabold text-slate-900 text-base border-b pb-3 font-heading">White Label Partner Testimonials</h3>
                <p class="text-xs text-slate-500 mt-1">Manage partner reviews, live domains (youverse.in, nooryak.in, cockroach.in, bullionxacademy.com), and locations.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Section Title</label>
                    <input type="text" name="lp_testimonials_title" value="{{ $data['lp_testimonials_title'] ?? 'WHAT OUR WHITE LABEL PARTNERS SAY' }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Section Subtitle</label>
                    <input type="text" name="lp_testimonials_subtitle" value="{{ $data['lp_testimonials_subtitle'] ?? 'Real stories from active agencies and resellers running on our platform.' }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs">
                </div>
            </div>

            <!-- Partner Cards Repeater -->
            <div class="space-y-4 pt-2">
                <label class="block text-xs font-extrabold text-slate-800 uppercase tracking-wider">Partner Testimonial Cards (With Live Clickable Domains)</label>
                
                @if(is_array($data['lp_testimonials_items']))
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                    @foreach($data['lp_testimonials_items'] as $pIdx => $partner)
                    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-3.5">
                        <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                            <span class="text-xs font-extrabold text-indigo-600">Partner #{{ $pIdx + 1 }}</span>
                            <span class="text-[11px] text-slate-400 font-mono">{{ $partner['domain'] ?? '' }}</span>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 mb-1">Brand / Agency Name</label>
                                <input type="text" name="lp_testimonials_items[{{ $pIdx }}][name]" value="{{ $partner['name'] ?? '' }}" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 text-xs">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 mb-1">Live Domain (e.g. youverse.in)</label>
                                <input type="text" name="lp_testimonials_items[{{ $pIdx }}][domain]" value="{{ $partner['domain'] ?? '' }}" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-mono">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 mb-1">Live Website URL</label>
                                <input type="text" name="lp_testimonials_items[{{ $pIdx }}][url]" value="{{ $partner['url'] ?? '' }}" placeholder="https://..." class="w-full px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-mono">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 mb-1">Role / Niche</label>
                                <input type="text" name="lp_testimonials_items[{{ $pIdx }}][role]" value="{{ $partner['role'] ?? '' }}" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 text-xs">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">City / Location</label>
                            <input type="text" name="lp_testimonials_items[{{ $pIdx }}][location]" value="{{ $partner['location'] ?? '' }}" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 text-xs">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Review Quote</label>
                            <textarea name="lp_testimonials_items[{{ $pIdx }}][quote]" rows="3" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 text-xs leading-relaxed">{{ $partner['quote'] ?? '' }}</textarea>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- FAQ Section Header -->
            <div class="pt-6 border-t border-slate-200">
                <h4 class="font-extrabold text-slate-900 text-sm font-heading mb-4">FAQ Section Settings</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">FAQ Tag</label>
                        <input type="text" name="lp_faqs_tag" value="{{ $data['lp_faqs_tag'] }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">FAQ Heading</label>
                        <input type="text" name="lp_faqs_title" value="{{ $data['lp_faqs_title'] }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs">
                    </div>
                </div>
            </div>
        </div>

                <!-- TAB: SECURITY & RECAPTCHA -->
        <div x-show="activeTab === 'security'" class="card-white p-6 rounded-2xl space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="font-bold text-slate-900 text-base font-heading">Google reCAPTCHA v2 Security Settings</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Protect the "Book Free 1:1 Live Demo" modal popup and lead forms from automated bots.</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ !empty($data['recaptcha_site_key']) ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                        {{ !empty($data['recaptcha_site_key']) ? 'Configured' : 'Keys Missing' }}
                    </span>
                </div>
            </div>

            <div class="space-y-5">
                <!-- Guidance Box -->
                <div class="p-4 rounded-2xl bg-indigo-50/70 border border-indigo-100 text-xs text-slate-700 space-y-2">
                    <p class="font-bold text-indigo-900 flex items-center gap-1.5">
                        <i data-lucide="shield-check" class="w-4 h-4 text-indigo-600"></i>
                        <span>How to get your free Google reCAPTCHA v2 keys:</span>
                    </p>
                    <ol class="list-decimal list-inside space-y-1 text-slate-600 pl-1">
                        <li>Visit the <a href="https://www.google.com/recaptcha/admin/create" target="_blank" class="text-indigo-600 font-bold underline hover:text-indigo-700">Google reCAPTCHA Admin Console</a>.</li>
                        <li>Choose <strong>reCAPTCHA v2</strong> &rarr; <strong>"I'm not a robot" Checkbox</strong>.</li>
                        <li>Add your authorized domain (e.g. <code>saasreselling.com</code> or <code>localhost</code>) and submit.</li>
                        <li>Copy the generated <strong>Site Key</strong> and <strong>Secret Key</strong> and paste them in the fields below.</li>
                    </ol>
                </div>

                <!-- Toggle Verification -->
                <div class="flex items-center space-x-3 p-4 bg-slate-50 rounded-2xl border border-slate-200">
                    <input type="checkbox" id="enable_recaptcha" name="enable_recaptcha" value="1" {{ (!empty($data['enable_recaptcha']) && $data['enable_recaptcha'] == '1') ? 'checked' : '' }} class="w-4 h-4 rounded text-indigo-600 border-slate-300 focus:ring-indigo-500">
                    <div>
                        <label for="enable_recaptcha" class="text-xs font-bold text-slate-800 cursor-pointer block">
                            Enable Google reCAPTCHA Verification on Demo Popup Modal
                        </label>
                        <span class="text-[11px] text-slate-500 block">When checked, visitors must solve the checkbox before submitting a demo request.</span>
                    </div>
                </div>

                <!-- Input Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Google reCAPTCHA Site Key (Public Key)</label>
                        <input type="text" name="recaptcha_site_key" value="{{ $data['recaptcha_site_key'] ?? '' }}" placeholder="e.g. 6Ldxxxxxxxxxx-xxxxxxx" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono focus:ring-2 focus:ring-indigo-500">
                        <span class="text-[11px] text-slate-400 mt-1 block">Renders the frontend checkbox inside the modal popup.</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Google reCAPTCHA Secret Key (Private Key)</label>
                        <input type="password" name="recaptcha_secret_key" value="{{ $data['recaptcha_secret_key'] ?? '' }}" placeholder="e.g. 6Ldxxxxxxxxxx-xxxxxxx" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono focus:ring-2 focus:ring-indigo-500">
                        <span class="text-[11px] text-slate-400 mt-1 block">Used securely by the server to verify tokens with Google.</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 9: CTA BANNER & FOOTER -->
        <div x-show="activeTab === 'footer'" class="card-white p-6 rounded-2xl space-y-6">
            <h3 class="font-bold text-slate-900 text-base border-b pb-3">CTA Banner, Footer & Social Links</h3>

            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">CTA Banner Heading</label>
                        <input type="text" name="lp_cta_banner_title" value="{{ $data['lp_cta_banner_title'] }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">CTA Banner Subtitle</label>
                        <input type="text" name="lp_cta_banner_subtitle" value="{{ $data['lp_cta_banner_subtitle'] }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Contact Email</label>
                        <input type="text" name="lp_contact_email" value="{{ $data['lp_contact_email'] }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Contact Phone</label>
                        <input type="text" name="lp_contact_phone" value="{{ $data['lp_contact_phone'] }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Footer Copyright Text</label>
                    <input type="text" name="lp_copyright_text" value="{{ $data['lp_copyright_text'] }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs">
                </div>
            </div>
        </div>

        <!-- Sticky Submit Footer Bar -->
        <div class="sticky bottom-4 z-20 bg-slate-900 text-white p-4 rounded-2xl shadow-2xl flex items-center justify-between">
            <div class="flex items-center space-x-2 text-xs">
                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-400"></i>
                <span>Ready to update saasreselling.com landing page</span>
            </div>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-lg transition-all flex items-center space-x-2">
                <i data-lucide="save" class="w-4 h-4"></i>
                <span>Save All Changes</span>
            </button>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
    function deleteLandingImage(imageKey) {
        if(!confirm('Are you sure you want to delete this image?')) return;

        fetch('{{ route("admin.landing-page.delete-image") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ image_key: imageKey })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                alert('Image deleted successfully!');
                window.location.reload();
            } else {
                alert('Failed to delete image.');
            }
        });
    }
</script>
@endpush
