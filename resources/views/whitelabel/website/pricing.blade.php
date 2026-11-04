@extends('layouts.whitelabel')

@section('title', 'Website - Pricing Plans')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 font-heading">Pricing Plans Manager</h1>
            <p class="text-xs text-slate-500">Configure your subscription pricing cards, monthly/yearly rates, feature checklists, and "View More Plans" CTA buttons.</p>
        </div>
        <a href="{{ route('whitelabel.website.preview') }}#pricing" target="_blank" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-md shadow-emerald-600/20 flex items-center space-x-2 transition-all">
            <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
            <span>Preview Pricing</span>
        </a>
    </div>

    <form action="{{ route('whitelabel.website.pricing.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Section Settings --}}
        <div class="card-white rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 font-heading border-b border-slate-100 pb-3 flex items-center space-x-2">
                <i data-lucide="settings-2" class="w-4 h-4 text-emerald-600"></i>
                <span>Pricing Section Header &amp; Discounts</span>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Section Heading</label>
                    <input type="text" name="pricing_section_title" value="{{ $agency->pricing_section_title ?? 'Simple & Transparent Pricing Plans' }}"
                           class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Section Subtitle</label>
                    <input type="text" name="pricing_section_subtitle" value="{{ $agency->pricing_section_subtitle ?? 'Choose the right software solution for your growth. Cancel or upgrade anytime.' }}"
                           class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Top Badge Pill Text</label>
                    <input type="text" name="pricing_badge_text" value="{{ $agency->pricing_badge_text ?? 'Flexible Pricing Plans' }}"
                           class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Yearly Discount Tag Text (on Toggle)</label>
                    <input type="text" name="pricing_yearly_discount" value="{{ $agency->pricing_yearly_discount ?? 'Save 20%' }}"
                           class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-emerald-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Bottom Trust Bar Text</label>
                    <input type="text" name="pricing_trust_bar" value="{{ $agency->pricing_trust_bar ?? '🔒 100% Secure Checkout | ⚡ Instant Account Setup | 📞 24/7 Dedicated Support | 🔄 7-Day Money-Back Guarantee' }}"
                           class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-emerald-500">
                </div>
            </div>
        </div>

        {{-- 3 Distinct Pricing Tier Cards --}}
        <div class="card-white rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 font-heading border-b border-slate-100 pb-3 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <i data-lucide="credit-card" class="w-4 h-4 text-emerald-600"></i>
                    <span>SaaS Pricing Plan Cards</span>
                </div>
                <span class="text-xs text-slate-400 font-normal">Controls the 3 monthly/yearly pricing cards shown on your public landing page</span>
            </h3>

            <div class="space-y-6">
                @foreach($pricingPlans as $idx => $plan)
                @php
                    $isEcom = ($idx === 0) || str_contains(strtolower($plan['plan_name'] ?? ($plan['product_name'] ?? '')), 'ecom');
                    $isWeb  = ($idx === 1) || str_contains(strtolower($plan['plan_name'] ?? ($plan['product_name'] ?? '')), 'web');
                    $isWa   = ($idx === 2) || str_contains(strtolower($plan['plan_name'] ?? ($plan['product_name'] ?? '')), 'wa');
                    
                    $defBadge = $isEcom ? 'ALL-IN-ONE ECOMMERCE' : ($isWeb ? 'WEB BUILDER' : 'SEND MSG');
                    $defName = $isEcom ? 'Ecom Basic Plan' : ($isWeb ? 'Web builder Basic Plan' : 'WhatsApp Plan');
                    $defMonthly = $isEcom ? '250' : ($isWeb ? '350' : '650');
                    $defYearly = $isEcom ? '2400' : ($isWeb ? '3500' : '6500');
                    $defColor = $isEcom ? '#ea580c' : ($isWeb ? '#2563eb' : '#059669');
                    $defCta = !empty($plan['cta_text']) && $plan['cta_text'] !== 'Launch Product &rarr;' ? $plan['cta_text'] : 'View More Plans &rarr;';
                @endphp
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                        <div class="flex items-center space-x-2">
                            <span class="text-xs font-bold text-emerald-800 bg-emerald-100 px-3 py-1 rounded-full border border-emerald-200">
                                Pricing Card #{{ $idx + 1 }}: {{ $plan['plan_name'] ?? ($plan['product_name'] ?? $defName) }}
                            </span>
                            @if(!empty($plan['is_popular']))
                            <span class="text-[10px] font-bold text-amber-700 bg-amber-100 px-2 py-0.5 rounded-full border border-amber-200">★ Most Popular</span>
                            @endif
                        </div>
                        <div class="flex items-center space-x-3">
                            <label class="flex items-center space-x-1.5 cursor-pointer text-xs font-bold text-slate-700">
                                <input type="checkbox" name="is_popular[{{ $idx }}]" value="1" {{ !empty($plan['is_popular']) ? 'checked' : '' }}
                                       class="rounded text-emerald-600 focus:ring-emerald-500">
                                <span>Mark as "Most Popular"</span>
                            </label>
                            <div class="flex items-center space-x-1.5 ml-3">
                                <label class="text-[11px] font-bold text-slate-600">Theme Color:</label>
                                <input type="color" name="product_color[]" value="{{ $plan['color'] ?? $defColor }}" class="h-6 w-10 rounded cursor-pointer border border-slate-200">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- LEFT COLUMN: Plan Identity, Badge & Pricing --}}
                        <div class="bg-white border border-slate-200 rounded-lg p-4 space-y-3">
                            <div class="text-[11px] font-extrabold text-emerald-900 uppercase tracking-wide border-b border-slate-100 pb-1.5 flex items-center justify-between">
                                <span>Plan Name, Logo &amp; Rates</span>
                                <i data-lucide="tag" class="w-3.5 h-3.5 text-emerald-500"></i>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Plan Title</label>
                                    <input type="text" name="plan_name[]" value="{{ $plan['plan_name'] ?? ($plan['product_name'] ?? $defName) }}" placeholder="e.g. Ecom Basic Plan"
                                           class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-emerald-500">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Category Badge Pill</label>
                                    <input type="text" name="plan_badge[]" value="{{ $plan['plan_badge'] ?? ($plan['solution_badge'] ?? $defBadge) }}" placeholder="e.g. ALL-IN-ONE ECOMMERCE"
                                           class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-emerald-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Plan Subtitle / Description</label>
                                <input type="text" name="plan_subtitle[]" value="{{ $plan['plan_subtitle'] ?? ($plan['product_subtitle'] ?? 'Everything you need to start your online business.') }}" placeholder="Brief plan summary..."
                                       class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-medium focus:outline-none focus:border-emerald-500">
                            </div>

                            {{-- Product Logo Upload --}}
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Brand Logo (displayed top left of card)</label>
                                <div class="flex items-center space-x-2">
                                    @if(!empty($plan['product_logo']))
                                    <img src="{{ asset(ltrim($plan['product_logo'], '/')) }}" class="h-8 max-w-[100px] object-contain border rounded p-1 bg-slate-50">
                                    @endif
                                    <input type="text" name="product_logo[]" value="{{ $plan['product_logo'] ?? '' }}" class="flex-1 bg-slate-50 border border-slate-200 rounded-lg px-3 py-1 text-xs font-mono">
                                </div>
                                <input type="file" name="product_logo_file[{{ $idx }}]" accept="image/*" class="text-[11px] text-slate-500 mt-1">
                            </div>

                            {{-- Monthly & Yearly Price --}}
                            <div class="grid grid-cols-2 gap-3 pt-1">
                                <div class="p-3 bg-emerald-50/50 border border-emerald-100 rounded-lg">
                                    <label class="block text-[11px] font-extrabold text-emerald-900 mb-1">Monthly Price (₹)</label>
                                    <input type="text" name="price_monthly[]" value="{{ $plan['price_monthly'] ?? $defMonthly }}" placeholder="250"
                                           class="w-full bg-white border border-emerald-200 rounded-lg px-3 py-1.5 text-sm font-bold text-slate-900 focus:outline-none focus:border-emerald-500">
                                    <span class="text-[10px] text-slate-400 mt-0.5 block">Shown on Monthly tab</span>
                                </div>
                                <div class="p-3 bg-indigo-50/50 border border-indigo-100 rounded-lg">
                                    <label class="block text-[11px] font-extrabold text-indigo-900 mb-1">Yearly Price (₹)</label>
                                    <input type="text" name="price_yearly[]" value="{{ $plan['price_yearly'] ?? $defYearly }}" placeholder="2400"
                                           class="w-full bg-white border border-indigo-200 rounded-lg px-3 py-1.5 text-sm font-bold text-slate-900 focus:outline-none focus:border-indigo-500">
                                    <span class="text-[10px] text-slate-400 mt-0.5 block">Shown on Yearly tab</span>
                                </div>
                            </div>
                        </div>

                        {{-- RIGHT COLUMN: What's Included Features & CTA Button --}}
                        <div class="bg-white border border-slate-200 rounded-lg p-4 space-y-3">
                            <div class="text-[11px] font-extrabold text-emerald-900 uppercase tracking-wide border-b border-slate-100 pb-1.5 flex items-center justify-between">
                                <span>What's Included &amp; Action CTA</span>
                                <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-500"></i>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">
                                    What's Included Checklist <span class="font-normal text-slate-400">(one item per line)</span>
                                </label>
                                @php
                                    $pFeats = is_array($plan['features'] ?? null) ? implode("\n", $plan['features']) : ($plan['features'] ?? '');
                                @endphp
                                <textarea name="features[]" rows="6" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs font-medium focus:outline-none focus:border-emerald-500" placeholder="1 Online Store (Product Module)&#10;Up to 1,000 Orders / Customers&#10;Inventory & Order Management">{{ $pFeats }}</textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">CTA Button Text</label>
                                    <input type="text" name="cta_text[]" value="{{ $defCta }}" placeholder="View More Plans &rarr;"
                                           class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-emerald-500">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Subnote below button</label>
                                    <input type="text" name="cta_subnote[]" value="{{ $plan['cta_subnote'] ?? '⚡ Instant Activation • Zero Setup Fees' }}"
                                           class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-emerald-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">CTA Button Link (URL)</label>
                                <input type="text" name="cta_url[]" value="{{ $plan['cta_url'] ?? '' }}" placeholder="https://ecom.saasreselling.in/#pricing"
                                       class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-mono focus:outline-none focus:border-emerald-500">
                                <span class="text-[10px] text-slate-400 mt-0.5 block">Leave empty to auto-link directly to that product's dedicated pricing page.</span>
                            </div>

                            <input type="hidden" name="product_gradient[]" value="{{ $plan['gradient'] ?? '' }}">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Trust Elements (3 cards below pricing) --}}
        <div class="card-white rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 font-heading border-b border-slate-100 pb-3 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <i data-lucide="shield-check" class="w-4 h-4 text-emerald-600"></i>
                    <span>Secure &amp; Reliable Trust Elements (3 Cards below Pricing)</span>
                </div>
                <span class="text-[11px] text-slate-400 font-normal">Controls the 3 trust features shown under the pricing cards</span>
            </h3>

            @php
                $trustCards = $agency->parsed_pricing_trust_elements ?? [
                    ['icon' => 'shield-check', 'title' => 'Bank-Grade Security', 'desc' => '256-bit SSL encryption & secure cloud infrastructure protect your data 24/7.', 'bg' => '#d1fae5', 'color' => '#059669'],
                    ['icon' => 'headset',      'title' => '24/7 Dedicated Support', 'desc' => 'Priority WhatsApp & ticket assistance from our specialized engineering team.', 'bg' => '#dbeafe', 'color' => '#2563eb'],
                    ['icon' => 'refresh-cw',   'title' => 'No Risk Guarantee', 'desc' => 'Instant activation, cancel or switch plans anytime with one click from dashboard.', 'bg' => '#ede9fe', 'color' => '#7c3aed'],
                ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($trustCards as $tIdx => $tc)
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-700">Trust Element #{{ $tIdx + 1 }}</span>
                        <div class="flex items-center space-x-1.5">
                            <label class="text-[10px] font-bold text-slate-500">Color:</label>
                            <input type="color" name="trust_item_color[]" value="{{ $tc['color'] ?? '#059669' }}" class="h-6 w-10 rounded cursor-pointer border border-slate-200">
                            <input type="hidden" name="trust_item_bg[]" value="{{ $tc['bg'] ?? '#d1fae5' }}">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-600 mb-1">Lucide Icon Name</label>
                        <input type="text" name="trust_item_icon[]" value="{{ $tc['icon'] ?? 'shield-check' }}" placeholder="shield-check"
                               class="w-full bg-white border border-slate-200 rounded px-2.5 py-1 text-xs font-mono">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-600 mb-1">Title</label>
                        <input type="text" name="trust_item_title[]" value="{{ $tc['title'] ?? '' }}" placeholder="Bank-Grade Security"
                               class="w-full bg-white border border-slate-200 rounded px-2.5 py-1 text-xs font-semibold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-600 mb-1">Description</label>
                        <textarea name="trust_item_desc[]" rows="2" class="w-full bg-white border border-slate-200 rounded px-2.5 py-1 text-xs">{{ $tc['desc'] ?? '' }}</textarea>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Submit Button --}}
        <div class="flex items-center justify-end space-x-3 pt-2">
            <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-md shadow-emerald-600/20 flex items-center space-x-2 transition-all">
                <i data-lucide="check" class="w-4 h-4"></i>
                <span>Save Pricing Plans</span>
            </button>
        </div>

    </form>
</div>
@endsection
