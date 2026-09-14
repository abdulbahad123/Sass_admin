@extends('layouts.admin')

@section('title', 'Platform Landing Page Editor')
@section('page_title', 'Nooryak.in Landing Page Editor')

@section('content')
<div class="max-w-6xl mx-auto space-y-8" x-data="{ activeTab: 'hero' }">

    <!-- Top Banner & Actions -->
    <div class="card-white p-6 rounded-2xl flex flex-col md:flex-row md:items-center justify-between gap-4 border border-slate-200">
        <div>
            <h2 class="text-xl font-bold text-slate-900 font-heading">Nooryak Platform Landing Page Editor</h2>
            <p class="text-xs text-slate-500 mt-1">Manage all headlines, images, product features, model cards, calculator values, pricing, FAQs, and testimonials on <span class="font-bold text-blue-600">nooryak.in</span>.</p>
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

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Navbar Logo Image</label>
                    @if($data['lp_header_logo'])
                        <div class="mb-3 flex items-center space-x-3">
                            <img src="{{ asset($data['lp_header_logo']) }}" class="h-10 w-auto rounded border p-1 bg-white">
                            <button type="button" onclick="deleteLandingImage('lp_header_logo')" class="px-3 py-1 bg-rose-50 text-rose-600 rounded-lg text-xs font-bold border border-rose-200">Delete Image</button>
                        </div>
                    @endif
                    <input type="file" name="lp_header_logo" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
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
            <h3 class="font-bold text-slate-900 text-base border-b pb-3">Hero Banner Section</h3>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Hero Main Headline</label>
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
                        <textarea name="lp_model_cards[{{ $mIndex }}][features_raw]" rows="4" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs">{{ isset($mCard['features']) && is_array($mCard['features']) ? implode("\n", $mCard['features']) : '' }}</textarea>
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

        <!-- TAB 6: HOW IT WORKS & WHY CHOOSE NOORYAK -->
        <div x-show="activeTab === 'workflow'" class="card-white p-6 rounded-2xl space-y-6">
            <h3 class="font-bold text-slate-900 text-base border-b pb-3">How Nooryak Works Section</h3>

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

        <!-- TAB 7: PRICING & GROWTH -->
        <div x-show="activeTab === 'pricing'" class="card-white p-6 rounded-2xl space-y-6">
            <h3 class="font-bold text-slate-900 text-base border-b pb-3">Pricing & Growth Path</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Pricing Tag</label>
                    <input type="text" name="lp_pricing_tag" value="{{ $data['lp_pricing_tag'] }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Pricing Section Heading</label>
                    <input type="text" name="lp_pricing_title" value="{{ $data['lp_pricing_title'] }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs">
                </div>
            </div>
        </div>

        <!-- TAB 8: TESTIMONIALS & FAQS -->
        <div x-show="activeTab === 'faqs'" class="card-white p-6 rounded-2xl space-y-6">
            <h3 class="font-bold text-slate-900 text-base border-b pb-3">Testimonials & FAQ Accordions</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">FAQ Tag</label>
                    <input type="text" name="lp_faqs_tag" value="{{ $data['lp_faqs_tag'] }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">FAQ Heading</label>
                    <input type="text" name="lp_faqs_title" value="{{ $data['lp_faqs_title'] }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs">
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
                <span>Ready to update Nooryak.in landing page</span>
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
