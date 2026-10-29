<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        // Immediate dark mode initialization to prevent flash
        if (localStorage.getItem('theme') === 'dark') {
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
    
        /* Light/Dark Logo Display */
        html.dark .site-logo-light { display: none !important; }
        html.dark .site-logo-dark { display: block !important; }
        html:not(.dark) .site-logo-light { display: block !important; }
        html:not(.dark) .site-logo-dark { display: none !important; }

    </style>
</head>
<body class="antialiased min-h-screen flex flex-col justify-between">

    {{-- EXACT SAME HEADER AS LANDING PAGE (With Theme Toggle) --}}
    <header class="site-header" style="background:#fff;border-bottom:1px solid #f1f5f9;position:sticky;top:0;z-index:50;transition:background-color .2s,border-color .2s">
        <div style="max-width:1200px;margin:0 auto;padding:0 24px;display:flex;align-items:center;justify-content:space-between;height:88px">

            {{-- Logo --}}
            <a href="{{ url('/') }}" style="display:flex;align-items:center;gap:12px;text-decoration:none;flex-shrink:0">
                @if(!empty($agency->logo))
                    <img src="{{ asset(ltrim($agency->logo, '/')) }}" alt="{{ $agency->name ?? $agencyName }}" class="site-logo-light" style="height:76px;max-height:80px;width:auto;max-width:260px;object-fit:contain">
                @endif
                @if(!empty($agency->logo_dark))
                    <img src="{{ asset(ltrim($agency->logo_dark, '/')) }}" alt="{{ $agency->name ?? $agencyName }}" class="site-logo-dark" style="height:76px;max-height:80px;width:auto;max-width:260px;object-fit:contain">
                @elseif(!empty($agency->logo))
                    <img src="{{ asset(ltrim($agency->logo, '/')) }}" alt="{{ $agency->name ?? $agencyName }}" class="site-logo-dark" style="height:76px;max-height:80px;width:auto;max-width:260px;object-fit:contain">
                @else
                    <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#f97316,#ea580c);display:flex;align-items:center;justify-content:center">
                        <i class="fas fa-layer-group text-white text-base"></i>
                    </div>
                    <span class="logo-text" style="font-family:'Outfit',sans-serif;font-size:22px;font-weight:900;color:#0f172a;letter-spacing:-.5px">{{ $agency->name ?? $agencyName }}</span>
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
                <button type="button" onclick="toggleTheme()" class="theme-toggle-btn" style="width:40px;height:40px;border-radius:50%;border:1.5px solid #e2e8f0;background:transparent;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#475569;transition:all .2s;flex-shrink:0;" title="Toggle Dark Mode">
                    <i class="fas fa-moon" id="moonIcon" style="font-size:15px"></i>
                    <i class="fas fa-sun" id="sunIcon" style="font-size:15px;color:#fbbf24;display:none;"></i>
                </button>
                <a href="{{ !empty($agency->cta_url) ? $agency->cta_url : 'https://wa.me/916374913298?text=Hello%2C%20I%20would%20like%20to%20enquire%20about%20your%20services' }}" class="text-white text-xs font-extrabold px-6 py-2.5 rounded-full shadow-lg transition-all" style="background:linear-gradient(135deg,#7c3aed,#ea580c);">
                    {{ !empty($agency->cta_text) && $agency->cta_text !== 'Start Free Today' ? $agency->cta_text : 'Enquire' }}
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
                <a href="{{ !empty($agency->cta_url) ? $agency->cta_url : 'https://wa.me/916374913298?text=Hello%2C%20I%20would%20like%20to%20enquire%20about%20your%20services' }}" class="block w-full text-center text-white px-4 py-2.5 rounded-xl text-xs font-bold shadow-md" style="background:linear-gradient(135deg,#7c3aed,#ea580c);">
                    {{ !empty($agency->cta_text) && $agency->cta_text !== 'Start Free Today' ? $agency->cta_text : 'Enquire' }}
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
                    @if($type === 'about')
                        <span class="badge-pill mb-2">
                            {{ $agency->about_section_badge ?? 'About Us' }}
                        </span>
                        <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 font-space tracking-tight">{{ $agency->about_title ?? ($agency->name ?? $agencyName) }}</h1>
                        <p class="text-sm text-slate-500 mt-1.5 font-medium">{{ $agency->about_mission ?? 'Learn more about our vision, team, and story' }}</p>
                    @else
                        <span class="badge-pill mb-2">
                            Official Policy Document
                        </span>
                        <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 font-space tracking-tight">{{ $pageTitle }}</h1>
                        <p class="text-xs text-slate-500 mt-1.5 font-medium">Effective Date: {{ date('F d, Y') }} — {{ $agencyName }}</p>
                    @endif
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
                    <div class="space-y-10">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                            <div class="lg:col-span-7 space-y-5">
                                <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 dark:text-white font-space leading-tight">
                                    {{ $agency->about_title ?? 'Built for entrepreneurs, by entrepreneurs.' }}
                                </h2>
                                <p class="text-slate-700 dark:text-slate-100 text-base sm:text-lg leading-relaxed font-semibold">
                                    {{ $agency->about_mission ?? 'Our mission is to help Indian entrepreneurs automate repetitive operations, boost sales revenue, build customer loyalty, and scale seamlessly without juggling multiple expensive tools.' }}
                                </p>
                                @if(!empty($agency->about_content))
                                    <div class="text-slate-600 dark:text-slate-200 text-sm sm:text-base leading-relaxed space-y-3 font-normal">
                                        {!! nl2br(e($agency->about_content)) !!}
                                    </div>
                                @endif
                            </div>
                            <div class="lg:col-span-5">
                                <img src="{{ $aboutImg }}" alt="About {{ $agencyName }}" class="w-full h-auto rounded-2xl border border-slate-200 dark:border-slate-700 shadow-xl object-cover">
                            </div>
                        </div>

                        <!-- 4 Stats Grid -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-6 border-t border-slate-100 dark:border-slate-800">
                            <div class="about-stat-card bg-slate-50 dark:bg-slate-800/80 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-700 text-center space-y-1.5 shadow-sm">
                                <h4 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white font-space gradient-text">10,000+</h4>
                                <p class="text-xs font-bold text-slate-600 dark:text-slate-300">Active Partners</p>
                            </div>
                            <div class="about-stat-card bg-slate-50 dark:bg-slate-800/80 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-700 text-center space-y-1.5 shadow-sm">
                                <h4 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white font-space gradient-text">1M+</h4>
                                <p class="text-xs font-bold text-slate-600 dark:text-slate-300">End Users Served</p>
                            </div>
                            <div class="about-stat-card bg-slate-50 dark:bg-slate-800/80 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-700 text-center space-y-1.5 shadow-sm">
                                <h4 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white font-space gradient-text">500K+</h4>
                                <p class="text-xs font-bold text-slate-600 dark:text-slate-300">Subscriptions Managed</p>
                            </div>
                            <div class="about-stat-card bg-slate-50 dark:bg-slate-800/80 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-700 text-center space-y-1.5 shadow-sm">
                                <h4 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white font-space gradient-text">99.9%</h4>
                                <p class="text-xs font-bold text-slate-600 dark:text-slate-300">Uptime Guarantee</p>
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
                    @if(!empty($agency->privacy_policy))
                        {!! $agency->privacy_policy !!}
                    @else
                        <h2>Privacy Policy for {{ $agencyName }}</h2>
                        <p>At <strong>{{ $agencyName }}</strong> (accessible at <a href="{{ !empty($agency->custom_domain) ? 'https://' . $agency->custom_domain : 'https://saasreselling.com/' }}" target="_blank">{{ !empty($agency->custom_domain) ? 'https://' . $agency->custom_domain : 'https://saasreselling.com/' }}</a>), we respect your privacy and are committed to protecting the personal information you share with us. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website or use our services.</p>
                        <p>By accessing or using our website and services, you agree to the terms of this Privacy Policy.</p>

                        <h3>1. Information We Collect</h3>
                        <p>We may collect the following types of information:</p>

                        <h4>1.1 Personal Information</h4>
                        <p>When you register, contact us, or make a purchase, we may collect:</p>
                        <ul>
                            <li>Name</li>
                            <li>Email address</li>
                            <li>Phone number</li>
                            <li>Business name</li>
                            <li>Billing and payment-related details (payment verification only; we do not store card details)</li>
                        </ul>

                        <h4>1.2 Non-Personal Information</h4>
                        <p>We may automatically collect:</p>
                        <ul>
                            <li>IP address</li>
                            <li>Browser type and version</li>
                            <li>Device information</li>
                            <li>Pages visited and time spent on the website</li>
                            <li>Referring URLs</li>
                        </ul>
                        <p>This data is used for analytics, security, and website optimization.</p>

                        <h3>2. How We Use Your Information</h3>
                        <p>We use the collected information to:</p>
                        <ul>
                            <li>Provide and deliver our products and services</li>
                            <li>Verify payments, including manual transactions (UPI/bank transfers)</li>
                            <li>Communicate regarding inquiries, orders, and support requests</li>
                            <li>Improve website functionality and user experience</li>
                            <li>Send important updates, service-related messages, or promotional communication (you may opt out anytime)</li>
                            <li>Prevent fraud, abuse, or unauthorized access</li>
                        </ul>

                        <h3>3. Manual Payments &amp; Data Usage</h3>
                        <p>If you choose to make a manual payment, we may collect payment reference details or screenshots solely for verification purposes. This information is:</p>
                        <ul>
                            <li>Used only to confirm payment</li>
                            <li>Not shared publicly</li>
                            <li>Retained only as long as required for accounting, legal, or dispute-resolution purposes</li>
                        </ul>

                        <h3>4. Cookies &amp; Tracking Technologies</h3>
                        <p>We use cookies and similar technologies to:</p>
                        <ul>
                            <li>Understand user behavior</li>
                            <li>Improve site performance</li>
                            <li>Track marketing and advertising effectiveness</li>
                        </ul>
                        <p>You can disable cookies through your browser settings. However, some features of the website may not function properly if cookies are disabled.</p>

                        <h3>5. Sharing of Information</h3>
                        <p>We do not sell, trade, or rent your personal information to third parties.</p>
                        <p>We may share information only:</p>
                        <ul>
                            <li>With trusted service providers (hosting, payment gateways, analytics tools) strictly for service delivery</li>
                            <li>To comply with legal obligations or law enforcement requests</li>
                            <li>To protect our rights, property, or safety</li>
                        </ul>

                        <h3>6. Data Security</h3>
                        <p>We implement reasonable administrative, technical, and security measures to protect your personal information. However, no online system is 100% secure, and we cannot guarantee absolute security of data transmitted online.</p>

                        <h3>7. Third-Party Links</h3>
                        <p>Our website may contain links to third-party websites or tools. We are not responsible for the privacy practices or content of those external sites. We encourage you to review their privacy policies separately.</p>

                        <h3>8. Data Retention</h3>
                        <p>We retain personal information only for as long as necessary to:</p>
                        <ul>
                            <li>Fulfill the purposes outlined in this policy</li>
                            <li>Comply with legal, tax, or regulatory requirements</li>
                            <li>Resolve disputes and enforce agreements</li>
                        </ul>

                        <h3>9. Your Rights</h3>
                        <p>Depending on applicable laws, you may have the right to:</p>
                        <ul>
                            <li>Access your personal data</li>
                            <li>Request correction or update of your information</li>
                            <li>Request deletion of your data (subject to legal and business requirements)</li>
                            <li>Opt out of marketing communications</li>
                        </ul>
                        <p>To exercise these rights, please contact us using the details below.</p>

                        <h3>10. Changes to This Privacy Policy</h3>
                        <p>We reserve the right to update or modify this Privacy Policy at any time. Changes will be effective immediately upon posting on this page. Continued use of our website means you accept the updated policy.</p>

                        <h3>11. Contact Us</h3>
                        <p>If you have any questions or concerns regarding this Privacy Policy, please contact us:</p>
                        <p>
                            <strong>Website:</strong> <a href="{{ !empty($agency->custom_domain) ? 'https://' . $agency->custom_domain : 'https://saasreselling.com/' }}" target="_blank">{{ !empty($agency->custom_domain) ? 'https://' . $agency->custom_domain : 'https://saasreselling.com/' }}</a><br>
                            <strong>Phone / WhatsApp:</strong> {{ $agency->contact_phone ?? '+91 63749 13298' }}<br>
                            <strong>Email:</strong> {{ $agency->contact_email ?? 'support@saasreselling.com' }}
                        </p>
                        <p>By using our website or services, you acknowledge that you have read, understood, and agreed to this Privacy Policy.</p>
                    @endif
                @elseif($type === 'terms')
                    @if(!empty($agency->terms_conditions))
                        {!! $agency->terms_conditions !!}
                    @else
                        <h2>Terms and Conditions for {{ $agencyName }}</h2>
                        <p>Welcome to <strong>{{ $agencyName }}</strong> (accessible at <a href="{{ !empty($agency->custom_domain) ? 'https://' . $agency->custom_domain : 'https://saasreselling.com/' }}" target="_blank">{{ !empty($agency->custom_domain) ? 'https://' . $agency->custom_domain : 'https://saasreselling.com/' }}</a>, including related sub-pages such as “White Label Solutions”). These Terms and Conditions (“Terms”) govern your access to and use of our website, products, and services. By accessing our website or making a purchase, you agree to be bound by these Terms. If you do not agree, please do not use our services.</p>

                        <h3>1. About Our Services</h3>
                        <p><strong>{{ $agencyName }}</strong> provides digital products, software tools, white-label SaaS solutions, website services, reseller programs, and related digital services. All offerings are provided under your acceptance of these Terms, our Refund Policy, and other policies published on our website.</p>

                        <h3>2. Eligibility</h3>
                        <p>By using our website or services, you confirm that:</p>
                        <ul>
                            <li>You are at least 18 years old, or legally eligible to enter into a binding agreement under Indian law.</li>
                            <li>You are providing accurate and complete information while purchasing or registering.</li>
                        </ul>

                        <h3>3. Pricing &amp; Payments</h3>
                        <h4>3.1 Payment Modes</h4>
                        <p>We accept payments through:</p>
                        <ul>
                            <li>Online payment gateways (cards, UPI, net banking, wallets, etc.)</li>
                            <li>Manual payments such as UPI transfer, bank transfer, or other offline modes communicated by our team</li>
                        </ul>

                        <h4>3.2 Manual Transactions</h4>
                        <p>For manual transactions:</p>
                        <ul>
                            <li>Payment confirmation may take additional time.</li>
                            <li>Services or access will be provided only after successful verification of payment.</li>
                            <li>You are responsible for sharing correct payment proof.</li>
                        </ul>

                        <h4>3.3 Taxes</h4>
                        <p>All prices are exclusive of applicable taxes unless stated otherwise. Any applicable GST or government taxes will be borne by the customer.</p>

                        <h4>3.4 Payment &amp; Transaction Disclaimer</h4>
                        <p>All payments made on <a href="{{ !empty($agency->custom_domain) ? 'https://' . $agency->custom_domain : 'https://saasreselling.com/' }}" target="_blank">{{ !empty($agency->custom_domain) ? 'https://' . $agency->custom_domain : 'https://saasreselling.com/' }}</a> are solely for purchasing {{ $agencyName }}’s own SaaS products, digital services, or reseller memberships.</p>
                        <p><strong>{{ $agencyName }}</strong> does not facilitate, process, hold, split, or route payments between resellers and their customers. All reseller-to-customer transactions, pricing, billing, and payment collection are handled independently by the reseller using their own systems.</p>

                        <h3>4. No Refund &amp; No Cancellation Policy</h3>
                        <p>All sales are final and non-refundable, including payments made via manual or online methods. Once payment is completed and service access is provided or work is initiated, no refunds, cancellations, or chargebacks will be entertained, except where required by applicable law.</p>
                        <p>Please refer to our Refund Policy page for complete details.</p>

                        <h3>5. Service Delivery</h3>
                        <ul>
                            <li>Digital products or access credentials are usually delivered electronically.</li>
                            <li>Timelines may vary based on the nature of the service (standard, custom, or reseller plans).</li>
                            <li>Delays caused due to incomplete information, incorrect details, or customer non-responsiveness will not be our responsibility.</li>
                        </ul>

                        <h3>6. User Responsibilities</h3>
                        <p>You agree:</p>
                        <ul>
                            <li>Not to misuse, copy, resell, reverse-engineer, or redistribute our products or services unless explicitly allowed under a reseller agreement.</li>
                            <li>Not to use our services for illegal, abusive, fraudulent, or unethical purposes.</li>
                            <li>To maintain confidentiality of login credentials and account details.</li>
                        </ul>

                        <h3>7. Intellectual Property Rights</h3>
                        <p>All content on this website, including text, graphics, logos, software, UI/UX, designs, and trademarks, are the intellectual property of <strong>{{ $agencyName }}</strong>, unless stated otherwise.</p>
                        <p>You may not copy, reproduce, modify, distribute, or exploit any content without prior written permission.</p>

                        <h3>8. Third-Party Tools &amp; Services</h3>
                        <p>Some services may integrate or rely on third-party platforms (e.g., hosting providers, APIs, payment gateways). We are not responsible for downtime, policy changes, or issues caused by third-party services.</p>

                        <h3>9. Limitation of Liability</h3>
                        <p>To the maximum extent permitted by law:</p>
                        <ul>
                            <li><strong>{{ $agencyName }}</strong> shall not be liable for any indirect, incidental, special, or consequential damages.</li>
                            <li>We do not guarantee specific business results, revenue, leads, or profits from using our products or reseller programs.</li>
                            <li>All services are provided on an “as-is” and “as-available” basis.</li>
                        </ul>

                        <h3>10. Termination of Access</h3>
                        <p>We reserve the right to suspend or terminate access to our services without prior notice if:</p>
                        <ul>
                            <li>These Terms are violated</li>
                            <li>Payment disputes or chargebacks are raised</li>
                            <li>Services are misused or abused</li>
                        </ul>
                        <p>No refunds will be provided in such cases.</p>

                        <h3>11. Source Code &amp; Script Policy</h3>
                        <p>All our software products are provided as fully managed, white-label solutions under the client’s brand name and domain.</p>
                        <p>However, the complete system, source code, scripts, and backend infrastructure remain hosted on our VPS servers and are owned and controlled by us.</p>
                        <p>We do not sell, transfer, or provide access to the source code or core system files under any circumstances.</p>
                        <p>Clients are granted usage rights to operate and resell the platform under their branding, while all technical maintenance, security, and server management are handled by our team.</p>
                        <p>Any attempt to copy, reverse engineer, resell, or misuse the system is strictly prohibited.</p>

                        <h3>12. Modifications to Terms</h3>
                        <p>We may update or modify these Terms at any time without prior notice. Continued use of the website or services after changes means you accept the updated Terms.</p>

                        <h3>13. Governing Law &amp; Jurisdiction</h3>
                        <p>These Terms shall be governed by and interpreted in accordance with the laws of India. Any disputes shall be subject to the exclusive jurisdiction of courts located in India.</p>

                        <h3>14. Contact Information</h3>
                        <p>If you have any questions regarding these Terms and Conditions, please contact us:</p>
                        <p>
                            <strong>Website:</strong> <a href="{{ !empty($agency->custom_domain) ? 'https://' . $agency->custom_domain : 'https://saasreselling.com/' }}" target="_blank">{{ !empty($agency->custom_domain) ? 'https://' . $agency->custom_domain : 'https://saasreselling.com/' }}</a><br>
                            <strong>Phone / WhatsApp:</strong> {{ $agency->contact_phone ?? '+91 63749 13298' }}<br>
                            <strong>Email:</strong> {{ $agency->contact_email ?? 'support@saasreselling.com' }}
                        </p>
                        <p>By using our website or purchasing from <strong>{{ $agencyName }}</strong>, you acknowledge that you have read, understood, and agreed to these Terms and Conditions.</p>
                    @endif
                @elseif($type === 'shipping')
                    {!! !empty($agency->shipping_policy) ? $agency->shipping_policy : ("<h2>Shipping & Delivery Policy for " . e($agencyName) . "</h2><p>All SaaS products, white-label portals, and software subscriptions purchased from " . e($agencyName) . " are delivered 100% electronically via instant email confirmation and dashboard portal access.</p><h3>Instant Digital Fulfillment</h3><p>No physical shipping or postal dispatch is involved. Account credentials and setup tools become active immediately upon payment confirmation.</p><h3>Delivery Timeline</h3><p>Software provisioning completes automatically within 5 to 15 minutes of purchase.</p><h3>Contact Support</h3><p>Email: <strong>" . e($agency->contact_email ?? $data['lp_contact_email'] ?? 'support@saasreselling.com') . "</strong></p>") !!}
                @elseif($type === 'refund')
                    @if(!empty($agency->refund_policy))
                        {!! $agency->refund_policy !!}
                    @else
                        <h2>Cancellation &amp; Refund Policy for {{ $agencyName }}</h2>
                        <p>Thank you for choosing <strong>{{ $agencyName }}</strong>. We strive to deliver the highest quality digital products, services, and reseller solutions. Please read this Refund Policy carefully before making a purchase.</p>

                        <h3>1. No Refunds / All Sales Are Final</h3>
                        <p>All purchases made through <a href="{{ !empty($agency->custom_domain) ? 'https://' . $agency->custom_domain : 'https://saasreselling.com/' }}" target="_blank">{{ !empty($agency->custom_domain) ? 'https://' . $agency->custom_domain : 'https://saasreselling.com/' }}</a> or related sub-sites or Manual Payments are final and non-refundable. Once a purchase is completed, we do not offer returns, refunds, cancellations, credits, or exchanges under any circumstances.</p>
                        <p>This includes, but is not limited to:</p>
                        <ul>
                            <li>Digital products and downloads</li>
                            <li>Website design &amp; development services</li>
                            <li>Software tools, templates, and digital assets</li>
                            <li>Reseller membership plans and franchise fees</li>
                            <li>Custom development &amp; ongoing support services</li>
                            <li>All other goods and services sold through our platform</li>
                        </ul>

                        <h3>2. Why We Do Not Offer Refunds</h3>
                        <p>Because our products and services are delivered instantly or involve digital access and customized work, refunds cannot be processed once service delivery has begun or digital access has been provided. Digital content cannot be “returned” once delivered or accessed, and services begin as soon as you engage with our team or platform.</p>

                        <h3>3. Exceptions Only as Required by Law</h3>
                        <p>We do not provide refunds for change of mind, dissatisfaction, incorrect decisions, or any reason related to personal preference. We will only consider exceptions if required under applicable consumer protection laws. In such cases, the refund process will follow legal guidelines and timelines.</p>

                        <h3>4. Defects or Access Issues</h3>
                        <p>If you experience technical issues or are unable to access a digital product or service due to a problem on our side, please contact support immediately. We will make commercially reasonable efforts to resolve access issues but this is not a guarantee of a refund. In rare cases, we may provide a replacement or corrective action at our discretion.</p>

                        <h3>5. Chargebacks and Disputes</h3>
                        <p>Customers must first contact us with any concerns before initiating a chargeback or payment dispute. Unilateral chargebacks without communication may result in the suspension of service access or future account restrictions.</p>

                        <h3>6. Contact Us</h3>
                        <p>If you have questions about this Refund Policy or encounter technical issues, please get in touch:</p>
                        <p>
                            <strong>Website:</strong> <a href="{{ !empty($agency->custom_domain) ? 'https://' . $agency->custom_domain : 'https://saasreselling.com/' }}" target="_blank">{{ !empty($agency->custom_domain) ? 'https://' . $agency->custom_domain : 'https://saasreselling.com/' }}</a><br>
                            <strong>Phone / WhatsApp:</strong> {{ $agency->contact_phone ?? '+91 63749 13298' }}<br>
                            <strong>Email:</strong> {{ $agency->contact_email ?? 'support@saasreselling.com' }}
                        </p>
                    @endif
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
        <footer class="site-footer" style="background:#020617;border-top:1px solid #1e293b;padding:56px 0 28px;margin-top:auto">
        <div style="max-width:1200px;margin:0 auto;padding:0 24px">
            <div style="display:grid;grid-template-columns:260px 1fr 1fr 1fr;gap:36px;padding-bottom:44px;border-bottom:1px solid #1e293b" class="footer-grid">

                {{-- Col 1: Brand Info & Socials --}}
                <div style="display:flex;flex-direction:column;gap:16px">
                    <div style="display:flex;align-items:center;gap:12px">
                        @if(!empty($agency->logo_dark))
                            <img src="{{ asset(ltrim($agency->logo_dark, '/')) }}" alt="{{ $agencyName }}" class="site-logo-dark" style="height:64px;max-height:68px;width:auto;object-fit:contain">
                            <img src="{{ asset(ltrim($agency->logo, '/')) }}" alt="{{ $agencyName }}" class="site-logo-light" style="height:64px;max-height:68px;width:auto;object-fit:contain">
                        @elseif(!empty($agency->logo))
                            <img src="{{ asset(ltrim($agency->logo, '/')) }}" alt="{{ $agencyName }}" style="height:64px;max-height:68px;width:auto;object-fit:contain">
                        @else
                            <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#7c3aed,#ea580c);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                <i class="fas fa-layer-group text-white text-base"></i>
                            </div>
                            <span style="font-family:'Outfit',sans-serif;font-size:20px;font-weight:900;color:#e2e8f0">{{ $agencyName }}</span>
                        @endif
                    </div>
                    <p style="font-size:12.5px;color:#94a3b8;line-height:1.7;max-width:240px">
                        {{ $agency->footer_content ?? 'Your technology partner in building profitable digital and white-label SaaS businesses.' }}
                    </p>
                    <div style="display:flex;gap:10px;margin-top:4px">
                        @if(!empty($agency->facebook_url))<a href="{{ $agency->facebook_url }}" target="_blank" style="width:34px;height:34px;border-radius:8px;background:#1e293b;display:flex;align-items:center;justify-content:center;color:#94a3b8;text-decoration:none;"><i class="fab fa-facebook-f text-xs"></i></a>@endif
                        @if(!empty($agency->instagram_url))<a href="{{ $agency->instagram_url }}" target="_blank" style="width:34px;height:34px;border-radius:8px;background:#1e293b;display:flex;align-items:center;justify-content:center;color:#94a3b8;text-decoration:none;"><i class="fab fa-instagram text-xs"></i></a>@endif
                        @if(!empty($agency->youtube_url))<a href="{{ $agency->youtube_url }}" target="_blank" style="width:34px;height:34px;border-radius:8px;background:#1e293b;display:flex;align-items:center;justify-content:center;color:#94a3b8;text-decoration:none;"><i class="fab fa-youtube text-xs"></i></a>@endif
                        @if(!empty($agency->linkedin_url))<a href="{{ $agency->linkedin_url }}" target="_blank" style="width:34px;height:34px;border-radius:8px;background:#1e293b;display:flex;align-items:center;justify-content:center;color:#94a3b8;text-decoration:none;"><i class="fab fa-linkedin-in text-xs"></i></a>@endif
                    </div>
                </div>

                {{-- Col 2: Quick Links (Screenshot 5 Match) --}}
                <div style="display:flex;flex-direction:column;gap:14px">
                    <h4 style="font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.1em;color:#e2e8f0">Quick Links</h4>
                    <ul style="list-style:none;display:flex;flex-direction:column;gap:10px;margin:0;padding:0;">
                        <li><a href="{{ url('/') }}" style="font-size:12.5px;color:#94a3b8;text-decoration:none;transition:color .15s" onmouseover="this.style.color='#c7d2fe'" onmouseout="this.style.color='#94a3b8'">Home</a></li>
                        <li><a href="{{ url('/#products') }}" style="font-size:12.5px;color:#94a3b8;text-decoration:none;transition:color .15s" onmouseover="this.style.color='#c7d2fe'" onmouseout="this.style.color='#94a3b8'">Products</a></li>
                        <li><a href="{{ url('/#about-section') }}" style="font-size:12.5px;color:#94a3b8;text-decoration:none;transition:color .15s" onmouseover="this.style.color='#c7d2fe'" onmouseout="this.style.color='#94a3b8'">About Us</a></li>
                        <li><a href="{{ url('/#how-it-works') }}" style="font-size:12.5px;color:#94a3b8;text-decoration:none;transition:color .15s" onmouseover="this.style.color='#c7d2fe'" onmouseout="this.style.color='#94a3b8'">How It Works</a></li>
                        <li><a href="{{ url('/#testimonials') }}" style="font-size:12.5px;color:#94a3b8;text-decoration:none;transition:color .15s" onmouseover="this.style.color='#c7d2fe'" onmouseout="this.style.color='#94a3b8'">Reviews</a></li>
                        <li><a href="{{ url('/#faq') }}" style="font-size:12.5px;color:#94a3b8;text-decoration:none;transition:color .15s" onmouseover="this.style.color='#c7d2fe'" onmouseout="this.style.color='#94a3b8'">FAQ</a></li>
                    </ul>
                </div>

                {{-- Col 3: Our Products (Screenshot 5 Match) --}}
                <div style="display:flex;flex-direction:column;gap:14px">
                    <h4 style="font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.1em;color:#e2e8f0">Our Products</h4>
                    <ul style="list-style:none;display:flex;flex-direction:column;gap:10px;margin:0;padding:0;">
                        <li><a href="{{ url('/#products') }}" style="font-size:12.5px;color:#94a3b8;text-decoration:none;transition:color .15s" onmouseover="this.style.color='#c7d2fe'" onmouseout="this.style.color='#94a3b8'">Ecombuilder</a></li>
                        <li><a href="{{ url('/#products') }}" style="font-size:12.5px;color:#94a3b8;text-decoration:none;transition:color .15s" onmouseover="this.style.color='#c7d2fe'" onmouseout="this.style.color='#94a3b8'">Website Builder</a></li>
                        <li><a href="{{ url('/#products') }}" style="font-size:12.5px;color:#94a3b8;text-decoration:none;transition:color .15s" onmouseover="this.style.color='#c7d2fe'" onmouseout="this.style.color='#94a3b8'">WhatsApp Bulk</a></li>
                    </ul>
                </div>

                {{-- Col 4: Policies / Legal (policy.md Column) --}}
                <div style="display:flex;flex-direction:column;gap:14px">
                    <h4 style="font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.1em;color:#e2e8f0">Policies</h4>
                    <ul style="list-style:none;display:flex;flex-direction:column;gap:10px;margin:0;padding:0;">
                        <li><a href="{{ route('agency.privacy') }}" style="font-size:12.5px;color:#94a3b8;text-decoration:none;transition:color .15s" onmouseover="this.style.color='#c7d2fe'" onmouseout="this.style.color='#94a3b8'">Privacy Policy</a></li>
                        <li><a href="{{ route('agency.terms') }}" style="font-size:12.5px;color:#94a3b8;text-decoration:none;transition:color .15s" onmouseover="this.style.color='#c7d2fe'" onmouseout="this.style.color='#94a3b8'">Terms &amp; Conditions</a></li>
                        <li><a href="{{ route('agency.refund') }}" style="font-size:12.5px;color:#94a3b8;text-decoration:none;transition:color .15s" onmouseover="this.style.color='#c7d2fe'" onmouseout="this.style.color='#94a3b8'">Refund Policy</a></li>
                        <li><a href="{{ route('agency.contact') }}" style="font-size:12.5px;color:#94a3b8;text-decoration:none;transition:color .15s" onmouseover="this.style.color='#c7d2fe'" onmouseout="this.style.color='#94a3b8'">Contact Us</a></li>
                    </ul>
                </div>

            </div>

            {{-- Bottom Bar Matching Screenshot 5 --}}
            <div style="padding-top:28px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
                <p style="font-size:12px;color:#64748b;margin:0">&copy; {{ date('Y') }} {{ $agencyName }}. All rights reserved.</p>
                <div style="display:flex;align-items:center;gap:14px;font-size:12px;color:#64748b">
                    <a href="{{ route('agency.privacy') }}" style="color:#64748b;text-decoration:none;" onmouseover="this.style.color='#c7d2fe'" onmouseout="this.style.color='#64748b'">Privacy Policy</a>
                    <span>|</span>
                    <a href="{{ route('agency.terms') }}" style="color:#64748b;text-decoration:none;" onmouseover="this.style.color='#c7d2fe'" onmouseout="this.style.color='#64748b'">Terms &amp; Conditions</a>
                    <span>|</span>
                    <a href="{{ route('agency.refund') }}" style="color:#64748b;text-decoration:none;" onmouseover="this.style.color='#c7d2fe'" onmouseout="this.style.color='#64748b'">Refund Policy</a>
                </div>
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