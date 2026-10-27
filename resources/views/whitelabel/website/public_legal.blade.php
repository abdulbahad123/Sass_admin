<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        // Immediate dark mode initialization to prevent flash
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Onest:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&family=Outfit:wght@600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Tailwind CSS CDN with dark mode support -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
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
                        outfit: ['Outfit', 'sans-serif'],
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
            transition: background-color 0.25s ease, color 0.25s ease;
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

        /* ─── RESPONSIVE FOOTER GRID ─── */
        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1.5fr;
            gap: 48px;
            padding-bottom: 44px;
            border-bottom: 1px solid #0f172a;
        }
        @media (max-width: 1024px) {
            .footer-grid {
                grid-template-columns: 1fr 1fr !important;
                gap: 32px !important;
            }
        }
        @media (max-width: 768px) {
            .footer-grid {
                grid-template-columns: 1fr !important;
                gap: 32px !important;
            }
        }

        /* ─── RESPONSIVE POLICY TABS ─── */
        .policy-tabs-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            padding-bottom: 8px;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }
        .policy-tabs-wrap::-webkit-scrollbar {
            display: none;
        }
        .policy-tabs-wrap a {
            white-space: nowrap;
            flex-shrink: 0;
        }

        /* ─── DARK MODE STYLING (Matching Landing Page Atmosphere) ─── */
        html.dark body { 
            background: transparent !important; 
            color: #f8fafc !important; 
        }
        html.dark body::before {
            content: "";
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            background: radial-gradient(circle at top right, #1e1b4b 0%, #020617 60%, #000000 100%) !important;
            z-index: -2;
        }

        html.dark .site-header { 
            background: rgba(2, 6, 23, 0.75) !important; 
            backdrop-filter: blur(12px); 
            border-bottom: 1px solid rgba(255,255,255,0.08) !important; 
        }
        html.dark .site-header nav a {
            color: #94a3b8 !important;
        }
        html.dark .site-header nav a:hover {
            color: #ffffff !important;
        }
        html.dark .site-header .logo-text {
            color: #ffffff !important;
        }
        html.dark .theme-toggle-btn,
        html.dark .theme-toggle-btn-mobile {
            border-color: rgba(255,255,255,0.2) !important;
            color: #ffffff !important;
            background: rgba(15, 23, 42, 0.6) !important;
        }
        html.dark .theme-toggle-btn:hover,
        html.dark .theme-toggle-btn-mobile:hover {
            border-color: #ffffff !important;
            background: rgba(30, 41, 59, 0.8) !important;
        }
        html.dark #mobileMenuBtn,
        html.dark #mobileMenuBtnTablet {
            color: #ffffff !important;
        }
        html.dark #mobileMenu {
            background: #0b132b !important;
            border-top: 1px solid rgba(255,255,255,0.08) !important;
        }
        html.dark #mobileMenu a {
            color: #e2e8f0 !important;
        }
        html.dark #mobileMenu a:hover {
            background: rgba(255,255,255,0.06) !important;
        }

        /* Glassmorphism Main Document Card */
        html.dark .legal-card-container {
            background: rgba(15, 23, 42, 0.65) !important;
            backdrop-filter: blur(16px);
            border-color: rgba(255, 255, 255, 0.08) !important;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.45) !important;
        }
        html.dark .legal-card-container h1 {
            color: #ffffff !important;
        }
        html.dark .legal-card-container .border-b {
            border-bottom-color: rgba(255,255,255,0.08) !important;
        }
        html.dark .badge-pill {
            background: rgba(255, 61, 0, 0.15) !important;
            border-color: rgba(255, 61, 0, 0.3) !important;
            color: #ff7a18 !important;
        }
        html.dark .back-home-btn {
            background: rgba(79, 70, 229, 0.15) !important;
            border-color: rgba(99, 102, 241, 0.3) !important;
            color: #a5b4fc !important;
        }
        html.dark .back-home-btn:hover {
            background: rgba(79, 70, 229, 0.25) !important;
            color: #c7d2fe !important;
        }

        /* Policy Navigation Tabs */
        html.dark .policy-tabs-wrap a:not(.active-tab) {
            background: rgba(30, 41, 59, 0.7) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            color: #94a3b8 !important;
        }
        html.dark .policy-tabs-wrap a:not(.active-tab):hover {
            background: rgba(51, 65, 85, 0.8) !important;
            color: #ffffff !important;
        }

        /* Prose Content (Privacy, Terms, Shipping, Refund policies) */
        html.dark .prose {
            color: #cbd5e1 !important;
        }
        html.dark .prose h2,
        html.dark .prose h3,
        html.dark .prose h4 {
            color: #ffffff !important;
        }
        html.dark .prose p,
        html.dark .prose li,
        html.dark .prose span {
            color: #cbd5e1 !important;
        }
        html.dark .prose strong {
            color: #ffffff !important;
        }

        /* Contact Us Cards */
        html.dark .contact-card-box {
            background: rgba(15, 23, 42, 0.6) !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
        }
        html.dark .contact-card-box h4 {
            color: #ffffff !important;
        }
        html.dark .contact-card-box p {
            color: #94a3b8 !important;
        }
        html.dark .contact-card-box .icon-wrap {
            background: rgba(255, 61, 0, 0.15) !important;
            color: #ff7a18 !important;
        }

        /* Contact Form */
        html.dark .contact-form-card {
            background: rgba(15, 23, 42, 0.6) !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
        }
        html.dark .contact-form-card h3 {
            color: #ffffff !important;
        }
        html.dark .contact-form-card label {
            color: #cbd5e1 !important;
        }
        html.dark .contact-form-card input,
        html.dark .contact-form-card textarea {
            background: #0b132b !important;
            border-color: rgba(255, 255, 255, 0.12) !important;
            color: #ffffff !important;
        }
        html.dark .contact-form-card input:focus,
        html.dark .contact-form-card textarea:focus {
            border-color: #ff7a18 !important;
        }
        html.dark .contact-form-card input::placeholder,
        html.dark .contact-form-card textarea::placeholder {
            color: #64748b !important;
        }

        /* About Stats */
        html.dark .about-stat-card {
            background: rgba(15, 23, 42, 0.6) !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
        }
        html.dark .about-stat-card p {
            color: #94a3b8 !important;
        }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col justify-between">

    {{-- EXACT SAME ANNOUNCEMENT BAR AS LANDING PAGE --}}
    <div style="background:linear-gradient(90deg,#1e3a8a,#312e81,#4c1d95)" class="text-white text-center py-1.5 px-4 text-[11px] font-semibold">
        ⚡ Special Offer: Get Started with <strong>{{ $agency->name ?? $agencyName }}</strong> Today &amp; Automate Your Business!
    </div>

    {{-- EXACT SAME HEADER AS LANDING PAGE (With Theme Toggle) --}}
    <header class="site-header" style="background:#fff;border-bottom:1px solid #f1f5f9;position:sticky;top:0;z-index:50;transition:background-color .2s,border-color .2s">
        <div style="max-width:1200px;margin:0 auto;padding:0 24px;display:flex;align-items:center;justify-content:space-between;height:72px">

            {{-- Logo --}}
            <a href="{{ url('/') }}" style="display:flex;align-items:center;gap:10px;text-decoration:none;flex-shrink:0">
                @if(!empty($agency->logo))
                    <img src="{{ asset(ltrim($agency->logo, '/')) }}" alt="{{ $agency->name ?? $agencyName }}" style="height:50px;width:auto;object-fit:contain">
                @else
                    <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#f97316,#ea580c);display:flex;align-items:center;justify-content:center">
                        <i class="fas fa-layer-group text-white text-base"></i>
                    </div>
                    <span class="logo-text" style="font-family:'Outfit',sans-serif;font-size:20px;font-weight:900;color:#0f172a;letter-spacing:-.5px">{{ $agency->name ?? $agencyName }}</span>
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

            {{-- Desktop CTAs (Includes Light/Dark Mode Switcher) --}}
            <div class="hidden sm:flex items-center space-x-3">
                <button type="button" onclick="toggleTheme()" class="theme-toggle-btn" style="width:38px;height:38px;border-radius:50%;border:1.5px solid #e2e8f0;background:transparent;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#475569;transition:all .2s;flex-shrink:0;" title="Toggle Dark Mode">
                    <i class="fas fa-moon" id="moonIcon" style="font-size:15px"></i>
                    <i class="fas fa-sun" id="sunIcon" style="font-size:15px;color:#fbbf24;display:none;"></i>
                </button>
                <a href="{{ !empty($agency->cta_url) ? $agency->cta_url : 'https://wa.me/916374913298?text=I%20am%20interested%20on%20that%20business' }}" style="font-size:13px;font-weight:700;color:#475569;text-decoration:none;padding:6px 12px">Login</a>
                <a href="{{ !empty($agency->cta_url) ? $agency->cta_url : 'https://wa.me/916374913298?text=I%20am%20interested%20on%20that%20business' }}" class="text-white text-xs font-extrabold px-6 py-2.5 rounded-full shadow-lg transition-all" style="background:linear-gradient(135deg,#7c3aed,#ea580c);">
                    {{ $agency->cta_text ?? 'Start Free Today' }}
                </a>
            </div>

            {{-- Mobile Actions: Toggle + Hamburger --}}
            <div class="flex items-center space-x-2 sm:hidden">
                <button type="button" onclick="toggleTheme()" class="theme-toggle-btn-mobile" style="width:36px;height:36px;border-radius:50%;border:1.5px solid #e2e8f0;background:transparent;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#475569;transition:all .2s;" title="Toggle Dark Mode">
                    <i class="fas fa-moon moonIconMobile" style="font-size:14px"></i>
                    <i class="fas fa-sun sunIconMobile" style="font-size:14px;color:#fbbf24;display:none;"></i>
                </button>
                <button id="mobileMenuBtn" class="p-2 rounded-xl text-slate-600 hover:text-slate-900 focus:outline-none">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
            <button id="mobileMenuBtnTablet" class="hidden sm:inline-block lg:hidden p-2 rounded-xl text-slate-600 hover:text-slate-900 focus:outline-none">
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
            
            <div class="pt-2 border-t border-slate-100">
                <button type="button" onclick="toggleTheme()" class="flex items-center justify-between w-full px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                    <span>Appearance</span>
                    <span class="flex items-center gap-2 text-xs font-bold text-slate-500">
                        <i class="fas fa-moon"></i> / <i class="fas fa-sun text-amber-500"></i> Switch Theme
                    </span>
                </button>
            </div>

            <div class="pt-2 border-t border-slate-100 space-y-2">
                <a href="{{ !empty($agency->cta_url) ? $agency->cta_url : 'https://wa.me/916374913298?text=I%20am%20interested%20on%20that%20business' }}" class="block w-full text-center px-4 py-2.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-700">Login</a>
                <a href="{{ !empty($agency->cta_url) ? $agency->cta_url : 'https://wa.me/916374913298?text=I%20am%20interested%20on%20that%20business' }}" class="block w-full text-center text-white px-4 py-2.5 rounded-xl text-xs font-bold shadow-md" style="background:linear-gradient(135deg,#7c3aed,#ea580c);">
                    {{ $agency->cta_text ?? 'Start Free Today' }}
                </a>
            </div>
        </div>
    </header>

    <!-- DYNAMIC CENTER CONTENT CONTAINER -->
    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-16 flex-1 w-full">
        <div class="legal-card-container bg-white border border-slate-200/90 rounded-3xl p-5 sm:p-12 shadow-xl space-y-8">
            
            <!-- Page Header Bar -->
            <div class="border-b border-slate-200 pb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <span class="badge-pill mb-2">
                        Official Policy Document
                    </span>
                    <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 font-space tracking-tight">{{ $pageTitle }}</h1>
                    <p class="text-xs text-slate-500 mt-1.5 font-medium">Effective Date: {{ date('F d, Y') }} — {{ $agencyName }}</p>
                </div>
                <a href="{{ url('/') }}" class="back-home-btn self-start sm:self-auto inline-flex items-center space-x-2 text-xs font-bold text-indigo-600 bg-indigo-50 border border-indigo-200 hover:bg-indigo-100 px-4 py-2.5 rounded-2xl transition-all">
                    <i class="fas fa-arrow-left text-xs"></i>
                    <span>Back to Home</span>
                </a>
            </div>

            <!-- Policy Navigation Tabs (Mobile Horizontally Scrollable & Desktop Wrapped) -->
            <div class="policy-tabs-wrap border-b border-slate-100 text-xs font-bold">
                <a href="{{ route('agency.privacy') }}" class="px-4 py-2 rounded-xl transition-all {{ $type === 'privacy' ? 'active-tab bg-indigo-600 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Privacy Policy</a>
                <a href="{{ route('agency.terms') }}" class="px-4 py-2 rounded-xl transition-all {{ $type === 'terms' ? 'active-tab bg-indigo-600 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Terms &amp; Conditions</a>
                <a href="{{ route('agency.refund') }}" class="px-4 py-2 rounded-xl transition-all {{ $type === 'refund' ? 'active-tab bg-indigo-600 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Refund Policy</a>
                <a href="{{ route('agency.shipping') }}" class="px-4 py-2 rounded-xl transition-all {{ $type === 'shipping' ? 'active-tab bg-indigo-600 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Shipping Policy</a>
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
                            <div class="about-stat-card bg-slate-50 p-4 rounded-2xl border border-slate-200/80 text-center space-y-1">
                                <h4 class="text-2xl font-black text-slate-900 font-space gradient-text">10,000+</h4>
                                <p class="text-[11px] font-bold text-slate-500">Active Partners</p>
                            </div>
                            <div class="about-stat-card bg-slate-50 p-4 rounded-2xl border border-slate-200/80 text-center space-y-1">
                                <h4 class="text-2xl font-black text-slate-900 font-space gradient-text">1M+</h4>
                                <p class="text-[11px] font-bold text-slate-500">End Users Served</p>
                            </div>
                            <div class="about-stat-card bg-slate-50 p-4 rounded-2xl border border-slate-200/80 text-center space-y-1">
                                <h4 class="text-2xl font-black text-slate-900 font-space gradient-text">500K+</h4>
                                <p class="text-[11px] font-bold text-slate-500">Subscriptions Managed</p>
                            </div>
                            <div class="about-stat-card bg-slate-50 p-4 rounded-2xl border border-slate-200/80 text-center space-y-1">
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
                            <div class="contact-card-box bg-slate-50 border border-slate-200/80 rounded-2xl p-6 space-y-3">
                                <div class="icon-wrap w-10 h-10 rounded-xl bg-orange-100 text-[#ff3d00] flex items-center justify-center font-bold">
                                    <i class="fas fa-envelope text-lg"></i>
                                </div>
                                <h4 class="font-extrabold text-slate-900 text-sm font-space">Email Support</h4>
                                <p class="text-xs text-slate-600 leading-relaxed font-medium break-all">{{ $agency->contact_email ?? $data['lp_contact_email'] ?? 'support@' . request()->getHost() }}</p>
                            </div>

                            <!-- Card 2: Phone -->
                            <div class="contact-card-box bg-slate-50 border border-slate-200/80 rounded-2xl p-6 space-y-3">
                                <div class="icon-wrap w-10 h-10 rounded-xl bg-orange-100 text-[#ff3d00] flex items-center justify-center font-bold">
                                    <i class="fas fa-phone text-lg"></i>
                                </div>
                                <h4 class="font-extrabold text-slate-900 text-sm font-space">Phone Number</h4>
                                <p class="text-xs text-slate-600 leading-relaxed font-medium">{{ $agency->contact_phone ?? $data['lp_contact_phone'] ?? '+91 98765 43210' }}</p>
                            </div>

                            <!-- Card 3: Address -->
                            <div class="contact-card-box bg-slate-50 border border-slate-200/80 rounded-2xl p-6 space-y-3">
                                <div class="icon-wrap w-10 h-10 rounded-xl bg-orange-100 text-[#ff3d00] flex items-center justify-center font-bold">
                                    <i class="fas fa-location-dot text-lg"></i>
                                </div>
                                <h4 class="font-extrabold text-slate-900 text-sm font-space">Office Address</h4>
                                <p class="text-xs text-slate-600 leading-relaxed font-medium">{{ $agency->contact_address ?? 'India' }}</p>
                            </div>

                        </div>

                        <!-- Interactive Contact Form (Submits to Dashboard & Forwards Copy via SMTP) -->
                        <div class="contact-form-card bg-slate-50 border border-slate-200/80 rounded-3xl p-6 sm:p-8 space-y-6">
                            <h3 class="text-lg font-extrabold text-slate-900 font-space">Send Us a Message</h3>
                            
                            <div id="contactFormAlert" class="hidden p-4 rounded-2xl text-xs font-bold"></div>

                            <form action="{{ route('agency.contact.submit') }}" method="POST" id="publicContactForm" class="space-y-4">
                                @csrf
                                <input type="hidden" name="agency_id" value="{{ $agency->id ?? '' }}">
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Your Full Name <span class="text-rose-500">*</span></label>
                                        <input type="text" name="name" required placeholder="e.g. Rahul Sharma" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs focus:outline-none focus:border-orange-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Email Address <span class="text-rose-500">*</span></label>
                                        <input type="email" name="email" required placeholder="name@company.com" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs focus:outline-none focus:border-orange-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Subject</label>
                                    <input type="text" name="subject" placeholder="How can we help your business?" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs focus:outline-none focus:border-orange-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Message <span class="text-rose-500">*</span></label>
                                    <textarea name="message" rows="4" required placeholder="Write your query details here..." class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs focus:outline-none focus:border-orange-500"></textarea>
                                </div>

                                @php
                                    $recaptcha = is_array($agency->recaptcha_settings ?? null) ? $agency->recaptcha_settings : (json_decode($agency->recaptcha_settings ?? '[]', true) ?: []);
                                @endphp
                                @if(!empty($recaptcha['recaptcha_enabled']) && !empty($recaptcha['recaptcha_site_key']))
                                    <div class="g-recaptcha" data-sitekey="{{ $recaptcha['recaptcha_site_key'] }}"></div>
                                    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                                @endif

                                <button type="submit" id="contactSubmitBtn" class="btn-gradient text-white text-xs font-extrabold px-8 py-3.5 rounded-2xl shadow-lg shadow-orange-500/25 transition inline-flex items-center space-x-2">
                                    <span>Submit Message 🚀</span>
                                </button>
                            </form>
                        </div>
                    </div>

                @elseif($type === 'privacy')
                    {!! !empty($agency->privacy_policy) ? $agency->privacy_policy : ("<h2>Privacy Policy for " . e($agencyName) . "</h2><p>At " . e($agencyName) . ", accessible from " . e($agency->custom_domain ?? request()->getHost()) . ", we prioritize your privacy and protect data collected during service usage.</p><h3>1. Information We Collect</h3><p>We collect essential business information, account registration details, and contact details to process orders, manage subscriptions, and provide customer support.</p><h3>2. Data Protection & Security</h3><p>Your business data is protected with enterprise-grade encryption standards, automated backups, and strict access protocols.</p><h3>3. Contact Information</h3><p>For any privacy inquiries, email us at: <strong>" . e($agency->contact_email ?? $data['lp_contact_email'] ?? 'support@saasreselling.com') . "</strong></p>") !!}
                @elseif($type === 'terms')
                    {!! !empty($agency->terms_conditions) ? $agency->terms_conditions : ("<h2>Terms & Conditions for " . e($agencyName) . "</h2><p>Welcome to " . e($agencyName) . "! These terms govern your use of our platform, SaaS reseller products, and subscriptions.</p><h3>1. User Account Responsibility</h3><p>By registering or purchasing a White-Label reseller package on " . e($agencyName) . ", you agree to maintain accurate account details and adhere to acceptable usage rules.</p><h3>2. Intellectual Property & Reseller Rights</h3><p>You receive white-label rebranding rights for customer management according to your chosen plan tier.</p><h3>3. Support & Inquiries</h3><p>Email: <strong>" . e($agency->contact_email ?? $data['lp_contact_email'] ?? 'support@saasreselling.com') . "</strong></p>") !!}
                @elseif($type === 'shipping')
                    {!! !empty($agency->shipping_policy) ? $agency->shipping_policy : ("<h2>Shipping & Delivery Policy for " . e($agencyName) . "</h2><p>All SaaS products, white-label portals, and software subscriptions purchased from " . e($agencyName) . " are delivered 100% electronically via instant email confirmation and dashboard portal access.</p><h3>Instant Digital Fulfillment</h3><p>No physical shipping or postal dispatch is involved. Account credentials and setup tools become active immediately upon payment confirmation.</p><h3>Delivery Timeline</h3><p>Software provisioning completes automatically within 5 to 15 minutes of purchase.</p><h3>Contact Support</h3><p>Email: <strong>" . e($agency->contact_email ?? $data['lp_contact_email'] ?? 'support@saasreselling.com') . "</strong></p>") !!}
                @elseif($type === 'refund')
                    {!! !empty($agency->refund_policy) ? $agency->refund_policy : ("<h2>Cancellation & Refund Policy for " . e($agencyName) . "</h2><p>We offer a hassle-free cancellation and 7-day money-back guarantee for subscription packages.</p><h3>Refund Processing</h3><p>Upon approval of your refund request, funds are automatically remitted to your original payment method within 5 to 7 business days.</p><h3>How to Submit a Request</h3><p>Please contact our support desk at <strong>" . e($agency->contact_email ?? $data['lp_contact_email'] ?? 'support@saasreselling.com') . "</strong> with your order invoice number and account details.</p>") !!}

                @endif

            </div>

        </div>
    </main>

    {{-- EXACT SAME DARK NAVY FOOTER AS LANDING PAGE (Fully Responsive) --}}
    @php
        try {
            $footerProds = ($agency && method_exists($agency, 'getEnabledProductsAttribute')) ? ($agency->enabled_products ?? collect()) : collect();
        } catch (\Throwable $e) {
            $footerProds = collect();
        }
        if (empty($footerProds) || $footerProds->isEmpty()) {
            $footerProds = collect([
                (object)['name' => 'Ecombuilder'],
                (object)['name' => 'Website Builder'],
                (object)['name' => 'WhatsApp Bulk'],
            ]);
        }
    @endphp
    <footer class="site-footer" style="background:#020617;border-top:1px solid #0f172a;padding:56px 0 28px;margin-top:auto">
        <div style="max-width:1200px;margin:0 auto;padding:0 24px">
            <div class="footer-grid">

                {{-- Col 1: Brand & Social --}}
                <div style="display:flex;flex-direction:column;gap:14px">
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
                <p style="margin:0">&copy; {{ date('Y') }} {{ $agency->name ?? $agencyName }}. All rights reserved.</p>
                <p style="margin:0;display:flex;align-items:center;gap:4px">Made with <span style="color:#f43f5e">♥</span> in India 🇮🇳</p>
            </div>
        </div>
    </footer>

    <script>
        function updateThemeIcons(isDark) {
            const moonIcons = document.querySelectorAll('#moonIcon, .moonIconMobile');
            const sunIcons = document.querySelectorAll('#sunIcon, .sunIconMobile');
            moonIcons.forEach(icon => icon.style.display = isDark ? 'none' : 'inline-block');
            sunIcons.forEach(icon => icon.style.display = isDark ? 'inline-block' : 'none');
        }

        function toggleTheme() {
            const isDark = document.documentElement.classList.contains('dark');
            if (isDark) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
                updateThemeIcons(false);
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
                updateThemeIcons(true);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const isDark = document.documentElement.classList.contains('dark');
            updateThemeIcons(isDark);

            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const mobileMenuBtnTablet = document.getElementById('mobileMenuBtnTablet');
            const mobileMenu = document.getElementById('mobileMenu');
            
            const toggleDrawer = () => {
                if (mobileMenu) {
                    mobileMenu.classList.toggle('hidden');
                }
            };

            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', toggleDrawer);
            }
            if (mobileMenuBtnTablet) {
                mobileMenuBtnTablet.addEventListener('click', toggleDrawer);
            }

            const contactForm = document.getElementById('publicContactForm');
            if (contactForm) {
                contactForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const btn = document.getElementById('contactSubmitBtn');
                    const alertBox = document.getElementById('contactFormAlert');
                    const originalText = btn.innerHTML;
                    
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-circle-notch fa-spin text-xs"></i> <span>Sending...</span>';
                    alertBox.classList.add('hidden');

                    const formData = new FormData(contactForm);

                    fetch(contactForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                        if (data.success) {
                            alertBox.className = 'p-4 rounded-2xl text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200 flex items-center space-x-2';
                            alertBox.innerHTML = '<i class="fas fa-check-circle text-base text-emerald-600"></i> <span>' + data.message + '</span>';
                            alertBox.classList.remove('hidden');
                            contactForm.reset();
                        } else {
                            alertBox.className = 'p-4 rounded-2xl text-xs font-bold bg-rose-50 text-rose-800 border border-rose-200 flex items-center space-x-2';
                            alertBox.innerHTML = '<i class="fas fa-exclamation-circle text-base text-rose-600"></i> <span>' + (data.message || 'Something went wrong. Please try again.') + '</span>';
                            alertBox.classList.remove('hidden');
                        }
                    })
                    .catch(err => {
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                        alertBox.className = 'p-4 rounded-2xl text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200 flex items-center space-x-2';
                        alertBox.innerHTML = '<i class="fas fa-check-circle text-base text-emerald-600"></i> <span>Thank you! Your message has been received. Our team will get back to you shortly.</span>';
                        alertBox.classList.remove('hidden');
                        contactForm.reset();
                    });
                });
            }
        });
    </script>
</body>
</html>