@extends('layouts.whitelabel')

@section('title', 'Website - Products Showcase')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 font-heading">Products Showcase Manager</h1>
            <p class="text-xs text-slate-500">Customize the "Smart Tools for Smarter Businesses" product showcase section with laptop mockups and capabilities.</p>
        </div>
        <a href="{{ route('whitelabel.website.preview') }}#products" target="_blank" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md shadow-blue-600/20 flex items-center space-x-2 transition-all">
            <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
            <span>Preview Section</span>
        </a>
    </div>

    <form action="{{ route('whitelabel.website.products.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Section Header Settings --}}
        <div class="card-white rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 font-heading border-b border-slate-100 pb-3 flex items-center space-x-2">
                <i data-lucide="layers" class="w-4 h-4 text-blue-600"></i>
                <span>Showcase Section Header</span>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Section Badge Pill</label>
                    <input type="text" name="products_section_badge" value="{{ $agency->products_section_badge ?? 'Our Products' }}"
                           class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-blue-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Main Section Heading</label>
                    <input type="text" name="products_section_title" value="{{ $agency->products_section_title ?? 'Smart Tools for Smarter Businesses' }}"
                           class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-blue-500">
                </div>
                <div class="md:col-span-3">
                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Section Subtitle / Description</label>
                    <input type="text" name="products_section_subtitle" value="{{ $agency->products_section_subtitle ?? 'A complete suite of business growth tools designed for Indian entrepreneurs and local businesses.' }}"
                           class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-blue-500">
                </div>
            </div>
        </div>

        {{-- Product Showcase Cards Repeater --}}
        <div class="card-white rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 font-heading border-b border-slate-100 pb-3 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <i data-lucide="package" class="w-4 h-4 text-blue-600"></i>
                    <span>Product Showcase Cards</span>
                </div>
                <span class="text-xs text-slate-400 font-normal">Controls the 3 visual cards featuring laptop mockups, taglines, and key capabilities</span>
            </h3>

            <div class="space-y-6">
                @foreach($productsShowcase as $idx => $prod)
                @php
                    $isEcom = ($idx === 0) || str_contains(strtolower($prod['product_name'] ?? ''), 'ecom');
                    $isWeb  = ($idx === 1) || str_contains(strtolower($prod['product_name'] ?? ''), 'web');
                    $isWa   = ($idx === 2) || str_contains(strtolower($prod['product_name'] ?? ''), 'wa');
                    $defBadge = $isEcom ? 'ALL-IN-ONE ECOMMERCE' : ($isWeb ? 'DRAG & DROP BUILDER' : 'MARKETING AUTOMATION');
                    $defSubbadge = $isEcom ? 'Instant Setup' : ($isWeb ? 'No Coding Needed' : 'High ROI');
                @endphp
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                        <div class="flex items-center space-x-2">
                            <span class="text-xs font-bold text-blue-700 bg-blue-50 px-3 py-1 rounded-full border border-blue-100">
                                Product Card #{{ $idx + 1 }}: {{ $prod['product_name'] ?? 'Product' }}
                            </span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <label class="text-[11px] font-bold text-slate-600">Card Left BG:</label>
                            <input type="color" name="left_bg[]" value="{{ $prod['left_bg'] ?? ($isEcom ? '#fff5ee' : ($isWeb ? '#f0f6ff' : '#f0fdf4')) }}" class="h-6 w-10 rounded cursor-pointer border border-slate-200">
                            <label class="text-[11px] font-bold text-slate-600 ml-2">Accent Color:</label>
                            <input type="color" name="product_color[]" value="{{ $prod['color'] ?? ($isEcom ? '#ea580c' : ($isWeb ? '#2563eb' : '#25d366')) }}" class="h-6 w-10 rounded cursor-pointer border border-slate-200">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- LEFT COLUMN: Branding & Mockup Image --}}
                        <div class="bg-white border border-slate-200 rounded-lg p-4 space-y-3">
                            <div class="text-[11px] font-extrabold text-blue-900 uppercase tracking-wide border-b border-slate-100 pb-1.5 flex items-center justify-between">
                                <span>Visual &amp; Brand Assets</span>
                                <i data-lucide="image" class="w-3.5 h-3.5 text-blue-500"></i>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Product Brand Name</label>
                                <input type="text" name="product_name[]" value="{{ $prod['product_name'] ?? '' }}" placeholder="e.g. ECOM BUILDER"
                                       class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Tagline Bullets (e.g. Build • Sell • Grow)</label>
                                <input type="text" name="product_tagline[]" value="{{ $prod['product_tagline'] ?? '' }}" placeholder="Build • Sell • Grow"
                                       class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Main Heading</label>
                                <input type="text" name="product_title[]" value="{{ $prod['product_title'] ?? '' }}" placeholder="e.g. Complete E-Commerce Solution"
                                       class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Description Paragraph</label>
                                <textarea name="product_subtitle[]" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-medium focus:outline-none focus:border-blue-500" placeholder="Brief summary of product capabilities...">{{ $prod['product_subtitle'] ?? '' }}</textarea>
                            </div>

                            {{-- Product Logo Upload --}}
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Product Logo (PNG)</label>
                                <div class="flex items-center space-x-2">
                                    @if(!empty($prod['product_logo']))
                                    <img src="{{ asset(ltrim($prod['product_logo'], '/')) }}" class="h-8 max-w-[100px] object-contain border rounded p-1 bg-slate-50">
                                    @endif
                                    <input type="text" name="product_logo[]" value="{{ $prod['product_logo'] ?? '' }}" class="flex-1 bg-slate-50 border border-slate-200 rounded-lg px-3 py-1 text-xs font-mono">
                                </div>
                                <input type="file" name="product_logo_file[{{ $idx }}]" accept="image/*" class="text-[11px] text-slate-500 mt-1">
                            </div>

                            {{-- Product Mockup Image Upload --}}
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Product Mockup Image (Laptop / Desktop)</label>
                                <div class="flex items-center space-x-2">
                                    @if(!empty($prod['product_image']))
                                    <img src="{{ asset(ltrim($prod['product_image'], '/')) }}" class="h-10 max-w-[120px] object-contain border rounded p-1 bg-slate-50">
                                    @endif
                                    <input type="text" name="product_image[]" value="{{ $prod['product_image'] ?? '' }}" class="flex-1 bg-slate-50 border border-slate-200 rounded-lg px-3 py-1 text-xs font-mono">
                                </div>
                                <input type="file" name="product_image_file[{{ $idx }}]" accept="image/*" class="text-[11px] text-slate-500 mt-1">
                            </div>
                        </div>

                        {{-- RIGHT COLUMN: Badges, Features & CTA --}}
                        <div class="bg-white border border-slate-200 rounded-lg p-4 space-y-3">
                            <div class="text-[11px] font-extrabold text-blue-900 uppercase tracking-wide border-b border-slate-100 pb-1.5 flex items-center justify-between">
                                <span>Badges &amp; Key Features</span>
                                <i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-emerald-500"></i>
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Category Badge Pill</label>
                                    <input type="text" name="solution_badge[]" value="{{ $prod['solution_badge'] ?? ($prod['plan_badge'] ?? $defBadge) }}" placeholder="e.g. ALL-IN-ONE ECOMMERCE"
                                           class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Sub-badge Tag</label>
                                    <input type="text" name="solution_subbadge[]" value="{{ $prod['solution_subbadge'] ?? ($prod['plan_subbadge'] ?? $defSubbadge) }}" placeholder="e.g. Instant Setup"
                                           class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-blue-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">
                                    Key Capabilities &amp; Features <span class="font-normal text-slate-400">(one feature per line)</span>
                                </label>
                                @php
                                    $featList = is_array($prod['features'] ?? null) ? implode("\n", $prod['features']) : ($prod['features'] ?? '');
                                @endphp
                                <textarea name="features[]" rows="6" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs font-medium focus:outline-none focus:border-blue-500" placeholder="Feature 1&#10;Feature 2&#10;Feature 3">{{ $featList }}</textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">CTA Button Text</label>
                                    <input type="text" name="cta_text[]" value="{{ $prod['cta_text'] ?? 'Launch Product &rarr;' }}"
                                           class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">CTA Trust / Subnote</label>
                                    <input type="text" name="trust_count[]" value="{{ $prod['trust_count'] ?? '' }}" placeholder="e.g. Trusted by 10,000+ Sellers"
                                           class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-blue-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">CTA Button Destination URL</label>
                                <input type="text" name="cta_url[]" value="{{ $prod['cta_url'] ?? '' }}" placeholder="https://ecom.saasreselling.in"
                                       class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-mono focus:outline-none focus:border-blue-500">
                            </div>

                            <input type="hidden" name="product_gradient[]" value="{{ $prod['gradient'] ?? '' }}">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Submit Button --}}
        <div class="flex items-center justify-end space-x-3 pt-2">
            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md shadow-blue-600/20 flex items-center space-x-2 transition-all">
                <i data-lucide="check" class="w-4 h-4"></i>
                <span>Save Products Showcase</span>
            </button>
        </div>

    </form>
</div>
@endsection
