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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Onest:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome & Bootstrap Icons -->
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

    <!-- Custom CSS matching Nooryak branding -->
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
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200/80 transition-all duration-300">
        <div class="max-w-[1340px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <a href="{{ url('/') }}" class="flex items-center space-x-3">
                    @if($data['lp_header_logo'])
                        <img src="{{ asset($data['lp_header_logo']) }}" alt="Nooryak Logo" class="h-12 sm:h-14 lg:h-16 w-auto object-contain transition-all">
                    @else
                        <div class="flex items-center space-x-2.5">
                            <div class="w-11 h-11 sm:w-13 sm:h-13 rounded-2xl btn-gradient flex items-center justify-center text-white font-bold text-2xl shadow-md shadow-orange-500/20">
                                <i class="fas fa-layer-group text-lg sm:text-xl"></i>
                            </div>
                            <span class="font-space font-bold text-2xl sm:text-3xl tracking-tight text-slate-900">Nooryak</span>
                        </div>
                    @endif
                </a>

                <!-- Desktop Menu Navigation -->
                <nav class="hidden lg:flex items-center space-x-8 text-sm font-semibold text-slate-600 relative">
                    <a href="#hero" class="hover:text-[#ff3d00] transition-colors py-6">Home</a>
                    
                    <!-- Solutions Dropdown -->
                    <div class="relative group">
                        <a href="#about" class="hover:text-[#ff3d00] transition-colors flex items-center py-6 cursor-pointer">
                            Solutions <i class="fas fa-chevron-down text-[10px] ml-1 transition-transform group-hover:rotate-180"></i>
                        </a>
                        <div class="absolute top-full left-1/2 -translate-x-1/2 mt-0 w-[420px] bg-[#0f172a] rounded-2xl border border-slate-700/50 shadow-2xl shadow-slate-900/50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 z-50">
                            <div class="p-4 flex flex-col space-y-2">
                                <!-- Bundle -->
                                <a href="#" class="flex items-start space-x-4 p-4 rounded-xl hover:bg-white/5 transition-colors group/item">
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
                                    <a href="#" class="flex items-start space-x-3 p-3 rounded-xl hover:bg-white/5 transition-colors">
                                        <div class="w-8 h-8 rounded-lg bg-orange-500/10 text-orange-400 flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <i class="fas fa-shopping-cart"></i>
                                        </div>
                                        <div>
                                            <span class="block text-white font-bold text-[13px] mb-0.5">Ecom Builder</span>
                                            <span class="block text-[10px] text-slate-400">Launch e-commerce stores easily.</span>
                                        </div>
                                    </a>
                                    <!-- Product 2 -->
                                    <a href="#" class="flex items-start space-x-3 p-3 rounded-xl hover:bg-white/5 transition-colors">
                                        <div class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-400 flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <i class="fas fa-globe"></i>
                                        </div>
                                        <div>
                                            <span class="block text-white font-bold text-[13px] mb-0.5">Website Builder</span>
                                            <span class="block text-[10px] text-slate-400">Create sites with AI & templates.</span>
                                        </div>
                                    </a>
                                    <!-- Product 3 -->
                                    <a href="#" class="flex items-start space-x-3 p-3 rounded-xl hover:bg-white/5 transition-colors col-span-2">
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
                                <a href="#" class="flex items-start space-x-3 p-3 rounded-xl hover:bg-white/5 transition-colors">
                                    <i class="fab fa-youtube text-red-500 text-lg mt-0.5 w-6 text-center"></i>
                                    <div>
                                        <span class="block text-white font-bold text-sm mb-0.5">YouTube</span>
                                        <span class="block text-xs text-slate-400">Tutorials & demos</span>
                                    </div>
                                </a>
                                <a href="mailto:{{ $data['lp_contact_email'] ?? '' }}" class="flex items-start space-x-3 p-3 rounded-xl hover:bg-white/5 transition-colors">
                                    <i class="far fa-envelope text-sky-400 text-lg mt-0.5 w-6 text-center"></i>
                                    <div>
                                        <span class="block text-white font-bold text-sm mb-0.5">Contact Us</span>
                                        <span class="block text-xs text-slate-400">Get in touch</span>
                                    </div>
                                </a>
                                <a href="#faq" class="flex items-start space-x-3 p-3 rounded-xl hover:bg-white/5 transition-colors">
                                    <i class="far fa-question-circle text-sky-500 text-lg mt-0.5 w-6 text-center"></i>
                                    <div>
                                        <span class="block text-white font-bold text-sm mb-0.5">FAQ</span>
                                        <span class="block text-xs text-slate-400">Common questions</span>
                                    </div>
                                </a>
                                <a href="https://wa.me/6374913298" target="_blank" class="flex items-start space-x-3 p-3 rounded-xl hover:bg-white/5 transition-colors">
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

                <!-- Action Buttons -->
                <div class="hidden sm:flex items-center space-x-3">
                    <a href="{{ $data['lp_book_demo_url'] }}" class="px-5 py-2.5 rounded-full border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-100 transition-all shadow-sm">
                        Book a Demo
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
            <a href="#hero" class="block px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-100">Home</a>
            <a href="#about" class="block px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-100">Solutions</a>
            <a href="#pricing" class="block px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-100">Pricing</a>
            <a href="#products" class="block px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-100">Products</a>
            <a href="#faq" class="block px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-100">FAQ</a>
            <div class="pt-4 border-t border-slate-100 space-y-2">
                <a href="{{ $data['lp_book_demo_url'] }}" class="block w-full text-center px-4 py-3 rounded-full border border-slate-300 text-xs font-bold text-slate-700">Book a Demo</a>
            </div>
        </div>
    </header>

    <!-- SECTION 1: HERO SECTION -->
    <section id="hero" class="relative py-8 sm:py-10 lg:py-12 overflow-hidden bg-gradient-to-b from-orange-50/60 via-amber-50/20 to-transparent reveal">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                
                <!-- Left Text Column (6 cols on lg for dedicated text space) -->
                <div class="lg:col-span-6 min-w-0 space-y-6 text-left relative z-20">
                    <div class="inline-flex items-center space-x-2 bg-orange-100/90 border border-orange-200/80 px-4 py-1.5 rounded-full text-orange-700 text-[10px] font-bold tracking-wide uppercase shadow-sm">
                        <span><i class="fas fa-users mr-1 opacity-70"></i> FOR AGENCIES | FREELANCERS | RESELLERS | DIGITAL MARKETERS</span>
                    </div>

                    <h1 class="font-space font-extrabold text-3xl sm:text-5xl lg:text-6xl text-slate-900 leading-[1.12] tracking-tight">
                        Launch Your Own SaaS Business <span class="gradient-text">Under Your Brand</span>
                    </h1>

                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed font-normal max-w-xl">
                        {{ $data['lp_hero_subtitle'] }}
                    </p>

                    <!-- CTAs -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-2">
                        <a href="{{ $data['lp_hero_cta1_url'] }}" class="btn-gradient px-8 py-4 rounded-full text-white font-bold text-xs sm:text-sm shadow-xl shadow-orange-500/30 text-center">
                            {{ $data['lp_hero_cta1_text'] }}
                        </a>

                        <a href="{{ $data['lp_hero_cta2_url'] }}" class="px-7 py-4 rounded-full bg-white border border-slate-300 text-slate-900 font-bold text-xs sm:text-sm hover:bg-slate-50 transition-all flex items-center justify-center space-x-3 shadow-sm">
                            <div class="w-6 h-6 rounded-full bg-slate-900 text-white flex items-center justify-center text-[10px]">
                                <i class="fas fa-play ml-0.5"></i>
                            </div>
                            <span>{{ $data['lp_hero_cta2_text'] }}</span>
                        </a>
                    </div>

                    <!-- Hero Feature Checkmarks -->
                    <div class="flex flex-wrap items-center gap-4 sm:gap-6 pt-2 text-xs font-bold text-slate-700">
                        @if(is_array($data['lp_hero_badges']))
                            @foreach($data['lp_hero_badges'] as $badge)
                            <div class="flex items-center space-x-2">
                                <div class="w-4 h-4 rounded-full bg-[#ff3d00] text-white flex items-center justify-center text-[9px]">
                                    <i class="fas fa-check"></i>
                                </div>
                                <span>{{ $badge }}</span>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Right Hero Image Graphic (Enlarged by ~20%, Contained Within 6-Col Right Area) -->
                <div class="lg:col-span-6 min-w-0 relative mt-6 lg:mt-0 flex items-center justify-center lg:justify-end">
                    <div class="relative z-10 w-full min-w-0 flex items-center justify-center lg:justify-end">
                        <img src="{{ asset($data['lp_hero_image'] ?? '/assets/images/herobanner_right.png') }}" alt="Nooryak SaaS Platform" class="w-full h-auto max-h-[600px] sm:max-h-[720px] lg:max-h-[820px] xl:max-h-[900px] object-contain object-center lg:object-right transition-transform duration-500 lg:scale-125">
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION 2: COUNTER STATS BAR CARD -->
    <div class="max-w-[1340px] mx-auto px-4 sm:px-6 lg:px-8 mt-4 mb-6 relative z-20 reveal">
        <div class="bg-white rounded-3xl p-4 sm:p-5 shadow-xl border border-slate-100">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4 text-center">
                @if(is_array($data['lp_stats_bar']))
                    @foreach($data['lp_stats_bar'] as $stat)
                    <div class="p-2 sm:p-2.5 rounded-2xl hover:bg-orange-50/50 transition-colors">
                        <div class="flex items-center justify-center space-x-2.5 mb-0.5">
                            <div class="w-9 h-9 rounded-xl bg-orange-100/90 text-[#ff3d00] flex items-center justify-center text-xs sm:text-sm font-bold shadow-sm flex-shrink-0">
                                <i class="{{ $stat['icon'] ?? 'fas fa-chart-pie' }}"></i>
                            </div>
                            <!-- Task 3: Animated Running Counter Number -->
                            <span class="stat-counter font-space font-extrabold text-xl sm:text-2xl text-slate-900" data-target="{{ $stat['count'] ?? '0' }}">
                                {{ $stat['count'] ?? '0' }}
                            </span>
                        </div>
                        <p class="text-[10px] sm:text-[11px] font-semibold text-slate-500 uppercase tracking-wide mt-0.5">{{ $stat['label'] ?? '' }}</p>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <!-- SECTION 3: ABOUT NOORYAK -->
    <section id="about" class="py-10 lg:py-14 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <!-- Left Content -->
                <div class="lg:col-span-6 space-y-5 text-left">
                    <span class="badge-pill">{{ $data['lp_about_tag'] }}</span>
                    <h2 class="font-space font-extrabold text-3xl sm:text-4xl text-slate-900 tracking-tight">
                        {{ $data['lp_about_title'] }}
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        {{ $data['lp_about_desc'] }}
                    </p>
                    <p class="text-xs sm:text-sm font-bold text-[#ff3d00]">
                        Your Brand. Our Technology. Unlimited Growth.
                    </p>

                    <!-- 3 Feature Icons in 1 Row (Task 1: 2 columns on mobile to prevent overflow) -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 sm:gap-3 pt-3">
                        @if(is_array($data['lp_about_features']))
                            @foreach(array_slice($data['lp_about_features'], 0, 3) as $feat)
                            <div class="flex items-center space-x-2.5 p-3 rounded-2xl bg-orange-50/60 border border-orange-100">
                                <div class="w-8 h-8 rounded-full bg-[#ff3d00] text-white flex items-center justify-center text-xs flex-shrink-0">
                                    <i class="{{ $feat['icon'] ?? 'fas fa-star' }}"></i>
                                </div>
                                <h4 class="font-bold text-[11px] sm:text-xs text-slate-900 leading-snug break-words">{{ $feat['title'] ?? '' }}</h4>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Right Graphic -->
                <div class="lg:col-span-6">
                    <div class="relative">
                        <img src="{{ asset($data['lp_about_image'] ?? '/assets/images/about_right.png') }}" alt="About Nooryak" class="w-full h-auto rounded-3xl">
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION 4: WHITE LABEL SAAS PARTNER & MASTER PANEL CARDS (Task 1: Master Label SaaS Glassmorphism Coming Soon Overlay) -->
    <section class="py-10 lg:py-12 bg-slate-50/80 border-t border-slate-200/80 reveal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">
                
                <!-- Card 1: White Label SaaS Partner Card (Active) -->
                @php $model = $data['lp_model_cards'][0] ?? null; @endphp
                @if($model)
                <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xl border border-slate-200/90 flex flex-col justify-between hover:shadow-2xl transition-all duration-300 relative overflow-hidden text-left">
                    <div class="text-left">
                        <span class="inline-block px-3.5 py-1 rounded-full text-xs font-bold mb-3 bg-orange-100 text-[#ff3d00] border border-orange-200/80">
                            {{ $model['badge'] ?? 'White Label SaaS' }}
                        </span>
                        <h3 class="font-space font-extrabold text-2xl sm:text-3xl text-slate-900 mb-2 text-left">
                            {{ $model['title'] ?? 'White Label SaaS Partner' }}
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 mb-6 leading-relaxed text-left">
                            {{ $model['desc'] ?? '' }}
                        </p>

                        <!-- Feature List (Left) & Screen Preview (Right) -->
                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-5 items-center mb-6 text-left">
                            <div class="sm:col-span-6 space-y-2.5 text-left">
                                @php
                                    $cardFeatures = (isset($model['features']) && is_array($model['features']) && count($model['features']) > 0) 
                                        ? $model['features'] 
                                        : [
                                            'Launch Your Own SaaS Brand',
                                            'Sell Unlimited Subscriptions',
                                            'SaaS Products Included',
                                            'Manage Your Customers & Business',
                                            'Custom Domain & Branding',
                                            'Build Recurring Revenue'
                                        ];
                                @endphp
                                @foreach($cardFeatures as $f)
                                <div class="flex items-start space-x-2.5 text-xs font-medium text-slate-700 text-left">
                                    <div class="w-4 h-4 rounded-full bg-[#ff3d00] text-white flex items-center justify-center text-[9px] mt-0.5 flex-shrink-0">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <span>{{ $f }}</span>
                                </div>
                                @endforeach
                            </div>
                            <div class="sm:col-span-6 mt-4 sm:mt-0">
                                <img src="{{ asset($model['image'] ?? '/assets/images/user_dashboard.png') }}" alt="Dashboard Preview" class="w-full h-auto rounded-2xl shadow-lg border border-slate-200">
                            </div>
                        </div>
                    </div>

                    <!-- CTA Button -->
                    <div class="pt-4 border-t border-slate-100 text-left mt-auto">
                        <a href="{{ $model['cta_url'] ?? '/login' }}" class="btn-gradient inline-flex items-center justify-between w-full px-6 py-3.5 rounded-2xl font-bold text-xs sm:text-sm text-white transition-all shadow-lg shadow-orange-500/20">
                            <span>{{ $model['cta_text'] ?? 'Start with White Label SaaS' }}</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                @endif

                <!-- Card 2: White Label Master Panel (With Glassmorphism COMING SOON Overlay) -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xl border border-slate-200/90 flex flex-col justify-between relative overflow-hidden text-left group">
                    
                    <!-- Background Card Content (Visible behind glass blur) -->
                    <div class="text-left filter blur-[2px] opacity-75 select-none pointer-events-none">
                        <span class="inline-block px-3.5 py-1 rounded-full text-xs font-bold mb-3 bg-slate-100 text-slate-700 border border-slate-200">
                            Master Label SaaS
                        </span>
                        <h3 class="font-space font-extrabold text-2xl sm:text-3xl text-slate-900 mb-2 text-left">
                            White Label Master Panel
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 mb-6 leading-relaxed text-left">
                            Ideal for business network builders who want to create & manage SaaS reseller partners.
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-5 items-center mb-6 text-left">
                            <div class="sm:col-span-6 space-y-2.5 text-left">
                                <div class="flex items-start space-x-2.5 text-xs font-medium text-slate-700">
                                    <div class="w-4 h-4 rounded-full bg-slate-800 text-white flex items-center justify-center text-[9px] mt-0.5 flex-shrink-0">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <span>Create Unlimited Reseller Panels</span>
                                </div>
                                <div class="flex items-start space-x-2.5 text-xs font-medium text-slate-700">
                                    <div class="w-4 h-4 rounded-full bg-slate-800 text-white flex items-center justify-center text-[9px] mt-0.5 flex-shrink-0">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <span>Partner Management System</span>
                                </div>
                                <div class="flex items-start space-x-2.5 text-xs font-medium text-slate-700">
                                    <div class="w-4 h-4 rounded-full bg-slate-800 text-white flex items-center justify-center text-[9px] mt-0.5 flex-shrink-0">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <span>Set Your Own Reseller Pricing</span>
                                </div>
                                <div class="flex items-start space-x-2.5 text-xs font-medium text-slate-700">
                                    <div class="w-4 h-4 rounded-full bg-slate-800 text-white flex items-center justify-center text-[9px] mt-0.5 flex-shrink-0">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <span>Central Master Admin Portal</span>
                                </div>
                            </div>
                            <div class="sm:col-span-6 mt-4 sm:mt-0">
                                <img src="{{ asset('/assets/images/user_dashboard2.png') }}" alt="Master Panel Preview" class="w-full h-auto rounded-2xl shadow-lg border border-slate-200">
                            </div>
                        </div>
                    </div>

                    <!-- Glassmorphism "COMING SOON" Overlay -->
                    <div class="absolute inset-0 z-20 bg-slate-900/60 backdrop-blur-md flex flex-col items-center justify-center p-6 text-center transition-all duration-300">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-amber-500 to-orange-500 text-white flex items-center justify-center text-2xl shadow-xl shadow-orange-500/30 mb-4 animate-bounce">
                            <i class="fas fa-rocket"></i>
                        </div>
                        <span class="px-4 py-1.5 rounded-full bg-gradient-to-r from-amber-500 via-orange-500 to-red-500 text-white font-extrabold text-xs tracking-widest uppercase shadow-lg shadow-orange-500/40 mb-2.5">
                            COMING SOON
                        </span>
                        <h4 class="font-space font-extrabold text-xl sm:text-2xl text-white tracking-tight">Master Label SaaS Panel</h4>
                        <p class="text-xs text-slate-200 max-w-xs mt-2 leading-relaxed">
                            Empower your agency network to build & sell SaaS partners under your master brand. Launching soon!
                        </p>
                        <div class="mt-5 inline-flex items-center space-x-2 px-5 py-2.5 rounded-full bg-white/10 border border-white/25 text-white font-bold text-xs backdrop-blur-md">
                            <i class="fas fa-lock text-amber-400 text-xs"></i>
                            <span>Under Active Development</span>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <!-- SECTION 5: OUR PRODUCTS (First Reference Layout for Products + Glassmorphism Coming Soon Cards) -->
    <section id="products" class="py-12 lg:py-16 bg-white reveal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-3xl mx-auto mb-10 sm:mb-14">
                <span class="badge-pill mx-auto">{{ $data['lp_products_tag'] }}</span>
                <h2 class="font-space font-extrabold text-2xl sm:text-4xl text-slate-900 tracking-tight mt-3">
                    {{ $data['lp_products_title'] }}
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 mt-2">
                    {{ $data['lp_products_desc'] }}
                </p>
            </div>

            <!-- Products Grid: 3-Column Per Row Layout (2 Active Products + 1 Coming Soon WhatsApp Automation Card) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-stretch">

                <!-- CARD 1: LAUNCHSHOP ECOM BUILDER -->
                <div class="bg-white rounded-3xl border border-orange-200/90 shadow-xl hover:shadow-2xl transition-all duration-300 overflow-hidden flex flex-col justify-between p-6 sm:p-7 group relative">
                    <div>
                        <!-- Brand Logo -->
                        <div class="flex items-center justify-between mb-4">
                            <img src="{{ asset('/assets/landing_page/ecom_logo.png') }}" alt="Ecom Builder Logo" class="h-10 w-auto object-contain">
                            <span class="text-[11px] font-extrabold tracking-widest text-[#ff3d00] uppercase bg-orange-50 px-2.5 py-1 rounded-lg border border-orange-200/60">STORE BUILDER</span>
                        </div>

                        <h3 class="font-space font-extrabold text-xl sm:text-2xl text-slate-900 mb-2 leading-tight">Complete E-Commerce Solution</h3>
                        <p class="text-xs text-slate-600 leading-relaxed font-normal">Launch your online store, manage products, payments, and customers — all in one place.</p>
                        
                        <!-- Product Image Mockup -->
                        <div class="my-5 relative overflow-hidden rounded-2xl">
                            <img src="{{ asset('/assets/landing_page/ecombuilder_image.png') }}" alt="Launchshop E-Commerce Solution" class="w-full h-48 object-cover object-top rounded-2xl shadow-md border border-orange-200/60 transform group-hover:scale-[1.03] transition-transform duration-300">
                        </div>

                        <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider mb-3">INCLUDED FEATURES:</p>

                        <ul class="space-y-2.5 mb-6 text-xs text-slate-700 font-medium">
                            <li class="flex items-center space-x-2.5">
                                <div class="w-4 h-4 rounded-full bg-[#ff3d00] text-white flex items-center justify-center text-[9px] flex-shrink-0"><i class="fas fa-check"></i></div>
                                <span>1 Online Store (Product Module)</span>
                            </li>
                            <li class="flex items-center space-x-2.5">
                                <div class="w-4 h-4 rounded-full bg-[#ff3d00] text-white flex items-center justify-center text-[9px] flex-shrink-0"><i class="fas fa-check"></i></div>
                                <span>Up to 1,000 Orders / Customers</span>
                            </li>
                            <li class="flex items-center space-x-2.5">
                                <div class="w-4 h-4 rounded-full bg-[#ff3d00] text-white flex items-center justify-center text-[9px] flex-shrink-0"><i class="fas fa-check"></i></div>
                                <span>Inventory & Order Management</span>
                            </li>
                            <li class="flex items-center space-x-2.5">
                                <div class="w-4 h-4 rounded-full bg-[#ff3d00] text-white flex items-center justify-center text-[9px] flex-shrink-0"><i class="fas fa-check"></i></div>
                                <span>Basic Analytics & Reports</span>
                            </li>
                            <li class="flex items-center space-x-2.5">
                                <div class="w-4 h-4 rounded-full bg-[#ff3d00] text-white flex items-center justify-center text-[9px] flex-shrink-0"><i class="fas fa-check"></i></div>
                                <span>Custom Domain Setup</span>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <a href="https://launchshop.saasreselling.com" target="_blank" class="btn-gradient w-full py-3.5 px-6 rounded-2xl font-bold text-xs sm:text-sm text-white text-center shadow-lg shadow-orange-500/25 flex items-center justify-center space-x-2 hover:scale-[1.02] transition-transform">
                            <span>Explore Launchshop</span>
                            <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                        <p class="text-center text-[11px] text-slate-400 font-medium mt-3">Trusted by 10,000+ Sellers</p>
                    </div>
                </div>

                <!-- CARD 2: WEBSITE BUILDER -->
                <div class="bg-white rounded-3xl border-2 border-blue-400/80 shadow-xl hover:shadow-2xl transition-all duration-300 overflow-hidden flex flex-col justify-between p-6 sm:p-7 group relative">
                    <div>
                        <!-- Brand Logo -->
                        <div class="flex items-center justify-between mb-4">
                            <img src="{{ asset('/assets/landing_page/websitebuilder_logo.png') }}" alt="Website Builder Logo" class="h-10 w-auto object-contain">
                            <span class="text-[11px] font-extrabold tracking-widest text-blue-600 uppercase bg-blue-50 px-2.5 py-1 rounded-lg border border-blue-200/60">WEBSITE BUILDER</span>
                        </div>

                        <h3 class="font-space font-extrabold text-xl sm:text-2xl text-slate-900 mb-2 leading-tight">Professional Website Solution</h3>
                        <p class="text-xs text-slate-600 leading-relaxed font-normal">Create stunning websites with ease using our drag-and-drop website builder with zero coding required.</p>
                        
                        <!-- Product Image Mockup -->
                        <div class="my-5 relative overflow-hidden rounded-2xl">
                            <img src="{{ asset('/assets/landing_page/websitebuilder_image.png') }}" alt="Website Builder Solution" class="w-full h-48 object-cover object-top rounded-2xl shadow-md border border-blue-200/60 transform group-hover:scale-[1.03] transition-transform duration-300">
                        </div>

                        <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider mb-3">INCLUDED FEATURES:</p>

                        <ul class="space-y-2.5 mb-6 text-xs text-slate-700 font-medium">
                            <li class="flex items-center space-x-2.5">
                                <div class="w-4 h-4 rounded-full bg-blue-600 text-white flex items-center justify-center text-[9px] flex-shrink-0"><i class="fas fa-check"></i></div>
                                <span>Unlimited Pages & Custom Designs</span>
                            </li>
                            <li class="flex items-center space-x-2.5">
                                <div class="w-4 h-4 rounded-full bg-blue-600 text-white flex items-center justify-center text-[9px] flex-shrink-0"><i class="fas fa-check"></i></div>
                                <span>Drag & Drop Visual Builder</span>
                            </li>
                            <li class="flex items-center space-x-2.5">
                                <div class="w-4 h-4 rounded-full bg-blue-600 text-white flex items-center justify-center text-[9px] flex-shrink-0"><i class="fas fa-check"></i></div>
                                <span>Custom Domain & Free SSL</span>
                            </li>
                            <li class="flex items-center space-x-2.5">
                                <div class="w-4 h-4 rounded-full bg-blue-600 text-white flex items-center justify-center text-[9px] flex-shrink-0"><i class="fas fa-check"></i></div>
                                <span>SEO Tools & Analytics Integration</span>
                            </li>
                            <li class="flex items-center space-x-2.5">
                                <div class="w-4 h-4 rounded-full bg-blue-600 text-white flex items-center justify-center text-[9px] flex-shrink-0"><i class="fas fa-check"></i></div>
                                <span>AI Templates & Interactive Widgets</span>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <a href="https://websitebuilder.saasreselling.com" target="_blank" class="w-full bg-blue-600 hover:bg-blue-700 py-3.5 px-6 rounded-2xl font-bold text-xs sm:text-sm text-white text-center shadow-lg shadow-blue-500/25 flex items-center justify-center space-x-2 hover:scale-[1.02] transition-transform">
                            <span>Explore Website Builder</span>
                            <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                        <p class="text-center text-[11px] text-slate-400 font-medium mt-3">Trusted by 50,000+ Creators</p>
                    </div>
                </div>

                <!-- CARD 3: WHATSAPP AUTOMATION TOOL (COMING SOON) -->
                <div class="bg-white rounded-3xl border border-emerald-200/90 shadow-xl overflow-hidden flex flex-col justify-between p-6 sm:p-7 relative group">
                    
                    <!-- Background Content visible behind blur -->
                    <div class="w-full flex flex-col justify-between filter blur-[3px] opacity-60 select-none pointer-events-none">
                        <div>
                            <div class="inline-flex items-center space-x-2 bg-emerald-50 px-3 py-1.5 rounded-xl border border-emerald-200 mb-4">
                                <div class="w-6 h-6 rounded-lg bg-emerald-600 text-white flex items-center justify-center text-xs font-bold">
                                    <i class="fab fa-whatsapp"></i>
                                </div>
                                <span class="font-space font-extrabold text-xs text-emerald-900 uppercase">WHATSAPP AUTOMATION</span>
                            </div>
                            <h3 class="font-space font-extrabold text-xl text-slate-900 mb-2">WhatsApp Automation Tool</h3>
                            <p class="text-xs text-slate-600">Automate customer messaging, broadcast marketing campaigns, and AI chatbots directly on WhatsApp.</p>
                            <div class="my-5 rounded-2xl bg-emerald-50 p-4 border border-emerald-200/60">
                                <div class="w-full h-32 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 font-bold text-sm">
                                    <i class="fab fa-whatsapp text-4xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Glassmorphism "COMING SOON" Overlay -->
                    <div class="absolute inset-0 z-20 bg-slate-950/75 backdrop-blur-md flex flex-col items-center justify-center p-6 text-center transition-all duration-300">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-emerald-500 via-teal-500 to-green-500 text-white flex items-center justify-center text-3xl shadow-xl shadow-emerald-500/40 mb-4 animate-bounce">
                            <i class="fab fa-whatsapp text-white"></i>
                        </div>
                        <span class="px-4 py-1.5 rounded-full bg-gradient-to-r from-emerald-600 via-teal-600 to-green-600 text-white font-extrabold text-xs tracking-widest uppercase shadow-lg shadow-emerald-500/40 mb-3">
                            COMING SOON
                        </span>
                        <h4 class="font-space font-extrabold text-xl sm:text-2xl text-white tracking-tight">WhatsApp Automation Tool</h4>
                        <p class="text-xs text-slate-200 max-w-xs mt-2.5 leading-relaxed font-medium">
                            Automate bulk WhatsApp campaigns, instant AI auto-replies, catalog sharing, and order notifications. Launching soon!
                        </p>
                        <div class="mt-6 inline-flex items-center space-x-2 px-5 py-2.5 rounded-full bg-white/10 border border-white/25 text-white font-bold text-xs backdrop-blur-md shadow-inner">
                            <i class="fas fa-lock text-emerald-400 text-sm"></i>
                            <span>Under Active Development</span>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </section>

    <!-- SECTION 6: HOW NOORYAK WORKS (Task 2: Mobile scroll animation & downward arrows between steps) -->
    <section class="py-10 lg:py-14 bg-slate-50/60 border-t border-slate-200/70 reveal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            
            <span class="badge-pill mx-auto mb-3">{{ $data['lp_how_works_tag'] }}</span>
            <h2 class="font-space font-extrabold text-2xl sm:text-4xl text-slate-900 tracking-tight mb-14">
                {{ $data['lp_how_works_title'] }}
            </h2>

            @if(is_array($data['lp_how_works_steps']))
            <div class="relative flex flex-col lg:flex-row items-center justify-between gap-4 lg:gap-4 max-w-6xl mx-auto">
                @foreach($data['lp_how_works_steps'] as $index => $step)
                    
                    <!-- Step Item with mobile scroll reveal animation -->
                    <div class="step-card flex-1 flex flex-col items-center text-center group z-10 px-2 transition-all duration-700 ease-out transform opacity-0 translate-y-8">
                        <!-- Icon Circle -->
                        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full btn-gradient flex items-center justify-center text-white text-xl sm:text-2xl shadow-xl shadow-orange-500/25 mb-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="{{ $step['icon'] ?? 'fas fa-check' }}"></i>
                        </div>
                        
                        <!-- Step Label -->
                        <span class="text-xs font-bold text-[#ff3d00] uppercase tracking-wide mb-1">Step {{ $step['step'] ?? ($index + 1) }}</span>
                        
                        <!-- Title -->
                        <h4 class="font-space font-extrabold text-base sm:text-lg text-slate-900 mb-2 leading-snug">{{ $step['title'] ?? '' }}</h4>
                        
                        <!-- Description -->
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed max-w-[240px]">{{ $step['desc'] ?? '' }}</p>
                    </div>

                    <!-- Downward Arrow Connector between steps (MOBILE ONLY - Task 2) -->
                    @if(!$loop->last)
                    <div class="step-arrow flex lg:hidden items-center justify-center my-3 text-[#ff3d00] animate-bounce transition-all duration-500 opacity-0 transform translate-y-4">
                        <div class="w-9 h-9 rounded-full bg-orange-100/90 border border-orange-200 text-[#ff3d00] flex items-center justify-center text-xs shadow-sm">
                            <i class="fas fa-arrow-down"></i>
                        </div>
                    </div>

                    <!-- Right Arrow Connector between steps (DESKTOP ONLY) -->
                    <div class="hidden lg:flex items-center justify-center text-slate-400 font-bold -mt-16 text-lg">
                        <i class="fas fa-arrow-right text-slate-400 opacity-60"></i>
                    </div>
                    @endif

                @endforeach
            </div>
            @endif

        </div>
    </section>

    <!-- SECTION 7: WHY CHOOSE & REVENUE CALCULATOR -->
    <section class="py-10 lg:py-14 bg-white reveal border-t border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-stretch">
                
                <!-- Left: Why Choose Us (7 cols) -->
                <div class="lg:col-span-7 lg:pr-8 xl:pr-16">
                    <div class="mb-8 text-left">
                        <span class="badge-pill mb-3 inline-block">{{ $data['lp_why_choose_tag'] }}</span>
                        <h2 class="font-space font-extrabold text-2xl sm:text-4xl text-slate-900 tracking-tight">
                            {{ $data['lp_why_choose_title'] }}
                        </h2>
                    </div>

                    @if(is_array($data['lp_why_choose_items']))
                    @php
                        // Curated pastel background color mappings to match reference screenshot
                        $colorStyles = [
                            0 => ['bg' => 'bg-emerald-100/90 text-emerald-600'],
                            1 => ['bg' => 'bg-sky-100/90 text-sky-600'],
                            2 => ['bg' => 'bg-orange-100/90 text-orange-600'],
                            3 => ['bg' => 'bg-teal-100/90 text-teal-600'],
                            4 => ['bg' => 'bg-purple-100/90 text-purple-600'],
                            5 => ['bg' => 'bg-blue-100/90 text-blue-600'],
                        ];
                    @endphp
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 sm:gap-6">
                        @foreach($data['lp_why_choose_items'] as $index => $item)
                        @php
                            $style = $colorStyles[$index % count($colorStyles)];
                        @endphp
                        <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition-all flex flex-col items-start text-left">
                            <div class="w-9 h-9 rounded-xl {{ $style['bg'] }} flex items-center justify-center text-sm mb-3">
                                <i class="{{ $item['icon'] ?? 'fas fa-shield-alt' }}"></i>
                            </div>
                            <h4 class="font-space font-extrabold text-sm sm:text-base text-slate-900 mb-1.5">{{ $item['title'] ?? '' }}</h4>
                            <p class="text-[11px] sm:text-xs text-slate-500 leading-relaxed">{{ $item['desc'] ?? '' }}</p>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                <!-- Right: Revenue Calculator (5 cols) -->
                <div class="lg:col-span-5 flex">
                    <div class="relative w-full text-white rounded-3xl p-7 sm:p-8 shadow-2xl border border-slate-800/80 overflow-hidden flex flex-col justify-between" style="background-image: url('{{ asset('/images/revenue_calculator.png') }}'); background-size: cover; background-color: #0f172a;">
                        
                        <div class="flex items-center justify-between border-b border-slate-800 pb-4 mb-5">
                            <div>
                                <h3 class="font-space font-extrabold text-lg text-white">{{ $data['lp_rev_calc_title'] }}</h3>
                                <p class="text-[11px] text-slate-400 mt-0.5">{{ $data['lp_rev_calc_subtitle'] }}</p>
                            </div>
                            <div class="w-9 h-9 rounded-xl bg-orange-500/20 text-orange-400 flex items-center justify-center text-base">
                                <i class="fas fa-calculator"></i>
                            </div>
                        </div>

                        <!-- Interactive Range Slider -->
                        <div class="space-y-3 mb-5">
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-slate-300 font-medium">Active Customers:</span>
                                <span id="customerCountDisplay" class="font-extrabold text-orange-400 text-base bg-orange-500/20 px-3 py-0.5 rounded-lg">100</span>
                            </div>
                            <input type="range" id="customerSlider" min="10" max="1000" step="10" value="{{ $data['lp_rev_calc_default_customers'] }}">
                        </div>

                        <!-- Revenue Output -->
                        <div class="bg-white/10 rounded-2xl p-5 border border-white/10 text-center mb-5 backdrop-blur-sm">
                            <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider mb-1">Estimated Monthly Revenue</p>
                            <p id="monthlyRevDisplay" class="font-space font-extrabold text-2xl sm:text-3xl text-orange-400">
                                ₹99,900 <span class="text-xs font-normal text-slate-400">/month</span>
                            </p>
                            <p class="text-[10px] text-slate-400 mt-1.5">
                                Based on <span id="calcSubtextCount">100</span> customers × ₹{{ $data['lp_rev_calc_price_per_customer'] }}/mo
                            </p>
                        </div>

                        <!-- Bullets -->
                        <div class="grid grid-cols-2 gap-2.5 mb-6 text-[11px] font-semibold">
                            @if(is_array($data['lp_rev_calc_bullets']))
                                @foreach($data['lp_rev_calc_bullets'] as $bullet)
                                <div class="flex items-center space-x-2 text-slate-200">
                                    <i class="fas fa-check-circle text-orange-400 text-[11px]"></i>
                                    <span>{{ $bullet }}</span>
                                </div>
                                @endforeach
                            @endif
                        </div>

                        <!-- CTA -->
                        <a href="{{ $data['lp_cta_button_url'] }}" class="btn-gradient w-full block text-center py-3.5 rounded-2xl text-white font-bold text-[11px] shadow-lg">
                            Start Building Your Revenue <i class="fas fa-arrow-right ml-2 text-[10px]"></i>
                        </a>

                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- SECTION 8: PRICING -->
    <section id="pricing" class="py-12 lg:py-16 bg-slate-50/80 border-t border-slate-200/80 relative">
        <div class="max-w-[1100px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <div class="text-center max-w-2xl mx-auto mb-10">
                <span class="badge-pill mx-auto mb-3">{{ $data['lp_pricing_tag'] }}</span>
                <h2 class="font-space font-extrabold text-3xl sm:text-4xl text-slate-900 tracking-tight mt-3">
                    {{ $data['lp_pricing_title'] }}
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-2">{{ $data['lp_pricing_desc'] }}</p>
            </div>

            <!-- Monthly / Yearly Toggle -->
            <div class="flex flex-col items-center justify-center mb-10">
                <div class="flex items-center space-x-4">
                    <div class="bg-white p-1 rounded-full border border-slate-200 shadow-sm flex items-center relative">
                        <!-- Active Indicator Background -->
                        <div id="pricingToggleBg" class="absolute left-1 top-1 bottom-1 w-[90px] btn-gradient rounded-full transition-all duration-300 ease-in-out"></div>
                        
                        <button id="toggleMonthlyBtn" class="relative z-10 w-[90px] py-2 text-xs font-bold text-white transition-colors duration-300" onclick="setPricing('monthly')">Monthly</button>
                        <button id="toggleYearlyBtn" class="relative z-10 w-[90px] py-2 text-xs font-bold text-slate-600 transition-colors duration-300" onclick="setPricing('yearly')">Yearly</button>
                    </div>
                    <div class="text-[11px] font-bold text-orange-600 flex items-center">
                        <i class="fas fa-reply fa-flip-vertical mr-1 -mt-1"></i> Save 20%
                    </div>
                </div>
                
                <div id="pricingSubtextContainer" class="mt-4 bg-white px-4 py-1.5 rounded-full border border-orange-100 shadow-sm flex items-center space-x-2 transition-colors duration-300">
                    <i id="pricingSubtextIcon" class="far fa-calendar-alt text-orange-500 text-[10px]"></i>
                    <span id="pricingSubtext" class="text-[10px] font-bold text-orange-600 transition-colors duration-300">Monthly Plan Selected — Switch to Yearly and save 20%</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch max-w-full mx-auto">
                
                <!-- Card 1: White-Label Panel -->
                <div class="bg-white rounded-[2rem] p-8 shadow-xl border border-slate-200 flex flex-col relative hover:shadow-2xl transition-all duration-300">
                    <div class="flex justify-between items-start mb-6">
                        <div class="inline-flex items-center space-x-2 bg-orange-50 text-orange-600 text-[8px] font-extrabold px-3 py-1.5 rounded-full uppercase tracking-wider border border-orange-100 h-max">
                            <i class="fas fa-user-tie"></i> <span>For Agencies & Freelancers</span>
                        </div>
                        
                        <div class="text-right flex flex-col items-end">
                            <span class="text-[9px] font-extrabold text-orange-600 leading-tight">3 Powerful Products<br/>One Dashboard<br/>Your Brand</span>
                            <div class="mt-1 relative right-2">
                                <i class="fas fa-level-down-alt fa-flip-horizontal text-orange-400 text-lg"></i>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-start">
                        <div class="pr-2">
                            <h3 class="font-space font-extrabold text-2xl text-slate-900 mb-0.5 tracking-tight">White-Label Panel</h3>
                            <p class="text-[11px] text-slate-500 leading-relaxed min-h-[50px] pr-2 mt-2">Get 5 core SaaS products, unlimited client subscriptions, and complete white-label control under your own domain. Best for solo marketers and growth agencies.</p>
                        </div>
                        <div class="w-[120px] sm:w-[140px] flex-shrink-0 mt-2 rounded-lg overflow-hidden shadow-md border border-slate-100">
                            <img src="{{ asset('/assets/images/user_dashboard.png') }}" class="w-full h-auto object-cover opacity-90" alt="Dashboard Preview">
                        </div>
                    </div>
                    
                    <div class="my-6 flex flex-col items-start justify-end h-16">
                        <div class="flex items-end">
                            <span id="priceValue1" class="font-space font-extrabold text-[44px] text-slate-900 tracking-tighter leading-none">₹999</span>
                            <span id="pricePeriod1" class="text-sm text-slate-500 font-bold mb-1.5 ml-1">/ month</span>
                        </div>
                        <div id="priceSave1" class="hidden mt-1"></div>
                    </div>

                    <a href="{{ $data['lp_cta_button_url'] }}" class="btn-gradient w-full block text-center py-3.5 rounded-xl text-white font-bold text-sm shadow-md mb-4 hover:scale-[1.02] transition-transform">
                        Get Started <i class="fas fa-arrow-right ml-1"></i>
                    </a>

                    <div class="flex items-center justify-between text-[8px] sm:text-[9px] font-semibold text-slate-400 border-b border-slate-100 pb-5 mb-5">
                        <span class="flex items-center"><i class="fas fa-video mr-1"></i> 1:1 Demo on Google Meet</span>
                        <span class="flex items-center"><i class="far fa-credit-card mr-1"></i> No Credit Card</span>
                        <span class="flex items-center"><i class="fas fa-check-circle mr-1"></i> 100% Free Demo</span>
                    </div>

                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-extrabold text-slate-900">Everything You Get</span>
                        <span class="text-[9px] font-bold text-orange-600">All features included in yearly plan</span>
                    </div>

                    <div class="grid grid-cols-1 gap-y-3 mt-2 text-[11px] text-slate-700 font-medium">
                        <div class="flex items-start space-x-2"><i class="fas fa-check text-orange-500 mt-0.5 text-[10px]"></i> <span>1 White-Label Platform</span></div>
                        <div class="flex items-start space-x-2"><i class="fas fa-check text-orange-500 mt-0.5 text-[10px]"></i> <span>5 White-Label SaaS Products Included</span></div>
                        <div class="flex items-start space-x-2"><i class="fas fa-check text-orange-500 mt-0.5 text-[10px]"></i> <span>Custom Domain & Logo</span></div>
                        <div class="flex items-start space-x-2"><i class="fas fa-check text-orange-500 mt-0.5 text-[10px]"></i> <span>Unlimited End-Customers</span></div>
                        <div class="flex items-start space-x-2"><i class="fas fa-check text-orange-500 mt-0.5 text-[10px]"></i> <span>Full Partner Dashboard Access</span></div>
                    </div>

                    <div class="mt-8 bg-orange-50/70 rounded-xl p-4 flex items-start space-x-3 border border-orange-100">
                        <div class="w-8 h-8 rounded-md bg-orange-100 flex items-center justify-center text-orange-600 flex-shrink-0"><i class="fas fa-rocket"></i></div>
                        <div>
                            <span class="block text-[11px] font-extrabold text-slate-900 mb-0.5">Most Popular Choice</span>
                            <span class="block text-[9px] text-slate-500 leading-relaxed font-medium">Perfect for getting started and building your customer base.</span>
                        </div>
                    </div>
                </div>

                <!-- Card 2: White-Label Master Panel (With Coming Soon Overlay) -->
                <div class="bg-white rounded-[2rem] p-8 shadow-2xl border-2 border-orange-400 flex flex-col relative overflow-hidden group hover:shadow-orange-500/10 transition-all duration-300">
                    
                    <!-- Blurred background content -->
                    <div class="flex flex-col flex-grow filter blur-[2px] opacity-60 select-none pointer-events-none">
                        <div class="flex justify-between items-start mb-6">
                            <div class="inline-flex items-center space-x-2 bg-slate-100 text-slate-600 text-[8px] font-extrabold px-3 py-1.5 rounded-full uppercase tracking-wider border border-slate-200 h-max">
                                <i class="fas fa-network-wired"></i> <span>For Network Builders</span>
                            </div>
                            
                            <div class="flex flex-col items-end">
                                <span class="text-[9px] font-extrabold text-slate-600 text-right leading-tight mr-1">Create<br/>Manage<br/>Scale</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-start">
                            <div class="pr-2">
                                <h3 class="font-space font-extrabold text-2xl text-slate-900 mb-0.5 tracking-tight">White-Label Master Panel</h3>
                                <p class="text-[11px] text-slate-500 leading-relaxed min-h-[50px] pr-2 mt-2">Create and manage unlimited white-label sub-panels. Onboard your own reseller partners and control your entire ecosystem.</p>
                            </div>
                            <div class="w-[120px] sm:w-[140px] flex-shrink-0 mt-2 rounded-lg overflow-hidden shadow-md border border-slate-800 bg-slate-900">
                                <img src="{{ asset('/assets/images/user_dashboard2.png') }}" class="w-full h-auto object-cover opacity-90" alt="Master Dashboard Preview">
                            </div>
                        </div>
                        
                        <div class="my-6 flex flex-col items-start justify-end h-16">
                            <div class="flex items-end">
                                <span id="priceValue2" class="font-space font-extrabold text-[44px] text-slate-900 tracking-tighter leading-none">₹2,499</span>
                                <span id="pricePeriod2" class="text-sm text-slate-500 font-bold mb-1.5 ml-1">/ month</span>
                            </div>
                            <div id="priceSave2" class="hidden mt-1"></div>
                        </div>

                        <div class="w-full block text-center py-3.5 rounded-xl bg-slate-800 text-white font-bold text-sm shadow-md mb-4">
                            Get Started <i class="fas fa-arrow-right ml-1"></i>
                        </div>

                        <div class="flex items-center justify-between text-[8px] sm:text-[9px] font-semibold text-slate-400 border-b border-slate-100 pb-5 mb-5">
                            <span class="flex items-center"><i class="fas fa-video mr-1"></i> 1:1 Demo on Google Meet</span>
                            <span class="flex items-center"><i class="far fa-credit-card mr-1"></i> No Credit Card</span>
                            <span class="flex items-center"><i class="fas fa-check-circle mr-1"></i> 100% Free Demo</span>
                        </div>

                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm font-extrabold text-slate-900">Everything You Get</span>
                            <span class="text-[9px] font-bold text-slate-600">All features included in yearly plan</span>
                        </div>

                        <div class="grid grid-cols-1 gap-y-3 mt-2 text-[11px] text-slate-700 font-medium">
                            <div class="flex items-start space-x-2"><i class="fas fa-check text-slate-800 mt-0.5 text-[10px]"></i> <span>Unlimited White Label Panels</span></div>
                            <div class="flex items-start space-x-2"><i class="fas fa-check text-slate-800 mt-0.5 text-[10px]"></i> <span>5 White-Label SaaS Products</span></div>
                            <div class="flex items-start space-x-2"><i class="fas fa-check text-slate-800 mt-0.5 text-[10px]"></i> <span>Partner Management System</span></div>
                            <div class="flex items-start space-x-2"><i class="fas fa-check text-slate-800 mt-0.5 text-[10px]"></i> <span>Centralized Master Dashboard</span></div>
                            <div class="flex items-start space-x-2"><i class="fas fa-check text-slate-800 mt-0.5 text-[10px]"></i> <span>Complete Reseller Control</span></div>
                        </div>

                        <div class="mt-8 bg-slate-50 rounded-xl p-4 flex items-start space-x-3 border border-slate-200">
                            <div class="w-8 h-8 rounded-md bg-slate-200 flex items-center justify-center text-slate-700 flex-shrink-0"><i class="fas fa-globe"></i></div>
                            <div>
                                <span class="block text-[11px] font-extrabold text-slate-900 mb-0.5">Scale Globally</span>
                                <span class="block text-[9px] text-slate-500 leading-relaxed font-medium">Ideal for businesses building a vast reseller network.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Coming Soon Overlay -->
                    <div class="absolute inset-0 z-20 bg-slate-900/60 backdrop-blur-md flex flex-col items-center justify-center p-6 text-center transition-all duration-300">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-amber-500 to-orange-600 text-white flex items-center justify-center text-3xl shadow-xl shadow-orange-500/30 mb-5 animate-bounce">
                            <i class="fas fa-rocket"></i>
                        </div>
                        <span class="px-5 py-2 rounded-full bg-gradient-to-r from-amber-500 via-orange-500 to-red-500 text-white font-extrabold text-sm tracking-widest uppercase shadow-lg shadow-orange-500/40 mb-3">
                            COMING SOON
                        </span>
                        <h4 class="font-space font-extrabold text-2xl text-white tracking-tight">Master Label Panel</h4>
                        <p class="text-xs text-slate-200 max-w-xs mt-3 leading-relaxed font-medium">
                            Empower your agency network to build & sell SaaS partners under your master brand. Launching soon!
                        </p>
                        <div class="mt-6 inline-flex items-center space-x-2 px-5 py-2.5 rounded-full bg-white/10 border border-white/25 text-white font-bold text-xs backdrop-blur-md">
                            <i class="fas fa-lock text-amber-400 text-xs"></i>
                            <span>Under Active Development</span>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        
        <script>
            // Pricing Toggle Script
            function setPricing(plan) {
                const toggleBg = document.getElementById('pricingToggleBg');
                const btnMonthly = document.getElementById('toggleMonthlyBtn');
                const btnYearly = document.getElementById('toggleYearlyBtn');
                const price1 = document.getElementById('priceValue1');
                const price2 = document.getElementById('priceValue2');
                const period1 = document.getElementById('pricePeriod1');
                const period2 = document.getElementById('pricePeriod2');
                
                const save1 = document.getElementById('priceSave1');
                const save2 = document.getElementById('priceSave2');

                const subtextContainer = document.getElementById('pricingSubtextContainer');
                const subtextIcon = document.getElementById('pricingSubtextIcon');
                const subtext = document.getElementById('pricingSubtext');

                if (plan === 'monthly') {
                    toggleBg.style.transform = 'translateX(0)';
                    btnMonthly.classList.remove('text-slate-600');
                    btnMonthly.classList.add('text-white');
                    btnYearly.classList.remove('text-white');
                    btnYearly.classList.add('text-slate-600');
                    
                    price1.innerHTML = '₹999';
                    price2.innerHTML = '₹2,499';
                    period1.innerHTML = '/ month';
                    period2.innerHTML = '/ month';
                    
                    save1.classList.add('hidden');
                    save2.classList.add('hidden');
                    
                    if(subtextContainer) {
                        subtextContainer.classList.remove('bg-green-50', 'border-green-200');
                        subtextContainer.classList.add('bg-white', 'border-orange-100');
                        subtextIcon.classList.remove('text-green-600', 'fa-gift');
                        subtextIcon.classList.add('text-orange-500', 'fa-calendar-alt');
                        subtext.classList.remove('text-green-700');
                        subtext.classList.add('text-orange-600');
                        subtext.innerHTML = 'Monthly Plan Selected — Switch to Yearly and save 20%';
                    }
                } else {
                    toggleBg.style.transform = 'translateX(90px)';
                    btnYearly.classList.remove('text-slate-600');
                    btnYearly.classList.add('text-white');
                    btnMonthly.classList.remove('text-white');
                    btnMonthly.classList.add('text-slate-600');
                    
                    price1.innerHTML = '₹9,990';
                    price2.innerHTML = '₹24,990';
                    period1.innerHTML = '/ year';
                    period2.innerHTML = '/ year';
                    
                    save1.innerHTML = '<span class="inline-block text-[10px] ml-2 px-2 py-0.5 rounded-full bg-green-50 text-green-600 border border-green-200 font-extrabold flex items-center shadow-sm"><i class="fas fa-check mr-1 text-[8px]"></i> Save ₹1,998</span>';
                    save1.classList.remove('hidden');
                    save1.classList.add('flex');
                    
                    save2.innerHTML = '<span class="inline-block text-[10px] ml-2 px-2 py-0.5 rounded-full bg-green-50 text-green-600 border border-green-200 font-extrabold flex items-center shadow-sm"><i class="fas fa-check mr-1 text-[8px]"></i> Save ₹4,998</span>';
                    save2.classList.remove('hidden');
                    save2.classList.add('flex');

                    if(subtextContainer) {
                        subtextContainer.classList.remove('bg-white', 'border-orange-100');
                        subtextContainer.classList.add('bg-green-50', 'border-green-200');
                        subtextIcon.classList.remove('text-orange-500', 'fa-calendar-alt');
                        subtextIcon.classList.add('text-green-600', 'fa-gift');
                        subtext.classList.remove('text-orange-600');
                        subtext.classList.add('text-green-700');
                        subtext.innerHTML = 'Yearly Plan Selected (Best Value) — Save 20%';
                    }
                }
            }
        </script>
    </section>

    <!-- SECTION 9: YOUR GROWTH PATH & TESTIMONIALS -->
    <section class="py-10 lg:py-14 bg-white border-t border-slate-200/80 reveal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
            
            <!-- Growth Path -->
            <div>
                <div class="text-center mb-10">
                    <span class="badge-pill mx-auto">{{ $data['lp_growth_tag'] }}</span>
                    <h2 class="font-space font-extrabold text-2xl sm:text-4xl text-slate-900 tracking-tight mt-3">
                        {{ $data['lp_growth_title'] }}
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-600 mt-1">{{ $data['lp_growth_subtitle'] }}</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @if(is_array($data['lp_growth_steps']))
                        @foreach($data['lp_growth_steps'] as $gstep)
                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-start space-x-3">
                            <div class="w-8 h-8 rounded-full bg-orange-100 text-[#ff3d00] font-bold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="{{ $gstep['icon'] ?? 'fas fa-arrow-right' }}"></i>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-[#ff3d00] uppercase tracking-wider">{{ $gstep['step'] ?? '' }}</span>
                                <h5 class="font-bold text-xs text-slate-900 mt-0.5">{{ $gstep['title'] ?? '' }}</h5>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Testimonials (Task 3: Full Width Cards on Mobile - No text truncation) -->
            <div>
                <div class="text-center mb-10">
                    <span class="badge-pill mx-auto">{{ $data['lp_testimonials_tag'] }}</span>
                    <h2 class="font-space font-extrabold text-2xl sm:text-3xl text-slate-900 tracking-tight mt-3">
                        {{ $data['lp_testimonials_title'] }}
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-600 mt-1">{{ $data['lp_testimonials_subtitle'] }}</p>
                </div>

                <!-- Testimonials Mobile Horizontal Auto-Slider & Desktop Grid -->
                <div class="relative max-w-full overflow-hidden">
                    <div id="testimonialSlider" class="flex overflow-x-auto snap-x snap-mandatory scroll-smooth md:grid md:grid-cols-3 gap-6 pb-4 md:pb-0 no-scrollbar">
                        @if(is_array($data['lp_testimonials_items']))
                            @foreach($data['lp_testimonials_items'] as $tIdx => $testi)
                            <div class="testimonial-card w-full min-w-full md:min-w-0 snap-center flex-shrink-0 p-6 sm:p-8 rounded-3xl bg-slate-50 border border-slate-200/90 shadow-sm flex flex-col justify-between space-y-4">
                                <div class="space-y-3">
                                    <div class="flex items-center space-x-1 text-amber-400 text-xs">
                                        @for($r = 0; $r < ($testi['rating'] ?? 5); $r++)
                                            <i class="fas fa-star"></i>
                                        @endfor
                                    </div>
                                    <p class="text-xs sm:text-sm text-slate-700 leading-relaxed font-medium break-words">"{{ $testi['quote'] ?? '' }}"</p>
                                </div>
                                <div class="pt-4 border-t border-slate-200/70 flex items-center justify-between text-xs sm:text-sm">
                                    <span class="font-bold text-slate-900">{{ $testi['name'] ?? '' }}</span>
                                    <span class="text-slate-500 font-medium text-xs">{{ $testi['role'] ?? '' }}</span>
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
    <section id="faq" class="py-14 lg:py-20 bg-slate-50/60 border-t border-slate-200/60 reveal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
                
                <!-- Left FAQ Header (4 Cols) -->
                <div class="lg:col-span-4 text-left space-y-4">
                    <span class="inline-block px-3.5 py-1 rounded-full text-xs font-bold bg-orange-100 text-[#ff3d00] border border-orange-200">
                        {{ $data['lp_faqs_tag'] }}
                    </span>
                    <h2 class="font-space font-extrabold text-3xl sm:text-4xl text-slate-900 tracking-tight leading-tight">
                        {{ $data['lp_faqs_title'] }}
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        {{ $data['lp_faqs_subtitle'] }}
                    </p>
                    <div class="pt-2">
                        <a href="#contact" class="inline-flex items-center text-xs font-bold text-[#ff3d00] hover:text-orange-700 space-x-1">
                            <span>View All FAQs</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>

                <!-- Right FAQ Accordion Card (8 Cols) -->
                <div class="lg:col-span-8 bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80 space-y-3">
                    @if(is_array($data['lp_faqs_items']))
                        @foreach($data['lp_faqs_items'] as $fIndex => $faq)
                        <div class="border-b border-slate-100 last:border-0 pb-3 last:pb-0">
                            <button class="faq-toggle w-full py-3.5 text-left font-space font-bold text-sm text-slate-900 bg-white hover:text-[#ff3d00] flex items-center justify-between focus:outline-none transition-colors">
                                <span class="pr-4">{{ $faq['question'] ?? '' }}</span>
                                <i class="fas fa-plus text-xs text-slate-400 font-normal transition-transform duration-200 ml-2 flex-shrink-0"></i>
                            </button>
                            <div class="faq-content hidden pt-1 pb-3 text-xs text-slate-600 leading-relaxed">
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
    <footer class="bg-white text-slate-600 py-12 border-t border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-12 gap-6 sm:gap-8 pb-10 border-b border-slate-200/80">
                
                <!-- Col 1: Logo & Socials (Span 2 on mobile, 3 Cols on lg) -->
                <div class="col-span-2 lg:col-span-3 space-y-4">
                    <a href="{{ url('/') }}" class="flex items-center space-x-2.5 text-slate-900 font-space font-bold text-xl">
                        @if($data['lp_header_logo'])
                            <img src="{{ asset($data['lp_header_logo']) }}" alt="Nooryak" class="h-9 w-auto object-contain">
                        @else
                            <div class="w-9 h-9 rounded-xl btn-gradient flex items-center justify-center text-white text-sm font-bold shadow-sm">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <span class="text-xl font-bold tracking-tight text-slate-900">Nooryak</span>
                        @endif
                    </a>
                    <p class="text-xs text-slate-500 max-w-xs leading-relaxed">
                        {{ $data['lp_footer_desc'] }}
                    </p>
                    <div class="flex items-center space-x-2.5 pt-1">
                        @if(!empty($data['lp_fb_url']))<a href="{{ $data['lp_fb_url'] }}" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-orange-50 hover:text-[#ff3d00] flex items-center justify-center text-slate-500 text-xs transition-colors"><i class="fab fa-facebook-f"></i></a>@endif
                        @if(!empty($data['lp_tw_url']))<a href="{{ $data['lp_tw_url'] }}" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-orange-50 hover:text-[#ff3d00] flex items-center justify-center text-slate-500 text-xs transition-colors"><i class="fab fa-twitter"></i></a>@endif
                        @if(!empty($data['lp_li_url']))<a href="{{ $data['lp_li_url'] }}" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-orange-50 hover:text-[#ff3d00] flex items-center justify-center text-slate-500 text-xs transition-colors"><i class="fab fa-linkedin-in"></i></a>@endif
                        @if(!empty($data['lp_yt_url']))<a href="{{ $data['lp_yt_url'] }}" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-orange-50 hover:text-[#ff3d00] flex items-center justify-center text-slate-500 text-xs transition-colors"><i class="fab fa-discord"></i></a>@endif
                        @if(!empty($data['lp_ig_url']))<a href="{{ $data['lp_ig_url'] }}" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-orange-50 hover:text-[#ff3d00] flex items-center justify-center text-slate-500 text-xs transition-colors"><i class="fab fa-instagram"></i></a>@endif
                    </div>
                </div>

                <!-- Col 2: Quick Links (Col 1 on mobile, 2 Cols on lg) -->
                <div class="col-span-1 lg:col-span-2 space-y-3">
                    <h5 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Quick Links</h5>
                    <ul class="space-y-2 text-xs font-medium text-slate-600">
                        <li><a href="#hero" class="hover:text-[#ff3d00] transition-colors">Home</a></li>
                        <li><a href="#about" class="hover:text-[#ff3d00] transition-colors">Solutions</a></li>
                        <li><a href="#pricing" class="hover:text-[#ff3d00] transition-colors">Pricing</a></li>
                        <li><a href="#products" class="hover:text-[#ff3d00] transition-colors">Resources</a></li>
                        <li><a href="#faq" class="hover:text-[#ff3d00] transition-colors">Blog</a></li>
                    </ul>
                </div>

                <!-- Col 3: Our Products (Task 5: Dynamic Active Products Only - Remove Unwanted Products) -->
                <div class="col-span-1 lg:col-span-2 space-y-3">
                    <h5 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Our Products</h5>
                    <ul class="space-y-2 text-xs font-medium text-slate-600">
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
                    <h5 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Support</h5>
                    <ul class="space-y-2 text-xs font-medium text-slate-600">
                        <li><a href="#faq" class="hover:text-[#ff3d00] transition-colors">Help Center</a></li>
                        <li><a href="mailto:{{ $data['lp_contact_email'] }}" class="hover:text-[#ff3d00] transition-colors">Contact Us</a></li>
                        <li><a href="{{ $data['lp_book_demo_url'] }}" class="hover:text-[#ff3d00] transition-colors">Book a Demo</a></li>
                        <li><a href="{{ route('agency.privacy') }}" class="hover:text-[#ff3d00] transition-colors">Privacy Policy</a></li>
                        <li><a href="{{ route('agency.terms') }}" class="hover:text-[#ff3d00] transition-colors">Terms & Conditions</a></li>
                        <li><a href="{{ route('agency.refund') }}" class="hover:text-[#ff3d00] transition-colors">Refund Policy</a></li>
                        <li><a href="{{ route('agency.shipping') }}" class="hover:text-[#ff3d00] transition-colors">Shipping Policy</a></li>
                    </ul>
                </div>

                <!-- Col 5: Newsletter Subscription (Span 2 on mobile, 3 Cols on lg) -->
                <div class="col-span-2 lg:col-span-3 space-y-3">
                    <h5 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Subscribe to Our Newsletter</h5>
                    <p class="text-xs text-slate-500">Get the latest updates and offers.</p>
                    <form onsubmit="event.preventDefault(); alert('Subscribed successfully!');" class="flex items-center space-x-0 pt-1">
                        <input type="email" placeholder="Enter your email" required class="w-full px-3.5 py-2.5 rounded-l-xl border border-r-0 border-slate-200 text-xs text-slate-800 bg-slate-50 focus:bg-white focus:outline-none focus:ring-1 focus:ring-orange-500">
                        <button type="submit" class="px-4 py-2.5 rounded-r-xl btn-gradient text-white font-bold text-xs flex-shrink-0 transition-all">
                            Subscribe
                        </button>
                    </form>
                </div>

            </div>

            <!-- Bottom Copyright & Legal Links (Task 6: Legal Policy Links) -->
            <div class="pt-6 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 font-medium gap-3">
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
        // Mobile Drawer Navigation Toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }

        // Interactive Revenue Calculator Slider
        const slider = document.getElementById('customerSlider');
        const countDisplay = document.getElementById('customerCountDisplay');
        const revDisplay = document.getElementById('monthlyRevDisplay');
        const subtextCount = document.getElementById('calcSubtextCount');
        const pricePerCustomer = {{ $data['lp_rev_calc_price_per_customer'] ?? 999 }};

        if (slider && countDisplay && revDisplay) {
            function updateCalculator() {
                const customers = parseInt(slider.value);
                const totalRev = customers * pricePerCustomer;
                countDisplay.textContent = customers;
                if (subtextCount) subtextCount.textContent = customers;
                revDisplay.innerHTML = '₹' + totalRev.toLocaleString('en-IN') + ' <span class="text-xs font-normal text-slate-400">/month</span>';
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
    <!-- Exit Intent / Delayed Popup Modal -->
    <div id="promoModal" class="fixed inset-0 z-[100] flex items-start md:items-center justify-center bg-slate-900/80 backdrop-blur-sm opacity-0 invisible transition-all duration-500 overflow-y-auto">
        
        <!-- Modal Container -->
        <div class="relative w-full max-w-4xl mx-auto my-6 md:my-auto mx-4 flex flex-col md:flex-row bg-white rounded-2xl md:rounded-3xl shadow-2xl overflow-hidden transform scale-95 opacity-0 transition-all duration-500 delay-100" id="promoModalContent">
            
            <!-- Close Button -->
            <button onclick="closePromoModal()" class="absolute top-3 right-3 sm:top-4 sm:right-4 z-50 w-8 h-8 flex items-center justify-center rounded-full bg-slate-800/80 text-white hover:bg-slate-700 transition-colors focus:outline-none shadow-lg">
                <i class="fas fa-times"></i>
            </button>
            
            <!-- Left Side (Light) -->
            <div class="w-full md:w-1/2 p-5 md:p-12 flex flex-col justify-center">
                <div class="inline-flex items-center space-x-1.5 bg-orange-100 text-orange-600 px-3 py-1 md:py-1.5 rounded-full text-[9px] md:text-[10px] font-bold uppercase tracking-wider w-max mb-3 md:mb-5 border border-orange-200">
                    <i class="fas fa-bolt"></i> <span>Exclusive Offer</span>
                </div>
                
                <h3 class="font-space font-extrabold text-2xl md:text-4xl text-slate-900 leading-tight mb-2 md:mb-4 tracking-tight">
                    🚀 All-in-One White Label SaaS Panel
                </h3>
                
                <p class="text-[11px] md:text-sm text-slate-500 mb-4 md:mb-8 leading-relaxed font-medium">
                    Save Extra on All-in-One White-Label 2 Year Plan
                </p>
                
                <div class="space-y-2 md:space-y-3 mb-4 md:mb-8 text-[11px] md:text-sm font-semibold text-slate-700">
                    <div class="flex items-center space-x-2 md:space-x-3"><i class="fas fa-check-circle text-green-500"></i> <span>5 White-Label SaaS Products</span></div>
                    <div class="flex items-center space-x-2 md:space-x-3"><i class="fas fa-check-circle text-green-500"></i> <span>One Simple Subscription</span></div>
                    <div class="flex items-center space-x-2 md:space-x-3"><i class="fas fa-check-circle text-green-500"></i> <span>Priority Customer Support</span></div>
                    <div class="flex items-center space-x-2 md:space-x-3"><i class="fas fa-check-circle text-green-500"></i> <span>Unlimited SaaS Subscriptions</span></div>
                    <div class="flex items-center space-x-2 md:space-x-3"><i class="fas fa-check-circle text-green-500"></i> <span>Fully White-Label Control</span></div>
                </div>
                
                <div class="flex items-center space-x-2 md:space-x-3 text-sm md:text-lg">
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center"><i class="fas fa-bolt"></i></div>
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg bg-purple-50 text-purple-500 flex items-center justify-center"><i class="fas fa-comment-dots"></i></div>
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg bg-pink-50 text-pink-500 flex items-center justify-center"><i class="far fa-envelope"></i></div>
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg bg-amber-50 text-amber-500 flex items-center justify-center"><i class="fas fa-coins"></i></div>
                </div>
            </div>
            
            <!-- Right Side (Dark) -->
            <div class="w-full md:w-1/2 bg-slate-900 p-5 md:p-12 flex flex-col items-center justify-center text-center relative overflow-hidden">
                <!-- Background ambient glow -->
                <div class="absolute inset-0 bg-gradient-to-br from-orange-500/10 to-transparent pointer-events-none"></div>
                
                <div class="relative z-10 w-full">
                    <div class="w-12 h-12 md:w-16 md:h-16 rounded-full bg-white mx-auto flex items-center justify-center mb-3 md:mb-6 shadow-lg shadow-orange-500/20">
                        <i class="fas fa-stopwatch text-xl md:text-3xl text-slate-800"></i>
                    </div>
                    
                    <h3 class="font-space font-extrabold text-xl md:text-3xl text-white mb-1.5 md:mb-3 tracking-tight">
                        WAIT! BEFORE YOU GO
                    </h3>
                    
                    <p class="text-[10px] md:text-sm text-slate-400 mb-4 md:mb-6">
                        Save Extra on All-in-One White-Label 2 Year Plan
                    </p>
                    
                    <div class="flex items-center justify-center space-x-4 w-full mb-4 md:mb-8">
                        <div class="w-full h-px bg-slate-800"></div>
                        <span class="text-[9px] md:text-[10px] font-bold text-slate-500 uppercase tracking-widest whitespace-nowrap">Offer Expires In</span>
                        <div class="w-full h-px bg-slate-800"></div>
                    </div>
                    
                    <!-- Timer -->
                    <div class="flex items-center justify-center space-x-3 md:space-x-4 mb-5 md:mb-8">
                        <div class="flex flex-col items-center">
                            <div class="w-12 h-12 md:w-20 md:h-20 bg-white rounded-xl md:rounded-2xl flex items-center justify-center shadow-lg mb-1.5 md:mb-2">
                                <span class="font-space font-extrabold text-2xl md:text-4xl text-slate-900" id="promoHrs">02</span>
                            </div>
                            <span class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase">Hrs</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="w-12 h-12 md:w-20 md:h-20 bg-white rounded-xl md:rounded-2xl flex items-center justify-center shadow-lg mb-1.5 md:mb-2">
                                <span class="font-space font-extrabold text-2xl md:text-4xl text-slate-900" id="promoMins">13</span>
                            </div>
                            <span class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase">Min</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="w-12 h-12 md:w-20 md:h-20 bg-white rounded-xl md:rounded-2xl flex items-center justify-center shadow-lg mb-1.5 md:mb-2">
                                <span class="font-space font-extrabold text-2xl md:text-4xl text-slate-900" id="promoSecs">29</span>
                            </div>
                            <span class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase">Sec</span>
                        </div>
                    </div>
                    
                    <div class="bg-orange-500 text-slate-900 font-bold text-[10px] md:text-sm py-2 px-2 md:py-2.5 md:px-4 rounded-lg mb-3 md:mb-4 shadow-lg shadow-orange-500/20">
                        Don't lose your savings! Go for 2 Years Plan & Get 10% Off
                    </div>
                    
                    <a href="https://wa.me/6374913298" target="_blank" class="w-full block bg-white hover:bg-slate-50 text-slate-900 font-extrabold text-[13px] md:text-base py-3 md:py-4 rounded-xl transition-colors shadow-lg">
                        Claim My Discount Now <i class="fas fa-arrow-right ml-2 text-[10px] md:text-sm"></i>
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
            
            let timeInSeconds = (2 * 3600) + (13 * 60) + 29; // 2 hours, 13 minutes, 29 seconds
            
            const timerInterval = setInterval(() => {
                timeInSeconds--;
                if(timeInSeconds <= 0) {
                    clearInterval(timerInterval);
                }
                
                const h = Math.floor(timeInSeconds / 3600);
                const m = Math.floor((timeInSeconds % 3600) / 60);
                const s = Math.floor(timeInSeconds % 60);
                
                document.getElementById('promoHrs').textContent = h.toString().padStart(2, '0');
                document.getElementById('promoMins').textContent = m.toString().padStart(2, '0');
                document.getElementById('promoSecs').textContent = s.toString().padStart(2, '0');
            }, 1000);
        }
    </script>
</body>
</html>
