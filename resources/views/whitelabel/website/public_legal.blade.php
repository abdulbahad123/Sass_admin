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

    <!-- TOP ANNOUNCEMENT BAR -->
    <div style="background:linear-gradient(90deg,#1e3a8a,#312e81,#4c1d95)" class="text-white text-center py-1.5 px-4 text-[11px] font-semibold">
        🎉 <strong>{{ $agencyName }}</strong> — Official Legal &amp; Policy Center
    </div>

    <!-- STICKY NAVBAR (Task 4: Exact existing landing page header) -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200/80 transition-all duration-300">
        <div class="max-w-[1340px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <a href="{{ url('/') }}" class="flex items-center space-x-3">
                    @if(!empty($agency->logo))
                        <img src="{{ asset(ltrim($agency->logo, '/')) }}" alt="{{ $agencyName }}" class="h-10 sm:h-12 w-auto object-contain transition-all">
                    @else
                        <div class="flex items-center space-x-2.5">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 flex items-center justify-center text-white font-bold text-xl shadow-md">
                                <i class="fas fa-layer-group text-base"></i>
                            </div>
                            <span class="font-space font-bold text-xl sm:text-2xl tracking-tight text-slate-900">{{ $agencyName }}</span>
                        </div>
                    @endif
                </a>

                <!-- Desktop Menu Navigation -->
                <nav class="hidden lg:flex items-center space-x-8 text-sm font-semibold text-slate-600">
                    <a href="{{ url('/') }}" class="hover:text-blue-600 transition-colors">Home</a>
                    <a href="{{ url('/') }}#products" class="hover:text-blue-600 transition-colors">Products</a>
                    <a href="{{ url('/') }}#about-section" class="hover:text-blue-600 transition-colors">About Us</a>
                    <a href="{{ url('/') }}#testimonials" class="hover:text-blue-600 transition-colors">Reviews</a>
                    <a href="{{ url('/') }}#faq" class="hover:text-blue-600 transition-colors">FAQ</a>
                </nav>

                <!-- Action Buttons -->
                <div class="hidden sm:flex items-center space-x-3">
                    <a href="{{ url('/') }}" class="px-4 py-2 rounded-xl border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-100 transition-all shadow-sm flex items-center gap-1.5">
                        <i class="fas fa-arrow-left text-[11px]"></i>
                        <span>Back to Home</span>
                    </a>
                    <a href="{{ !empty($agency->cta_url) ? $agency->cta_url : 'https://wa.me/916374913298?text=I%20am%20interested%20on%20that%20business' }}" class="px-5 py-2.5 rounded-xl text-white text-xs font-bold shadow-md shadow-indigo-500/20 flex items-center space-x-2" style="background:linear-gradient(135deg,var(--brand-primary,#2563eb),var(--brand-secondary,#4f46e5));">
                        <span>{{ $agency->cta_text ?? 'Start Free Trial' }}</span>
                        <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button id="mobileMenuBtn" class="lg:hidden p-2 rounded-xl text-slate-600 hover:text-slate-900 focus:outline-none">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Drawer Menu -->
        <div id="mobileMenu" class="hidden lg:hidden border-t border-slate-200 bg-white px-4 pt-4 pb-6 space-y-3 shadow-lg">
            <a href="{{ url('/') }}" class="block px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-100">Home</a>
            <a href="{{ url('/') }}#products" class="block px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-100">Products</a>
            <a href="{{ url('/') }}#about-section" class="block px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-100">About Us</a>
            <a href="{{ url('/') }}#testimonials" class="block px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-100">Reviews</a>
            <a href="{{ url('/') }}#faq" class="block px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-100">FAQ</a>
            <div class="pt-4 border-t border-slate-100 space-y-2">
                <a href="{{ url('/') }}" class="block w-full text-center px-4 py-2.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-700">Back to Home</a>
                <a href="{{ !empty($agency->cta_url) ? $agency->cta_url : 'https://wa.me/916374913298?text=I%20am%20interested%20on%20that%20business' }}" class="block w-full text-center text-white px-4 py-2.5 rounded-xl text-xs font-bold shadow-md" style="background:linear-gradient(135deg,var(--brand-primary,#2563eb),var(--brand-secondary,#4f46e5));">
                    {{ $agency->cta_text ?? 'Start Free Trial' }}
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

    <!-- FOOTER -->
    <footer class="bg-white text-slate-600 py-12 border-t border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-12 gap-6 sm:gap-8 pb-10 border-b border-slate-200/80">
                
                <!-- Col 1: Logo & Socials -->
                <div class="col-span-2 lg:col-span-4 space-y-4">
                    <a href="{{ url('/') }}" class="flex items-center space-x-2.5 text-slate-900 font-space font-bold text-xl">
                        @if(!empty($agency->logo))
                            <img src="{{ asset(ltrim($agency->logo, '/')) }}" alt="{{ $agencyName }}" class="h-9 w-auto object-contain">
                        @else
                            <span class="text-xl font-bold tracking-tight text-slate-900">{{ $agencyName }}</span>
                        @endif
                    </a>
                    <p class="text-xs text-slate-500 max-w-xs leading-relaxed">
                        {{ $agency->footer_content ?? ($agencyName . ' — Empowering businesses with smart, scalable digital solutions.') }}
                    </p>
                    <div class="flex items-center space-x-2.5 pt-1">
                        @if(!empty($agency->facebook_url))<a href="{{ $agency->facebook_url }}" target="_blank" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-blue-50 hover:text-blue-600 flex items-center justify-center text-slate-500 text-xs transition-colors"><i class="fab fa-facebook-f"></i></a>@endif
                        @if(!empty($agency->twitter_url))<a href="{{ $agency->twitter_url }}" target="_blank" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-blue-50 hover:text-blue-600 flex items-center justify-center text-slate-500 text-xs transition-colors"><i class="fab fa-twitter"></i></a>@endif
                        @if(!empty($agency->linkedin_url))<a href="{{ $agency->linkedin_url }}" target="_blank" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-blue-50 hover:text-blue-600 flex items-center justify-center text-slate-500 text-xs transition-colors"><i class="fab fa-linkedin-in"></i></a>@endif
                        @if(!empty($agency->youtube_url))<a href="{{ $agency->youtube_url }}" target="_blank" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-blue-50 hover:text-blue-600 flex items-center justify-center text-slate-500 text-xs transition-colors"><i class="fab fa-youtube"></i></a>@endif
                        @if(!empty($agency->instagram_url))<a href="{{ $agency->instagram_url }}" target="_blank" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-blue-50 hover:text-blue-600 flex items-center justify-center text-slate-500 text-xs transition-colors"><i class="fab fa-instagram"></i></a>@endif
                    </div>
                </div>

                <!-- Col 2: Quick Links -->
                <div class="col-span-1 lg:col-span-3 space-y-3">
                    <h5 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Quick Links</h5>
                    <ul class="space-y-2 text-xs font-medium text-slate-600">
                        <li><a href="{{ url('/') }}" class="hover:text-blue-600 transition-colors">Home</a></li>
                        <li><a href="{{ url('/') }}#products" class="hover:text-blue-600 transition-colors">Products</a></li>
                        <li><a href="{{ url('/') }}#about-section" class="hover:text-blue-600 transition-colors">About Us</a></li>
                        <li><a href="{{ url('/') }}#testimonials" class="hover:text-blue-600 transition-colors">Reviews</a></li>
                        <li><a href="{{ url('/') }}#faq" class="hover:text-blue-600 transition-colors">FAQ</a></li>
                    </ul>
                </div>

                <!-- Col 3: Support & Legal Policies -->
                <div class="col-span-1 lg:col-span-3 space-y-3">
                    <h5 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Support &amp; Policies</h5>
                    <ul class="space-y-2 text-xs font-medium text-slate-600">
                        <li><a href="mailto:{{ $agency->contact_email ?? 'support@' . request()->getHost() }}" class="hover:text-blue-600 transition-colors">Contact Support</a></li>
                        <li><a href="{{ route('agency.privacy') }}" class="hover:text-blue-600 transition-colors">Privacy Policy</a></li>
                        <li><a href="{{ route('agency.terms') }}" class="hover:text-blue-600 transition-colors">Terms &amp; Conditions</a></li>
                        <li><a href="{{ route('agency.refund') }}" class="hover:text-blue-600 transition-colors">Refund Policy</a></li>
                        <li><a href="{{ route('agency.shipping') }}" class="hover:text-blue-600 transition-colors">Shipping Policy</a></li>
                    </ul>
                </div>

                <!-- Col 4: Contact / Help -->
                <div class="col-span-2 lg:col-span-2 space-y-3">
                    <h5 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Need Help?</h5>
                    <p class="text-xs text-slate-500">Reach out to our support team anytime.</p>
                    <a href="{{ url('/') }}" class="inline-block px-4 py-2 rounded-xl border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-100 transition-all">
                        Visit Home Page
                    </a>
                </div>

            </div>

            <!-- Bottom Copyright & Policy Links -->
            <div class="pt-6 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 font-medium gap-3">
                <p>© {{ date('Y') }} {{ $agencyName }}. All rights reserved.</p>
                <div class="flex flex-wrap items-center justify-center gap-2 sm:gap-4">
                    <a href="{{ route('agency.privacy') }}" class="hover:text-blue-600 transition-colors">Privacy Policy</a>
                    <span>|</span>
                    <a href="{{ route('agency.terms') }}" class="hover:text-blue-600 transition-colors">Terms &amp; Conditions</a>
                    <span>|</span>
                    <a href="{{ route('agency.refund') }}" class="hover:text-blue-600 transition-colors">Refund Policy</a>
                    <span>|</span>
                    <a href="{{ route('agency.shipping') }}" class="hover:text-blue-600 transition-colors">Shipping Policy</a>
                </div>
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
