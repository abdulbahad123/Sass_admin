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
                        <img src="{{ asset($data['lp_header_logo']) }}" alt="Nooryak Logo" class="h-9 sm:h-10 w-auto">
                    @else
                        <div class="flex items-center space-x-2">
                            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-2xl btn-gradient flex items-center justify-center text-white font-bold text-xl shadow-md shadow-orange-500/20">
                                <i class="fas fa-layer-group text-base sm:text-lg"></i>
                            </div>
                            <span class="font-space font-bold text-xl sm:text-2xl tracking-tight text-slate-900">Nooryak</span>
                        </div>
                    @endif
                </a>

                <!-- Desktop Menu Navigation -->
                <nav class="hidden lg:flex items-center space-x-8 text-sm font-semibold text-slate-600">
                    <a href="#hero" class="hover:text-[#ff3d00] transition-colors">Home</a>
                    <a href="#about" class="hover:text-[#ff3d00] transition-colors">Solutions <i class="fas fa-chevron-down text-[10px] ml-1"></i></a>
                    <a href="#pricing" class="hover:text-[#ff3d00] transition-colors">Pricing</a>
                    <a href="#products" class="hover:text-[#ff3d00] transition-colors">Resources <i class="fas fa-chevron-down text-[10px] ml-1"></i></a>
                </nav>

                <!-- Action Buttons -->
                <div class="hidden sm:flex items-center space-x-3">
                    <a href="{{ $data['lp_book_demo_url'] }}" class="px-5 py-2.5 rounded-full border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-100 transition-all shadow-sm">
                        Book a Demo
                    </a>
                    <a href="{{ $data['lp_cta_button_url'] }}" class="btn-gradient px-6 py-2.5 rounded-full text-white text-xs font-bold shadow-lg shadow-orange-500/30 flex items-center space-x-2">
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
    <section id="hero" class="relative pt-10 pb-12 lg:pt-14 lg:pb-16 overflow-hidden bg-gradient-to-b from-orange-50/60 via-amber-50/20 to-transparent reveal">
        <div class="max-w-[1340px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                
                <!-- Left Text Column (5 cols on lg for crisp text alignment) -->
                <div class="lg:col-span-5 space-y-6 text-left relative z-20">
                    <div class="inline-flex items-center space-x-2 bg-orange-100/90 border border-orange-200/80 px-4 py-1.5 rounded-full text-orange-700 text-[11px] font-bold tracking-wide uppercase shadow-sm">
                        <span>YOUR BRAND. OUR TECHNOLOGY. UNLIMITED GROWTH.</span>
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

                <!-- Right Hero Image Graphic (Right Aligned, Large, No Text Overlap) -->
                <div class="lg:col-span-7 relative mt-6 lg:mt-0 flex items-center justify-end">
                    <div class="relative z-10 w-full flex justify-center lg:justify-end items-center">
                        <img src="{{ asset($data['lp_hero_image'] ?? '/assets/images/herobanner_right.png') }}" alt="Nooryak SaaS Platform" class="w-full h-auto max-h-[650px] lg:max-h-[780px] xl:max-h-[850px] object-contain object-right transition-transform duration-500 hover:scale-105">
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION 2: COUNTER STATS BAR CARD -->
    <div class="max-w-[1340px] mx-auto px-4 sm:px-6 lg:px-8 mt-2 mb-10 relative z-20 reveal">
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
    <section id="about" class="py-16 lg:py-24 bg-white">
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

                    <!-- 3 Feature Icons in 1 Row -->
                    <div class="grid grid-cols-3 gap-3 pt-3">
                        @if(is_array($data['lp_about_features']))
                            @foreach(array_slice($data['lp_about_features'], 0, 3) as $feat)
                            <div class="flex items-center space-x-2.5 p-3 rounded-2xl bg-orange-50/60 border border-orange-100">
                                <div class="w-8 h-8 rounded-full bg-[#ff3d00] text-white flex items-center justify-center text-xs flex-shrink-0">
                                    <i class="{{ $feat['icon'] ?? 'fas fa-star' }}"></i>
                                </div>
                                <h4 class="font-bold text-[11px] text-slate-900 leading-tight">{{ $feat['title'] ?? '' }}</h4>
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

    <!-- SECTION 4: WHITE LABEL SAAS PARTNER MODEL CARD (Reduced width, aligned left) -->
    <section class="py-14 bg-slate-50/80 border-t border-slate-200/80 reveal">
        <div class="max-w-3xl ml-4 sm:ml-8 lg:ml-16 mr-auto px-4 sm:px-6">
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
                    <p class="text-xs sm:text-sm text-slate-600 mb-8 leading-relaxed text-left">
                        {{ $model['desc'] ?? '' }}
                    </p>

                    <!-- Feature List (Left) & Screen Preview (Right) (Task 2: Guaranteed feature list display) -->
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-6 items-center mb-8 text-left">
                        <div class="sm:col-span-6 space-y-3 text-left">
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
                            <div class="flex items-start space-x-2.5 text-xs sm:text-sm font-medium text-slate-700 text-left">
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
                <div class="pt-5 border-t border-slate-100 text-left">
                    <a href="{{ $model['cta_url'] ?? '/login' }}" class="btn-gradient inline-flex items-center justify-between w-full px-6 py-4 rounded-2xl font-bold text-xs sm:text-sm text-white transition-all shadow-lg shadow-orange-500/20">
                        <span>{{ $model['cta_text'] ?? 'Start with White Label SaaS' }}</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

            </div>
            @endif
        </div>
    </section>

    <!-- SECTION 5: OUR PRODUCTS (Dynamic products from Super Admin Catalog) -->
    <section id="products" class="py-16 lg:py-24 bg-white reveal">
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

            <!-- Dynamic Super Admin Active Products Grid -->
            @php
                $dbProds = $data['db_products'] ?? collect();
            @endphp
            @if($dbProds->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 {{ $dbProds->count() > 2 ? 'lg:grid-cols-3' : 'max-w-4xl mx-auto' }} gap-6">
                    @foreach($dbProds as $prod)
                    @php
                        $pIcon = trim($prod->icon ?? '');
                        if (!$pIcon || $pIcon === 'fas fa-store') {
                            $slugLower = strtolower(($prod->slug ?? '') . ' ' . ($prod->name ?? ''));
                            if (str_contains($slugLower, 'launch') || str_contains($slugLower, 'shop') || str_contains($slugLower, 'store')) {
                                $pIcon = 'fas fa-shopping-bag';
                            } elseif (str_contains($slugLower, 'website') || str_contains($slugLower, 'builder')) {
                                $pIcon = 'fas fa-cubes';
                            } elseif (str_contains($slugLower, 'review') || str_contains($slugLower, 'gmb')) {
                                $pIcon = 'fas fa-star';
                            } elseif (str_contains($slugLower, 'menu') || str_contains($slugLower, 'qr')) {
                                $pIcon = 'fas fa-utensils';
                            } elseif (str_contains($slugLower, 'vcard') || str_contains($slugLower, 'nfc') || str_contains($slugLower, 'card')) {
                                $pIcon = 'fas fa-id-card';
                            } elseif (str_contains($slugLower, 'loyalty') || str_contains($slugLower, 'reward')) {
                                $pIcon = 'fas fa-gift';
                            } else {
                                $pIcon = 'fas fa-box';
                            }
                        }
                    @endphp
                    <div class="p-6 rounded-3xl bg-slate-50/80 border border-slate-200/90 hover:bg-white hover:shadow-xl hover:border-orange-200 transition-all duration-300 flex flex-col justify-between group">
                        <div>
                            <div class="w-12 h-12 rounded-2xl btn-gradient flex items-center justify-center text-white text-xl shadow-md shadow-orange-500/20 mb-5">
                                <i class="{{ $pIcon }}"></i>
                            </div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-orange-600 bg-orange-100/80 px-2.5 py-0.5 rounded-full inline-block mb-2">Active Product</span>
                            <h4 class="font-space font-extrabold text-lg text-slate-900 mb-2 group-hover:text-[#ff3d00] transition-colors">{{ $prod->name }}</h4>
                            <p class="text-xs text-slate-600 leading-relaxed mb-6 font-medium">{{ $prod->tagline ?: $prod->description }}</p>
                        </div>
                        <a href="{{ $prod->getSubdomainPreviewUrl() }}" target="_blank" class="inline-flex items-center text-xs font-bold text-[#ff3d00] hover:text-orange-700 group/link mt-auto pt-3 border-t border-slate-200/60">
                            <span>Explore {{ $prod->name }}</span>
                            <i class="fas fa-arrow-right text-[10px] ml-1.5 transition-transform group-hover/link:translate-x-1"></i>
                        </a>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                    @if(is_array($data['lp_products_grid']))
                        @foreach($data['lp_products_grid'] as $prod)
                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 hover:bg-white hover:shadow-xl transition-all duration-200 flex flex-col justify-between">
                            <div>
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white text-base shadow-md mb-4 btn-gradient">
                                    <i class="{{ $prod['icon'] ?? 'fas fa-box' }}"></i>
                                </div>
                                <h4 class="font-space font-bold text-sm text-slate-900 mb-2">{{ $prod['title'] ?? '' }}</h4>
                                <p class="text-xs text-slate-600 leading-relaxed mb-4">{{ $prod['desc'] ?? '' }}</p>
                            </div>
                            <a href="{{ $prod['link'] ?? '/login' }}" class="inline-flex items-center text-xs font-bold text-[#ff3d00] hover:text-orange-700 group mt-auto">
                                <span>Learn More</span>
                                <i class="fas fa-arrow-right text-[10px] ml-1.5 transition-transform group-hover:translate-x-1"></i>
                            </a>
                        </div>
                        @endforeach
                    @endif
                </div>
            @endif

        </div>
    </section>

    <!-- SECTION 6: HOW NOORYAK WORKS (Pixel-Perfect Match with Reference Image) -->
    <section class="py-16 lg:py-24 bg-slate-50/60 border-t border-slate-200/70 reveal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            
            <span class="badge-pill mx-auto mb-3">{{ $data['lp_how_works_tag'] }}</span>
            <h2 class="font-space font-extrabold text-2xl sm:text-4xl text-slate-900 tracking-tight mb-14">
                {{ $data['lp_how_works_title'] }}
            </h2>

            @if(is_array($data['lp_how_works_steps']))
            <div class="relative flex flex-col lg:flex-row items-center justify-between gap-8 lg:gap-4 max-w-6xl mx-auto">
                @foreach($data['lp_how_works_steps'] as $index => $step)
                    
                    <!-- Step Item -->
                    <div class="flex-1 flex flex-col items-center text-center group z-10 px-2">
                        <!-- Icon Circle -->
                        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full btn-gradient flex items-center justify-center text-white text-xl sm:text-2xl shadow-xl shadow-orange-500/25 mb-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="{{ $step['icon'] ?? 'fas fa-check' }}"></i>
                        </div>
                        
                        <!-- Step Label -->
                        <span class="text-xs font-bold text-slate-600 uppercase tracking-wide mb-1">Step {{ $step['step'] ?? ($index + 1) }}</span>
                        
                        <!-- Title -->
                        <h4 class="font-space font-extrabold text-base sm:text-lg text-slate-900 mb-2 leading-snug">{{ $step['title'] ?? '' }}</h4>
                        
                        <!-- Description -->
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed max-w-[220px]">{{ $step['desc'] ?? '' }}</p>
                    </div>

                    <!-- Right Arrow Connector between steps (Hidden on Mobile) -->
                    @if(!$loop->last)
                    <div class="hidden lg:flex items-center justify-center text-slate-400 font-bold -mt-16 text-lg">
                        <i class="fas fa-arrow-right text-slate-400 opacity-60"></i>
                    </div>
                    @endif

                @endforeach
            </div>
            @endif

        </div>
    </section>

    <!-- SECTION 7: WHY CHOOSE NOORYAK? (Pixel-Perfect Match with Reference Image) -->
    <section class="py-16 lg:py-24 bg-white reveal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            
            <div class="mb-14">
                <span class="badge-pill mx-auto mb-3">{{ $data['lp_why_choose_tag'] }}</span>
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
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-6 sm:gap-8 items-start text-center">
                @foreach($data['lp_why_choose_items'] as $index => $item)
                @php
                    $style = $colorStyles[$index % count($colorStyles)];
                @endphp
                <div class="flex flex-col items-center group px-1">
                    <!-- Soft Pastel Colored Circle Icon Container -->
                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full {{ $style['bg'] }} flex items-center justify-center text-xl sm:text-2xl mb-4 group-hover:scale-110 transition-transform duration-300 shadow-sm">
                        <i class="{{ $item['icon'] ?? 'fas fa-shield-alt' }}"></i>
                    </div>
                    
                    <!-- Title -->
                    <h4 class="font-space font-extrabold text-xs sm:text-sm text-slate-900 mb-1.5 leading-snug">{{ $item['title'] ?? '' }}</h4>
                    
                    <!-- Description -->
                    <p class="text-[11px] sm:text-xs text-slate-600 leading-relaxed max-w-[170px]">{{ $item['desc'] ?? '' }}</p>
                </div>
                @endforeach
            </div>
            @endif

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
                
                <!-- Left Pricing Cards (7 Cols) -->
                <div class="lg:col-span-7 grid grid-cols-1 sm:grid-cols-1 gap-6">
                    @if(is_array($data['lp_pricing_plans']))
                        @foreach($data['lp_pricing_plans'] as $plan)
                        @if(!str_contains(strtolower($plan['name'] ?? ''), 'master') && !str_contains(strtolower($plan['desc'] ?? ''), 'create saas partners'))
                        <div class="bg-white rounded-3xl p-6 shadow-xl border border-slate-200 flex flex-col justify-between relative">
                            @if(!empty($plan['badge']))
                                <div class="absolute -top-3 right-6 btn-gradient text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-sm">
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
                                            <i class="fas fa-check text-orange-500 text-xs"></i>
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
                        @endif
                        @endforeach
                    @endif
                </div>

                <!-- Right Revenue Calculator Card (5 Cols) (Task 4: Background image using revenue_calculator.png only) -->
                <div class="lg:col-span-5">
                    <div class="relative text-white rounded-3xl p-7 sm:p-8 shadow-2xl border border-slate-800/80 overflow-hidden" style="background-image: url('{{ asset('/assets/images/revenue_calculator.png') }}'); background-size: cover;">
                        
                        <div class="flex items-center justify-between border-b border-slate-800 pb-4 mb-5">
                            <div>
                                <h3 class="font-space font-extrabold text-lg text-white">{{ $data['lp_rev_calc_title'] }}</h3>
                                <p class="text-xs text-slate-400 mt-0.5">{{ $data['lp_rev_calc_subtitle'] }}</p>
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
                        <div class="grid grid-cols-2 gap-2.5 mb-6 text-xs font-semibold">
                            @if(is_array($data['lp_rev_calc_bullets']))
                                @foreach($data['lp_rev_calc_bullets'] as $bullet)
                                <div class="flex items-center space-x-2 text-slate-200">
                                    <i class="fas fa-check-circle text-orange-400 text-xs"></i>
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
    <section class="py-16 bg-white border-t border-slate-200/80 reveal">
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
    <section id="faq" class="py-16 bg-slate-50/80 border-t border-slate-200/80 reveal">
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

    <!-- SECTION 11: CTA BANNER (Task 5: Using cta_background.png image) -->
    <section class="py-14 bg-white reveal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-3xl p-8 sm:p-12 lg:p-16 text-white text-center relative overflow-hidden shadow-2xl border border-slate-800/50" style="background-image: url('{{ asset('/assets/images/cta_background.png') }}'); background-size: cover;">
                <div class="relative z-10 max-w-3xl mx-auto space-y-5">
                    <h2 class="font-space font-extrabold text-2xl sm:text-4xl lg:text-5xl tracking-tight leading-tight">
                        {{ $data['lp_cta_banner_title'] }}
                    </h2>
                    <p class="text-xs sm:text-base text-slate-300">
                        {{ $data['lp_cta_banner_subtitle'] }}
                    </p>

                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
                        <a href="{{ $data['lp_cta_banner_button1_url'] }}" class="btn-gradient w-full sm:w-auto px-8 py-3.5 rounded-full text-white font-bold text-xs shadow-xl shadow-orange-500/30">
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
                            <div class="w-8 h-8 rounded-xl btn-gradient flex items-center justify-center text-white text-xs">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <span>Nooryak</span>
                        @endif
                    </a>
                    <p class="text-xs text-slate-400 max-w-sm leading-relaxed">
                        {{ $data['lp_footer_desc'] }}
                    </p>
                    <div class="flex items-center space-x-4 text-xs text-slate-400 pt-2">
                        @if(!empty($data['lp_fb_url']))<a href="{{ $data['lp_fb_url'] }}" class="hover:text-orange-500"><i class="fab fa-[#ff3d00] fa-facebook-f"></i></a>@endif
                        @if(!empty($data['lp_ig_url']))<a href="{{ $data['lp_ig_url'] }}" class="hover:text-pink-500"><i class="fab fa-instagram"></i></a>@endif
                        @if(!empty($data['lp_yt_url']))<a href="{{ $data['lp_yt_url'] }}" class="hover:text-red-500"><i class="fab fa-youtube"></i></a>@endif
                        @if(!empty($data['lp_li_url']))<a href="{{ $data['lp_li_url'] }}" class="hover:text-orange-400"><i class="fab fa-linkedin-in"></i></a>@endif
                        @if(!empty($data['lp_tw_url']))<a href="{{ $data['lp_tw_url'] }}" class="hover:text-orange-400"><i class="fab fa-twitter"></i></a>@endif
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
                    <p class="text-xs"><i class="fas fa-envelope mr-1.5 text-orange-500"></i> {{ $data['lp_contact_email'] }}</p>
                    <p class="text-xs"><i class="fas fa-phone mr-1.5 text-orange-500"></i> {{ $data['lp_contact_phone'] }}</p>
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

        // Task 3: Full Scrolling Reveal Animation
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

            // Task 3: Animated Running Counter Numbers
            const counters = document.querySelectorAll('.stat-counter');
            const counterObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const counter = entry.target;
                        const targetVal = counter.getAttribute('data-target') || counter.innerText.trim();
                        
                        // Parse numbers and non-numeric prefix/suffix (e.g., "500+" -> num 500, suffix "+")
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
                                // Ease-out quad curve
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
        });
    </script>
</body>
</html>
