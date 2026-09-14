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
                            50: '#eff6ff',
                            100: '#dbeafe',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                        accent: {
                            purple: '#6366f1',
                            indigo: '#4f46e5',
                            orange: '#FF5722',
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
            background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .btn-gradient {
            background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%);
            transition: all 0.3s ease;
        }
        .btn-gradient:hover {
            box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.4);
            transform: translateY(-2px);
        }

        .badge-pill {
            background: #e0e7ff;
            color: #4338ca;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 4px 14px;
            border-radius: 9999px;
            display: inline-block;
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
            background: #2563eb;
            cursor: pointer;
            -webkit-appearance: none;
            margin-top: -7px;
            box-shadow: 0 0 10px rgba(37, 99, 235, 0.5);
        }

        .handwritten { font-family: 'Poppins', cursive; font-style: italic; }
    </style>
</head>
<body class="antialiased">

    <!-- TOP ANNOUNCEMENT BAR -->
    @if(!empty($data['lp_announcement_text']))
    <div class="bg-gradient-to-r from-blue-900 via-indigo-900 to-blue-900 text-white text-[11px] sm:text-xs py-2 px-4 text-center font-medium tracking-wide flex items-center justify-center gap-2">
        <span class="bg-blue-500/30 text-blue-200 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider">{{ $data['lp_announcement_badge'] }}</span>
        <span>{{ $data['lp_announcement_text'] }}</span>
    </div>
    @endif

    <!-- STICKY NAVBAR -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200/80 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <a href="{{ url('/') }}" class="flex items-center space-x-3">
                    @if($data['lp_header_logo'])
                        <img src="{{ asset($data['lp_header_logo']) }}" alt="Nooryak Logo" class="h-9 sm:h-10 w-auto">
                    @else
                        <div class="flex items-center space-x-2">
                            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white font-bold text-xl shadow-md shadow-blue-500/20">
                                <i class="fas fa-layer-group text-base sm:text-lg"></i>
                            </div>
                            <span class="font-space font-bold text-xl sm:text-2xl tracking-tight text-slate-900">Nooryak</span>
                        </div>
                    @endif
                </a>

                <!-- Desktop Menu Navigation -->
                <nav class="hidden lg:flex items-center space-x-8 text-sm font-semibold text-slate-600">
                    <a href="#hero" class="hover:text-blue-600 transition-colors">Home</a>
                    <a href="#about" class="hover:text-blue-600 transition-colors">Solutions <i class="fas fa-chevron-down text-[10px] ml-1"></i></a>
                    <a href="#pricing" class="hover:text-blue-600 transition-colors">Pricing</a>
                    <a href="#products" class="hover:text-blue-600 transition-colors">Resources <i class="fas fa-chevron-down text-[10px] ml-1"></i></a>
                </nav>

                <!-- Action Buttons -->
                <div class="hidden sm:flex items-center space-x-3">
                    <a href="{{ $data['lp_book_demo_url'] }}" class="px-5 py-2.5 rounded-full border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-100 transition-all shadow-sm">
                        Book a Demo
                    </a>
                    <a href="{{ $data['lp_cta_button_url'] }}" class="btn-gradient px-6 py-2.5 rounded-full text-white text-xs font-bold shadow-lg shadow-blue-600/30 flex items-center space-x-2">
                        <span>{{ $data['lp_cta_button_text'] }}</span>
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
            <a href="#hero" class="block px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-100">Home</a>
            <a href="#about" class="block px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-100">Solutions</a>
            <a href="#pricing" class="block px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-100">Pricing</a>
            <a href="#products" class="block px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-100">Products</a>
            <a href="#faq" class="block px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-100">FAQ</a>
            <div class="pt-4 border-t border-slate-100 space-y-2">
                <a href="{{ $data['lp_book_demo_url'] }}" class="block w-full text-center px-4 py-3 rounded-full border border-slate-300 text-xs font-bold text-slate-700">Book a Demo</a>
                <a href="{{ $data['lp_cta_button_url'] }}" class="block w-full text-center btn-gradient text-white px-4 py-3 rounded-full text-xs font-bold shadow-md">
                    {{ $data['lp_cta_button_text'] }}
                </a>
            </div>
        </div>
    </header>

    <!-- SECTION 1: HERO SECTION -->
    <section id="hero" class="relative pt-10 pb-16 lg:pt-16 lg:pb-28 overflow-hidden bg-gradient-to-b from-blue-50/60 via-indigo-50/20 to-transparent">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                
                <!-- Left Text Column -->
                <div class="lg:col-span-7 space-y-6 text-left">
                    <div class="inline-flex items-center space-x-2 bg-blue-100/80 border border-blue-200 px-3.5 py-1.5 rounded-full text-blue-700 text-[11px] font-bold tracking-wide uppercase">
                        <i class="fas fa-sparkles text-blue-600"></i>
                        <span>YOUR BRAND. OUR TECHNOLOGY. UNLIMITED GROWTH.</span>
                    </div>

                    <h1 class="font-space font-extrabold text-3xl sm:text-5xl lg:text-6xl text-slate-900 leading-[1.15] tracking-tight">
                        Launch Your Own SaaS Business <span class="gradient-text">Under Your Brand</span>
                    </h1>

                    <p class="text-sm sm:text-base lg:text-lg text-slate-600 leading-relaxed font-normal max-w-2xl">
                        {{ $data['lp_hero_subtitle'] }}
                    </p>

                    <!-- CTAs -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-2">
                        <a href="{{ $data['lp_hero_cta1_url'] }}" class="btn-gradient px-8 py-4 rounded-full text-white font-bold text-xs sm:text-sm shadow-xl shadow-blue-600/30 flex items-center justify-center space-x-3 group">
                            <span>{{ $data['lp_hero_cta1_text'] }}</span>
                            <i class="fas fa-arrow-right text-xs transition-transform group-hover:translate-x-1"></i>
                        </a>

                        <a href="{{ $data['lp_hero_cta2_url'] }}" class="px-7 py-4 rounded-full bg-white border border-slate-300 text-slate-800 font-bold text-xs sm:text-sm hover:bg-slate-50 transition-all flex items-center justify-center space-x-3 shadow-sm">
                            <div class="w-7 h-7 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs">
                                <i class="fas fa-play text-[9px] ml-0.5"></i>
                            </div>
                            <span>{{ $data['lp_hero_cta2_text'] }}</span>
                        </a>
                    </div>

                    <!-- Hero Feature Checkmarks -->
                    <div class="flex flex-wrap items-center gap-4 sm:gap-6 pt-3 text-xs font-bold text-slate-700">
                        @if(is_array($data['lp_hero_badges']))
                            @foreach($data['lp_hero_badges'] as $badge)
                            <div class="flex items-center space-x-2">
                                <div class="w-4 h-4 rounded-full bg-blue-600 text-white flex items-center justify-center text-[9px]">
                                    <i class="fas fa-check"></i>
                                </div>
                                <span>{{ $badge }}</span>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Right Hero Image / Dashboard Graphic -->
                <div class="lg:col-span-5 relative mt-6 lg:mt-0">
                    <div class="relative z-10">
                        <img src="{{ asset($data['lp_hero_image'] ?? '/assets/images/herobanner_right.png') }}" alt="Nooryak SaaS Platform" class="w-full h-auto rounded-3xl shadow-2xl border border-slate-200">
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION 2: STATS BAR -->
    <section class="py-6 bg-white border-y border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 sm:gap-6 text-center">
                @if(is_array($data['lp_stats_bar']))
                    @foreach($data['lp_stats_bar'] as $stat)
                    <div class="p-3 sm:p-4 rounded-2xl hover:bg-slate-50 transition-colors">
                        <div class="flex items-center justify-center space-x-2.5 mb-1">
                            <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-xs sm:text-sm">
                                <i class="{{ $stat['icon'] ?? 'fas fa-chart-pie' }}"></i>
                            </div>
                            <span class="font-space font-extrabold text-xl sm:text-2xl text-slate-900">{{ $stat['count'] ?? '' }}</span>
                        </div>
                        <p class="text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wide">{{ $stat['label'] ?? '' }}</p>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>

    <!-- SECTION 3: ABOUT NOORYAK -->
    <section id="about" class="py-16 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                
                <!-- Left Graphic -->
                <div class="lg:col-span-5 order-2 lg:order-1">
                    <div class="relative">
                        <img src="{{ asset($data['lp_about_image'] ?? '/assets/images/about_right.png') }}" alt="About Nooryak" class="w-full h-auto rounded-3xl shadow-xl border border-slate-200">
                    </div>
                </div>

                <!-- Right Content -->
                <div class="lg:col-span-7 order-1 lg:order-2 space-y-5">
                    <span class="badge-pill">{{ $data['lp_about_tag'] }}</span>
                    <h2 class="font-space font-extrabold text-2xl sm:text-4xl text-slate-900 tracking-tight">
                        {{ $data['lp_about_title'] }}
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        {{ $data['lp_about_desc'] }}
                    </p>

                    <!-- Feature Badges -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-3">
                        @if(is_array($data['lp_about_features']))
                            @foreach($data['lp_about_features'] as $feat)
                            <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200/80 text-center hover:shadow-sm transition-all">
                                <div class="w-9 h-9 mx-auto rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-sm mb-1.5">
                                    <i class="{{ $feat['icon'] ?? 'fas fa-star' }}"></i>
                                </div>
                                <h4 class="font-bold text-[11px] text-slate-800">{{ $feat['title'] ?? '' }}</h4>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION 4: MODEL CARDS (Model 01 & Model 02) -->
    <section class="py-14 bg-slate-50/80 border-t border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                @if(is_array($data['lp_model_cards']))
                    @foreach($data['lp_model_cards'] as $index => $model)
                    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xl border border-slate-200 flex flex-col justify-between hover:shadow-2xl transition-all duration-300 relative overflow-hidden">
                        
                        <div>
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold text-white mb-3 {{ $index == 0 ? 'bg-emerald-500' : 'bg-indigo-600' }}">
                                {{ $model['badge'] ?? 'Model' }}
                            </span>
                            <h3 class="font-space font-extrabold text-xl sm:text-2xl text-slate-900 mb-2">
                                {{ $model['title'] ?? '' }}
                            </h3>
                            <p class="text-xs text-slate-600 mb-5 leading-relaxed">
                                {{ $model['desc'] ?? '' }}
                            </p>

                            <!-- Feature Grid (2 cols) & Preview Image -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-center mb-6">
                                <div class="space-y-2.5">
                                    @if(isset($model['features']) && is_array($model['features']))
                                        @foreach($model['features'] as $f)
                                        <div class="flex items-start space-x-2 text-xs font-medium text-slate-700">
                                            <i class="fas fa-check-circle text-blue-600 mt-0.5 flex-shrink-0"></i>
                                            <span>{{ $f }}</span>
                                        </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="mt-4 sm:mt-0">
                                    <img src="{{ asset($model['image'] ?? ($index == 0 ? '/assets/images/user_dashboard.png' : '/assets/images/user_dashboard2.png')) }}" alt="Dashboard Preview" class="w-full h-auto rounded-2xl shadow-md border border-slate-200">
                                </div>
                            </div>
                        </div>

                        <!-- CTA Button -->
                        <div class="pt-4 border-t border-slate-100">
                            <a href="{{ $model['cta_url'] ?? '/login' }}" class="inline-flex items-center justify-between w-full px-6 py-3.5 rounded-2xl font-bold text-xs text-white {{ $index == 0 ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gradient-to-r from-indigo-600 to-purple-600 hover:opacity-95' }} transition-all shadow-md">
                                <span>{{ $model['cta_text'] ?? 'Get Started' }}</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>

                    </div>
                    @endforeach
                @endif

            </div>
        </div>
    </section>

    <!-- SECTION 5: OUR PRODUCTS -->
    <section id="products" class="py-16 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="badge-pill mx-auto">{{ $data['lp_products_tag'] }}</span>
                <h2 class="font-space font-extrabold text-2xl sm:text-4xl text-slate-900 tracking-tight mt-3">
                    {{ $data['lp_products_title'] }}
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 mt-2">
                    {{ $data['lp_products_desc'] }}
                </p>
            </div>

            <!-- 5 Product Cards Horizontal Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                @if(is_array($data['lp_products_grid']))
                    @foreach($data['lp_products_grid'] as $prod)
                    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 hover:bg-white hover:shadow-xl transition-all duration-200 flex flex-col justify-between">
                        <div>
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white text-base shadow-md mb-4" style="background-color: {{ $prod['color'] ?? '#2563eb' }}">
                                <i class="{{ $prod['icon'] ?? 'fas fa-box' }}"></i>
                            </div>
                            <h4 class="font-space font-bold text-sm text-slate-900 mb-2">{{ $prod['title'] ?? '' }}</h4>
                            <p class="text-xs text-slate-600 leading-relaxed mb-4">{{ $prod['desc'] ?? '' }}</p>
                        </div>
                        <a href="{{ $prod['link'] ?? '/login' }}" class="inline-flex items-center text-xs font-bold text-blue-600 hover:text-blue-700 group mt-auto">
                            <span>Learn More</span>
                            <i class="fas fa-arrow-right text-[10px] ml-1.5 transition-transform group-hover:translate-x-1"></i>
                        </a>
                    </div>
                    @endforeach
                @endif
            </div>

        </div>
    </section>

    <!-- SECTION 6: HOW NOORYAK WORKS -->
    <section class="py-16 bg-slate-50/80 border-t border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="badge-pill mx-auto">{{ $data['lp_how_works_tag'] }}</span>
            <h2 class="font-space font-extrabold text-2xl sm:text-4xl text-slate-900 tracking-tight mt-3 mb-12">
                {{ $data['lp_how_works_title'] }}
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @if(is_array($data['lp_how_works_steps']))
                    @foreach($data['lp_how_works_steps'] as $step)
                    <div class="bg-white rounded-3xl p-6 shadow-md border border-slate-200/80 flex flex-col items-center text-center hover:shadow-xl transition-all">
                        <div class="w-10 h-10 rounded-2xl bg-blue-600 text-white font-bold text-xs flex items-center justify-center shadow-md shadow-blue-500/30 mb-3">
                            Step {{ $step['step'] ?? '1' }}
                        </div>
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-base mb-3">
                            <i class="{{ $step['icon'] ?? 'fas fa-arrow-right' }}"></i>
                        </div>
                        <h4 class="font-space font-bold text-sm text-slate-900 mb-2">{{ $step['title'] ?? '' }}</h4>
                        <p class="text-xs text-slate-600 leading-relaxed">{{ $step['desc'] ?? '' }}</p>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>

    <!-- SECTION 7: WHY CHOOSE NOORYAK? -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="badge-pill mx-auto">{{ $data['lp_why_choose_tag'] }}</span>
                <h2 class="font-space font-extrabold text-2xl sm:text-4xl text-slate-900 tracking-tight mt-3">
                    {{ $data['lp_why_choose_title'] }}
                </h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @if(is_array($data['lp_why_choose_items']))
                    @foreach($data['lp_why_choose_items'] as $item)
                    <div class="p-6 rounded-3xl bg-slate-50 border border-slate-200/80 hover:bg-white hover:shadow-xl transition-all">
                        <div class="w-10 h-10 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center text-lg mb-3">
                            <i class="{{ $item['icon'] ?? 'fas fa-shield-alt' }}"></i>
                        </div>
                        <h4 class="font-space font-bold text-sm text-slate-900 mb-2">{{ $item['title'] ?? '' }}</h4>
                        <p class="text-xs text-slate-600 leading-relaxed">{{ $item['desc'] ?? '' }}</p>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>

    <!-- SECTION 8: PRICING & REVENUE CALCULATOR -->
    <section id="pricing" class="py-16 lg:py-24 bg-slate-50/80 border-t border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="badge-pill mx-auto">{{ $data['lp_pricing_tag'] }}</span>
                <h2 class="font-space font-extrabold text-2xl sm:text-4xl text-slate-900 tracking-tight mt-3">
                    {{ $data['lp_pricing_title'] }}
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 mt-1">{{ $data['lp_pricing_desc'] }}</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left 2 Pricing Cards (7 Cols) -->
                <div class="lg:col-span-7 grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @if(is_array($data['lp_pricing_plans']))
                        @foreach($data['lp_pricing_plans'] as $plan)
                        <div class="bg-white rounded-3xl p-6 shadow-xl border border-slate-200 flex flex-col justify-between relative">
                            @if(!empty($plan['badge']))
                                <div class="absolute -top-3 right-6 bg-blue-600 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                                    {{ $plan['badge'] }}
                                </div>
                            @endif
                            <div>
                                <h4 class="font-space font-bold text-base text-slate-900">{{ $plan['name'] ?? '' }}</h4>
                                <p class="text-[11px] text-slate-500 mt-1">{{ $plan['desc'] ?? '' }}</p>
                                
                                <div class="my-5">
                                    <span class="font-space font-extrabold text-3xl text-slate-900">₹{{ $plan['price'] ?? '999' }}</span>
                                    <span class="text-xs text-slate-500 font-medium">{{ $plan['period'] ?? '/month' }}</span>
                                </div>

                                <div class="space-y-2 mb-6 text-xs text-slate-700">
                                    @if(isset($plan['features']) && is_array($plan['features']))
                                        @foreach($plan['features'] as $pf)
                                        <div class="flex items-center space-x-2">
                                            <i class="fas fa-check text-emerald-500 text-xs"></i>
                                            <span>{{ $pf }}</span>
                                        </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <a href="{{ $plan['cta_url'] ?? '/login' }}" class="btn-gradient w-full block text-center py-3 rounded-xl text-white font-bold text-xs shadow-md">
                                {{ $plan['cta_text'] ?? 'Get Started' }}
                            </a>
                        </div>
                        @endforeach
                    @endif
                </div>

                <!-- Right Revenue Calculator Card (5 Cols) -->
                <div class="lg:col-span-5">
                    <div class="bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 text-white rounded-3xl p-7 sm:p-8 shadow-2xl border border-slate-800">
                        
                        <div class="flex items-center justify-between border-b border-slate-800 pb-4 mb-5">
                            <div>
                                <h3 class="font-space font-extrabold text-lg text-white">{{ $data['lp_rev_calc_title'] }}</h3>
                                <p class="text-xs text-slate-400 mt-0.5">{{ $data['lp_rev_calc_subtitle'] }}</p>
                            </div>
                            <div class="w-9 h-9 rounded-xl bg-blue-600/30 text-blue-400 flex items-center justify-center text-base">
                                <i class="fas fa-calculator"></i>
                            </div>
                        </div>

                        <!-- Interactive Range Slider -->
                        <div class="space-y-3 mb-5">
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-slate-300 font-medium">Active Customers:</span>
                                <span id="customerCountDisplay" class="font-extrabold text-blue-400 text-base bg-blue-500/20 px-3 py-0.5 rounded-lg">100</span>
                            </div>
                            <input type="range" id="customerSlider" min="10" max="1000" step="10" value="{{ $data['lp_rev_calc_default_customers'] }}">
                        </div>

                        <!-- Revenue Output -->
                        <div class="bg-white/10 rounded-2xl p-5 border border-white/10 text-center mb-5 backdrop-blur-sm">
                            <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider mb-1">Estimated Monthly Revenue</p>
                            <p id="monthlyRevDisplay" class="font-space font-extrabold text-2xl sm:text-3xl text-emerald-400">
                                ₹99,900 <span class="text-xs font-normal text-slate-400">/month</span>
                            </p>
                            <p class="text-[10px] text-slate-400 mt-1.5">
                                Based on <span id="calcSubtextCount">100</span> customers × ₹{{ $data['lp_rev_calc_price_per_customer'] }}/mo
                            </p>
                        </div>

                        <!-- Bullets -->
                        <div class="grid grid-cols-2 gap-2.5 mb-6 text-xs font-semibold">
                            @if(is_array($data['lp_rev_calc_bullets']))
                                @foreach($data['lp_rev_calc_bullets'] as $bullet)
                                <div class="flex items-center space-x-2 text-slate-200">
                                    <i class="fas fa-check-circle text-emerald-400 text-xs"></i>
                                    <span>{{ $bullet }}</span>
                                </div>
                                @endforeach
                            @endif
                        </div>

                        <!-- CTA -->
                        <a href="{{ $data['lp_cta_button_url'] }}" class="btn-gradient w-full block text-center py-3.5 rounded-2xl text-white font-bold text-xs shadow-lg">
                            Start Building Your Revenue <i class="fas fa-arrow-right ml-2"></i>
                        </a>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION 9: YOUR GROWTH PATH & TESTIMONIALS -->
    <section class="py-16 bg-white border-t border-slate-200/80">
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
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 font-bold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="{{ $gstep['icon'] ?? 'fas fa-arrow-right' }}"></i>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-blue-600 uppercase tracking-wider">{{ $gstep['step'] ?? '' }}</span>
                                <h5 class="font-bold text-xs text-slate-900 mt-0.5">{{ $gstep['title'] ?? '' }}</h5>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Testimonials -->
            <div>
                <div class="text-center mb-10">
                    <span class="badge-pill mx-auto">{{ $data['lp_testimonials_tag'] }}</span>
                    <h2 class="font-space font-extrabold text-2xl sm:text-3xl text-slate-900 tracking-tight mt-3">
                        {{ $data['lp_testimonials_title'] }}
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-600 mt-1">{{ $data['lp_testimonials_subtitle'] }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @if(is_array($data['lp_testimonials_items']))
                        @foreach($data['lp_testimonials_items'] as $testi)
                        <div class="p-6 rounded-3xl bg-slate-50 border border-slate-200/80 shadow-sm flex flex-col justify-between space-y-4">
                            <div class="space-y-3">
                                <div class="flex items-center space-x-1 text-amber-400 text-xs">
                                    @for($r = 0; $r < ($testi['rating'] ?? 5); $r++)
                                        <i class="fas fa-star"></i>
                                    @endfor
                                </div>
                                <p class="text-xs text-slate-600 leading-relaxed font-normal">"{{ $testi['quote'] ?? '' }}"</p>
                            </div>
                            <div class="pt-3 border-t border-slate-200/60 flex items-center justify-between text-xs">
                                <span class="font-bold text-slate-900">{{ $testi['name'] ?? '' }}</span>
                                <span class="text-slate-500 text-[11px]">{{ $testi['role'] ?? '' }}</span>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>

        </div>
    </section>

    <!-- SECTION 10: FAQ ACCORDION -->
    <section id="faq" class="py-16 bg-slate-50/80 border-t border-slate-200/80">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <span class="badge-pill mx-auto">{{ $data['lp_faqs_tag'] }}</span>
                <h2 class="font-space font-extrabold text-2xl sm:text-4xl text-slate-900 tracking-tight mt-3">
                    {{ $data['lp_faqs_title'] }}
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 mt-2">{{ $data['lp_faqs_subtitle'] }}</p>
            </div>

            <div class="space-y-3">
                @if(is_array($data['lp_faqs_items']))
                    @foreach($data['lp_faqs_items'] as $faq)
                    <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm transition-all duration-200">
                        <button class="faq-toggle w-full px-5 py-4 text-left font-space font-bold text-xs sm:text-sm text-slate-900 bg-white hover:bg-slate-50 flex items-center justify-between focus:outline-none">
                            <span>{{ $faq['question'] ?? '' }}</span>
                            <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200 ml-2 flex-shrink-0"></i>
                        </button>
                        <div class="faq-content hidden px-5 py-3.5 text-xs text-slate-600 leading-relaxed bg-slate-50/60 border-t border-slate-100">
                            {{ $faq['answer'] ?? '' }}
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>

    <!-- SECTION 11: CTA BANNER -->
    <section class="py-14 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-8 sm:p-12 lg:p-16 text-white text-center relative overflow-hidden shadow-2xl" style="background-image: url('{{ asset($data['lp_cta_banner_bg'] ?? '/assets/images/cta_background.png') }}'); background-size: cover; background-position: center;">
                <div class="relative z-10 max-w-3xl mx-auto space-y-5">
                    <h2 class="font-space font-extrabold text-2xl sm:text-4xl lg:text-5xl tracking-tight leading-tight">
                        {{ $data['lp_cta_banner_title'] }}
                    </h2>
                    <p class="text-xs sm:text-base text-slate-300">
                        {{ $data['lp_cta_banner_subtitle'] }}
                    </p>

                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
                        <a href="{{ $data['lp_cta_banner_button1_url'] }}" class="btn-gradient w-full sm:w-auto px-8 py-3.5 rounded-full text-white font-bold text-xs shadow-xl shadow-blue-500/30">
                            {{ $data['lp_cta_banner_button1_text'] }} <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                        <a href="{{ $data['lp_cta_banner_button2_url'] }}" class="w-full sm:w-auto px-8 py-3.5 rounded-full bg-white/10 hover:bg-white/20 border border-white/20 text-white font-bold text-xs backdrop-blur-sm transition-all">
                            {{ $data['lp_cta_banner_button2_text'] }}
                        </a>
                    </div>
                </div>

                <div class="hidden lg:block absolute bottom-6 right-10 text-amber-300 handwritten text-xs transform rotate-6">
                    Your Success Starts Here! ➔
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 12: FOOTER -->
    <footer class="bg-slate-950 text-slate-400 py-12 border-t border-slate-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-8 pb-10 border-b border-slate-900">
                
                <div class="lg:col-span-2 space-y-3">
                    <a href="{{ url('/') }}" class="flex items-center space-x-2 text-white font-space font-bold text-xl">
                        @if($data['lp_header_logo'])
                            <img src="{{ asset($data['lp_header_logo']) }}" alt="Nooryak" class="h-8 w-auto">
                        @else
                            <div class="w-8 h-8 rounded-xl bg-blue-600 flex items-center justify-center text-white text-xs">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <span>Nooryak</span>
                        @endif
                    </a>
                    <p class="text-xs text-slate-400 max-w-sm leading-relaxed">
                        {{ $data['lp_footer_desc'] }}
                    </p>
                    <div class="flex items-center space-x-4 text-xs text-slate-400 pt-2">
                        @if(!empty($data['lp_fb_url']))<a href="{{ $data['lp_fb_url'] }}" class="hover:text-blue-500"><i class="fab fa-facebook-f"></i></a>@endif
                        @if(!empty($data['lp_ig_url']))<a href="{{ $data['lp_ig_url'] }}" class="hover:text-pink-500"><i class="fab fa-instagram"></i></a>@endif
                        @if(!empty($data['lp_yt_url']))<a href="{{ $data['lp_yt_url'] }}" class="hover:text-red-500"><i class="fab fa-youtube"></i></a>@endif
                        @if(!empty($data['lp_li_url']))<a href="{{ $data['lp_li_url'] }}" class="hover:text-blue-400"><i class="fab fa-linkedin-in"></i></a>@endif
                        @if(!empty($data['lp_tw_url']))<a href="{{ $data['lp_tw_url'] }}" class="hover:text-blue-400"><i class="fab fa-twitter"></i></a>@endif
                    </div>
                </div>

                <div class="space-y-2">
                    <h5 class="text-xs font-bold text-white uppercase tracking-wider">Platform</h5>
                    <ul class="space-y-1.5 text-xs">
                        <li><a href="#hero" class="hover:text-white">Home</a></li>
                        <li><a href="#about" class="hover:text-white">Solutions</a></li>
                        <li><a href="#pricing" class="hover:text-white">Pricing Plans</a></li>
                        <li><a href="#products" class="hover:text-white">White Label SaaS</a></li>
                    </ul>
                </div>

                <div class="space-y-2">
                    <h5 class="text-xs font-bold text-white uppercase tracking-wider">Legal & Support</h5>
                    <ul class="space-y-1.5 text-xs">
                        <li><a href="{{ route('agency.terms') }}" class="hover:text-white">Terms & Conditions</a></li>
                        <li><a href="{{ route('agency.privacy') }}" class="hover:text-white">Privacy Policy</a></li>
                        <li><a href="{{ route('agency.refund') }}" class="hover:text-white">Refund Policy</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white">Super Admin Login</a></li>
                    </ul>
                </div>

                <div class="space-y-2">
                    <h5 class="text-xs font-bold text-white uppercase tracking-wider">Contact</h5>
                    <p class="text-xs"><i class="fas fa-envelope mr-1.5 text-blue-500"></i> {{ $data['lp_contact_email'] }}</p>
                    <p class="text-xs"><i class="fas fa-phone mr-1.5 text-blue-500"></i> {{ $data['lp_contact_phone'] }}</p>
                </div>

            </div>

            <div class="pt-6 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500">
                <p>{{ $data['lp_copyright_text'] }}</p>
                <div class="mt-3 sm:mt-0 space-x-4">
                    <a href="{{ route('agency.privacy') }}" class="hover:text-slate-400">Privacy</a>
                    <a href="{{ route('agency.terms') }}" class="hover:text-slate-400">Terms</a>
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
    </script>
</body>
</html>
