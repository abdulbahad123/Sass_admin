@php
    $dbProds = $data['db_products'] ?? collect();
@endphp
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $data['lp_meta_title'] }}</title>
    <meta name="description" content="{{ $data['lp_meta_description'] }}">
    <link rel="icon" href="{{ $data['lp_header_logo'] ?? '/assets/images/common/Logo-blue.png' }}" type="image/png">

    <!-- Fonts from D:\nooryak\nooryakwebsite -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,700;1,800&family=Inter:wght@300;400;500;600;700;800;900&family=Onest:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome & Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Tailwind CSS CDN -->
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
                        sans: ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'],
                        heading: ['"Plus Jakarta Sans"', 'sans-serif'],
                        jakarta: ['"Plus Jakarta Sans"', 'sans-serif'],
                        onest: ['Onest', 'sans-serif'],
                        poppins: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Custom CSS matching Nooryak branding -->
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            overflow-x: hidden;
        }
        .font-space, .font-heading, h1, h2, h3, h4, h5, h6 {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            letter-spacing: -0.025em;
        }
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

        /* Custom Range Slider */
        input[type=range] {
            -webkit-appearance: none;
            width: 100%;
            background: transparent;
        }
        input[type=range]:focus { outline: none; }
        input[type=range]::-webkit-slider-runnable-track {
            width: 100%;
            height: 8px;
            cursor: pointer;
            background: #cbd5e1;
            border-radius: 4px;
        }
        input[type=range]::-webkit-slider-thumb {
            height: 22px;
            width: 22px;
            border-radius: 50%;
            background: #ff3d00;
            cursor: pointer;
            -webkit-appearance: none;
            margin-top: -7px;
            box-shadow: 0 0 10px rgba(255, 61, 0, 0.5);
        }

        .handwritten { font-family: 'Poppins', cursive; font-style: italic; }

        /* Scroll Reveal Animation (Task 3) */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }
    
        html.dark body {
            background-color: #0B0F19;
            color: #f8fafc;
        }
        /* Fix the dropdowns in dark mode */
        html.dark .group-hover\\:opacity-100 {
            /* If any specific dark mode fixes are needed */
        }
    </style>
</head>
<body class="antialiased">

    <!-- TOP ANNOUNCEMENT BAR -->
    @if(!empty($data['lp_announcement_text']))
    <div class="bg-gradient-to-r from-slate-900 via-orange-950 to-slate-900 text-white text-[11px] sm:text-xs py-2 px-4 text-center font-medium tracking-wide flex items-center justify-center gap-2">
        <span class="bg-orange-500/30 text-orange-200 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider">{{ $data['lp_announcement_badge'] }}</span>
        <span>{{ $data['lp_announcement_text'] }}</span>
    </div>
    @endif

    <!-- STICKY NAVBAR -->
    <header class="sticky top-0 z-50 bg-white/95 dark:bg-[#0B0F19]/95 backdrop-blur-md border-b border-slate-200 dark:border-slate-700/80 transition-all duration-300">
        <div class="max-w-[1340px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20 sm:h-24">
                <!-- Logo (Increased Size & Dual Dark/White Mode Support) -->
                @php
                    $lightLogo = !empty($data['lp_header_logo']) ? $data['lp_header_logo'] : null;
                    $darkLogo  = !empty($data['lp_header_logo_dark']) ? $data['lp_header_logo_dark'] : $lightLogo;
                @endphp
                <a href="{{ url('/') }}" class="flex items-center space-x-3 py-1">
                    @if($lightLogo || $darkLogo)
                        @if($lightLogo)
                            <img src="{{ asset($lightLogo) }}" alt="Logo" class="h-14 sm:h-16 lg:h-20 max-h-[72px] w-auto object-contain transition-all {{ $darkLogo ? 'dark:hidden' : '' }}">
                        @endif
                        @if($darkLogo)
                            <img src="{{ asset($darkLogo) }}" alt="Logo Dark" class="h-14 sm:h-16 lg:h-20 max-h-[72px] w-auto object-contain transition-all hidden dark:block">
                        @endif
                    @else
                        <div class="flex items-center space-x-2.5">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl btn-gradient flex items-center justify-center text-white font-bold text-2xl shadow-md shadow-orange-500/20">
                                <i class="fas fa-layer-group text-xl sm:text-2xl"></i>
                            </div>
                            <span class="font-heading font-extrabold text-2xl sm:text-3xl tracking-tight text-slate-900 dark:text-white">SaaSReselling</span>
                        </div>
                    @endif
                </a>

                <!-- Desktop Menu Navigation -->
                <nav class="hidden lg:flex items-center space-x-8 text-sm font-semibold text-slate-600 dark:text-slate-300 relative">
                    <a href="#hero" class="hover:text-[#ff3d00] transition-colors py-6">Home</a>
                    
                    <!-- Solutions Dropdown -->
                    <div class="relative group">
                        <a href="#about" class="hover:text-[#ff3d00] transition-colors flex items-center py-6 cursor-pointer">
                            Solutions <i class="fas fa-chevron-down text-[10px] ml-1 transition-transform group-hover:rotate-180"></i>
                        </a>
                        <div class="absolute top-full left-1/2 -translate-x-1/2 mt-0 w-[420px] bg-[#0f172a] rounded-2xl border border-slate-700/50 shadow-2xl shadow-slate-900/50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 z-50">
                            <div class="p-4 flex flex-col space-y-2">
                                <!-- Bundle -->
                                <a href="#" class="flex items-start space-x-4 p-4 rounded-xl hover:bg-white dark:bg-[#0B0F19]/5 transition-colors group/item">
                                    <div class="w-10 h-10 rounded-lg bg-indigo-500/20 text-indigo-400 flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <i class="fas fa-bolt"></i>
                                    </div>
                                    <div>
                                        <div class="flex items-center space-x-2 mb-1">
                                            <span class="text-white font-bold text-sm group-hover/item:text-indigo-400 transition-colors">All-in-one-SaaS Bundle</span>
                                            <span class="bg-gradient-to-r from-purple-500 to-pink-500 text-white text-[9px] font-extrabold px-2 py-0.5 rounded-full tracking-wider shadow-md">BEST VALUE</span>
                                        </div>
                                        <p class="text-xs text-slate-400 leading-relaxed">Access to all 5 SaaS applications at a single subscription price.</p>
                                    </div>
                                </a>
                                
                                <div class="w-full h-px bg-slate-800/80 my-2"></div>
                                
                                <div class="grid grid-cols-2 gap-2">
                                    <!-- Product 1 -->
                                    <a href="#" class="flex items-start space-x-3 p-3 rounded-xl hover:bg-white dark:bg-[#0B0F19]/5 transition-colors">
                                        <div class="w-8 h-8 rounded-lg bg-orange-500/10 text-orange-400 flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <i class="fas fa-shopping-cart"></i>
                                        </div>
                                        <div>
                                            <span class="block text-white font-bold text-[13px] mb-0.5">Ecom Builder</span>
                                            <span class="block text-[10px] text-slate-400">Launch e-commerce stores easily.</span>
                                        </div>
                                    </a>
                                    <!-- Product 2 -->
                                    <a href="#" class="flex items-start space-x-3 p-3 rounded-xl hover:bg-white dark:bg-[#0B0F19]/5 transition-colors">
                                        <div class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-400 flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <i class="fas fa-globe"></i>
                                        </div>
                                        <div>
                                            <span class="block text-white font-bold text-[13px] mb-0.5">Website Builder</span>
                                            <span class="block text-[10px] text-slate-400">Create sites with AI & templates.</span>
                                        </div>
                                    </a>
                                    <!-- Product 3 -->
                                    <a href="#" class="flex items-start space-x-3 p-3 rounded-xl hover:bg-white dark:bg-[#0B0F19]/5 transition-colors col-span-2">
                                        <div class="w-8 h-8 rounded-lg bg-green-500/10 text-green-400 flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <i class="fab fa-whatsapp"></i>
                                        </div>
                                        <div>
                                            <span class="block text-white font-bold text-[13px] mb-0.5">WhatsApp Automation</span>
                                            <span class="block text-[10px] text-slate-400">Automate messaging & marketing.</span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <a href="#pricing" class="hover:text-[#ff3d00] transition-colors py-6">Pricing</a>
                    
                    <!-- Resources Dropdown -->
                    <div class="relative group">
                        <a href="#products" class="hover:text-[#ff3d00] transition-colors flex items-center py-6 cursor-pointer">
                            Resources <i class="fas fa-chevron-down text-[10px] ml-1 transition-transform group-hover:rotate-180"></i>
                        </a>
                        <div class="absolute top-full left-1/2 -translate-x-1/2 mt-0 w-[380px] bg-[#0f172a] rounded-2xl border border-slate-700/50 shadow-2xl shadow-slate-900/50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 z-50">
                            <div class="p-4 grid grid-cols-2 gap-2">
                                <a href="#" class="flex items-start space-x-3 p-3 rounded-xl hover:bg-white dark:bg-[#0B0F19]/5 transition-colors">
                                    <i class="fab fa-youtube text-red-500 text-lg mt-0.5 w-6 text-center"></i>
                                    <div>
                                        <span class="block text-white font-bold text-sm mb-0.5">YouTube</span>
                                        <span class="block text-xs text-slate-400">Tutorials & demos</span>
                                    </div>
                                </a>
                                <a href="mailto:{{ $data['lp_contact_email'] ?? '' }}" class="flex items-start space-x-3 p-3 rounded-xl hover:bg-white dark:bg-[#0B0F19]/5 transition-colors">
                                    <i class="far fa-envelope text-sky-400 text-lg mt-0.5 w-6 text-center"></i>
                                    <div>
                                        <span class="block text-white font-bold text-sm mb-0.5">Contact Us</span>
                                        <span class="block text-xs text-slate-400">Get in touch</span>
                                    </div>
                                </a>
                                <a href="#faq" class="flex items-start space-x-3 p-3 rounded-xl hover:bg-white dark:bg-[#0B0F19]/5 transition-colors">
                                    <i class="far fa-question-circle text-sky-500 text-lg mt-0.5 w-6 text-center"></i>
                                    <div>
                                        <span class="block text-white font-bold text-sm mb-0.5">FAQ</span>
                                        <span class="block text-xs text-slate-400">Common questions</span>
                                    </div>
                                </a>
                                <a href="https://wa.me/6374913298" target="_blank" class="flex items-start space-x-3 p-3 rounded-xl hover:bg-white dark:bg-[#0B0F19]/5 transition-colors">
                                    <i class="fab fa-whatsapp text-green-500 text-lg mt-0.5 w-6 text-center"></i>
                                    <div>
                                        <span class="block text-white font-bold text-sm mb-0.5">WhatsApp</span>
                                        <span class="block text-xs text-slate-400">Chat with us</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- Right Side Actions & Menu -->
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <!-- Action Buttons -->
                    <div class="hidden sm:flex items-center space-x-3">
                        <button onclick="window.openDemoModal()" class="px-5 py-2.5 rounded-full btn-gradient text-white text-xs sm:text-sm font-bold shadow-lg shadow-orange-500/25 hover:shadow-orange-500/40 hover:scale-[1.02] transition-all flex items-center space-x-2">
                            <i class="fas fa-video text-xs"></i>
                            <span>Book Free Live Demo</span>
                        </button>
                    </div>
                    
                    <!-- Theme Toggle -->
                    <button onclick="toggleTheme()" class="p-2 sm:p-2.5 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                        <i class="fas fa-moon dark:hidden text-lg"></i>
                        <i class="fas fa-sun hidden dark:block text-lg text-amber-400"></i>
                    </button>

                    <!-- Mobile Menu Button -->
                    <button id="mobileMenuBtn" class="lg:hidden p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Drawer Menu -->
        <div id="mobileMenu" class="hidden lg:hidden border-t border-slate-200 dark:border-slate-700 bg-white dark:bg-[#0B0F19] px-4 pt-4 pb-6 space-y-3 shadow-lg">
            <a href="#hero" class="block px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:bg-[#1e293b]">Home</a>
            <a href="#about" class="block px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:bg-[#1e293b]">Solutions</a>
            <a href="#pricing" class="block px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:bg-[#1e293b]">Pricing</a>
            <a href="#products" class="block px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:bg-[#1e293b]">Products</a>
            <a href="#faq" class="block px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:bg-[#1e293b]">FAQ</a>
            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 space-y-2">
                <button onclick="window.openDemoModal()" class="block w-full text-center px-4 py-3 rounded-full btn-gradient text-xs font-bold text-white shadow-md">Book Free Live Demo</button>
            </div>
        </div>
    </header>

    <!-- SECTION 1: HERO SECTION -->
    <section id="hero" class="relative py-8 sm:py-10 lg:py-12 overflow-hidden bg-gradient-to-b from-orange-50/60 via-amber-50/20 to-transparent dark:from-indigo-950/40 dark:via-purple-900/20 dark:to-[#0B0F19] reveal">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                
                <!-- Left Text Column (6 cols on lg for dedicated text space - Matches Reference Design) -->
                <div class="lg:col-span-6 min-w-0 space-y-6 text-left relative z-20">
                    
                    <!-- Top Audience Pill Badge (Task 1: Pipe Separator) -->
                    <div class="inline-flex items-center space-x-2 bg-[#fff3ec] dark:bg-orange-950/40 border border-[#ffdcd0] dark:border-orange-800/60 px-4 py-2 rounded-full text-[#c2410c] dark:text-orange-300 text-[11px] sm:text-xs font-bold tracking-wide uppercase shadow-sm">
                        <div class="w-5 h-5 rounded-full bg-[#ff5a2c] text-white flex items-center justify-center text-[10px] flex-shrink-0">
                            <i class="fas fa-users"></i>
                        </div>
                        <span>{{ str_replace(' · ', ' | ', $data['lp_hero_announcement_badge'] ?? 'BUILT FOR AGENCIES | FREELANCERS | RESELLERS | DIGITAL MARKETERS') }}</span>
                    </div>

                    <!-- Main Headline matching Reference Image (Task 1: Added Bottom Gap Below "Turn Your Agency Into a") -->
                    <h1 class="font-heading font-extrabold text-3xl sm:text-4xl lg:text-[44px] xl:text-[50px] 2xl:text-[54px] text-slate-900 dark:text-white leading-[1.16] tracking-tight mb-4 sm:mb-5">
                        <span class="block whitespace-normal sm:whitespace-nowrap pb-1.5 sm:pb-2.5">Turn Your Agency Into a</span>
                        <span class="text-[#ff3d00] block whitespace-normal sm:whitespace-nowrap">SaaS Software Brand</span>
                    </h1>

                    <!-- Subtitle paragraph matching Reference Image -->
                    <p class="text-sm sm:text-base text-slate-600 dark:text-slate-300 leading-relaxed font-normal max-w-xl">
                        {{ (!empty($data['lp_hero_subtitle']) && stripos($data['lp_hero_subtitle'], 'Nooryak is an All-In-One') === false) 
                            ? $data['lp_hero_subtitle'] 
                            : 'Turn your experties into your own SaaS business with 3 ready-to-sell white-label products, one unified platform, and your brand across every customer touchpoint. No coding or server management required. You stay in control of your brand while we handle the technology.' }}
                    </p>

                    <!-- CTAs matching Reference Image -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-2">
                        <a href="{{ $data['lp_hero_cta1_url'] ?? 'https://wa.me/916374913298?text=I%20am%20interested%20on%20that%20business' }}" class="px-8 py-3.5 sm:py-4 rounded-full bg-gradient-to-r from-[#ff5722] to-[#ff3d00] hover:from-[#ff3d00] hover:to-[#e63600] text-white font-bold text-sm sm:text-base shadow-xl shadow-orange-500/25 transition-all text-center flex items-center justify-center space-x-2 group">
                            <span>{{ $data['lp_hero_cta1_text'] ?? 'Start Your SaaS Business' }}</span>
                            <i class="fas fa-arrow-right text-xs transition-transform group-hover:translate-x-1"></i>
                        </a>

                        <a href="{{ $data['lp_hero_cta2_url'] ?? 'https://wa.me/916374913298?text=I%20want%20the%20demo%20explaination' }}" class="px-7 py-3.5 sm:py-4 rounded-full bg-white dark:bg-[#0B0F19] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white font-bold text-sm sm:text-base hover:bg-slate-50 dark:hover:bg-[#151c2c] transition-all flex items-center justify-center space-x-3 shadow-sm">
                            <div class="w-6 h-6 rounded-full bg-slate-900 dark:bg-white text-white dark:text-slate-900 flex items-center justify-center text-[10px]">
                                <i class="fas fa-play ml-0.5"></i>
                            </div>
                            <span>{{ $data['lp_hero_cta2_text'] ?? 'Book Live Demo' }}</span>
                        </a>
                    </div>

                    <!-- Hero Feature Checkmarks matching Reference Image -->
                    <div class="flex flex-wrap items-center gap-4 sm:gap-6 pt-2 text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-200">
                        @php
                            $badges = (!empty($data['lp_hero_badges']) && is_array($data['lp_hero_badges'])) 
                                ? $data['lp_hero_badges'] 
                                : ['White Label Ready', 'Custom Branding', 'Unlimited Customers'];
                        @endphp
                        @foreach($badges as $badge)
                        <div class="flex items-center space-x-2">
                            <div class="w-5 h-5 rounded-full bg-[#ff3d00] text-white flex items-center justify-center text-[10px] shadow-sm flex-shrink-0">
                                <i class="fas fa-check"></i>
                            </div>
                            <span>{{ $badge }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Right Hero Image Graphic (Enlarged by ~20%, Contained Within 6-Col Right Area) -->
                <div class="lg:col-span-6 min-w-0 relative mt-6 lg:mt-0 flex items-center justify-center lg:justify-end">
                    <div class="relative z-10 w-full min-w-0 flex items-center justify-center lg:justify-end">
                        <img src="{{ asset($data['lp_hero_image'] ?? '/assets/images/herobanner_right.png') }}" alt="SaaSReselling Platform" class="w-full h-auto max-h-[600px] sm:max-h-[720px] lg:max-h-[820px] xl:max-h-[900px] object-contain object-center lg:object-right transition-transform duration-500 lg:scale-125">
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION 2: COUNTER STATS BAR CARD (Matches Reference Image) -->
    <div class="max-w-[1380px] mx-auto px-4 sm:px-6 lg:px-8 mt-6 mb-8 relative z-20 reveal">
        <div class="bg-white dark:bg-[#0B0F19] rounded-2xl sm:rounded-3xl p-5 sm:p-7 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 sm:gap-6 items-center">
                @php
                    $stats = (!empty($data['lp_stats_bar']) && is_array($data['lp_stats_bar']) && count($data['lp_stats_bar']) > 0) 
                        ? $data['lp_stats_bar'] 
                        : [
                            ['count' => '100%', 'label' => 'WHITE-LABEL SOLUTION', 'icon' => 'fas fa-shield-alt'],
                            ['count' => '50K+', 'label' => 'BUSINESSES SERVED', 'icon' => 'fas fa-building'],
                            ['count' => '3', 'label' => 'POWERFUL SAAS PRODUCTS', 'icon' => 'fas fa-cube'],
                            ['count' => '99.9%', 'label' => 'UPTIME GUARANTEE', 'icon' => 'fas fa-shield-alt'],
                            ['count' => '24/7', 'label' => 'EXPERT SUPPORT', 'icon' => 'fas fa-headset']
                        ];
                    // Task 3: Guarantee 100% WHITE-LABEL SOLUTION and 3 POWERFUL SAAS PRODUCTS
                    foreach ($stats as &$stItem) {
                        if (isset($stItem['label']) && (stripos($stItem['label'], 'ACTIVE PARTNERS') !== false || stripos($stItem['label'], 'PARTNERS') !== false)) {
                            $stItem['count'] = '100%';
                            $stItem['label'] = 'WHITE-LABEL SOLUTION';
                            $stItem['icon'] = 'fas fa-shield-alt';
                        }
                        if (isset($stItem['label']) && stripos($stItem['label'], 'POWERFUL SAAS PRODUCTS') !== false) {
                            $stItem['count'] = '3';
                        }
                    }
                @endphp
                @foreach($stats as $stat)
                <div class="flex items-center space-x-3 p-2 sm:p-2.5 rounded-xl">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-[#fff2ec] dark:bg-orange-950/40 text-[#ff3d00] flex items-center justify-center text-sm sm:text-base font-bold shadow-sm flex-shrink-0">
                        <i class="{{ $stat['icon'] ?? 'fas fa-chart-pie' }}"></i>
                    </div>
                    <div class="text-left">
                        <div class="stat-counter font-heading font-extrabold text-xl sm:text-2xl text-slate-900 dark:text-white leading-tight" data-target="{{ $stat['count'] ?? '0' }}">
                            {{ $stat['count'] ?? '0' }}
                        </div>
                        <p class="text-[10px] sm:text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mt-0.5 leading-tight">{{ $stat['label'] ?? '' }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- SECTION 3: ABOUT SAASRESELLING (Task 3: Reference Image 4 UI/UX & Content) -->
    <section id="about" class="py-12 lg:py-16 bg-white dark:bg-[#0B0F19]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-center">
                
                <!-- Left Content matching Reference Image 4 -->
                <div class="lg:col-span-6 space-y-5 text-left">
                    <span class="inline-block px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wide bg-[#fff2ec] dark:bg-orange-950/40 text-[#c2410c] dark:text-orange-300 border border-[#fed7aa]/60">
                        {{ (!empty($data['lp_about_tag']) && stripos($data['lp_about_tag'], 'NOORYAK') === false) ? $data['lp_about_tag'] : 'ABOUT SAASRESELLING' }}
                    </span>
                    
                    <h2 class="font-heading font-extrabold text-3xl sm:text-4xl lg:text-[46px] text-slate-900 dark:text-white leading-[1.12] tracking-tight">
                        Your Partner in <br>
                        <span class="text-[#ff3d00]">SaaS Growth</span>
                    </h2>
                    
                    <p class="text-sm sm:text-base text-slate-600 dark:text-slate-300 leading-relaxed font-normal">
                        {{ (!empty($data['lp_about_desc']) && stripos($data['lp_about_desc'], 'Nooryak is an All-In-One') === false)
                            ? $data['lp_about_desc']
                            : 'SaaSReselling helps agencies, freelancers, and entrepreneurs launch their own software brand with 3 ready-to-sell white-label solutions. Manage your offerings through one unified platform, with your own branding and customer experience—without the complexity of building or maintaining the technology yourself.' }}
                    </p>
                    
                    <p class="text-sm sm:text-base font-bold text-[#ff3d00] dark:text-orange-400">
                        {{ $data['lp_about_tagline'] ?? 'Your Brand. Our Technology. Built for Recurring Revenue.' }}
                    </p>

                    <!-- 3 Feature Cards matching Reference Image 4 -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 pt-2">
                        <!-- Card 1: Trusted Technology -->
                        <div class="flex items-center space-x-3.5 p-3.5 sm:p-4 rounded-2xl bg-[#fffcf8] dark:bg-slate-900/90 border border-orange-200/60 dark:border-slate-800 shadow-sm">
                            <div class="w-11 h-11 rounded-full bg-[#ff3d00] text-white flex items-center justify-center text-base flex-shrink-0 shadow-sm">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="text-left leading-tight">
                                <span class="block font-bold text-slate-900 dark:text-white text-xs sm:text-[13px]">Trusted</span>
                                <span class="block font-bold text-slate-900 dark:text-white text-xs sm:text-[13px]">Technology</span>
                            </div>
                        </div>

                        <!-- Card 2: Built to Scale -->
                        <div class="flex items-center space-x-3.5 p-3.5 sm:p-4 rounded-2xl bg-[#fffcf8] dark:bg-slate-900/90 border border-orange-200/60 dark:border-slate-800 shadow-sm">
                            <div class="w-11 h-11 rounded-full bg-[#ff3d00] text-white flex items-center justify-center text-base flex-shrink-0 shadow-sm">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="text-left leading-tight">
                                <span class="block font-bold text-slate-900 dark:text-white text-xs sm:text-[13px]">Built to</span>
                                <span class="block font-bold text-slate-900 dark:text-white text-xs sm:text-[13px]">Scale</span>
                            </div>
                        </div>

                        <!-- Card 3: Made for Entrepreneurs -->
                        <div class="flex items-center space-x-3.5 p-3.5 sm:p-4 rounded-2xl bg-[#fffcf8] dark:bg-slate-900/90 border border-orange-200/60 dark:border-slate-800 shadow-sm">
                            <div class="w-11 h-11 rounded-full bg-[#ff3d00] text-white flex items-center justify-center text-base flex-shrink-0 shadow-sm">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div class="text-left leading-tight">
                                <span class="block font-bold text-slate-900 dark:text-white text-xs sm:text-[13px]">Made for</span>
                                <span class="block font-bold text-slate-900 dark:text-white text-xs sm:text-[13px]">Entrepreneurs</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Graphic matching Reference Image 4 -->
                <div class="lg:col-span-6 flex items-center justify-center">
                    <div class="relative w-full">
                        <img src="{{ asset($data['lp_about_image'] ?? '/assets/images/about_right.png') }}" alt="About SaaSReselling" class="w-full h-auto rounded-3xl border border-slate-100 dark:border-slate-800 object-contain">
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION 4: WHITE LABEL SAAS PARTNER (Task 1: Reduced Width Container on Left & Explanation Outside on Right) -->
    <section class="py-12 sm:py-16 lg:py-20 bg-slate-50 dark:bg-[#151c2c]/80 border-t border-slate-200 dark:border-slate-700/80 reveal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                
                <!-- LEFT SIDE: WHITE LABEL SAAS PARTNER CONTAINER (Reduced Width - Task 1) -->
                @php $model = $data['lp_model_cards'][0] ?? null; @endphp
                @if($model)
                <div class="lg:col-span-7 flex">
                    <div class="bg-white dark:bg-[#0B0F19] rounded-3xl p-6 sm:p-8 lg:p-9 shadow-xl border border-slate-200 dark:border-slate-700/90 flex flex-col justify-between hover:shadow-2xl transition-all duration-300 relative overflow-hidden text-left w-full">
                        <div>
                            <!-- Badge -->
                            <span class="inline-block px-3.5 py-1 rounded-full text-xs font-bold mb-3 bg-orange-100 dark:bg-orange-900/30 text-[#ff3d00] border border-orange-200/80 dark:border-indigo-700/50">
                                {{ $model['badge'] ?? 'White Label SaaS' }}
                            </span>
                            
                            <!-- Title -->
                            <h3 class="font-heading font-extrabold text-2xl sm:text-3xl lg:text-[34px] text-slate-900 dark:text-white mb-2.5 tracking-tight text-left leading-[1.18]">
                                {{ $model['title'] ?? 'White Label SaaS Partner' }}
                            </h3>
                            
                            <!-- Subtitle -->
                            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 mb-6 leading-relaxed text-left font-normal">
                                {{ $model['desc'] ?? 'Launch your own branded SaaS platform and sell subscriptions directly to business owners.' }}
                            </p>

                            <!-- Features List (Left) & Screen Preview (Right) -->
                            <div class="grid grid-cols-1 sm:grid-cols-12 gap-5 items-center mb-6 text-left">
                                <div class="sm:col-span-6 space-y-2.5 text-left">
                                    @php
                                        $cardFeatures = (isset($model['features']) && is_array($model['features']) && count($model['features']) > 0) 
                                            ? $model['features'] 
                                            : [
                                                'Launch Your Own SaaS Brand',
                                                'Sell Unlimited Subscriptions',
                                                '3 SaaS Products Included',
                                                'Manage Your Customers & Business',
                                                'Custom Domain & Branding',
                                                'Build Recurring Revenue'
                                            ];
                                        foreach ($cardFeatures as &$featItem) {
                                            if ($featItem === 'SaaS Products Included') {
                                                $featItem = '3 SaaS Products Included';
                                            }
                                        }
                                    @endphp
                                    @foreach($cardFeatures as $f)
                                    <div class="flex items-center space-x-2.5 text-xs sm:text-[13px] font-semibold text-slate-800 dark:text-slate-200 text-left">
                                        <div class="w-4 h-4 rounded-full bg-[#ff3d00] text-white flex items-center justify-center text-[9px] flex-shrink-0 shadow-sm">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <span>{{ $f }}</span>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="sm:col-span-6 mt-4 sm:mt-0">
                                    <div class="rounded-2xl overflow-hidden shadow-lg border border-slate-200 dark:border-slate-700">
                                        <img src="{{ asset($model['image'] ?? '/assets/images/user_dashboard.png') }}" alt="User Dashboard Preview" class="w-full h-auto object-contain">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CTA Button at bottom of card -->
                        <div class="pt-5 border-t border-slate-100 dark:border-slate-800 text-left mt-auto">
                            <a href="{{ $model['cta_url'] ?? 'https://wa.me/916374913298?text=I%20am%20interested%20on%20that%20business' }}" class="btn-gradient inline-flex items-center justify-between w-full px-6 py-3.5 sm:py-4 rounded-2xl font-bold text-xs sm:text-sm text-white transition-all shadow-lg shadow-orange-500/25 group">
                                <span>{{ $model['cta_text'] ?? 'Start with White Label SaaS' }}</span>
                                <i class="fas fa-arrow-right ml-2 text-xs transition-transform group-hover:translate-x-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- RIGHT SIDE: EXPLANATION CONTENT OUTSIDE THE CONTAINER (Task 1) -->
                <div class="lg:col-span-5 flex flex-col justify-between space-y-6 text-left py-2">
                    <div class="space-y-4">
                        <div class="inline-flex items-center space-x-2 px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-orange-100 dark:bg-orange-950/40 text-[#c2410c] dark:text-orange-300 border border-orange-200/80">
                            <i class="fas fa-sparkles text-[10px]"></i>
                            <span>PARTNER OPPORTUNITY</span>
                        </div>

                        <h3 class="font-heading font-extrabold text-2xl sm:text-3xl lg:text-[34px] text-slate-900 dark:text-white leading-[1.2] tracking-tight">
                            Build a High-Margin Software Business Under Your Own Brand
                        </h3>

                        <p class="text-sm sm:text-[15px] text-slate-600 dark:text-slate-300 leading-relaxed font-normal">
                            The White Label SaaS Partner program is engineered specifically for agencies, freelancers, IT consultants, and entrepreneurs looking to generate recurring revenue without product development overhead.
                        </p>

                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed font-normal">
                            You get full turnkey access to 3 ready-to-sell software products—E-Commerce Builder, Website Builder, and WhatsApp Automation. Launch under your custom domain, set your own subscription pricing, onboard unlimited clients, and retain 100% of the customer payments.
                        </p>

                        <!-- 3 Quick Highlight Benefit Cards -->
                        <div class="space-y-3 pt-2">
                            <div class="flex items-start space-x-3.5 p-3.5 rounded-2xl bg-white dark:bg-[#0B0F19] border border-slate-200 dark:border-slate-800 shadow-sm">
                                <div class="w-9 h-9 rounded-xl bg-orange-50 dark:bg-orange-950/40 text-[#ff3d00] flex items-center justify-center text-sm flex-shrink-0 mt-0.5">
                                    <i class="fas fa-rocket"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-xs sm:text-sm text-slate-900 dark:text-white">Turnkey 24-Hour Launch</h4>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 leading-snug">Everything is pre-built, tested, and configured under your logo and custom domain.</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3.5 p-3.5 rounded-2xl bg-white dark:bg-[#0B0F19] border border-slate-200 dark:border-slate-800 shadow-sm">
                                <div class="w-9 h-9 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 flex items-center justify-center text-sm flex-shrink-0 mt-0.5">
                                    <i class="fas fa-coins"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-xs sm:text-sm text-slate-900 dark:text-white">100% Recurring Profit Retention</h4>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 leading-snug">Direct billing integration into your bank account. Keep every dollar you earn.</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3.5 p-3.5 rounded-2xl bg-white dark:bg-[#0B0F19] border border-slate-200 dark:border-slate-800 shadow-sm">
                                <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-950/40 text-blue-600 flex items-center justify-center text-sm flex-shrink-0 mt-0.5">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-xs sm:text-sm text-slate-900 dark:text-white">Zero Maintenance & Full Tech Support</h4>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 leading-snug">We handle security, cloud servers, and code updates silently behind the scenes.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION 4.5: KEY PILLARS FEATURE TICKER STRIP (Task 2: Below White Label SaaS Partner) -->
    <section class="py-5 sm:py-6 bg-[#070B19] border-y border-slate-800/80 relative overflow-hidden">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex items-center justify-between gap-3 sm:gap-4">
                
                <!-- Left Nav Arrow Button -->
                <button type="button" id="featureStripPrev" aria-label="Previous feature" class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-slate-900/90 hover:bg-slate-800 text-slate-300 hover:text-white border border-slate-700/80 flex items-center justify-center flex-shrink-0 transition-all shadow-md active:scale-95 focus:outline-none">
                    <i class="fas fa-chevron-left text-xs"></i>
                </button>

                <!-- 4 Feature Items Container -->
                <div id="featureStripContainer" class="flex-1 flex items-center justify-between gap-4 sm:gap-6 overflow-x-auto scroll-smooth scrollbar-none py-1">
                    
                    <!-- Item 1: Launch in 24 Hours -->
                    <div class="flex-shrink-0 lg:flex-1 flex items-center space-x-3.5 min-w-[240px] px-2">
                        <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-blue-950/80 border border-blue-500/30 flex items-center justify-center text-sky-400 shadow-[0_0_15px_rgba(56,189,248,0.15)] flex-shrink-0">
                            <i class="fas fa-bolt text-base sm:text-lg"></i>
                        </div>
                        <div class="text-left">
                            <h4 class="text-white font-bold text-sm sm:text-[15px] font-heading tracking-tight leading-tight">Launch in 24 Hours</h4>
                            <p class="text-slate-400 text-xs sm:text-[13px] mt-0.5 font-medium leading-snug">Start your software business, faster.</p>
                        </div>
                    </div>

                    <!-- Item 2: 100% White-Label -->
                    <div class="flex-shrink-0 lg:flex-1 flex items-center space-x-3.5 min-w-[250px] px-2">
                        <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-purple-950/80 border border-purple-500/30 flex items-center justify-center text-purple-400 shadow-[0_0_15px_rgba(192,132,252,0.15)] flex-shrink-0">
                            <i class="fas fa-shield-halved text-base sm:text-lg"></i>
                        </div>
                        <div class="text-left">
                            <h4 class="text-white font-bold text-sm sm:text-[15px] font-heading tracking-tight leading-tight">100% White-Label</h4>
                            <p class="text-slate-400 text-xs sm:text-[13px] mt-0.5 font-medium leading-snug">Your brand. Your domain. Your business.</p>
                        </div>
                    </div>

                    <!-- Item 3: Dedicated Support -->
                    <div class="flex-shrink-0 lg:flex-1 flex items-center space-x-3.5 min-w-[240px] px-2">
                        <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-cyan-950/80 border border-cyan-500/30 flex items-center justify-center text-cyan-400 shadow-[0_0_15px_rgba(34,211,238,0.15)] flex-shrink-0">
                            <i class="fas fa-life-ring text-base sm:text-lg"></i>
                        </div>
                        <div class="text-left">
                            <h4 class="text-white font-bold text-sm sm:text-[15px] font-heading tracking-tight leading-tight">Dedicated Support</h4>
                            <p class="text-slate-400 text-xs sm:text-[13px] mt-0.5 font-medium leading-snug">We're with you at every step.</p>
                        </div>
                    </div>

                    <!-- Item 4: Scalable Technology -->
                    <div class="flex-shrink-0 lg:flex-1 flex items-center space-x-3.5 min-w-[240px] px-2">
                        <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-indigo-950/80 border border-indigo-500/30 flex items-center justify-center text-indigo-400 shadow-[0_0_15px_rgba(129,140,248,0.15)] flex-shrink-0">
                            <i class="fas fa-chart-line text-base sm:text-lg"></i>
                        </div>
                        <div class="text-left">
                            <h4 class="text-white font-bold text-sm sm:text-[15px] font-heading tracking-tight leading-tight">Scalable Technology</h4>
                            <p class="text-slate-400 text-xs sm:text-[13px] mt-0.5 font-medium leading-snug">Built for long-term growth.</p>
                        </div>
                    </div>

                </div>

                <!-- Right Nav Arrow Button -->
                <button type="button" id="featureStripNext" aria-label="Next feature" class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-slate-900/90 hover:bg-slate-800 text-slate-300 hover:text-white border border-slate-700/80 flex items-center justify-center flex-shrink-0 transition-all shadow-md active:scale-95 focus:outline-none">
                    <i class="fas fa-chevron-right text-xs"></i>
                </button>

            </div>
        </div>
    </section>

    <!-- SECTION 5: OUR ADVANCED SAAS PRODUCTS (Task 2: Headline Spacing, Blinking Animated Badge & Highlighted Orange Trust Labels) -->
    <section id="products" class="py-12 lg:py-20 bg-white dark:bg-[#0B0F19] reveal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
                <!-- Top Badge (Task 2: Attractive, Blinking Pulse & Gradient Font) -->
                <div class="inline-flex items-center space-x-2 px-5 py-2 rounded-full text-xs sm:text-sm font-extrabold uppercase tracking-wider bg-gradient-to-r from-orange-500/15 via-[#ff3d00]/10 to-amber-500/15 text-[#ff3d00] dark:text-orange-400 border border-[#ff3d00]/40 shadow-sm relative overflow-hidden group animate-pulse mx-auto">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#ff3d00] animate-ping"></span>
                    <span class="bg-gradient-to-r from-[#ff3d00] via-orange-500 to-amber-500 bg-clip-text text-transparent font-black tracking-wide">
                        {{ (!empty($data['lp_products_tag']) && $data['lp_products_tag'] !== 'OUR PRODUCTS') ? $data['lp_products_tag'] : 'Our Advanced SAAS Products' }}
                    </span>
                </div>
                
                <!-- Main Heading (Task 2: Added Bottom Gap between "Everything You Need to Build &" and "Scale Your SaaS Business") -->
                <h2 class="font-heading font-extrabold text-3xl sm:text-4xl lg:text-[46px] xl:text-[52px] tracking-tight mt-5 mb-5 sm:mb-6 leading-[1.25]">
                    <span class="block text-slate-900 dark:text-white mb-2 sm:mb-2.5">Everything You Need to Build &</span>
                    <span class="block bg-gradient-to-r from-[#ff3d00] via-orange-500 to-amber-500 bg-clip-text text-transparent">Scale Your SaaS Business</span>
                </h2>
                
                <!-- Description -->
                <p class="text-sm sm:text-base text-slate-600 dark:text-slate-300 mt-4 max-w-2xl mx-auto leading-relaxed font-normal">
                    {{ (!empty($data['lp_products_desc']) && stripos($data['lp_products_desc'], 'Nooryak') === false && stripos($data['lp_products_desc'], '5 powerful') === false)
                        ? $data['lp_products_desc']
                        : 'SaaSReselling combines 3 powerful White Label SaaS products, a unified dashboard, Single Sign-On (SSO), custom branding, and enterprise-grade management tools into one complete platform.' }}
                </p>
            </div>

            <!-- Products Grid: 3-Column Per Row Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-stretch">

                <!-- CARD 1: LAUNCHSHOP ECOM BUILDER -->
                <div class="bg-white dark:bg-[#0B0F19] rounded-3xl border border-orange-200/90 shadow-xl hover:shadow-2xl transition-all duration-300 overflow-hidden flex flex-col justify-between p-6 sm:p-7 group relative">
                    <div>
                        <!-- Brand Logo -->
                        <div class="flex items-center justify-between mb-4">
                            <img src="{{ asset('/assets/landing_page/ecom_logo.png') }}" alt="Ecom Builder Logo" class="h-10 w-auto object-contain">
                            <span class="text-[11px] font-extrabold tracking-widest text-[#ff3d00] uppercase bg-orange-50 dark:bg-[#1e293b] px-2.5 py-1 rounded-lg border border-orange-200/60 dark:border-slate-700">STORE BUILDER</span>
                        </div>

                        <h3 class="font-heading font-extrabold text-xl sm:text-2xl text-slate-900 dark:text-white mb-2.5 leading-tight">Complete E-Commerce Solution</h3>
                        
                        <!-- Product Description -->
                        <p class="text-sm sm:text-[15px] text-slate-600 dark:text-slate-300 leading-relaxed font-normal mb-1">
                            Launch your online store, manage products, payments, and customers — all in one place.
                        </p>
                        
                        <!-- Product Image Mockup -->
                        <div class="my-5 relative overflow-hidden rounded-2xl">
                            <img src="{{ asset('/assets/landing_page/ecombuilder_image.png') }}" alt="Launchshop E-Commerce Solution" class="w-full h-48 sm:h-52 object-cover object-top rounded-2xl shadow-md border border-orange-200/60 dark:border-slate-700 transform group-hover:scale-[1.03] transition-transform duration-300">
                        </div>

                        <!-- Included Features Header -->
                        <p class="text-xs sm:text-[13px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3.5">INCLUDED FEATURES:</p>

                        <!-- Feature List -->
                        <ul class="space-y-3 mb-6 text-xs sm:text-sm text-slate-700 dark:text-slate-200 font-semibold">
                            <li class="flex items-center space-x-3">
                                <div class="w-5 h-5 rounded-full bg-[#ff3d00] text-white flex items-center justify-center text-[10px] flex-shrink-0 shadow-sm"><i class="fas fa-check"></i></div>
                                <span>1 Online Store (Product Module)</span>
                            </li>
                            <li class="flex items-center space-x-3">
                                <div class="w-5 h-5 rounded-full bg-[#ff3d00] text-white flex items-center justify-center text-[10px] flex-shrink-0 shadow-sm"><i class="fas fa-check"></i></div>
                                <span>Up to 1,000 Orders / Customers</span>
                            </li>
                            <li class="flex items-center space-x-3">
                                <div class="w-5 h-5 rounded-full bg-[#ff3d00] text-white flex items-center justify-center text-[10px] flex-shrink-0 shadow-sm"><i class="fas fa-check"></i></div>
                                <span>Inventory & Order Management</span>
                            </li>
                            <li class="flex items-center space-x-3">
                                <div class="w-5 h-5 rounded-full bg-[#ff3d00] text-white flex items-center justify-center text-[10px] flex-shrink-0 shadow-sm"><i class="fas fa-check"></i></div>
                                <span>Basic Analytics & Reports</span>
                            </li>
                            <li class="flex items-center space-x-3">
                                <div class="w-5 h-5 rounded-full bg-[#ff3d00] text-white flex items-center justify-center text-[10px] flex-shrink-0 shadow-sm"><i class="fas fa-check"></i></div>
                                <span>Custom Domain Setup</span>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <a href="https://launchshop.saasreselling.com" target="_blank" class="btn-gradient w-full py-3.5 sm:py-4 px-6 rounded-2xl font-bold text-xs sm:text-sm text-white text-center shadow-lg shadow-orange-500/25 flex items-center justify-center space-x-2 hover:scale-[1.02] transition-transform">
                            <span>Explore Launchshop</span>
                            <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                        <!-- Task 2: Highlighted Orange Gradient Trust Label (Increased Font Size) -->
                        <div class="mt-3.5 text-center">
                            <span class="inline-flex items-center justify-center px-4 py-1.5 rounded-full text-xs sm:text-[13px] font-bold bg-gradient-to-r from-orange-500/15 via-[#ff3d00]/10 to-orange-500/15 border border-[#ff3d00]/30 text-[#ff3d00] dark:text-orange-400 shadow-sm">
                                <i class="fas fa-shield-check text-[11px] mr-1.5"></i>
                                Trusted by Ecommerce Brands
                            </span>
                        </div>
                    </div>
                </div>

                <!-- CARD 2: WEBSITE BUILDER -->
                <div class="bg-white dark:bg-[#0B0F19] rounded-3xl border-2 border-blue-400/80 shadow-xl hover:shadow-2xl transition-all duration-300 overflow-hidden flex flex-col justify-between p-6 sm:p-7 group relative">
                    <div>
                        <!-- Brand Logo -->
                        <div class="flex items-center justify-between mb-4">
                            <img src="{{ asset('/assets/landing_page/websitebuilder_logo.png') }}" alt="Website Builder Logo" class="h-10 w-auto object-contain">
                            <span class="text-[11px] font-extrabold tracking-widest text-blue-600 uppercase bg-blue-50 dark:bg-[#1e293b] px-2.5 py-1 rounded-lg border border-blue-200/60 dark:border-slate-700">WEBSITE BUILDER</span>
                        </div>

                        <h3 class="font-heading font-extrabold text-xl sm:text-2xl text-slate-900 dark:text-white mb-2.5 leading-tight">Professional Website Solution</h3>
                        
                        <!-- Product Description -->
                        <p class="text-sm sm:text-[15px] text-slate-600 dark:text-slate-300 leading-relaxed font-normal mb-1">
                            Create stunning websites with ease using our drag-and-drop website builder with zero coding required.
                        </p>
                        
                        <!-- Product Image Mockup -->
                        <div class="my-5 relative overflow-hidden rounded-2xl">
                            <img src="{{ asset('/assets/landing_page/websitebuilder_image.png') }}" alt="Website Builder Solution" class="w-full h-48 sm:h-52 object-cover object-top rounded-2xl shadow-md border border-blue-200/60 dark:border-slate-700 transform group-hover:scale-[1.03] transition-transform duration-300">
                        </div>

                        <!-- Included Features Header -->
                        <p class="text-xs sm:text-[13px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3.5">INCLUDED FEATURES:</p>

                        <!-- Feature List -->
                        <ul class="space-y-3 mb-6 text-xs sm:text-sm text-slate-700 dark:text-slate-200 font-semibold">
                            <li class="flex items-center space-x-3">
                                <div class="w-5 h-5 rounded-full bg-blue-600 text-white flex items-center justify-center text-[10px] flex-shrink-0 shadow-sm"><i class="fas fa-check"></i></div>
                                <span>Unlimited Pages & Custom Designs</span>
                            </li>
                            <li class="flex items-center space-x-3">
                                <div class="w-5 h-5 rounded-full bg-blue-600 text-white flex items-center justify-center text-[10px] flex-shrink-0 shadow-sm"><i class="fas fa-check"></i></div>
                                <span>Drag & Drop Visual Builder</span>
                            </li>
                            <li class="flex items-center space-x-3">
                                <div class="w-5 h-5 rounded-full bg-blue-600 text-white flex items-center justify-center text-[10px] flex-shrink-0 shadow-sm"><i class="fas fa-check"></i></div>
                                <span>Custom Domain & Free SSL</span>
                            </li>
                            <li class="flex items-center space-x-3">
                                <div class="w-5 h-5 rounded-full bg-blue-600 text-white flex items-center justify-center text-[10px] flex-shrink-0 shadow-sm"><i class="fas fa-check"></i></div>
                                <span>SEO Tools & Analytics Integration</span>
                            </li>
                            <li class="flex items-center space-x-3">
                                <div class="w-5 h-5 rounded-full bg-blue-600 text-white flex items-center justify-center text-[10px] flex-shrink-0 shadow-sm"><i class="fas fa-check"></i></div>
                                <span>AI Templates & Interactive Widgets</span>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <a href="https://websitebuilder.saasreselling.com" target="_blank" class="w-full bg-blue-600 hover:bg-blue-700 py-3.5 sm:py-4 px-6 rounded-2xl font-bold text-xs sm:text-sm text-white text-center shadow-lg shadow-blue-500/25 flex items-center justify-center space-x-2 hover:scale-[1.02] transition-transform">
                            <span>Explore Website Builder</span>
                            <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                        <!-- Task 2: Highlighted Orange Gradient Trust Label -->
                        <div class="mt-3.5 text-center">
                            <span class="inline-flex items-center justify-center px-4 py-1.5 rounded-full text-xs sm:text-[13px] font-bold bg-gradient-to-r from-orange-500/15 via-[#ff3d00]/10 to-orange-500/15 border border-[#ff3d00]/30 text-[#ff3d00] dark:text-orange-400 shadow-sm">
                                <i class="fas fa-shield-check text-[11px] mr-1.5"></i>
                                Trusted by Agencies & Creators
                            </span>
                        </div>
                    </div>
                </div>

                <!-- CARD 3: WHATSAPP AUTOMATION TOOL (ACTIVE) -->
                <div class="bg-white dark:bg-[#0B0F19] rounded-3xl border-2 border-emerald-500/80 shadow-xl hover:shadow-2xl transition-all duration-300 overflow-hidden flex flex-col justify-between p-6 sm:p-7 group relative">
                    <div>
                        <!-- Brand Logo -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-2">
                                <img src="{{ asset('/assets/landing_page/whatsapp_logo.png') }}" alt="WhatsApp Automation Logo" class="h-10 w-auto object-contain" onerror="this.onerror=null; this.src='https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg';">
                            </div>
                            <span class="text-[11px] font-extrabold tracking-widest text-emerald-600 uppercase bg-emerald-50 dark:bg-[#1e293b] px-2.5 py-1 rounded-lg border border-emerald-200/60 dark:border-slate-700">WHATSAPPBULK</span>
                        </div>

                        <h3 class="font-heading font-extrabold text-xl sm:text-2xl text-slate-900 dark:text-white mb-2.5 leading-tight">Whatsappbulk Marketing & Automation</h3>
                        
                        <!-- Product Description -->
                        <p class="text-sm sm:text-[15px] text-slate-600 dark:text-slate-300 leading-relaxed font-normal mb-1">
                            Automate bulk messaging campaigns, AI auto-replies, catalog sharing, and customer engagement directly on WhatsApp.
                        </p>
                        
                        <!-- Product Image Mockup -->
                        <div class="my-5 relative overflow-hidden rounded-2xl">
                            <img src="{{ asset('/assets/landing_page/whatsapp_image.png') }}" alt="WhatsApp Automation Solution" class="w-full h-48 sm:h-52 object-cover object-top rounded-2xl shadow-md border border-emerald-200/60 dark:border-slate-700 transform group-hover:scale-[1.03] transition-transform duration-300">
                        </div>

                        <!-- Included Features Header -->
                        <p class="text-xs sm:text-[13px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3.5">INCLUDED FEATURES:</p>

                        <!-- Feature List -->
                        <ul class="space-y-3 mb-6 text-xs sm:text-sm text-slate-700 dark:text-slate-200 font-semibold">
                            <li class="flex items-center space-x-3">
                                <div class="w-5 h-5 rounded-full bg-emerald-600 text-white flex items-center justify-center text-[10px] flex-shrink-0 shadow-sm"><i class="fas fa-check"></i></div>
                                <span>Bulk WhatsApp Campaigns & Broadcasts</span>
                            </li>
                            <li class="flex items-center space-x-3">
                                <div class="w-5 h-5 rounded-full bg-emerald-600 text-white flex items-center justify-center text-[10px] flex-shrink-0 shadow-sm"><i class="fas fa-check"></i></div>
                                <span>AI Smart Auto-Reply & Keyword Rules</span>
                            </li>
                            <li class="flex items-center space-x-3">
                                <div class="w-5 h-5 rounded-full bg-emerald-600 text-white flex items-center justify-center text-[10px] flex-shrink-0 shadow-sm"><i class="fas fa-check"></i></div>
                                <span>Multi-Device & QR Code Connection</span>
                            </li>
                            <li class="flex items-center space-x-3">
                                <div class="w-5 h-5 rounded-full bg-emerald-600 text-white flex items-center justify-center text-[10px] flex-shrink-0 shadow-sm"><i class="fas fa-check"></i></div>
                                <span>Contact Manager & Group Export</span>
                            </li>
                            <li class="flex items-center space-x-3">
                                <div class="w-5 h-5 rounded-full bg-emerald-600 text-white flex items-center justify-center text-[10px] flex-shrink-0 shadow-sm"><i class="fas fa-check"></i></div>
                                <span>Real-Time Analytics & Delivery Reports</span>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <a href="https://whatsappbulk.saasreselling.com" target="_blank" class="w-full bg-emerald-600 hover:bg-emerald-700 py-3.5 sm:py-4 px-6 rounded-2xl font-bold text-xs sm:text-sm text-white text-center shadow-lg shadow-emerald-500/25 flex items-center justify-center space-x-2 hover:scale-[1.02] transition-transform">
                            <span>Explore Whatsappbulk</span>
                            <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                        <!-- Task 2: Highlighted Orange Gradient Trust Label -->
                        <div class="mt-3.5 text-center">
                            <span class="inline-flex items-center justify-center px-4 py-1.5 rounded-full text-xs sm:text-[13px] font-bold bg-gradient-to-r from-orange-500/15 via-[#ff3d00]/10 to-orange-500/15 border border-[#ff3d00]/30 text-[#ff3d00] dark:text-orange-400 shadow-sm">
                                <i class="fas fa-shield-check text-[11px] mr-1.5"></i>
                                Trusted by Modern Businesses
                            </span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- SECTION 6: HOW SAASRESELLING WORKS (Task 3: Connected Zip Line & Attractive Glowing Badge) -->
    <section class="py-12 lg:py-18 bg-slate-50 dark:bg-[#151c2c]/60 border-t border-slate-200 dark:border-slate-700/70 reveal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            
            <!-- Attractive Blinking Badge (Task 3) -->
            <div class="inline-flex items-center space-x-2 px-5 py-2 rounded-full text-xs sm:text-sm font-extrabold uppercase tracking-wider bg-gradient-to-r from-orange-500/15 via-[#ff3d00]/10 to-amber-500/15 text-[#ff3d00] dark:text-orange-400 border border-[#ff3d00]/40 shadow-sm relative overflow-hidden group animate-pulse mx-auto mb-4">
                <span class="w-2.5 h-2.5 rounded-full bg-[#ff3d00] animate-ping"></span>
                <span class="bg-gradient-to-r from-[#ff3d00] via-orange-500 to-amber-500 bg-clip-text text-transparent font-black tracking-wide">
                    {{ (!empty($data['lp_how_works_tag']) && $data['lp_how_works_tag'] !== 'HOW NOORYAK WORKS') ? $data['lp_how_works_tag'] : 'HOW SAASRESELLING WORKS' }}
                </span>
            </div>
            
            <h2 class="font-heading font-extrabold text-2xl sm:text-4xl lg:text-[42px] text-slate-900 dark:text-white tracking-tight mb-14">
                {{ $data['lp_how_works_title'] }}
            </h2>

            @if(is_array($data['lp_how_works_steps']))
            <div class="relative flex flex-col lg:flex-row items-center justify-between gap-6 lg:gap-4 max-w-6xl mx-auto">
                
                <!-- Continuous Zip Line Track behind the step icons (Desktop: Horizontal, Mobile: Vertical) -->
                <div class="hidden lg:block absolute top-9 left-[10%] right-[10%] border-t-2 border-dashed border-[#ff3d00] dark:border-orange-500 z-0 opacity-80"></div>
                <div class="lg:hidden absolute top-12 bottom-12 left-1/2 -translate-x-1/2 border-l-2 border-dashed border-[#ff3d00] dark:border-orange-500 z-0 opacity-80"></div>

                @foreach($data['lp_how_works_steps'] as $index => $step)
                    
                    <!-- Step Item with zip line connector -->
                    <div class="step-card flex-1 flex flex-col items-center text-center group z-10 px-2 transition-all duration-700 ease-out transform opacity-0 translate-y-8 relative w-full sm:w-auto">
                        <!-- Icon Circle with ring mask over zip line -->
                        <div class="w-18 h-18 sm:w-20 sm:h-20 rounded-full btn-gradient flex items-center justify-center text-white text-xl sm:text-2xl shadow-xl shadow-orange-500/25 mb-4 group-hover:scale-110 transition-transform duration-300 ring-8 ring-slate-50 dark:ring-[#151c2c] z-10">
                            <i class="{{ $step['icon'] ?? 'fas fa-check' }}"></i>
                        </div>
                        
                        <!-- Step Label -->
                        <span class="text-xs font-extrabold text-[#ff3d00] uppercase tracking-wider mb-1.5">Step {{ $step['step'] ?? ($index + 1) }}</span>
                        
                        <!-- Title -->
                        <h4 class="font-heading font-extrabold text-base sm:text-lg text-slate-900 dark:text-white mb-2 leading-snug">{{ $step['title'] ?? '' }}</h4>
                        
                        <!-- Description -->
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed max-w-[240px]">{{ $step['desc'] ?? '' }}</p>
                    </div>

                    <!-- Subtle arrow indicator for desktop between steps -->
                    @if(!$loop->last)
                    <div class="hidden lg:flex items-center justify-center text-[#ff3d00] font-bold -mt-16 text-sm z-10">
                        <div class="w-6 h-6 rounded-full bg-white dark:bg-slate-900 border border-orange-200 dark:border-slate-700 flex items-center justify-center shadow-sm">
                            <i class="fas fa-chevron-right text-[10px]"></i>
                        </div>
                    </div>
                    @endif

                @endforeach
            </div>
            @endif

        </div>
    </section>

    <!-- SECTION 7: WHY CHOOSE & REVENUE CALCULATOR (Task 4: Reference Image 5 Layout & Default 1 Customer) -->
    <section class="py-12 lg:py-18 bg-white dark:bg-[#0B0F19] reveal border-t border-slate-200 dark:border-slate-700/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-stretch">
                
                <!-- Left: Why Choose Us (6 cols) -->
                <div class="lg:col-span-6 flex flex-col justify-between">
                    <div>
                        <!-- Attractive Badge for WHY CHOOSE (Task 3) -->
                        <div class="inline-flex items-center space-x-2 px-5 py-2 rounded-full text-xs sm:text-sm font-extrabold uppercase tracking-wider bg-gradient-to-r from-orange-500/15 via-[#ff3d00]/10 to-amber-500/15 text-[#ff3d00] dark:text-orange-400 border border-[#ff3d00]/40 shadow-sm relative overflow-hidden group animate-pulse mb-4 text-left">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#ff3d00] animate-ping"></span>
                            <span class="bg-gradient-to-r from-[#ff3d00] via-orange-500 to-amber-500 bg-clip-text text-transparent font-black tracking-wide">
                                {{ (!empty($data['lp_why_choose_tag']) && $data['lp_why_choose_tag'] !== 'WHY CHOOSE NOORYAK?') ? $data['lp_why_choose_tag'] : 'WHY CHOOSE SAASRESELLING?' }}
                            </span>
                        </div>

                        <h2 class="font-heading font-extrabold text-2xl sm:text-4xl text-slate-900 dark:text-white tracking-tight mb-8 text-left">
                            {{ $data['lp_why_choose_title'] }}
                        </h2>

                        <!-- 6 Why Choose Feature Cards matching Reference Image 5 Style -->
                        @if(is_array($data['lp_why_choose_items']))
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @php
                                $colorStyles = [
                                    ['bg' => 'bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400', 'icon' => 'fas fa-shield-alt'],
                                    ['bg' => 'bg-orange-50 text-orange-600 dark:bg-orange-950/40 dark:text-orange-400', 'icon' => 'fas fa-bolt'],
                                    ['bg' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400', 'icon' => 'fas fa-coins'],
                                    ['bg' => 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400', 'icon' => 'fas fa-tag'],
                                    ['bg' => 'bg-sky-50 text-sky-600 dark:bg-sky-950/40 dark:text-sky-400', 'icon' => 'fas fa-layer-group'],
                                    ['bg' => 'bg-purple-50 text-purple-600 dark:bg-purple-950/40 dark:text-purple-400', 'icon' => 'fas fa-life-ring']
                                ];
                            @endphp
                            @foreach($data['lp_why_choose_items'] as $index => $item)
                            @php
                                $style = $colorStyles[$index % count($colorStyles)];
                            @endphp
                            <div class="bg-white dark:bg-[#151c2c] p-4 sm:p-5 rounded-2xl border border-slate-200 dark:border-slate-700/80 shadow-sm hover:shadow-md transition-all flex flex-col items-start text-left">
                                <div class="w-9 h-9 rounded-xl {{ $style['bg'] }} flex items-center justify-center text-sm mb-3">
                                    <i class="{{ $item['icon'] ?? $style['icon'] }}"></i>
                                </div>
                                <h4 class="font-heading font-extrabold text-sm sm:text-base text-slate-900 dark:text-white mb-1.5 leading-snug">{{ $item['title'] ?? '' }}</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed font-normal">{{ $item['desc'] ?? '' }}</p>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Right: Revenue Opportunity Calculator (Task 2 & 4: 1 Customer = ₹999, Expanded White Box & Zero Dead Whitespace) -->
                <div class="lg:col-span-6 flex">
                    <div class="relative w-full text-white rounded-3xl p-6 sm:p-8 lg:p-9 shadow-2xl border border-slate-800 flex flex-col justify-between bg-[#080E24] overflow-hidden min-h-[520px]">
                        
                        <!-- Subtle ambient glow in background -->
                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/10 via-transparent to-orange-500/10 pointer-events-none"></div>

                        <div class="relative z-10 flex-1 flex flex-col justify-between">
                            <!-- Calculator Header -->
                            <div class="flex items-center justify-between pb-3.5 mb-4 border-b border-slate-800">
                                <div>
                                    <h3 class="font-heading font-extrabold text-xl sm:text-2xl text-white">{{ $data['lp_rev_calc_title'] ?? 'Revenue Opportunity Calculator' }}</h3>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $data['lp_rev_calc_subtitle'] ?? 'See how much you can earn every month' }}</p>
                                </div>
                                <div class="w-10 h-10 rounded-xl bg-orange-500/20 border border-orange-500/30 text-orange-400 flex items-center justify-center text-lg shadow-sm flex-shrink-0">
                                    <i class="fas fa-calculator"></i>
                                </div>
                            </div>

                            <!-- Interactive Slider Row: Starts at 1 -->
                            <div class="space-y-2 mb-4">
                                <div class="flex justify-between items-center text-xs sm:text-sm">
                                    <span class="text-slate-200 font-bold">Active Customers:</span>
                                    <div class="bg-[#11193d] border border-slate-700/80 px-4 py-1 rounded-xl text-center shadow-inner min-w-[50px]">
                                        <span id="customerCountDisplay" class="font-heading font-black text-white text-base sm:text-lg">1</span>
                                    </div>
                                </div>
                                
                                <input type="range" id="customerSlider" min="1" max="500" step="1" value="1" class="w-full accent-[#ff3d00] cursor-pointer h-2.5 bg-slate-800 rounded-lg">
                            </div>

                            <!-- Calculation Result Box: Expanded White Container Filling Vertical Space (Task 2) -->
                            <div class="bg-white rounded-2xl p-6 sm:p-7 border border-slate-200 shadow-xl my-4 text-left flex-1 flex flex-col justify-center">
                                <div class="grid grid-cols-1 md:grid-cols-12 gap-5 items-center">
                                    
                                    <!-- Left Column: Revenue Amount -->
                                    <div class="md:col-span-7 border-b md:border-b-0 md:border-r border-slate-100 pb-4 md:pb-0 md:pr-4">
                                        <p class="text-xs sm:text-sm font-bold text-blue-600 uppercase tracking-wide mb-1.5">
                                            Estimated Monthly Revenue
                                        </p>
                                        <div id="monthlyRevDisplay" class="font-heading font-black text-4xl sm:text-5xl lg:text-[46px] text-slate-900 leading-none">
                                            ₹999 <span class="text-sm sm:text-base font-semibold text-slate-400">/month</span>
                                        </div>
                                        <p class="text-xs sm:text-sm text-slate-800 font-bold mt-2.5">
                                            <span id="calcSubtextCount">1</span> Customers × ₹999
                                        </p>
                                        <p class="text-[11px] text-slate-400 mt-0.5 font-normal">
                                            *Average price per customer/month
                                        </p>
                                    </div>

                                    <!-- Right Column: 4 Feature Checkmark Bullets -->
                                    <div class="md:col-span-5 space-y-3">
                                        <div class="flex items-center space-x-2.5 text-xs sm:text-[13px] font-bold text-slate-800">
                                            <i class="fas fa-check-circle text-blue-600 text-base flex-shrink-0"></i>
                                            <span>Low Investment</span>
                                        </div>
                                        <div class="flex items-center space-x-2.5 text-xs sm:text-[13px] font-bold text-slate-800">
                                            <i class="fas fa-check-circle text-blue-600 text-base flex-shrink-0"></i>
                                            <span>High Margin</span>
                                        </div>
                                        <div class="flex items-center space-x-2.5 text-xs sm:text-[13px] font-bold text-slate-800">
                                            <i class="fas fa-check-circle text-blue-600 text-base flex-shrink-0"></i>
                                            <span>Recurring Income</span>
                                        </div>
                                        <div class="flex items-center space-x-2.5 text-xs sm:text-[13px] font-bold text-slate-800">
                                            <i class="fas fa-check-circle text-blue-600 text-base flex-shrink-0"></i>
                                            <span>Unlimited Potential</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- CTA Button at bottom of Calculator (No empty dead space above) -->
                            <div class="pt-1">
                                <a href="https://wa.me/916374913298?text=Hi%20SaaSReselling,%20I%27m%20interested%20in%20the%20Revenue%20Opportunity" target="_blank" class="btn-gradient w-full py-4 px-6 rounded-2xl text-white font-bold text-sm sm:text-base shadow-xl shadow-orange-500/25 flex items-center justify-center space-x-2.5 hover:scale-[1.01] transition-transform">
                                    <span>Start Building Your Revenue</span>
                                    <i class="fas fa-arrow-right text-xs sm:text-sm"></i>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION 8: PRICING (Task 3: Pixel-Perfect Match with Reference Image 3 & Full Dashboard Integration) -->
    <section id="pricing" class="py-14 lg:py-20 bg-slate-50 dark:bg-[#151c2c]/80 border-t border-slate-200 dark:border-slate-700/80 relative">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <!-- Section Header -->
            <div class="text-center max-w-2xl mx-auto mb-10">
                <span class="inline-block px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wide bg-[#fff2ec] dark:bg-orange-950/40 text-[#c2410c] dark:text-orange-300 border border-[#fed7aa]/60 mx-auto mb-3">
                    {{ $data['lp_pricing_tag'] ?? 'PRICING' }}
                </span>
                <h2 class="font-heading font-extrabold text-3xl sm:text-4xl lg:text-[42px] text-slate-900 dark:text-white tracking-tight mt-2">
                    {{ $data['lp_pricing_title'] ?? 'Transparent Pricing for Every Stage' }}
                </h2>
            </div>

            <!-- Monthly / Yearly Toggle with Save 20% curved badge -->
            <div class="flex flex-col items-center justify-center mb-10">
                <div class="flex items-center space-x-4">
                    <div class="bg-white dark:bg-[#0B0F19] p-1.5 rounded-full border border-slate-200 dark:border-slate-700 shadow-sm flex items-center relative">
                        <div id="pricingToggleBg" class="absolute left-1.5 top-1.5 bottom-1.5 w-[100px] btn-gradient rounded-full transition-all duration-300 ease-in-out"></div>
                        <button type="button" id="toggleMonthlyBtn" class="relative z-10 w-[100px] py-2 text-xs sm:text-sm font-bold text-white transition-colors duration-300" onclick="window.setPricingPlan('monthly')">Monthly</button>
                        <button type="button" id="toggleYearlyBtn" class="relative z-10 w-[100px] py-2 text-xs sm:text-sm font-bold text-slate-600 dark:text-slate-300 transition-colors duration-300" onclick="window.setPricingPlan('yearly')">Yearly</button>
                    </div>
                    <div class="text-xs font-extrabold text-[#ff3d00] flex items-center gap-1">
                        <i class="fas fa-reply fa-flip-vertical text-sm -mt-1"></i>
                        <span>Save 20%</span>
                    </div>
                </div>
                
                <div id="pricingSubtextContainer" class="mt-4 bg-orange-50 dark:bg-orange-950/30 px-5 py-2 rounded-full border border-orange-200/80 dark:border-orange-800/50 shadow-sm flex items-center space-x-2 transition-all">
                    <i id="pricingSubtextIcon" class="far fa-calendar-alt text-[#ff3d00] text-xs"></i>
                    <span id="pricingSubtext" class="text-xs font-bold text-[#c2410c] dark:text-orange-300">Monthly Plan Selected — Switch to Yearly and save 20%</span>
                </div>
            </div>

            <!-- Main High-Converting Pricing Card (Reference Image 3) -->
            <div class="bg-white dark:bg-[#0B0F19] rounded-[2.5rem] p-6 sm:p-9 lg:p-12 shadow-2xl border border-slate-200/90 dark:border-slate-800 relative overflow-hidden">
                
                <!-- Upper Split: Plan Info (Left) & Screen Preview (Right) -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
                    
                    <!-- Left Column: Core Pricing Info -->
                    <div class="lg:col-span-7 text-left space-y-4">
                        <!-- Top Tag -->
                        <div class="inline-flex items-center space-x-2 bg-orange-50 dark:bg-orange-950/40 text-[#c2410c] dark:text-orange-300 text-xs font-extrabold px-3.5 py-1.5 rounded-full uppercase tracking-wider border border-orange-200/80 dark:border-orange-900/60">
                            <i class="fas fa-user-friends text-xs"></i>
                            <span>{{ $data['lp_pricing_badge'] ?? 'FOR AGENCIES & FREELANCERS' }}</span>
                        </div>

                        <!-- Title -->
                        <h3 class="font-heading font-extrabold text-3xl sm:text-4xl text-slate-900 dark:text-white tracking-tight leading-tight">
                            {{ $data['lp_pricing_name'] ?? 'White-Label Panel' }}
                        </h3>

                        <!-- Subtitle -->
                        <h4 class="font-bold text-base sm:text-lg text-slate-800 dark:text-slate-200 leading-snug">
                            {{ $data['lp_pricing_subtitle'] ?? 'One platform. Your brand. Unlimited growth.' }}
                        </h4>

                        <!-- Description -->
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed font-normal">
                            {{ $data['lp_pricing_desc'] ?? 'Get 3 core SaaS products, unlimited client subscriptions, and complete white-label control under your own domain. Best for solo marketers and growth agencies.' }}
                        </p>

                        <!-- Price Box -->
                        <div class="pt-2 pb-1 flex items-baseline space-x-2">
                            <span id="priceMainAmount" class="font-heading font-black text-4xl sm:text-5xl lg:text-[54px] text-slate-900 dark:text-white tracking-tight">₹{{ $data['lp_pricing_monthly'] ?? '999' }}</span>
                            <span id="priceMainPeriod" class="text-sm sm:text-base font-semibold text-slate-500 dark:text-slate-400">/ month</span>
                        </div>

                        <!-- CTA Button -->
                        <div class="pt-2">
                            <a href="{{ $data['lp_pricing_cta_url'] ?? 'https://wa.me/916374913298?text=I%20am%20interested%20in%20White-Label%20Panel' }}" class="btn-gradient inline-flex items-center justify-center w-full py-4 rounded-2xl font-bold text-sm sm:text-base text-white shadow-xl shadow-orange-500/30 hover:scale-[1.01] transition-transform group text-center">
                                <span>{{ $data['lp_pricing_cta_text'] ?? 'Get Started' }}</span>
                                <i class="fas fa-arrow-right ml-2 text-sm transition-transform group-hover:translate-x-1"></i>
                            </a>
                        </div>

                        <!-- 3 Trust Badges below button -->
                        <div class="grid grid-cols-3 gap-2 pt-4 border-t border-slate-100 dark:border-slate-800 text-center">
                            @php
                                $trustBadges = (isset($data['lp_pricing_trust_badges']) && is_array($data['lp_pricing_trust_badges']) && count($data['lp_pricing_trust_badges']) > 0)
                                    ? $data['lp_pricing_trust_badges']
                                    : ['1:1 Demo on Google Meet', 'No Credit Card', '100% Free Demo'];
                                $tIcons = ['fas fa-video text-blue-500', 'far fa-credit-card text-emerald-500', 'fas fa-check-circle text-orange-500'];
                            @endphp
                            @foreach($trustBadges as $bIdx => $badge)
                            <div class="flex items-center justify-center space-x-1.5 text-[10px] sm:text-[11px] font-bold text-slate-600 dark:text-slate-300">
                                <i class="{{ $tIcons[$bIdx % count($tIcons)] }}"></i>
                                <span>{{ $badge }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Right Column: Screen Preview Mockup with Arrow Tag -->
                    <div class="lg:col-span-5 text-right flex flex-col items-end w-full">
                        <div class="mb-2 text-right">
                            <span class="inline-block text-[11px] sm:text-xs font-bold text-[#ff3d00] italic leading-tight">
                                {{ $data['lp_pricing_mockup_badge'] ?? '3 Powerful Products / One Dashboard / Your Brand' }} ⤵
                            </span>
                        </div>
                        <div class="w-full relative rounded-2xl overflow-hidden shadow-2xl border border-slate-200 dark:border-slate-700 bg-slate-900">
                            <img src="{{ asset(ltrim($data['lp_pricing_image'] ?? 'assets/images/user_dashboard.png', '/')) }}" alt="SaaS Dashboard Preview" class="w-full h-auto object-contain">
                        </div>
                    </div>

                </div>

                <!-- Lower Section: Everything You Get Checklist & Trophy Box -->
                <div class="pt-8 mt-8 border-t border-slate-200 dark:border-slate-800 text-left">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-6">
                        <h4 class="font-heading font-extrabold text-lg sm:text-xl text-slate-900 dark:text-white">Everything You Get</h4>
                        <span class="text-xs sm:text-sm font-bold text-[#ff3d00]">All features included in monthly plan</span>
                    </div>

                    <!-- 6 Feature Checklist in 2 Columns -->
                    @php
                        $pricingFeatures = (isset($data['lp_pricing_features']) && is_array($data['lp_pricing_features']) && count($data['lp_pricing_features']) > 0)
                            ? $data['lp_pricing_features']
                            : [
                                '1 White-Label Platform',
                                'Unlimited End-Customers',
                                '3 White-Label SaaS Products Included',
                                'Full Partner Dashboard Access',
                                'Custom Domain & Logo',
                                'Lifetime Updates & Support'
                            ];
                    @endphp
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 mb-6">
                        @foreach($pricingFeatures as $feat)
                        <div class="flex items-center space-x-3 text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-200">
                            <div class="w-5 h-5 rounded-full bg-[#ff3d00] text-white flex items-center justify-center text-[10px] flex-shrink-0 shadow-sm">
                                <i class="fas fa-check"></i>
                            </div>
                            <span>{{ $feat }}</span>
                        </div>
                        @endforeach
                    </div>

                    <!-- Trophy Box (Reference Image 3) -->
                    <div class="p-4 sm:p-5 rounded-2xl bg-orange-50/80 dark:bg-orange-950/20 border border-orange-200/80 dark:border-orange-900/40 flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-xl bg-orange-100 dark:bg-orange-900/40 text-[#ff3d00] flex items-center justify-center text-xl flex-shrink-0 shadow-sm">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div class="text-left">
                            <h5 class="font-heading font-extrabold text-sm sm:text-base text-slate-900 dark:text-white">{{ $data['lp_pricing_trophy_title'] ?? 'Most Popular Choice' }}</h5>
                            <p class="text-xs text-slate-600 dark:text-slate-300 mt-0.5 leading-snug">{{ $data['lp_pricing_trophy_desc'] ?? 'Perfect for getting started and building your customer base.' }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION 9: YOUR GROWTH PATH & TESTIMONIALS -->
    <section class="py-14 lg:py-20 bg-white dark:bg-[#0B0F19] border-t border-slate-200 dark:border-slate-700/80 reveal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
            
            <!-- Growth Path (Task 3: Attractive UI/UX, Increased Font Size & Full Mobile Responsiveness) -->
            <div id="growth-path">
                <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
                    <span class="inline-flex items-center space-x-2 px-5 py-2 rounded-full text-xs sm:text-sm font-extrabold uppercase tracking-wider bg-orange-50 dark:bg-orange-950/40 text-[#c2410c] dark:text-orange-300 border border-orange-200/80 dark:border-orange-900/60 shadow-sm mx-auto mb-3">
                        <i class="fas fa-chart-line text-xs"></i>
                        <span>{{ $data['lp_growth_tag'] ?? 'YOUR GROWTH PATH' }}</span>
                    </span>
                    <h2 class="font-heading font-extrabold text-3xl sm:text-4xl lg:text-[44px] text-slate-900 dark:text-white tracking-tight mt-2 leading-tight">
                        {{ $data['lp_growth_title'] ?? 'From One Business to a Global Brand' }}
                    </h2>
                    <p class="text-sm sm:text-base lg:text-lg text-slate-600 dark:text-slate-300 mt-3 font-medium">
                        {{ (stripos($data['lp_growth_subtitle'] ?? '', 'Nooryak') !== false || empty($data['lp_growth_subtitle'])) ? 'Start small, dream big. SaaSReselling grows with you at every stage.' : $data['lp_growth_subtitle'] }}
                    </p>
                </div>

                <!-- 4 Step Interactive Timeline Cards -->
                @php
                    $growthStepsData = [
                        [
                            'step' => '01 • START',
                            'title' => 'Launch Your Own SaaS',
                            'desc' => 'Deploy your white-label portal in 24 hours with 3 ready-to-sell products under your brand & custom domain.',
                            'icon' => 'fas fa-rocket',
                            'gradient' => 'from-orange-500 to-amber-500',
                            'badge' => 'Day 1 Launch'
                        ],
                        [
                            'step' => '02 • GROW',
                            'title' => 'Add Paying Customers',
                            'desc' => 'Acquire local clients, set your own pricing, collect payments directly, and build predictable monthly recurring revenue.',
                            'icon' => 'fas fa-chart-line',
                            'gradient' => 'from-blue-500 to-indigo-600',
                            'badge' => 'Scale Revenue'
                        ],
                        [
                            'step' => '03 • EXPAND',
                            'title' => 'Build Reseller Network',
                            'desc' => 'Upgrade to Master Panel to onboard sub-agencies and partners who sell for you while you earn overrides.',
                            'icon' => 'fas fa-users-gear',
                            'gradient' => 'from-purple-500 to-pink-600',
                            'badge' => 'Partner Overrides'
                        ],
                        [
                            'step' => '04 • GLOBAL',
                            'title' => 'Scale to a Global Brand',
                            'desc' => 'Automate your software operations and expand your SaaS enterprise across national and global markets.',
                            'icon' => 'fas fa-globe',
                            'gradient' => 'from-emerald-500 to-teal-600',
                            'badge' => 'Global Enterprise'
                        ]
                    ];
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-7">
                    @foreach($growthStepsData as $idx => $gstep)
                    <div class="relative group rounded-3xl p-6 sm:p-7 bg-white dark:bg-[#11192e] border-2 border-slate-100 dark:border-slate-800/90 shadow-lg hover:shadow-2xl hover:border-orange-500/50 hover:-translate-y-2 transition-all duration-300 flex flex-col justify-between overflow-hidden">
                        
                        <!-- Top Ambient Card Accent -->
                        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r {{ $gstep['gradient'] }} opacity-80 group-hover:h-2 transition-all"></div>

                        <div>
                            <!-- Header: Step Pill & Milestone Badge -->
                            <div class="flex items-center justify-between gap-2 mb-5">
                                <span class="px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-700">
                                    {{ $gstep['step'] }}
                                </span>
                                <span class="text-[11px] font-bold text-orange-500 dark:text-orange-400 bg-orange-50 dark:bg-orange-950/40 px-2.5 py-0.5 rounded-full border border-orange-200/60 dark:border-orange-800/40">
                                    {{ $gstep['badge'] }}
                                </span>
                            </div>

                            <!-- Icon Container -->
                            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-gradient-to-br {{ $gstep['gradient'] }} text-white flex items-center justify-center text-xl sm:text-2xl shadow-md mb-5 group-hover:scale-110 transition-transform duration-300">
                                <i class="{{ $gstep['icon'] }}"></i>
                            </div>

                            <!-- Step Title (Increased Font Size) -->
                            <h3 class="font-heading font-extrabold text-lg sm:text-xl text-slate-900 dark:text-white mb-2.5 leading-snug tracking-tight">
                                {{ $gstep['title'] }}
                            </h3>

                            <!-- Step Description (Readable, Clear Font) -->
                            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed font-normal">
                                {{ $gstep['desc'] }}
                            </p>
                        </div>

                        <!-- Step Footer: Interactive Arrow Indicator -->
                        <div class="pt-5 mt-5 border-t border-slate-100 dark:border-slate-800/70 flex items-center justify-between text-xs font-bold text-slate-400 group-hover:text-orange-500 transition-colors">
                            <span class="text-[11px] uppercase tracking-wider">Milestone {{ $idx + 1 }}</span>
                            <i class="fas fa-arrow-right text-xs transform group-hover:translate-x-1.5 transition-transform"></i>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Testimonials (Task 3: Full Width Cards on Mobile - No text truncation) -->
            <div>
                <div class="text-center mb-10">
                    <span class="badge-pill mx-auto">{{ $data['lp_testimonials_tag'] }}</span>
                    <h2 class="font-space font-extrabold text-2xl sm:text-3xl text-slate-900 dark:text-white tracking-tight mt-3">
                        {{ $data['lp_testimonials_title'] }}
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 mt-1">{{ $data['lp_testimonials_subtitle'] }}</p>
                </div>

                <!-- Testimonials Mobile Horizontal Auto-Slider & Desktop Grid -->
                <div class="relative max-w-full overflow-hidden">
                    <div id="testimonialSlider" class="flex overflow-x-auto snap-x snap-mandatory scroll-smooth md:grid md:grid-cols-3 gap-6 pb-4 md:pb-0 no-scrollbar">
                        @if(is_array($data['lp_testimonials_items']))
                            @foreach($data['lp_testimonials_items'] as $tIdx => $testi)
                            <div class="testimonial-card w-full min-w-full md:min-w-0 snap-center flex-shrink-0 p-6 sm:p-8 rounded-3xl bg-slate-50 dark:bg-[#151c2c] border border-slate-200 dark:border-slate-700/90 shadow-sm flex flex-col justify-between space-y-4">
                                <div class="space-y-3">
                                    <div class="flex items-center space-x-1 text-amber-400 text-xs">
                                        @for($r = 0; $r < ($testi['rating'] ?? 5); $r++)
                                            <i class="fas fa-star"></i>
                                        @endfor
                                    </div>
                                    <p class="text-xs sm:text-sm text-slate-700 dark:text-slate-200 leading-relaxed font-medium break-words">"{{ $testi['quote'] ?? '' }}"</p>
                                </div>
                                <div class="pt-4 border-t border-slate-200 dark:border-slate-700/70 flex items-center justify-between text-xs sm:text-sm">
                                    <span class="font-bold text-slate-900 dark:text-white">{{ $testi['name'] ?? '' }}</span>
                                    <span class="text-slate-500 dark:text-slate-400 font-medium text-xs">{{ $testi['role'] ?? '' }}</span>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Mobile Slider Navigation Dots -->
                <div id="testimonialDots" class="flex md:hidden justify-center items-center space-x-2 mt-6">
                    @if(is_array($data['lp_testimonials_items']))
                        @foreach($data['lp_testimonials_items'] as $tIdx => $testi)
                        <button class="t-dot h-2.5 rounded-full transition-all duration-300 {{ $tIdx === 0 ? 'bg-[#ff3d00] w-6' : 'bg-slate-300 w-2.5' }}" data-index="{{ $tIdx }}"></button>
                        @endforeach
                    @endif
                </div>
            </div>

        </div>
    </section>

    <!-- SECTION 10: FAQ ACCORDION -->
    <section id="faq" class="py-14 lg:py-20 bg-slate-50 dark:bg-[#151c2c]/60 border-t border-slate-200 dark:border-slate-700/60 reveal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
                
                <!-- Left FAQ Header (4 Cols) -->
                <div class="lg:col-span-4 text-left space-y-4">
                    <span class="inline-block px-3.5 py-1 rounded-full text-xs font-bold bg-orange-100 dark:bg-orange-900/30 text-[#ff3d00] border border-orange-200">
                        {{ $data['lp_faqs_tag'] }}
                    </span>
                    <h2 class="font-space font-extrabold text-3xl sm:text-4xl text-slate-900 dark:text-white tracking-tight leading-tight">
                        {{ $data['lp_faqs_title'] }}
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                        {{ $data['lp_faqs_subtitle'] }}
                    </p>
                    <div class="pt-2">
                        <a href="#contact" class="inline-flex items-center text-xs font-bold text-[#ff3d00] hover:text-orange-700 dark:text-indigo-300 space-x-1">
                            <span>View All FAQs</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>

                <!-- Right FAQ Accordion Card (8 Cols) -->
                <div class="lg:col-span-8 bg-white dark:bg-[#0B0F19] rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200 dark:border-slate-700/80 space-y-3">
                    @if(is_array($data['lp_faqs_items']))
                        @foreach($data['lp_faqs_items'] as $fIndex => $faq)
                        <div class="border-b border-slate-100 dark:border-slate-800 last:border-0 pb-3 last:pb-0">
                            <button class="faq-toggle w-full py-3.5 text-left font-space font-bold text-sm text-slate-900 dark:text-white bg-white dark:bg-[#0B0F19] hover:text-[#ff3d00] flex items-center justify-between focus:outline-none transition-colors">
                                <span class="pr-4">{{ $faq['question'] ?? '' }}</span>
                                <i class="fas fa-plus text-xs text-slate-400 font-normal transition-transform duration-200 ml-2 flex-shrink-0"></i>
                            </button>
                            <div class="faq-content hidden pt-1 pb-3 text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                                {{ $faq['answer'] ?? '' }}
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION 11: CTA BANNER (Task 1: Background image revenue_calculator.png & Task 2: Orange CTA Button) -->
    <section class="py-14 sm:py-16 text-white relative overflow-hidden reveal bg-cover bg-center" style="background-image: url('{{ asset('/images/revenue_calculator.png') }}');">
        <!-- Dark tint backdrop overlay for readability -->
        <div class="absolute inset-0 bg-slate-950/85 backdrop-blur-[2px]"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-8">
                
                <!-- Left Title & Subtitle -->
                <div class="text-center lg:text-left space-y-3 max-w-2xl">
                    <h2 class="font-space font-extrabold text-2xl sm:text-4xl lg:text-5xl tracking-tight leading-tight">
                        {{ $data['lp_cta_banner_title'] }}
                    </h2>
                    <p class="text-xs sm:text-base text-slate-300 leading-relaxed">
                        {{ $data['lp_cta_banner_subtitle'] }}
                    </p>
                </div>

                <!-- Right Buttons & Handwritten Arrow (Task 2: Changed blue button to orange) -->
                <div class="flex flex-col sm:flex-row items-center gap-4 relative">
                    <a href="{{ $data['lp_cta_banner_button1_url'] }}" class="btn-gradient px-8 py-4 rounded-full text-white font-extrabold text-xs sm:text-sm shadow-xl shadow-orange-500/40 flex items-center space-x-2.5 transition-all hover:scale-105">
                        <span>{{ $data['lp_cta_banner_button1_text'] }}</span>
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                    <a href="{{ $data['lp_cta_banner_button2_url'] }}" class="px-7 py-4 rounded-full bg-slate-900/80 hover:bg-slate-800 border border-slate-700 text-white font-bold text-xs sm:text-sm backdrop-blur-md transition-all flex items-center space-x-2.5 shadow-md">
                        <i class="fas fa-play text-[10px]"></i>
                        <span>{{ $data['lp_cta_banner_button2_text'] }}</span>
                    </a>

                    <!-- Rotated Handwritten Annotation -->
                    <div class="hidden xl:block absolute -bottom-9 right-2 text-orange-300 handwritten text-xs transform rotate-2 flex items-center space-x-1 pointer-events-none">
                        <span>Your Success Starts Here!</span>
                        <span>➔</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION 12: FOOTER (Task 5: Mobile 2 columns per row & Dynamic active products only) -->
    <footer class="bg-white dark:bg-[#0B0F19] text-slate-600 dark:text-slate-300 py-12 border-t border-slate-200 dark:border-slate-700/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-12 gap-6 sm:gap-8 pb-10 border-b border-slate-200 dark:border-slate-700/80">
                
                <!-- Col 1: Logo & Socials (Increased Size & Dual Dark/White Mode Support) -->
                <div class="col-span-2 lg:col-span-3 space-y-4">
                    <a href="{{ url('/') }}" class="flex items-center space-x-2.5 text-slate-900 dark:text-white font-heading font-bold text-xl">
                        @if($lightLogo || $darkLogo)
                            @if($lightLogo)
                                <img src="{{ asset($lightLogo) }}" alt="Logo" class="h-12 sm:h-14 lg:h-16 max-h-[60px] w-auto object-contain transition-all {{ $darkLogo ? 'dark:hidden' : '' }}">
                            @endif
                            @if($darkLogo)
                                <img src="{{ asset($darkLogo) }}" alt="Logo Dark" class="h-12 sm:h-14 lg:h-16 max-h-[60px] w-auto object-contain transition-all hidden dark:block">
                            @endif
                        @else
                            <div class="w-11 h-11 rounded-xl btn-gradient flex items-center justify-center text-white text-base font-bold shadow-sm">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <span class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">SaaSReselling</span>
                        @endif
                    </a>
                    <p class="text-xs text-slate-500 dark:text-slate-400 max-w-xs leading-relaxed">
                        {{ $data['lp_footer_desc'] }}
                    </p>
                    <div class="flex items-center space-x-2.5 pt-1">
                        @if(!empty($data['lp_fb_url']))<a href="{{ $data['lp_fb_url'] }}" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-[#1e293b] hover:bg-orange-50 dark:bg-[#1e293b] hover:text-[#ff3d00] flex items-center justify-center text-slate-500 dark:text-white text-xs transition-colors"><i class="fab fa-facebook-f"></i></a>@endif
                        @if(!empty($data['lp_tw_url']))<a href="{{ $data['lp_tw_url'] }}" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-[#1e293b] hover:bg-orange-50 dark:bg-[#1e293b] hover:text-[#ff3d00] flex items-center justify-center text-slate-500 dark:text-white text-xs transition-colors"><i class="fab fa-twitter"></i></a>@endif
                        @if(!empty($data['lp_li_url']))<a href="{{ $data['lp_li_url'] }}" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-[#1e293b] hover:bg-orange-50 dark:bg-[#1e293b] hover:text-[#ff3d00] flex items-center justify-center text-slate-500 dark:text-white text-xs transition-colors"><i class="fab fa-linkedin-in"></i></a>@endif
                        @if(!empty($data['lp_yt_url']))<a href="{{ $data['lp_yt_url'] }}" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-[#1e293b] hover:bg-orange-50 dark:bg-[#1e293b] hover:text-[#ff3d00] flex items-center justify-center text-slate-500 dark:text-white text-xs transition-colors"><i class="fab fa-discord"></i></a>@endif
                        @if(!empty($data['lp_ig_url']))<a href="{{ $data['lp_ig_url'] }}" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-[#1e293b] hover:bg-orange-50 dark:bg-[#1e293b] hover:text-[#ff3d00] flex items-center justify-center text-slate-500 dark:text-white text-xs transition-colors"><i class="fab fa-instagram"></i></a>@endif
                    </div>
                </div>

                <!-- Col 2: Quick Links (Col 1 on mobile, 2 Cols on lg) -->
                <div class="col-span-1 lg:col-span-2 space-y-3">
                    <h5 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Quick Links</h5>
                    <ul class="space-y-2 text-xs font-medium text-slate-600 dark:text-slate-300">
                        <li><a href="#hero" class="hover:text-[#ff3d00] transition-colors">Home</a></li>
                        <li><a href="#about" class="hover:text-[#ff3d00] transition-colors">Solutions</a></li>
                        <li><a href="#pricing" class="hover:text-[#ff3d00] transition-colors">Pricing</a></li>
                        <li><a href="#products" class="hover:text-[#ff3d00] transition-colors">Resources</a></li>
                        <li><a href="#faq" class="hover:text-[#ff3d00] transition-colors">Blog</a></li>
                    </ul>
                </div>

                <!-- Col 3: Our Products (Task 5: Dynamic Active Products Only - Remove Unwanted Products) -->
                <div class="col-span-1 lg:col-span-2 space-y-3">
                    <h5 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Our Products</h5>
                    <ul class="space-y-2 text-xs font-medium text-slate-600 dark:text-slate-300">
                        @if($dbProds->count() > 0)
                            @foreach($dbProds as $prod)
                                <li><a href="{{ $prod->getSubdomainPreviewUrl() }}" target="_blank" class="hover:text-[#ff3d00] transition-colors">{{ $prod->name }}</a></li>
                            @endforeach
                        @else
                            @if(is_array($data['lp_products_grid']))
                                @foreach($data['lp_products_grid'] as $prod)
                                    <li><a href="{{ $prod['link'] ?? '#products' }}" class="hover:text-[#ff3d00] transition-colors">{{ $prod['title'] ?? '' }}</a></li>
                                @endforeach
                            @endif
                        @endif
                    </ul>
                </div>

                <!-- Col 4: Support & Legal Policies (Col 1 on mobile, 2 Cols on lg) -->
                <div class="col-span-1 lg:col-span-2 space-y-3">
                    <h5 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Support</h5>
                    <ul class="space-y-2 text-xs font-medium text-slate-600 dark:text-slate-300">
                        <li><a href="#faq" class="hover:text-[#ff3d00] transition-colors">Help Center</a></li>
                        <li><a href="mailto:{{ $data['lp_contact_email'] }}" class="hover:text-[#ff3d00] transition-colors">Contact Us</a></li>
                        <li><a href="https://wa.me/916374913298?text=I%20want%20the%20demo%20explaination" class="hover:text-[#ff3d00] transition-colors">Book a Demo</a></li>
                        <li><a href="{{ route('agency.privacy') }}" class="hover:text-[#ff3d00] transition-colors">Privacy Policy</a></li>
                        <li><a href="{{ route('agency.terms') }}" class="hover:text-[#ff3d00] transition-colors">Terms & Conditions</a></li>
                        <li><a href="{{ route('agency.refund') }}" class="hover:text-[#ff3d00] transition-colors">Refund Policy</a></li>
                        <li><a href="{{ route('agency.shipping') }}" class="hover:text-[#ff3d00] transition-colors">Shipping Policy</a></li>
                    </ul>
                </div>

                <!-- Col 5: Newsletter Subscription (Span 2 on mobile, 3 Cols on lg) -->
                <div class="col-span-2 lg:col-span-3 space-y-3">
                    <h5 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Subscribe to Our Newsletter</h5>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Get the latest updates and offers.</p>
                    <form onsubmit="event.preventDefault(); alert('Subscribed successfully!');" class="flex items-center space-x-0 pt-1">
                        <input type="email" placeholder="Enter your email" required class="w-full px-3.5 py-2.5 rounded-l-xl border border-r-0 border-slate-200 dark:border-slate-700 text-xs text-slate-800 dark:text-slate-100 bg-slate-50 dark:bg-[#151c2c] focus:bg-white dark:bg-[#0B0F19] focus:outline-none focus:ring-1 focus:ring-orange-500">
                        <button type="submit" class="px-4 py-2.5 rounded-r-xl btn-gradient text-white font-bold text-xs flex-shrink-0 transition-all">
                            Subscribe
                        </button>
                    </form>
                </div>

            </div>

            <!-- Bottom Copyright & Legal Links (Task 6: Legal Policy Links) -->
            <div class="pt-6 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 dark:text-slate-400 font-medium gap-3">
                <p>{{ $data['lp_copyright_text'] }}</p>
                <div class="flex flex-wrap items-center justify-center gap-2 sm:gap-4">
                    <a href="{{ route('agency.privacy') }}" class="hover:text-[#ff3d00] transition-colors">Privacy Policy</a>
                    <span>|</span>
                    <a href="{{ route('agency.terms') }}" class="hover:text-[#ff3d00] transition-colors">Terms & Conditions</a>
                    <span>|</span>
                    <a href="{{ route('agency.refund') }}" class="hover:text-[#ff3d00] transition-colors">Refund Policy</a>
                    <span>|</span>
                    <a href="{{ route('agency.shipping') }}" class="hover:text-[#ff3d00] transition-colors">Shipping Policy</a>
                </div>
            </div>

        </div>
    </footer>

    <!-- Interactive Scripts -->
    <script>
        // Feature Strip Ticker Navigation
        const fContainer = document.getElementById('featureStripContainer');
        const fPrev = document.getElementById('featureStripPrev');
        const fNext = document.getElementById('featureStripNext');
        if (fContainer && fPrev && fNext) {
            fPrev.addEventListener('click', () => {
                fContainer.scrollBy({ left: -260, behavior: 'smooth' });
            });
            fNext.addEventListener('click', () => {
                fContainer.scrollBy({ left: 260, behavior: 'smooth' });
            });
        }
        
        // Mobile Drawer Navigation Toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }

        // Interactive Revenue Calculator Slider (Task 4: Starts at 1)
        const slider = document.getElementById('customerSlider');
        const countDisplay = document.getElementById('customerCountDisplay');
        const revDisplay = document.getElementById('monthlyRevDisplay');
        const subtextCount = document.getElementById('calcSubtextCount');
        const pricePerCustomer = {{ $data['lp_rev_calc_price_per_customer'] ?? 999 }};

        if (slider && countDisplay && revDisplay) {
            function updateCalculator() {
                const customers = parseInt(slider.value) || 1;
                const totalRev = customers * pricePerCustomer;
                countDisplay.textContent = customers;
                if (subtextCount) subtextCount.textContent = customers;
                revDisplay.innerHTML = '₹' + totalRev.toLocaleString('en-IN') + ' <span class="text-xs sm:text-sm font-semibold text-slate-400">/month</span>';
            }

            slider.addEventListener('input', updateCalculator);
            updateCalculator();
        }

        // FAQ Accordion Toggle
        document.querySelectorAll('.faq-toggle').forEach(btn => {
            btn.addEventListener('click', () => {
                const content = btn.nextElementSibling;
                const icon = btn.querySelector('i');
                const isHidden = content.classList.contains('hidden');

                document.querySelectorAll('.faq-content').forEach(c => c.classList.add('hidden'));
                document.querySelectorAll('.faq-toggle i').forEach(i => i.classList.remove('rotate-180'));

                if (isHidden) {
                    content.classList.remove('hidden');
                    icon.classList.add('rotate-180');
                }
            });
        });

        // Full Scrolling Reveal Animation
        document.addEventListener('DOMContentLoaded', () => {
            const reveals = document.querySelectorAll('section, .reveal');
            reveals.forEach(el => el.classList.add('reveal'));

            const revealObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                    }
                });
            }, { threshold: 0.1 });

            reveals.forEach(el => revealObserver.observe(el));

            // Animated Running Counter Numbers
            const counters = document.querySelectorAll('.stat-counter');
            const counterObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const counter = entry.target;
                        const targetVal = counter.getAttribute('data-target') || counter.innerText.trim();
                        
                        const match = targetVal.match(/^([^\d]*)([\d,.]+)(.*)$/);
                        if (match) {
                            const prefix = match[1] || '';
                            const rawNum = parseFloat(match[2].replace(/,/g, ''));
                            const suffix = match[3] || '';
                            const isFloat = match[2].includes('.');
                            const duration = 2000;
                            const startTime = performance.now();

                            function animateCount(currentTime) {
                                const elapsed = currentTime - startTime;
                                const progress = Math.min(elapsed / duration, 1);
                                const easeProgress = 1 - Math.pow(1 - progress, 3);
                                const currentNum = easeProgress * rawNum;

                                if (isFloat) {
                                    counter.innerText = prefix + currentNum.toFixed(1) + suffix;
                                } else {
                                    counter.innerText = prefix + Math.floor(currentNum).toLocaleString('en-US') + suffix;
                                }

                                if (progress < 1) {
                                    requestAnimationFrame(animateCount);
                                } else {
                                    counter.innerText = targetVal;
                                }
                            }
                            requestAnimationFrame(animateCount);
                        }
                        observer.unobserve(counter);
                    }
                });
            }, { threshold: 0.3 });

            counters.forEach(c => counterObserver.observe(c));

            // Task 2: Mobile Step Scroll Reveal & Down Arrow Observer
            const stepObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.remove('opacity-0', 'translate-y-8', 'translate-y-4');
                        entry.target.classList.add('opacity-100', 'translate-y-0');
                    }
                });
            }, { threshold: 0.15 });

            document.querySelectorAll('.step-card, .step-arrow').forEach(el => stepObserver.observe(el));

            // Task 4: Testimonials Mobile Auto-Slider
            const tSlider = document.getElementById('testimonialSlider');
            const tDots = document.querySelectorAll('.t-dot');
            if (tSlider && tDots.length > 0) {
                let tCurrentIndex = 0;
                const tCards = tSlider.querySelectorAll('.testimonial-card');
                
                function updateActiveDot(index) {
                    tDots.forEach((dot, idx) => {
                        if (idx === index) {
                            dot.className = 't-dot h-2 rounded-full transition-all duration-300 bg-orange-500 w-6';
                        } else {
                            dot.className = 't-dot h-2 rounded-full transition-all duration-300 bg-slate-300 w-2';
                        }
                    });
                }

                function slideTo(index) {
                    if (window.innerWidth < 768 && tCards[index]) {
                        const cardLeft = tCards[index].offsetLeft - tSlider.offsetLeft;
                        tSlider.scrollTo({ left: cardLeft, behavior: 'smooth' });
                        tCurrentIndex = index;
                        updateActiveDot(tCurrentIndex);
                    }
                }

                tDots.forEach(dot => {
                    dot.addEventListener('click', () => {
                        const idx = parseInt(dot.getAttribute('data-index'));
                        slideTo(idx);
                    });
                });

                setInterval(() => {
                    if (window.innerWidth < 768 && tCards.length > 0) {
                        tCurrentIndex = (tCurrentIndex + 1) % tCards.length;
                        slideTo(tCurrentIndex);
                    }
                }, 3500);

                tSlider.addEventListener('scroll', () => {
                    if (window.innerWidth < 768 && tCards.length > 0) {
                        const scrollLeft = tSlider.scrollLeft;
                        const cardWidth = tCards[0].offsetWidth;
                        const nearestIndex = Math.round(scrollLeft / cardWidth);
                        if (nearestIndex !== tCurrentIndex && nearestIndex >= 0 && nearestIndex < tCards.length) {
                            tCurrentIndex = nearestIndex;
                            updateActiveDot(tCurrentIndex);
                        }
                    }
                });
            }
        });
    </script>
    <!-- Exit Intent / Delayed Popup Modal (Task 2: Polished, Animated Pulse, Enlarged Timer, Highly Responsive Mobile View) -->
    <div id="promoModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/85 backdrop-blur-lg opacity-0 invisible transition-all duration-500 overflow-y-auto p-3 sm:p-6">
        
        <!-- Modal Container with subtle gradient border -->
        <div class="relative w-full max-w-4xl mx-auto flex flex-col md:flex-row bg-white rounded-2xl sm:rounded-3xl shadow-[0_25px_70px_rgba(0,0,0,0.55)] overflow-hidden transform scale-95 opacity-0 transition-all duration-500 border border-orange-500/20 max-h-[92vh] overflow-y-auto" id="promoModalContent">
            
            <!-- Close Button (Always accessible top right) -->
            <button onclick="closePromoModal()" aria-label="Close modal" class="absolute top-3.5 right-3.5 sm:top-4 sm:right-4 z-50 w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center rounded-full bg-slate-900/80 hover:bg-slate-800 text-white transition-colors focus:outline-none shadow-lg border border-slate-700/60 active:scale-95">
                <i class="fas fa-times text-xs sm:text-sm"></i>
            </button>
            
            <!-- Left Side (Clean White Panel) -->
            <div class="w-full md:w-1/2 p-5 sm:p-8 lg:p-10 flex flex-col justify-between bg-white text-left">
                <div>
                    <!-- Top Tag -->
                    <span class="inline-block text-[10px] sm:text-[11px] font-extrabold uppercase tracking-wider text-[#ff3d00] mb-2 font-heading bg-orange-50 px-2.5 py-1 rounded-md border border-orange-200/60">
                        SAASRESELLING · WHITE-LABEL PLATFORM
                    </span>
                    
                    <!-- Headline -->
                    <h3 class="font-heading font-extrabold text-2xl sm:text-3xl lg:text-[34px] text-slate-900 leading-[1.12] tracking-tight mt-1">
                        All-in-One <br>
                        <span class="text-[#ff3d00]">White-Label</span> <br>
                        SaaS Panel
                    </h3>
                    
                    <!-- Description -->
                    <p class="text-xs sm:text-sm text-slate-600 mt-2.5 leading-relaxed font-normal">
                        Launch your own software brand with 3 integrated SaaS solutions—managed from one unified dashboard, under your brand.
                    </p>
                    
                    <!-- 4 Feature Bullets -->
                    <div class="space-y-2.5 sm:space-y-3 mt-4 sm:mt-5">
                        <div class="flex items-center space-x-3 text-xs sm:text-[13px] font-bold text-slate-800">
                            <div class="w-7 h-7 rounded-lg bg-orange-50 border border-orange-200 text-[#ff3d00] flex items-center justify-center text-xs flex-shrink-0 shadow-sm">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <span>Unified SaaS Admin Dashboard</span>
                        </div>
                        <div class="flex items-center space-x-3 text-xs sm:text-[13px] font-bold text-slate-800">
                            <div class="w-7 h-7 rounded-lg bg-orange-50 border border-orange-200 text-[#ff3d00] flex items-center justify-center text-xs flex-shrink-0 shadow-sm">
                                <i class="fas fa-globe"></i>
                            </div>
                            <span>Custom Domain & White-Label Branding</span>
                        </div>
                        <div class="flex items-center space-x-3 text-xs sm:text-[13px] font-bold text-slate-800">
                            <div class="w-7 h-7 rounded-lg bg-orange-50 border border-orange-200 text-[#ff3d00] flex items-center justify-center text-xs flex-shrink-0 shadow-sm">
                                <i class="fas fa-user-friends"></i>
                            </div>
                            <span>Customer & Subscription Management</span>
                        </div>
                        <div class="flex items-center space-x-3 text-xs sm:text-[13px] font-bold text-slate-800">
                            <div class="w-7 h-7 rounded-lg bg-orange-50 border border-orange-200 text-[#ff3d00] flex items-center justify-center text-xs flex-shrink-0 shadow-sm">
                                <i class="fas fa-cog"></i>
                            </div>
                            <span>No Server Setup or Coding Required</span>
                        </div>
                    </div>
                </div>
                
                <!-- Bottom 3 Product Cards with interactive hover -->
                <div class="grid grid-cols-3 gap-2 sm:gap-2.5 pt-5 sm:pt-6 mt-4 border-t border-slate-100">
                    <div class="bg-slate-50 hover:bg-orange-50/50 border border-slate-200/80 hover:border-orange-300 rounded-xl p-2 sm:p-2.5 text-center flex flex-col items-center justify-center space-y-1 transition-all">
                        <i class="far fa-window-maximize text-[#ff3d00] text-sm sm:text-base"></i>
                        <span class="text-[9px] sm:text-[11px] font-bold text-slate-800 leading-tight">Website Builder</span>
                    </div>
                    <div class="bg-slate-50 hover:bg-orange-50/50 border border-slate-200/80 hover:border-orange-300 rounded-xl p-2 sm:p-2.5 text-center flex flex-col items-center justify-center space-y-1 transition-all">
                        <i class="fas fa-shopping-cart text-[#ff3d00] text-sm sm:text-base"></i>
                        <span class="text-[9px] sm:text-[11px] font-bold text-slate-800 leading-tight">E-commerce Builder</span>
                    </div>
                    <div class="bg-slate-50 hover:bg-orange-50/50 border border-slate-200/80 hover:border-orange-300 rounded-xl p-2 sm:p-2.5 text-center flex flex-col items-center justify-center space-y-1 transition-all">
                        <i class="fab fa-whatsapp text-[#ff3d00] text-sm sm:text-base"></i>
                        <span class="text-[9px] sm:text-[11px] font-bold text-slate-800 leading-tight">WhatsApp Automation</span>
                    </div>
                </div>
            </div>
            
            <!-- Right Side (Deep Navy with Subtle Animated Glow & Big Timer) -->
            <div class="w-full md:w-1/2 bg-[#080E24] p-5 sm:p-8 lg:p-10 flex flex-col justify-between text-left relative overflow-hidden">
                <!-- Background subtle glow -->
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/15 via-transparent to-orange-500/15 pointer-events-none"></div>
                
                <div class="relative z-10 w-full space-y-3.5 sm:space-y-4">
                    <!-- Top Pill Tag -->
                    <span class="inline-block px-3.5 py-1 rounded-full border border-orange-500/70 text-orange-400 text-[10px] font-bold uppercase tracking-wider bg-orange-950/30">
                        YOUR SAAS BUSINESS STARTS HERE
                    </span>
                    
                    <!-- Heading -->
                    <h3 class="font-heading font-extrabold text-2xl sm:text-3xl text-white leading-snug">
                        Ready to launch <br>
                        under your brand?
                    </h3>
                    
                    <!-- Subtitle -->
                    <p class="text-xs sm:text-[13px] text-slate-300 font-normal leading-relaxed">
                        Speak with our team about the All-in-One White-Label SaaS Panel.
                    </p>
                    
                    <!-- Enhanced Timer Box with Larger Fonts (Task 2) -->
                    <div class="bg-gradient-to-b from-[#11193d] to-[#090f28] border border-orange-500/40 rounded-2xl p-4 sm:p-5 text-center shadow-[0_0_25px_rgba(255,87,34,0.12)]">
                        <div class="flex items-center justify-center space-x-1.5 text-orange-400 text-[10px] sm:text-[11px] font-extrabold uppercase tracking-widest mb-2.5">
                            <i class="far fa-clock animate-spin" style="animation-duration: 6s;"></i>
                            <span>OFFER WINDOW</span>
                        </div>
                        <div class="flex items-center justify-center space-x-3 sm:space-x-4">
                            <div class="text-center min-w-[65px] sm:min-w-[80px]">
                                <span class="font-heading font-black text-4xl sm:text-5xl lg:text-6xl text-white tracking-tight leading-none block" id="promoMins">09</span>
                                <span class="block text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1.5">MINUTES</span>
                            </div>
                            <span class="text-3xl sm:text-4xl text-orange-400 font-extrabold pb-2 sm:pb-3">:</span>
                            <div class="text-center min-w-[65px] sm:min-w-[80px]">
                                <span class="font-heading font-black text-4xl sm:text-5xl lg:text-6xl text-white tracking-tight leading-none block" id="promoSecs">59</span>
                                <span class="block text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1.5">SECONDS</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- 15% OFF Badge Callout with subtle pulse glow -->
                    <div class="flex items-center space-x-3 bg-gradient-to-r from-orange-500/20 via-orange-500/10 to-transparent p-3 sm:p-3.5 rounded-xl border border-orange-500/40 shadow-sm">
                        <span class="px-3 py-1 rounded-full bg-[#ff5722] text-white font-extrabold text-xs sm:text-[13px] flex-shrink-0 shadow-lg shadow-orange-500/40 animate-pulse">
                            15% OFF
                        </span>
                        <div>
                            <span class="block text-white font-bold text-xs sm:text-sm leading-tight">On the 2-Year Plan</span>
                            <span class="block text-[10px] sm:text-[11px] text-slate-300 mt-0.5 font-normal">Mention this offer when you message us.</span>
                        </div>
                    </div>
                    
                    <!-- Big WhatsApp Action Button (NO preview message below) -->
                    <a href="https://wa.me/916374913298?text=Hi%20SaaSReselling,%20I%27m%20interested%20in%20the%20All-in-One%20White-Label%20SaaS%20Panel.%20Please%20share%20details%20about%20the%2015%25%20offer%20on%20the%202-year%20plan,%20setup,%20branding,%20and%20the%203%20SaaS%20solutions." target="_blank" class="w-full py-3.5 sm:py-4 px-5 rounded-2xl bg-[#22c55e] hover:bg-[#16a34a] text-white font-bold text-xs sm:text-sm shadow-xl shadow-green-500/30 flex items-center justify-center space-x-2.5 transition-all text-center hover:scale-[1.02] active:scale-[0.99]">
                        <i class="fab fa-whatsapp text-lg sm:text-xl"></i>
                        <span>Claim 15% Offer on WhatsApp</span>
                    </a>
                </div>
            </div>
            
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const promoModal = document.getElementById('promoModal');
            const promoModalContent = document.getElementById('promoModalContent');
            
            // Random delay between 2 to 5 seconds
            const delay = Math.floor(Math.random() * (5000 - 2000 + 1)) + 2000;
            
            setTimeout(() => {
                promoModal.classList.remove('opacity-0', 'invisible');
                promoModal.classList.add('opacity-100', 'visible');
                
                promoModalContent.classList.remove('scale-95', 'opacity-0');
                promoModalContent.classList.add('scale-100', 'opacity-100');
                
                startPromoTimer();
            }, delay);
        });
        
        function closePromoModal() {
            const promoModal = document.getElementById('promoModal');
            const promoModalContent = document.getElementById('promoModalContent');
            
            promoModalContent.classList.remove('scale-100', 'opacity-100');
            promoModalContent.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                promoModal.classList.remove('opacity-100', 'visible');
                promoModal.classList.add('opacity-0', 'invisible');
            }, 300);
        }
        
        function startPromoTimer() {
            // Check if timer already started to prevent double-starts on strict-mode or multiple loads
            if(window.promoTimerStarted) return;
            window.promoTimerStarted = true;
            
            let timeInSeconds = (9 * 60) + 59; // 09:59 countdown matching reference image
            
            const timerInterval = setInterval(() => {
                timeInSeconds--;
                if(timeInSeconds <= 0) {
                    clearInterval(timerInterval);
                }
                
                const h = Math.floor(timeInSeconds / 3600);
                const m = Math.floor((timeInSeconds % 3600) / 60);
                const s = Math.floor(timeInSeconds % 60);
                
                const hrsEl = document.getElementById('promoHrs');
                if (hrsEl) hrsEl.textContent = h.toString().padStart(2, '0');
                const minsEl = document.getElementById('promoMins');
                if (minsEl) minsEl.textContent = m.toString().padStart(2, '0');
                const secsEl = document.getElementById('promoSecs');
                if (secsEl) secsEl.textContent = s.toString().padStart(2, '0');
            }, 1000);
        }
    </script>
<script>
    function toggleTheme() {
        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        } else {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        }
    }
    
    // On page load
    if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
</script>

    <!-- BOOK FREE LIVE DEMO MODAL (Task 4: Reference Image 5 Design) -->
    <div id="demoModal" class="fixed inset-0 z-[110] flex items-center justify-center bg-slate-950/85 backdrop-blur-md opacity-0 invisible transition-all duration-300 overflow-y-auto p-4 sm:p-6">
        <div class="relative w-full max-w-lg mx-auto bg-[#080E24] border border-orange-500/30 rounded-3xl p-6 sm:p-8 shadow-2xl transform scale-95 opacity-0 transition-all duration-300 text-left overflow-hidden" id="demoModalCard">
            
            <!-- Ambient Background Glow -->
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/10 via-transparent to-orange-500/10 pointer-events-none"></div>

            <!-- Close Button -->
            <button onclick="window.closeDemoModal()" aria-label="Close" class="absolute top-4 right-4 z-50 w-8 h-8 flex items-center justify-center rounded-full bg-slate-800/80 hover:bg-slate-700 text-white transition-colors focus:outline-none shadow-md">
                <i class="fas fa-times text-xs"></i>
            </button>

            <div class="relative z-10 space-y-4">
                <!-- Header -->
                <div>
                    <h3 class="font-heading font-extrabold text-2xl sm:text-3xl text-white tracking-tight flex items-center gap-2">
                        <span>🚀 Launch Your SaaS in 24 Hours</span>
                    </h3>
                    <p class="text-xs text-slate-400 mt-1">
                        3 Ready-to-Sell Products • 1 Dashboard • Your Brand
                    </p>
                </div>

                <!-- Form -->
                <form id="liveDemoForm" onsubmit="window.submitLiveDemo(event)" class="space-y-3.5 pt-2">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">Full Name</label>
                        <input type="text" name="name" id="demo_name" required placeholder="Enter your full name" class="w-full bg-[#11193d] border border-slate-700/80 rounded-xl px-4 py-2.5 text-xs sm:text-sm text-white placeholder-slate-500 focus:border-[#ff3d00] focus:ring-1 focus:ring-[#ff3d00] outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">WhatsApp Number</label>
                        <input type="tel" name="whatsapp" id="demo_whatsapp" required placeholder="Enter your WhatsApp number (with country code)" class="w-full bg-[#11193d] border border-slate-700/80 rounded-xl px-4 py-2.5 text-xs sm:text-sm text-white placeholder-slate-500 focus:border-[#ff3d00] focus:ring-1 focus:ring-[#ff3d00] outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">Email Address</label>
                        <input type="email" name="email" id="demo_email" required placeholder="Enter your email" class="w-full bg-[#11193d] border border-slate-700/80 rounded-xl px-4 py-2.5 text-xs sm:text-sm text-white placeholder-slate-500 focus:border-[#ff3d00] focus:ring-1 focus:ring-[#ff3d00] outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">Current Profession</label>
                        <select name="profession" id="demo_profession" required class="w-full bg-[#11193d] border border-slate-700/80 rounded-xl px-4 py-2.5 text-xs sm:text-sm text-white focus:border-[#ff3d00] focus:ring-1 focus:ring-[#ff3d00] outline-none">
                            <option value="" disabled selected>Select an Option</option>
                            <option value="Agency Owner">Agency Owner</option>
                            <option value="Freelancer">Freelancer</option>
                            <option value="Digital Marketer">Digital Marketer</option>
                            <option value="IT Consultant / Developer">IT Consultant / Developer</option>
                            <option value="Business Owner / Entrepreneur">Business Owner / Entrepreneur</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <!-- Math Verification Captcha -->
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1" id="captchaLabel">What is 10 + 7?</label>
                        <input type="number" id="captchaAnswer" required placeholder="Enter the answer" class="w-full bg-[#11193d] border border-slate-700/80 rounded-xl px-4 py-2.5 text-xs sm:text-sm text-white placeholder-slate-500 focus:border-[#ff3d00] focus:ring-1 focus:ring-[#ff3d00] outline-none">
                        <input type="hidden" id="captchaExpected" value="17">
                    </div>

                    <div id="demoFormMsg" class="hidden text-xs font-bold p-2.5 rounded-xl"></div>

                    <!-- Submit Button -->
                    <button type="submit" id="demoSubmitBtn" class="w-full btn-gradient py-3.5 sm:py-4 px-6 rounded-2xl font-bold text-xs sm:text-sm text-white shadow-xl shadow-orange-500/30 flex items-center justify-center space-x-2 hover:scale-[1.01] transition-transform mt-2">
                        <span>Book Your Free Demo</span>
                        <i class="fas fa-arrow-right text-xs"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>


    <!-- GLOBAL SCRIPTS FOR PRICING TOGGLE & BOOK FREE LIVE DEMO MODAL -->
    <script>
        // Pricing Plan Toggle for Reference Image 3
        const pMonthly = {{ $data['lp_pricing_monthly'] ?? 999 }};
        const pYearly = {{ $data['lp_pricing_yearly'] ?? 799 }};

        window.setPricingPlan = function(plan) {
            const toggleBg = document.getElementById('pricingToggleBg');
            const btnMonthly = document.getElementById('toggleMonthlyBtn');
            const btnYearly = document.getElementById('toggleYearlyBtn');
            const priceAmount = document.getElementById('priceMainAmount');
            const pricePeriod = document.getElementById('priceMainPeriod');
            const subtext = document.getElementById('pricingSubtext');

            if (plan === 'monthly') {
                if (toggleBg) toggleBg.style.transform = 'translateX(0)';
                if (btnMonthly) {
                    btnMonthly.classList.remove('text-slate-600', 'dark:text-slate-300');
                    btnMonthly.classList.add('text-white');
                }
                if (btnYearly) {
                    btnYearly.classList.remove('text-white');
                    btnYearly.classList.add('text-slate-600', 'dark:text-slate-300');
                }
                if (priceAmount) priceAmount.textContent = '₹' + pMonthly;
                if (pricePeriod) pricePeriod.textContent = '/ month';
                if (subtext) subtext.textContent = 'Monthly Plan Selected — Switch to Yearly and save 20%';
            } else {
                if (toggleBg) toggleBg.style.transform = 'translateX(100px)';
                if (btnYearly) {
                    btnYearly.classList.remove('text-slate-600', 'dark:text-slate-300');
                    btnYearly.classList.add('text-white');
                }
                if (btnMonthly) {
                    btnMonthly.classList.remove('text-white');
                    btnMonthly.classList.add('text-slate-600', 'dark:text-slate-300');
                }
                if (priceAmount) priceAmount.textContent = '₹' + pYearly;
                if (pricePeriod) pricePeriod.textContent = '/ month (Billed Yearly)';
                if (subtext) subtext.textContent = 'Yearly Plan Selected — 20% Discount Applied!';
            }
        };

        // Live Demo Modal Controls
        window.openDemoModal = function() {
            window.generateCaptcha();
            const modal = document.getElementById('demoModal');
            const card = document.getElementById('demoModalCard');
            if (modal && card) {
                modal.classList.remove('opacity-0', 'invisible');
                modal.classList.add('opacity-100', 'visible');
                card.classList.remove('scale-95', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
                document.body.style.overflow = 'hidden';
            }
        };

        window.closeDemoModal = function() {
            const modal = document.getElementById('demoModal');
            const card = document.getElementById('demoModalCard');
            if (modal && card) {
                card.classList.remove('scale-100', 'opacity-100');
                card.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    modal.classList.remove('opacity-100', 'visible');
                    modal.classList.add('opacity-0', 'invisible');
                    document.body.style.overflow = '';
                }, 200);
            }
        };

        window.generateCaptcha = function() {
            const n1 = Math.floor(Math.random() * 10) + 1;
            const n2 = Math.floor(Math.random() * 10) + 1;
            const lbl = document.getElementById('captchaLabel');
            const exp = document.getElementById('captchaExpected');
            const ans = document.getElementById('captchaAnswer');
            if (lbl && exp) {
                lbl.textContent = `What is ${n1} + ${n2}?`;
                exp.value = n1 + n2;
                if (ans) ans.value = '';
            }
        };

        window.submitLiveDemo = function(e) {
            e.preventDefault();
            const btn = document.getElementById('demoSubmitBtn');
            const msg = document.getElementById('demoFormMsg');
            const n = document.getElementById('demo_name').value;
            const w = document.getElementById('demo_whatsapp').value;
            const em = document.getElementById('demo_email').value;
            const p = document.getElementById('demo_profession').value;
            const ans = document.getElementById('captchaAnswer').value;
            const exp = document.getElementById('captchaExpected').value;

            if (parseInt(ans) !== parseInt(exp)) {
                msg.className = 'text-xs font-bold p-2.5 rounded-xl bg-rose-500/20 text-rose-300 border border-rose-500/40';
                msg.textContent = 'Incorrect verification answer. Please try again.';
                msg.classList.remove('hidden');
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<i class=\"fas fa-spinner fa-spin mr-2\"></i> Submitting...';

            fetch('{{ route(\"demo.submit\") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    name: n,
                    whatsapp: w,
                    email: em,
                    profession: p,
                    captcha_answer: ans,
                    captcha_expected: exp
                })
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = '<span>Book Your Free Demo</span><i class=\"fas fa-arrow-right text-xs ml-2\"></i>';
                if (data.success) {
                    msg.className = 'text-xs font-bold p-3 rounded-xl bg-emerald-500/20 text-emerald-300 border border-emerald-500/40';
                    msg.textContent = data.message;
                    msg.classList.remove('hidden');
                    document.getElementById('liveDemoForm').reset();
                    setTimeout(() => {
                        window.closeDemoModal();
                        msg.classList.add('hidden');
                    }, 3000);
                } else {
                    msg.className = 'text-xs font-bold p-2.5 rounded-xl bg-rose-500/20 text-rose-300 border border-rose-500/40';
                    msg.textContent = data.message || 'An error occurred. Please try again.';
                    msg.classList.remove('hidden');
                }
            })
            .catch(() => {
                btn.disabled = false;
                btn.innerHTML = '<span>Book Your Free Demo</span><i class=\"fas fa-arrow-right text-xs ml-2\"></i>';
                msg.className = 'text-xs font-bold p-2.5 rounded-xl bg-rose-500/20 text-rose-300 border border-rose-500/40';
                msg.textContent = 'Unable to submit request. Please try contacting on WhatsApp directly.';
                msg.classList.remove('hidden');
            });
        };

        // Backdrop click listener to close demo modal
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('demoModal');
            if (modal) {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        window.closeDemoModal();
                    }
                });
            }
        });
    </script>

</body>
</html>
