<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $titles = [
            'privacy' => 'Privacy Policy',
            'terms' => 'Terms & Conditions',
            'shipping' => 'Shipping & Delivery Policy',
            'refund' => 'Cancellation & Refund Policy',
            'about' => 'About Us',
            'contact' => 'Contact Us',
        ];
        $data = $data ?? [];
        $agencyName = $agency->name ?? 'Youverse';
        $pageTitle = $titles[$type] ?? 'Legal Policy';
        $aboutImg = !empty($agency->about_image) ? asset(ltrim($agency->about_image, '/')) : asset('/assets/images/about_right.png');
        $dbProds = $data['db_products'] ?? collect();
    @endphp
    <title>{{ $pageTitle }} — {{ $data['lp_meta_title'] ?? $agencyName }}</title>
    <meta name="description" content="{{ $pageTitle }} for {{ $agencyName }}. Powering the growth of SaaS businesses.">

    @if(!empty($agency->favicon))
        <link rel="icon" type="image/png" href="{{ asset(ltrim($agency->favicon, '/')) }}">
    @elseif(!empty($agency->logo))
        <link rel="icon" type="image/png" href="{{ asset(ltrim($agency->logo, '/')) }}">
    @else
        <link rel="icon" href="/assets/images/common/Logo-blue.png" type="image/png">
    @endif

    <!-- Fonts matching Landing Page -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Onest:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            400: '#fb923c',
                            500: '#ff7a18',
                            600: '#ff3d00',
                            700: '#c2410c',
                            800: '#9a3412',
                            900: '#7c2d12',
                        },
                        accent: {
                            orange: '#ff3d00',
                            warm: '#ff7a18',
                        },
                        dark: '#0f172a'
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Space Grotesk', 'sans-serif'],
                        onest: ['Onest', 'sans-serif'],
                        poppins: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            overflow-x: hidden;
        }
        .font-space { font-family: 'Space Grotesk', sans-serif; }
        .font-poppins { font-family: 'Poppins', sans-serif; }
        .font-onest { font-family: 'Onest', sans-serif; }
        
        .gradient-text {
            background: linear-gradient(135deg, rgb(255, 122, 24), rgb(255, 61, 0));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .btn-gradient {
            background: linear-gradient(135deg, rgb(255, 122, 24), rgb(255, 61, 0));
            transition: all 0.3s ease;
        }
        .btn-gradient:hover {
            box-shadow: 0 10px 25px -5px rgba(255, 61, 0, 0.4);
            transform: translateY(-2px);
        }

        .badge-pill {
            background: #fff3eb;
            color: #ff3d00;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 4px 14px;
            border-radius: 9999px;
            display: inline-block;
            border: 1px solid #ffe3d1;
        }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col justify-between">

    {{-- ══ EXACT SAME ANNOUNCEMENT BAR AS LANDING PAGE ══ --}}
    <div style="background:linear-gradient(90deg,#1e3a8a,#312e81,#4c1d95)" class="text-white text-center py-1.5 px-4 text-[11px] font-semibold">
        🎉 Special Offer: Get Started with <strong>{{ $agency->name ?? $agencyName }}</strong> Today &amp; Automate Your Business!
    </div>

    {{-- ══ EXACT SAME HEADER AS LANDING PAGE (Screenshot 2 Match) ══ --}}
    <header class="site-header" style="background:#fff;border-bottom:1px solid #f1f5f9;position:sticky;top:0;z-index:50">
        <div style="max-width:1200px;margin:0 auto;padding:0 24px;display:flex;align-items:center;justify-content:space-between;height:72px">

            {{-- Logo --}}
            <a href="{{ url('/') }}" style="display:flex;align-items:center;gap:10px;text-decoration:none;flex-shrink:0">
                @if(!empty($agency->logo))
                    <img src="{{ asset(ltrim($agency->logo, '/')) }}" alt="{{ $agency->name ?? $agencyName }}" style="height:50px;width:auto;object-fit:contain">
                @else
                    <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#f97316,#ea580c);display:flex;align-items:center;justify-content:center">
                        <i class="fas fa-layer-group text-white text-base"></i>
                    </div>
                    <span style="font-family:'Outfit',sans-serif;font-size:20px;font-weight:900;color:#0f172a;letter-spacing:-.5px">{{ $agency->name ?? $agencyName }}</span>
                @endif
            </a>

            {{-- Desktop Nav --}}
            <nav class="hidden lg:flex items-center space-x-8">
                <a href="{{ url('/') }}" style="font-size:13px;font-weight:700;color:#475569;text-decoration:none;transition:color .15s" onmouseover="this.style.color='#4f46e5'" onmouseout="this.style.color='#475569'">Home</a>
                <a href="{{ url('/') }}#products" style="font-size:13px;font-weight:700;color:#475569;text-decoration:none;transition:color .15s" onmouseover="this.style.color='#4f46e5'" onmouseout="this.style.color='#475569'">Products</a>
                <a href="{{ url('/') }}#about-section" style="font-size:13px;font-weight:700;color:#475569;text-decoration:none;transition:color .15s" onmouseover="this.style.color='#4f46e5'" onmouseout="this.style.color='#475569'">About Us</a>
                <a href="{{ url('/') }}#how-it-works" style="font-size:13px;font-weight:700;color:#475569;text-decoration:none;transition:color .15s" onmouseover="this.style.color='#4f46e5'" onmouseout="this.style.color='#475569'">How It Works</a>
                <a href="{{ url('/') }}#testimonials" style="font-size:13px;font-weight:700;color:#475569;text-decoration:none;transition:color .15s" onmouseover="this.style.color='#4f46e5'" onmouseout="this.style.color='#475569'">Reviews</a>
                <a href="{{ url('/') }}#faq" style="font-size:13px;font-weight:700;color:#475569;text-decoration:none;transition:color .15s" onmouseover="this.style.color='#4f46e5'" onmouseout="this.style.color='#475569'">FAQ</a>
            </nav>

            {{-- Desktop CTAs --}}
            <div class="hidden sm:flex items-center space-x-3">
                <a href="{{ !empty($agency->cta_url) ? $agency->cta_url : 'https://wa.me/916374913298?text=I%20am%20interested%20on%20that%20business' }}" style="font-size:13px;font-weight:700;color:#475569;text-decoration:none;padding:6px 12px">Login</a>
                <a href="{{ !empty($agency->cta_url) ? $agency->cta_url : 'https://wa.me/916374913298?text=I%20am%20interested%20on%20that%20business' }}" class="text-white text-xs font-extrabold px-6 py-2.5 rounded-full shadow-lg transition-all" style="background:linear-gradient(135deg,#7c3aed,#ea580c);">
                    {{ $agency->cta_text ?? 'Start Free Today' }}
                </a>
            </div>

            {{-- Mobile Menu Button --}}
            <button id="mobileMenuBtn" class="lg:hidden p-2 rounded-xl text-slate-600 hover:text-slate-900 focus:outline-none">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>

        {{-- Mobile Drawer Menu --}}
        <div id="mobileMenu" class="hidden lg:hidden border-t border-slate-200 bg-white px-4 pt-4 pb-6 space-y-3 shadow-lg">
            <a href="{{ url('/') }}" class="block px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-100">Home</a>
            <a href="{{ url('/') }}#products" class="block px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-100">Products</a>
            <a href="{{ url('/') }}#about-section" class="block px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-100">About Us</a>
            <a href="{{ url('/') }}#how-it-works" class="block px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-100">How It Works</a>
            <a href="{{ url('/') }}#testimonials" class="block px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-100">Reviews</a>
            <a href="{{ url('/') }}#faq" class="block px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-100">FAQ</a>
            <div class="pt-4 border-t border-slate-100 space-y-2">
                <a href="{{ !empty($agency->cta_url) ? $agency->cta_url : 'https://wa.me/916374913298?text=I%20am%20interested%20on%20that%20business' }}" class="block w-full text-center px-4 py-2.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-700">Login</a>
                <a href="{{ !empty($agency->cta_url) ? $agency->cta_url : 'https://wa.me/916374913298?text=I%20am%20interested%20on%20that%20business' }}" class="block w-full text-center text-white px-4 py-2.5 rounded-xl text-xs font-bold shadow-md" style="background:linear-gradient(135deg,#7c3aed,#ea580c);">
                    {{ $agency->cta_text ?? 'Start Free Today' }}
                </a>
            </div>
        </div>
    </header>

    <!-- DYNAMIC CENTER CONTENT CONTAINER (Task 4: Dynamic Policy Content Only) -->
    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16 flex-1 w-full">
        <div class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-12 shadow-xl space-y-8">
            
            <!-- Page Header Bar -->
            <div class="border-b border-slate-200 pb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <span class="badge-pill mb-2">
                        Official Policy Document
                    </span>
                    <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 font-space tracking-tight">{{ $pageTitle }}</h1>
                    <p class="text-xs text-slate-500 mt-1.5 font-medium">Effective Date: {{ date('F d, Y') }} — {{ $agencyName }}</p>
                </div>
                <a href="{{ url('/') }}" class="self-start sm:self-auto inline-flex items-center space-x-2 text-xs font-bold text-indigo-600 bg-indigo-50 border border-indigo-200 hover:bg-indigo-100 px-4 py-2.5 rounded-2xl transition-all">
                    <i class="fas fa-arrow-left text-xs"></i>
                    <span>Back to Home</span>
                </a>
            </div>

            <!-- Policy Navigation Tabs -->
            <div class="flex flex-wrap items-center gap-2 pb-2 border-b border-slate-100 text-xs font-bold">
                <a href="{{ route('agency.privacy') }}" class="px-4 py-2 rounded-xl transition-all {{ $type === 'privacy' ? 'bg-indigo-600 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Privacy Policy</a>
                <a href="{{ route('agency.terms') }}" class="px-4 py-2 rounded-xl transition-all {{ $type === 'terms' ? 'bg-indigo-600 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Terms & Conditions</a>
                <a href="{{ route('agency.refund') }}" class="px-4 py-2 rounded-xl transition-all {{ $type === 'refund' ? 'bg-indigo-600 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Refund Policy</a>
                <a href="{{ route('agency.shipping') }}" class="px-4 py-2 rounded-xl transition-all {{ $type === 'shipping' ? 'bg-indigo-600 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Shipping Policy</a>
            </div>

            <!-- Page Specific Dynamic Body Content -->
            <div class="prose prose-slate max-w-none text-xs sm:text-sm leading-relaxed text-slate-700 space-y-6">
                
                @if($type === 'about')
                    <!-- ABOUT US PAGE CONTENT -->
                    <div class="space-y-8">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                            <div class="lg:col-span-7 space-y-4">
                                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-space">
                                    {{ $agency->about_title ?? 'Empower Your Business With White-Label SaaS Solutions.' }}
                                </h2>
                                @if(!empty($agency->about_content))
                                    <p class="text-slate-600 leading-relaxed font-medium">
                                        {{ $agency->about_content }}
                                    </p>
                                @endif
                                <p class="text-slate-600 leading-relaxed font-medium">
                                    {{ $agency->about_mission ?? 'Our mission is to help agencies and business owners launch, operate, and scale profitable SaaS software platforms under their custom brand identity.' }}
                                </p>
                            </div>
                            <div class="lg:col-span-5">
                                <img src="{{ $aboutImg }}" alt="About {{ $agencyName }}" class="w-full h-auto rounded-2xl border border-slate-200 shadow-md object-cover">
                            </div>
                        </div>

                        <!-- 4 Stats Grid -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-4 border-t border-slate-100">
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80 text-center space-y-1">
                                <h4 class="text-2xl font-black text-slate-900 font-space gradient-text">10,000+</h4>
                                <p class="text-[11px] font-bold text-slate-500">Active Partners</p>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80 text-center space-y-1">
                                <h4 class="text-2xl font-black text-slate-900 font-space gradient-text">1M+</h4>
                                <p class="text-[11px] font-bold text-slate-500">End Users Served</p>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80 text-center space-y-1">
                                <h4 class="text-2xl font-black text-slate-900 font-space gradient-text">500K+</h4>
                                <p class="text-[11px] font-bold text-slate-500">Subscriptions Managed</p>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80 text-center space-y-1">
                                <h4 class="text-2xl font-black text-slate-900 font-space gradient-text">99.9%</h4>
                                <p class="text-[11px] font-bold text-slate-500">Uptime Guarantee</p>
                            </div>
                        </div>
                    </div>

                @elseif($type === 'contact')
                    <!-- CONTACT US PAGE CONTENT -->
                    <div class="space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            
                            <!-- Card 1: Email -->
                            <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-6 space-y-3">
                                <div class="w-10 h-10 rounded-xl bg-orange-100 text-[#ff3d00] flex items-center justify-center font-bold">
                                    <i class="fas fa-envelope text-lg"></i>
                                </div>
                                <h4 class="font-extrabold text-slate-900 text-sm font-space">Email Support</h4>
                                <p class="text-xs text-slate-600 leading-relaxed font-medium">{{ $agency->contact_email ?? $data['lp_contact_email'] ?? 'support@nooryak.in' }}</p>
                            </div>

                            <!-- Card 2: Phone -->
                            <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-6 space-y-3">
                                <div class="w-10 h-10 rounded-xl bg-orange-100 text-[#ff3d00] flex items-center justify-center font-bold">
                                    <i class="fas fa-phone text-lg"></i>
                                </div>
                                <h4 class="font-extrabold text-slate-900 text-sm font-space">Phone Number</h4>
                                <p class="text-xs text-slate-600 leading-relaxed font-medium">{{ $agency->contact_phone ?? $data['lp_contact_phone'] ?? '+91 98765 43210' }}</p>
                            </div>

                            <!-- Card 3: Address -->
                            <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-6 space-y-3">
                                <div class="w-10 h-10 rounded-xl bg-orange-100 text-[#ff3d00] flex items-center justify-center font-bold">
                                    <i class="fas fa-location-dot text-lg"></i>
                                </div>
                                <h4 class="font-extrabold text-slate-900 text-sm font-space">Office Address</h4>
                                <p class="text-xs text-slate-600 leading-relaxed font-medium">{{ $agency->contact_address ?? 'India' }}</p>
                            </div>

                        </div>

                        <!-- Interactive Contact Form -->
                        <div class="bg-slate-50 border border-slate-200/80 rounded-3xl p-6 sm:p-8 space-y-6">
                            <h3 class="text-lg font-extrabold text-slate-900 font-space">Send Us a Message</h3>
                            <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Thank you! Your message has been sent to {{ $agencyName }} support team.');" class="space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Your Full Name</label>
                                        <input type="text" required placeholder="e.g. Rahul Sharma" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs focus:outline-none focus:border-orange-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Email Address</label>
                                        <input type="email" required placeholder="name@company.com" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs focus:outline-none focus:border-orange-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Subject</label>
                                    <input type="text" required placeholder="How can we help your business?" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs focus:outline-none focus:border-orange-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Message</label>
                                    <textarea rows="4" required placeholder="Write your query details here..." class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs focus:outline-none focus:border-orange-500"></textarea>
                                </div>
                                <button type="submit" class="btn-gradient text-white text-xs font-extrabold px-8 py-3.5 rounded-2xl shadow-lg shadow-orange-500/25 transition">
                                    Submit Message →
                                </button>
                            </form>
                        </div>
                    </div>

                @elseif($type === 'privacy')
                    {!! !empty($agency->privacy_policy) ? $agency->privacy_policy : ("<h2>Privacy Policy for " . e($agencyName) . "</h2><p>At " . e($agencyName) . ", accessible from " . e($agency->custom_domain ?? request()->getHost()) . ", we prioritize your privacy and protect data collected during service usage.</p><h3>1. Information We Collect</h3><p>We collect essential business information, account registration details, and contact details to process orders, manage subscriptions, and provide customer support.</p><h3>2. Data Protection & Security</h3><p>Your business data is protected with enterprise-grade encryption standards, automated backups, and strict access protocols.</p><h3>3. Contact Information</h3><p>For any privacy inquiries, email us at: <strong>" . e($agency->contact_email ?? $data['lp_contact_email'] ?? 'support@nooryak.in') . "</strong></p>") !!}
                @elseif($type === 'terms')
                    {!! !empty($agency->terms_conditions) ? $agency->terms_conditions : ("<h2>Terms & Conditions for " . e($agencyName) . "</h2><p>Welcome to " . e($agencyName) . "! These terms govern your use of our platform, SaaS reseller products, and subscriptions.</p><h3>1. User Account Responsibility</h3><p>By registering or purchasing a White-Label reseller package on " . e($agencyName) . ", you agree to maintain accurate account details and adhere to acceptable usage rules.</p><h3>2. Intellectual Property & Reseller Rights</h3><p>You receive white-label rebranding rights for customer management according to your chosen plan tier.</p><h3>3. Support & Inquiries</h3><p>Email: <strong>" . e($agency->contact_email ?? $data['lp_contact_email'] ?? 'support@nooryak.in') . "</strong></p>") !!}
                @elseif($type === 'shipping')
                    {!! !empty($agency->shipping_policy) ? $agency->shipping_policy : ("<h2>Shipping & Delivery Policy for " . e($agencyName) . "</h2><p>All SaaS products, white-label portals, and software subscriptions purchased from " . e($agencyName) . " are delivered 100% electronically via instant email confirmation and dashboard portal access.</p><h3>Instant Digital Fulfillment</h3><p>No physical shipping or postal dispatch is involved. Account credentials and setup tools become active immediately upon payment confirmation.</p><h3>Delivery Timeline</h3><p>Software provisioning completes automatically within 5 to 15 minutes of purchase.</p><h3>Contact Support</h3><p>Email: <strong>" . e($agency->contact_email ?? $data['lp_contact_email'] ?? 'support@nooryak.in') . "</strong></p>") !!}
                @elseif($type === 'refund')
                    {!! !empty($agency->refund_policy) ? $agency->refund_policy : ("<h2>Cancellation & Refund Policy for " . e($agencyName) . "</h2><p>We offer a hassle-free cancellation and 7-day money-back guarantee for subscription packages.</p><h3>Refund Processing</h3><p>Upon approval of your refund request, funds are automatically remitted to your original payment method within 5 to 7 business days.</p><h3>How to Submit a Request</h3><p>Please contact our support desk at <strong>" . e($agency->contact_email ?? $data['lp_contact_email'] ?? 'support@nooryak.in') . "</strong> with your order invoice number and account details.</p>") !!}

                @endif

            </div>

        </div>
    </main>

    {{-- ══ EXACT SAME DARK NAVY FOOTER AS LANDING PAGE (Screenshot 2 Match) ══ --}}
    @php
        $footerProds = ($agency && method_exists($agency, 'getEnabledProductsAttribute')) ? $agency->enabled_products : collect();
        if ($footerProds->isEmpty()) {
            $footerProds = collect([
                (object)['name' => 'Ecombuilder', 'slug' => 'launchshop'],
                (object)['name' => 'Website Builder', 'slug' => 'websitebuilder'],
                (object)['name' => 'WhatsApp Bulk', 'slug' => 'whatsappbulk'],
            ]);
        }
    @endphp
    <footer style="background:#0b132b;padding:60px 0 28px;border-top:1px solid #1e293b;color:#94a3b8">
        <div style="max-width:1200px;margin:0 auto;padding:0 24px">
            <div style="display:grid;grid-template-columns:2fr 1fr 1fr 1.5fr;gap:40px;padding-bottom:48px;border-bottom:1px solid #1e293b" class="footer-grid">

                {{-- Col 1: Brand & Tagline & Socials --}}
                <div style="display:flex;flex-direction:column;gap:16px">
                    <div style="display:flex;align-items:center;gap:10px">
                        @if(!empty($agency->logo))
                            <img src="{{ asset(ltrim($agency->logo, '/')) }}" alt="{{ $agency->name ?? $agencyName }}" style="height:50px;width:auto">
                        @else
                            <span style="font-family:'Outfit',sans-serif;font-size:20px;font-weight:900;color:#e2e8f0">{{ $agency->name ?? $agencyName }}</span>
                        @endif
                    </div>
                    <p style="font-size:12px;color:#64748b;line-height:1.7;max-width:240px">
                        {{ $agency->footer_content ?? 'Powering the growth of Indian local businesses with smart digital solutions.' }}
                    </p>
                    <div style="display:flex;gap:8px;margin-top:4px">
                        @if(!empty($agency->facebook_url))<a href="{{ $agency->facebook_url }}" target="_blank" style="width:34px;height:34px;border-radius:50%;background:#1e293b;display:flex;align-items:center;justify-content:center;color:#94a3b8;text-decoration:none;"><i class="fab fa-facebook-f text-xs"></i></a>@endif
                        @if(!empty($agency->instagram_url))<a href="{{ $agency->instagram_url }}" target="_blank" style="width:34px;height:34px;border-radius:50%;background:#1e293b;display:flex;align-items:center;justify-content:center;color:#94a3b8;text-decoration:none;"><i class="fab fa-instagram text-xs"></i></a>@endif
                        @if(!empty($agency->youtube_url))<a href="{{ $agency->youtube_url }}" target="_blank" style="width:34px;height:34px;border-radius:50%;background:#1e293b;display:flex;align-items:center;justify-content:center;color:#94a3b8;text-decoration:none;"><i class="fab fa-youtube text-xs"></i></a>@endif
                        @if(!empty($agency->linkedin_url))<a href="{{ $agency->linkedin_url }}" target="_blank" style="width:34px;height:34px;border-radius:50%;background:#1e293b;display:flex;align-items:center;justify-content:center;color:#94a3b8;text-decoration:none;"><i class="fab fa-linkedin-in text-xs"></i></a>@endif
                        @if(!empty($agency->twitter_url))<a href="{{ $agency->twitter_url }}" target="_blank" style="width:34px;height:34px;border-radius:50%;background:#1e293b;display:flex;align-items:center;justify-content:center;color:#94a3b8;text-decoration:none;"><i class="fab fa-twitter text-xs"></i></a>@endif
                    </div>
                </div>

                {{-- Col 2: Products --}}
                <div style="display:flex;flex-direction:column;gap:14px">
                    <h4 style="font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.1em;color:#e2e8f0">Products</h4>
                    <ul style="list-style:none;display:flex;flex-direction:column;gap:9px;margin:0;padding:0">
                        @foreach($footerProds as $fp)
                            <li>
                                <a href="{{ url('/#products') }}" style="display:flex;align-items:center;gap:6px;font-size:12px;color:#64748b;text-decoration:none;transition:color .15s" onmouseover="this.style.color='#c7d2fe'" onmouseout="this.style.color='#64748b'">
                                    <i class="fas fa-chevron-right text-[9px] text-slate-500"></i>
                                    {{ $fp->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Col 3: Legal Policies --}}
                <div style="display:flex;flex-direction:column;gap:14px">
                    <h4 style="font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.1em;color:#e2e8f0">Legal</h4>
                    <ul style="list-style:none;display:flex;flex-direction:column;gap:9px;margin:0;padding:0">
                        <li><a href="{{ route('agency.about') }}" style="display:flex;align-items:center;gap:6px;font-size:12px;color:#64748b;text-decoration:none;" onmouseover="this.style.color='#c7d2fe'" onmouseout="this.style.color='#64748b'"><i class="fas fa-chevron-right text-[9px] text-slate-500"></i> About Us</a></li>
                        <li><a href="{{ route('agency.contact') }}" style="display:flex;align-items:center;gap:6px;font-size:12px;color:#64748b;text-decoration:none;" onmouseover="this.style.color='#c7d2fe'" onmouseout="this.style.color='#64748b'"><i class="fas fa-chevron-right text-[9px] text-slate-500"></i> Contact Us</a></li>
                        <li><a href="{{ route('agency.privacy') }}" style="display:flex;align-items:center;gap:6px;font-size:12px;color:#64748b;text-decoration:none;" onmouseover="this.style.color='#c7d2fe'" onmouseout="this.style.color='#64748b'"><i class="fas fa-chevron-right text-[9px] text-slate-500"></i> Privacy Policy</a></li>
                        <li><a href="{{ route('agency.terms') }}" style="display:flex;align-items:center;gap:6px;font-size:12px;color:#64748b;text-decoration:none;" onmouseover="this.style.color='#c7d2fe'" onmouseout="this.style.color='#64748b'"><i class="fas fa-chevron-right text-[9px] text-slate-500"></i> Terms &amp; Conditions</a></li>
                        <li><a href="{{ route('agency.shipping') }}" style="display:flex;align-items:center;gap:6px;font-size:12px;color:#64748b;text-decoration:none;" onmouseover="this.style.color='#c7d2fe'" onmouseout="this.style.color='#64748b'"><i class="fas fa-chevron-right text-[9px] text-slate-500"></i> Shipping Policy</a></li>
                        <li><a href="{{ route('agency.refund') }}" style="display:flex;align-items:center;gap:6px;font-size:12px;color:#64748b;text-decoration:none;" onmouseover="this.style.color='#c7d2fe'" onmouseout="this.style.color='#64748b'"><i class="fas fa-chevron-right text-[9px] text-slate-500"></i> Refund Policy</a></li>
                    </ul>
                </div>

                {{-- Col 4: Newsletter --}}
                <div style="display:flex;flex-direction:column;gap:14px">
                    <h4 style="font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.1em;color:#e2e8f0">Subscribe to our newsletter</h4>
                    <p style="font-size:12px;color:#64748b;margin:0">Get updates, tips and offers.</p>
                    <form onsubmit="event.preventDefault(); alert('Thank you for subscribing!');" style="display:flex;align-items:center;background:#1e293b;border-radius:14px;padding:4px 6px;border:1px solid #334155;">
                        <input type="email" placeholder="Enter your email" required style="background:transparent;border:none;outline:none;color:#fff;font-size:12px;padding:8px 12px;flex:1;">
                        <button type="submit" style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#7c3aed,#ea580c);border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#fff;">
                            <i class="fas fa-paper-plane text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Bottom bar --}}
            <div style="padding-top:24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;font-size:11px;color:#475569">
                <p style="margin:0">© {{ date('Y') }} {{ $agency->name ?? $agencyName }}. All rights reserved.</p>
                <p style="margin:0;display:flex;align-items:center;gap:4px">Made with <span style="color:#f43f5e">❤️</span> in India 🇮🇳</p>
            </div>
        </div>
    </footer>

    <script>
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }
    </script>
</body>
</html>
