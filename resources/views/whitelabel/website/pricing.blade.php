@extends('layouts.whitelabel')

@section('title', 'Website - Pricing Plans')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 font-heading">Pricing Plans Manager</h1>
        <p class="text-xs text-slate-500">Configure the per-product pricing cards shown on your public landing page matching the 2-column card design.</p>
    </div>

    <form action="{{ route('whitelabel.website.pricing.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Section Settings --}}
        <div class="card-white rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 font-heading border-b border-slate-100 pb-3 flex items-center space-x-2">
                <i data-lucide="settings-2" class="w-4 h-4 text-indigo-600"></i>
                <span>Section Settings</span>
            </h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Section Heading</label>
                    <input type="text" name="pricing_section_title" value="{{ $agency->pricing_section_title ?? 'Choose Your Perfect Plan' }}"
                           class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Section Subtitle</label>
                    <input type="text" name="pricing_section_subtitle" value="{{ $agency->pricing_section_subtitle ?? 'Powerful tools to grow your business. Simple, transparent pricing.' }}"
                           class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                </div>
                <div class="col-span-2">
                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Trust Bar Items (comma-separated, e.g. 🔒 Secure &amp; Reliable, 📞 24/7 Support)</label>
                    <input type="text" name="pricing_trust_bar" value="{{ $agency->pricing_trust_bar ?? '🔒 Secure & Reliable,📞 24/7 Support,❤️ Trusted by 10,000+ Businesses' }}"
                           class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                </div>
            </div>
        </div>

        {{-- Per-Product Plan Cards --}}
        <div class="card-white rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 font-heading border-b border-slate-100 pb-3 flex items-center space-x-2">
                <i data-lucide="credit-card" class="w-4 h-4 text-indigo-600"></i>
                <span>Product Plan Cards</span>
                <span class="text-xs text-slate-400 font-normal ml-1">(Left panel = product branding &amp; mockup image; Right panel = plan pricing &amp; features)</span>
            </h3>

            <div id="pricingContainer" class="space-y-6">
                @foreach($pricingPlans as $idx => $plan)
                <div class="plan-item bg-slate-50 border border-slate-200 rounded-xl p-5 space-y-4">
                    {{-- Card Header --}}
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-indigo-700 bg-indigo-50 px-3 py-1 rounded-full">Product Card #{{ $idx + 1 }}</span>
                        <button type="button" onclick="this.closest('.plan-item').remove()" class="text-rose-500 hover:text-rose-700 text-xs font-bold flex items-center space-x-1">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                            <span>Remove</span>
                        </button>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- LEFT SIDE: Product Brand & Images --}}
                        <div class="space-y-3 border border-slate-200 rounded-lg p-4 bg-white">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                                <p class="text-[10px] font-extrabold text-slate-500 uppercase tracking-widest">← Left Panel (Product Visual)</p>
                                <div class="flex items-center space-x-1.5">
                                    <label class="text-[10px] font-bold text-slate-500">Panel BG:</label>
                                    <input type="color" name="left_bg[]" value="{{ $plan['left_bg'] ?? '#fff5ee' }}" class="h-6 w-10 rounded cursor-pointer border border-slate-200">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Product Name</label>
                                <input type="text" name="product_name[]" value="{{ $plan['product_name'] ?? '' }}" placeholder="e.g. ECOM BUILDER"
                                       class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                            </div>

                            {{-- Product Logo Upload / URL --}}
                            <div class="space-y-1">
                                <label class="block text-[11px] font-bold text-slate-600">Product Logo PNG</label>
                                <div class="flex items-center space-x-2">
                                    @if(!empty($plan['product_logo']))
                                    <img src="{{ asset($plan['product_logo']) }}" class="h-8 w-auto max-w-[100px] object-contain border rounded p-1 bg-slate-50">
                                    @endif
                                    <input type="text" name="product_logo[]" value="{{ $plan['product_logo'] ?? '' }}" placeholder="assets/landing_page/ecom_logo.png"
                                           class="flex-1 bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-mono focus:outline-none">
                                </div>
                                <input type="file" name="product_logo_file[{{ $idx }}]" accept="image/*" class="text-[11px] text-slate-500 mt-1">
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Tagline Bullets (e.g. Build • Sell • Grow)</label>
                                <input type="text" name="product_tagline[]" value="{{ $plan['product_tagline'] ?? '' }}" placeholder="e.g. Build • Sell • Grow"
                                       class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Main Heading (e.g. Complete E-Commerce Solution)</label>
                                <input type="text" name="product_title[]" value="{{ $plan['product_title'] ?? '' }}" placeholder="e.g. Complete E-Commerce Solution"
                                       class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Description Paragraph</label>
                                <textarea name="product_subtitle[]" rows="2" placeholder="Launch your online store, manage products..."
                                          class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs font-semibold focus:outline-none focus:border-indigo-500">{{ $plan['product_subtitle'] ?? '' }}</textarea>
                            </div>

                            {{-- Mockup Image Upload / URL --}}
                            <div class="space-y-1">
                                <label class="block text-[11px] font-bold text-slate-600">Product Mockup Image (Laptop / Desktop)</label>
                                <div class="flex items-center space-x-2">
                                    @if(!empty($plan['product_image']))
                                    <img src="{{ asset($plan['product_image']) }}" class="h-10 w-14 object-cover rounded border p-0.5">
                                    @endif
                                    <input type="text" name="product_image[]" value="{{ $plan['product_image'] ?? '' }}" placeholder="assets/landing_page/ecombuilder_image.png"
                                           class="flex-1 bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-mono focus:outline-none">
                                </div>
                                <input type="file" name="product_image_file[{{ $idx }}]" accept="image/*" class="text-[11px] text-slate-500 mt-1">
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Trust Pill Text</label>
                                <input type="text" name="trust_count[]" value="{{ $plan['trust_count'] ?? '' }}" placeholder="e.g. Trusted by 10,000+ Sellers"
                                       class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                            </div>

                            <div class="grid grid-cols-2 gap-2 pt-1 border-t border-slate-100">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Theme Accent Color</label>
                                    <div class="flex items-center space-x-2">
                                        <input type="color" name="product_color[]" value="{{ $plan['color'] ?? '#ea580c' }}" class="h-8 w-12 rounded cursor-pointer border border-slate-200">
                                        <input type="text" value="{{ $plan['color'] ?? '#ea580c' }}" class="flex-1 bg-slate-50 border border-slate-200 rounded-lg px-2 py-1 text-xs font-mono" readonly>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Button Gradient CSS</label>
                                    <input type="text" name="product_gradient[]" value="{{ $plan['gradient'] ?? 'linear-gradient(135deg,#f97316,#ea580c)' }}"
                                           class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2 py-1.5 text-[11px] font-mono focus:outline-none">
                                </div>
                            </div>
                        </div>

                        {{-- RIGHT SIDE: Plan Details & Pricing --}}
                        <div class="space-y-3 border border-slate-200 rounded-lg p-4 bg-white">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                                <p class="text-[10px] font-extrabold text-slate-500 uppercase tracking-widest">→ Right Panel (Plan &amp; Pricing)</p>
                                <div class="flex items-center space-x-1.5">
                                    <input type="checkbox" name="is_popular[{{ $idx }}]" id="popular_{{ $idx }}" value="1" {{ !empty($plan['is_popular']) ? 'checked' : '' }} class="rounded">
                                    <label for="popular_{{ $idx }}" class="text-[11px] font-bold text-indigo-700 whitespace-nowrap">🔥 Highlight Card</label>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Plan Badge (e.g. STARTER)</label>
                                    <input type="text" name="plan_badge[]" value="{{ $plan['plan_badge'] ?? 'STARTER' }}"
                                           class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Sub-badge (e.g. Solopreneurs)</label>
                                    <input type="text" name="plan_subbadge[]" value="{{ $plan['plan_subbadge'] ?? 'For Solopreneurs' }}"
                                           class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Plan Name</label>
                                <input type="text" name="plan_name[]" value="{{ $plan['plan_name'] ?? '' }}" placeholder="e.g. Starter Growth"
                                       class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Plan Subtitle</label>
                                <input type="text" name="plan_subtitle[]" value="{{ $plan['plan_subtitle'] ?? '' }}" placeholder="e.g. Everything you need to start..."
                                       class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Monthly Price (₹)</label>
                                    <input type="number" name="price_monthly[]" value="{{ $plan['price_monthly'] ?? '499' }}"
                                           class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Yearly Price (₹)</label>
                                    <input type="number" name="price_yearly[]" value="{{ $plan['price_yearly'] ?? '4999' }}"
                                           class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Features (one per line)</label>
                                <textarea name="features[]" rows="5" placeholder="1 Online Store&#10;Up to 1,000 Products&#10;Basic Analytics"
                                          class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs font-semibold focus:outline-none focus:border-indigo-500">{{ implode("\n", $plan['features'] ?? []) }}</textarea>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">CTA Button Text (with arrow)</label>
                                <input type="text" name="cta_text[]" value="{{ $plan['cta_text'] ?? 'Get Started with Ecom Builder →' }}"
                                       class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">CTA Subnote below button</label>
                                    <input type="text" name="cta_subnote[]" value="{{ $plan['cta_subnote'] ?? 'No credit card required • Setup in minutes' }}"
                                           class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">CTA Button URL</label>
                                    <input type="text" name="cta_url[]" value="{{ $plan['cta_url'] ?? '/login' }}"
                                           class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <button type="button" onclick="addPlanRow()" class="px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold rounded-xl text-xs flex items-center space-x-1.5 transition-all border border-indigo-100">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Add Product Plan Card</span>
            </button>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs shadow-lg shadow-indigo-600/30 transition-all">
                Save Pricing Plans
            </button>
        </div>
    </form>
</div>

<script>
function addPlanRow() {
    const container = document.getElementById('pricingContainer');
    const count = container.children.length + 1;
    const html = `
        <div class="plan-item bg-slate-50 border border-slate-200 rounded-xl p-5 space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-indigo-700 bg-indigo-50 px-3 py-1 rounded-full">Product Card #${count}</span>
                <button type="button" onclick="this.closest('.plan-item').remove()" class="text-rose-500 hover:text-rose-700 text-xs font-bold flex items-center space-x-1">
                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                    <span>Remove</span>
                </button>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-3 border border-slate-200 rounded-lg p-4 bg-white">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                        <p class="text-[10px] font-extrabold text-slate-500 uppercase tracking-widest">← Left Panel (Product Visual)</p>
                        <div class="flex items-center space-x-1.5">
                            <label class="text-[10px] font-bold text-slate-500">Panel BG:</label>
                            <input type="color" name="left_bg[]" value="#f0f6ff" class="h-6 w-10 rounded cursor-pointer border border-slate-200">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Product Name</label>
                        <input type="text" name="product_name[]" placeholder="e.g. Website Builder" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[11px] font-bold text-slate-600">Product Logo PNG</label>
                        <input type="text" name="product_logo[]" placeholder="assets/landing_page/websitebuilder_logo.png" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-mono focus:outline-none">
                        <input type="file" name="product_logo_file[]" accept="image/*" class="text-[11px] text-slate-500 mt-1">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Tagline Bullets</label>
                        <input type="text" name="product_tagline[]" placeholder="e.g. Design • Build • Grow" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Main Heading</label>
                        <input type="text" name="product_title[]" placeholder="e.g. Professional Website Solution" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Description Paragraph</label>
                        <textarea name="product_subtitle[]" rows="2" placeholder="Create stunning websites..." class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs font-semibold focus:outline-none focus:border-indigo-500"></textarea>
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[11px] font-bold text-slate-600">Product Mockup Image</label>
                        <input type="text" name="product_image[]" placeholder="assets/landing_page/websitebuilder_image.png" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-mono focus:outline-none">
                        <input type="file" name="product_image_file[]" accept="image/*" class="text-[11px] text-slate-500 mt-1">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Trust Pill Text</label>
                        <input type="text" name="trust_count[]" placeholder="e.g. Trusted by 50,000+ Creators" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                    </div>
                    <div class="grid grid-cols-2 gap-2 pt-1 border-t border-slate-100">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Theme Accent Color</label>
                            <input type="color" name="product_color[]" value="#2563eb" class="h-8 w-12 rounded cursor-pointer border border-slate-200">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Button Gradient CSS</label>
                            <input type="text" name="product_gradient[]" value="linear-gradient(135deg,#2563eb,#1d4ed8)" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2 py-1.5 text-[11px] font-mono focus:outline-none">
                        </div>
                    </div>
                </div>
                <div class="space-y-3 border border-slate-200 rounded-lg p-4 bg-white">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                        <p class="text-[10px] font-extrabold text-slate-500 uppercase tracking-widest">→ Right Panel (Plan &amp; Pricing)</p>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Plan Badge</label>
                            <input type="text" name="plan_badge[]" placeholder="👑 PRO BUSINESS" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Sub-badge</label>
                            <input type="text" name="plan_subbadge[]" placeholder="Most Popular" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Plan Name</label>
                        <input type="text" name="plan_name[]" placeholder="e.g. Professional Pro" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Plan Subtitle</label>
                        <input type="text" name="plan_subtitle[]" placeholder="All the tools to create..." class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Monthly Price (₹)</label>
                            <input type="number" name="price_monthly[]" value="1499" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Yearly Price (₹)</label>
                            <input type="number" name="price_yearly[]" value="14999" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Features (one per line)</label>
                        <textarea name="features[]" rows="5" placeholder="Feature 1&#10;Feature 2&#10;Feature 3" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs font-semibold focus:outline-none focus:border-indigo-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">CTA Button Text</label>
                        <input type="text" name="cta_text[]" value="Start 14-Day Free Trial →" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">CTA Subnote</label>
                            <input type="text" name="cta_subnote[]" value="No credit card required • Cancel anytime" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">CTA URL</label>
                            <input type="text" name="cta_url[]" value="/login" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
    lucide.createIcons();
}
</script>
@endsection
