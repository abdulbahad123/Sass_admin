@extends('layouts.whitelabel')

@section('title', 'Website - Pricing Plans')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 font-heading">Pricing Plans Manager</h1>
        <p class="text-xs text-slate-500">Configure the per-product pricing cards shown on your public landing page.</p>
    </div>

    <form action="{{ route('whitelabel.website.pricing.update') }}" method="POST" class="space-y-6">
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
                    <input type="text" name="pricing_section_subtitle" value="{{ $agency->pricing_section_subtitle ?? 'Scale seamlessly with zero hidden fees.' }}"
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
                <span class="text-xs text-slate-400 font-normal ml-1">(each product card shows left=brand, right=plan)</span>
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

                    <div class="grid grid-cols-2 gap-3">
                        {{-- LEFT SIDE: Product Brand --}}
                        <div class="space-y-3 border border-slate-200 rounded-lg p-3 bg-white">
                            <p class="text-[10px] font-extrabold text-slate-500 uppercase tracking-widest">← Product Brand (Left Panel)</p>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Product Name</label>
                                <input type="text" name="product_name[]" value="{{ $plan['product_name'] ?? '' }}" placeholder="e.g. ECOM BUILDER"
                                       class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Tagline Badge</label>
                                <input type="text" name="product_tagline[]" value="{{ $plan['product_tagline'] ?? '' }}" placeholder="e.g. Online Store Builder"
                                       class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Subtitle</label>
                                <input type="text" name="product_subtitle[]" value="{{ $plan['product_subtitle'] ?? '' }}" placeholder="e.g. Launch your digital store in minutes."
                                       class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Trust Count</label>
                                <input type="text" name="trust_count[]" value="{{ $plan['trust_count'] ?? '' }}" placeholder="e.g. 10,000+ Sellers Trust Us"
                                       class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Brand Color</label>
                                    <div class="flex items-center space-x-2">
                                        <input type="color" name="product_color[]" value="{{ $plan['color'] ?? '#f97316' }}" class="h-8 w-12 rounded cursor-pointer border border-slate-200">
                                        <input type="text" name="product_color_txt[]" value="{{ $plan['color'] ?? '#f97316' }}" class="flex-1 bg-slate-50 border border-slate-200 rounded-lg px-2 py-1.5 text-xs font-mono focus:outline-none" readonly>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Icon (Lucide)</label>
                                    <input type="text" name="product_icon[]" value="{{ $plan['icon'] ?? 'shopping-bag' }}" placeholder="e.g. shopping-bag"
                                           class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-mono focus:outline-none focus:border-indigo-500">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Brand Gradient (CSS)</label>
                                <input type="text" name="product_gradient[]" value="{{ $plan['gradient'] ?? 'linear-gradient(135deg,#f97316,#ea580c)' }}"
                                       class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-mono focus:outline-none focus:border-indigo-500">
                            </div>
                        </div>

                        {{-- RIGHT SIDE: Plan Details --}}
                        <div class="space-y-3 border border-slate-200 rounded-lg p-3 bg-white">
                            <p class="text-[10px] font-extrabold text-slate-500 uppercase tracking-widest">→ Plan Details (Right Panel)</p>
                            <div class="flex items-center space-x-2">
                                <div class="flex-1">
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Plan Badge Text</label>
                                    <input type="text" name="plan_badge[]" value="{{ $plan['plan_badge'] ?? 'STARTER TIER' }}"
                                           class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                                </div>
                                <div class="mt-4 flex items-center space-x-1.5">
                                    <input type="checkbox" name="is_popular[{{ $idx }}]" id="popular_{{ $idx }}" value="1" {{ !empty($plan['is_popular']) ? 'checked' : '' }} class="rounded">
                                    <label for="popular_{{ $idx }}" class="text-[11px] font-bold text-slate-600 whitespace-nowrap">🔥 Most Popular</label>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Plan Name</label>
                                <input type="text" name="plan_name[]" value="{{ $plan['plan_name'] ?? '' }}" placeholder="e.g. Starter Growth"
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
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">CTA Button Text</label>
                                    <input type="text" name="cta_text[]" value="{{ $plan['cta_text'] ?? 'Get Started Free' }}"
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
            <div class="grid grid-cols-2 gap-3">
                <div class="space-y-3 border border-slate-200 rounded-lg p-3 bg-white">
                    <p class="text-[10px] font-extrabold text-slate-500 uppercase tracking-widest">← Product Brand (Left Panel)</p>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Product Name</label>
                        <input type="text" name="product_name[]" placeholder="e.g. ECOM BUILDER" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Tagline Badge</label>
                        <input type="text" name="product_tagline[]" placeholder="e.g. Online Store Builder" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Subtitle</label>
                        <input type="text" name="product_subtitle[]" placeholder="e.g. Launch your digital store in minutes." class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Trust Count</label>
                        <input type="text" name="trust_count[]" placeholder="e.g. 10,000+ Sellers Trust Us" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Brand Color</label>
                            <input type="color" name="product_color[]" value="#6366f1" class="h-8 w-12 rounded cursor-pointer border border-slate-200">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Icon (Lucide)</label>
                            <input type="text" name="product_icon[]" placeholder="e.g. layers" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-mono focus:outline-none focus:border-indigo-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Brand Gradient (CSS)</label>
                        <input type="text" name="product_gradient[]" placeholder="linear-gradient(135deg,#6366f1,#4f46e5)" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-mono focus:outline-none focus:border-indigo-500">
                    </div>
                </div>
                <div class="space-y-3 border border-slate-200 rounded-lg p-3 bg-white">
                    <p class="text-[10px] font-extrabold text-slate-500 uppercase tracking-widest">→ Plan Details (Right Panel)</p>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Plan Badge Text</label>
                        <input type="text" name="plan_badge[]" placeholder="STARTER TIER" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Plan Name</label>
                        <input type="text" name="plan_name[]" placeholder="e.g. Starter Growth" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Monthly Price (₹)</label>
                            <input type="number" name="price_monthly[]" value="499" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Yearly Price (₹)</label>
                            <input type="number" name="price_yearly[]" value="4999" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Features (one per line)</label>
                        <textarea name="features[]" rows="5" placeholder="Feature 1&#10;Feature 2&#10;Feature 3" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs font-semibold focus:outline-none focus:border-indigo-500"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">CTA Button Text</label>
                            <input type="text" name="cta_text[]" value="Get Started Free" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-indigo-500">
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
