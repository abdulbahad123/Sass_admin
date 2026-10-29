@php
    $agencyName = $agency->name ?? 'SaaS Partner';
    $customDomain = $agency->custom_domain;
    $primaryColor = $agency->primary_color ?? '#4f46e5';
    $secondaryColor = $agency->secondary_color ?? '#7c3aed';

    // Image fallbacks
    $heroImg = !empty($agency->hero_image) ? asset(ltrim($agency->hero_image, '/')) : asset('assets/landing_page/header_image.png');
    $aboutImg = !empty($agency->about_image) ? asset(ltrim($agency->about_image, '/')) : asset('assets/landing_page/features_leftside.png');
    $ctaImg = !empty($agency->cta_image) ? asset(ltrim($agency->cta_image, '/')) : asset('assets/landing_page/cta_image.png');

    // Dynamic Collections from Database
    $aboutFeatures = is_array($agency->about_features_data ?? null) 
        ? $agency->about_features_data 
        : (json_decode($agency->about_features_data ?? '[]', true) ?: [
            ['title' => 'Lightning Performance', 'desc' => 'Modern architecture delivering sub-second load times and 99.9% uptime.', 'icon' => 'zap'],
            ['title' => 'Enterprise Security',   'desc' => 'Bank-grade encryption and industry-leading security protocols to protect your data.', 'icon' => 'shield-check'],
            ['title' => 'Scale Without Limits',  'desc' => 'Infrastructure that grows with your business — from 10 to 10,000 clients.', 'icon' => 'trending-up'],
        ]);

    $hiwSteps = is_array($agency->how_it_works_steps ?? null)
        ? $agency->how_it_works_steps
        : (json_decode($agency->how_it_works_steps ?? '[]', true) ?: [
            ['num' => '01', 'title' => 'Choose Your Solution',  'desc' => 'Select from E-Commerce Store, Website Builder, or WhatsApp Automation.', 'icon' => 'layout-grid'],
            ['num' => '02', 'title' => 'Instant Setup & Domain', 'desc' => 'Customize your brand, connect your custom domain, and upload products.', 'icon' => 'settings'],
            ['num' => '03', 'title' => 'Automate & Scale',       'desc' => 'Start taking orders, capturing leads, and growing recurring sales.',    'icon' => 'trending-up'],
        ]);

    $ctaCheckmarks = is_array($agency->cta_features_data ?? null)
        ? $agency->cta_features_data
        : (json_decode($agency->cta_features_data ?? '[]', true) ?: ['Quick Setup in 10 Mins', 'Free SSL & Custom Domain', '24/7 Dedicated Support']);

    // Testimonials - Cosmic Dark Theme with Avatars, Initials, Stars (Screenshot 3)
    $testimonials = is_array($agency->testimonials_data ?? null)
        ? $agency->testimonials_data
        : (json_decode($agency->testimonials_data ?? '[]', true) ?: [
            [
                'name'    => 'Rahul Sharma',
                'role'    => 'Mumbai',
                'initial' => 'R',
                'color'   => '#7c3aed',
                'rating'  => 5,
                'comment' => 'The QR Menu and online ordering system made it much easier for our customers to browse our menu and place orders. The setup was quick, and the platform is very easy to use.'
            ],
            [
                'name'    => 'Amit Verma',
                'role'    => 'Indore',
                'initial' => 'A',
                'color'   => '#2563eb',
                'rating'  => 5,
                'comment' => 'The AI Review Management feature helped us respond to customer reviews faster and manage our online reputation more efficiently.'
            ],
            [
                'name'    => 'Sneha Gupta',
                'role'    => 'Delhi NCR',
                'initial' => 'S',
                'color'   => '#8b5cf6',
                'rating'  => 5,
                'comment' => 'The platform is simple, professional, and has everything we need to strengthen our online presence. Managing our business has become much more organized.'
            ],
            [
                'name'    => 'Vikram Patel',
                'role'    => 'Ahmedabad',
                'initial' => 'V',
                'color'   => '#059669',
                'rating'  => 5,
                'comment' => 'The WhatsApp automation and bulk marketing tools helped us increase repeat orders and engage our regular customers effortlessly.'
            ],
        ]);

    $features = is_array($agency->features_data ?? null)
        ? $agency->features_data
        : (json_decode($agency->features_data ?? '[]', true) ?: [
            ['title' => 'Get More Customers', 'desc' => 'Build trust with reviews, smart websites and digital presence.', 'icon' => 'rocket',       'bg' => '#ede9fe', 'color' => '#7c3aed'],
            ['title' => 'Save Time & Effort',  'desc' => 'Automate repetitive tasks and focus on what matters most.',      'icon' => 'clock',        'bg' => '#d1fae5', 'color' => '#059669'],
            ['title' => 'Increase Revenue',    'desc' => 'Drive repeat business with loyalty programs & digital tools.',   'icon' => 'trending-up',  'bg' => '#ffedd5', 'color' => '#ea580c'],
            ['title' => 'Reliable & Secure',   'desc' => 'Your business data is safe with enterprise-grade security.',     'icon' => 'shield-check', 'bg' => '#dbeafe', 'color' => '#1d4ed8'],
        ]);

    // Product-focused FAQs (Screenshot 4: 2-Column Grid)
    $faqs = is_array($agency->faq_data ?? null)
        ? $agency->faq_data
        : (json_decode($agency->faq_data ?? '[]', true) ?: [
            [
                'q' => '1. WHAT IS ' . strtoupper($agencyName) . '?',
                'a' => $agencyName . ' is an all-in-one business growth platform designed to help local businesses build high-converting online stores, create professional websites, and automate WhatsApp customer engagement.'
            ],
            [
                'q' => '2. WHAT PRODUCTS ARE INCLUDED FOR MY BUSINESS?',
                'a' => 'You get complete access to 3 core solutions: 1) E-Commerce Online Store Builder, 2) Drag & Drop Website Builder, and 3) AI WhatsApp Marketing & Automation Suite.'
            ],
            [
                'q' => '3. HOW QUICKLY CAN I LAUNCH MY ONLINE STORE?',
                'a' => 'You can launch your online store in less than 10 minutes. Simply add your products, upload images, set prices, and start sharing your store link with customers to accept orders immediately.'
            ],
            [
                'q' => '4. DO I NEED CODING OR TECHNICAL KNOWLEDGE TO START?',
                'a' => 'Zero coding required! Everything is 100% visual, drag-and-drop, and ready-to-use with pre-designed templates so anyone can manage their website and products easily.'
            ],
            [
                'q' => '5. CAN I CONNECT MY OWN CUSTOM DOMAIN?',
                'a' => 'Yes, absolutely! You can link your existing custom domain (e.g. yourbusiness.com) to your website and online store with free SSL security encryption included.'
            ],
            [
                'q' => '6. CAN I ACCEPT ONLINE PAYMENTS LIKE UPI AND CARDS?',
                'a' => 'Yes! The platform supports all major Indian payment methods including UPI (Google Pay, PhonePe, Paytm), credit/debit cards, net banking, and Cash on Delivery (COD).'
            ],
            [
                'q' => '7. HOW DOES WHATSAPP AUTOMATION HELP MY BUSINESS?',
                'a' => 'WhatsApp Automation lets you send instant order alerts, broadcast special offers to customer lists, recover abandoned shopping carts, and reply 24/7 with smart chatbots.'
            ],
            [
                'q' => '8. CAN I MANAGE ORDERS FROM MY SMARTPHONE?',
                'a' => 'Yes, our platform is fully responsive and mobile-optimized so you can track sales, manage customer orders, and update inventory on the go from your smartphone.'
            ],
            [
                'q' => '9. IS CUSTOMER SUPPORT INCLUDED?',
                'a' => 'Yes! We provide dedicated customer support via live chat, WhatsApp, and email to guide you through setup and help your business scale.'
            ],
        ]);

    $categories = ($agency && method_exists($agency, 'getParsedCategoriesAttribute'))
        ? $agency->parsed_categories
        : [
            ['label' => 'Restaurants',    'icon' => '🍽️'],
            ['label' => 'Clinics',        'icon' => '🏥'],
            ['label' => 'Salons & Spas',  'icon' => '💇'],
            ['label' => 'Retail Shops',   'icon' => '🛍️'],
            ['label' => 'Hotels',         'icon' => '🏨'],
            ['label' => 'Gyms & Fitness', 'icon' => '🏋️'],
            ['label' => 'Real Estate',    'icon' => '🏡'],
            ['label' => '& Many More',   'icon' => '✨'],
        ];

    $services = ($agency && method_exists($agency, 'getParsedServicesAttribute'))
        ? $agency->parsed_services
        : [
            ['title' => 'E-Commerce Builder',  'desc' => 'Complete online store with UPI payments & order tracking.'],
            ['title' => 'Website Builder',     'desc' => 'No-code responsive site builder with custom domain support.'],
            ['title' => 'WhatsApp Marketing',  'desc' => 'Automated broadcasts, AI chatbot & cart recovery.'],
        ];

    // Ensure all 3 flagship products are present for showcase
    $pricingPlans = ($agency && method_exists($agency, 'getParsedPricingPlansAttribute'))
        ? $agency->parsed_pricing_plans
        : [];

    if (empty($pricingPlans)) {
        $pricingPlans = [
            [
                'product_slug'    => 'launchshop',
                'product_name'    => 'EcomBuilder',
                'color'           => '#ea580c',
                'gradient'        => 'linear-gradient(135deg, #f97316 0%, #ea580c 100%)',
                'left_bg'         => '#fff5ee',
                'product_logo'    => 'assets/landing_page/ecom_logo.png',
                'product_image'   => 'assets/landing_page/ecombuilder_image.png',
                'product_tagline' => 'BUILD • SELL • GROW',
                'product_title'   => 'Complete E-Commerce Solution',
                'product_subtitle'=> 'Launch your online store, manage products, payments, and customers — all in one place.',
                'plan_badge'      => 'ALL-IN-ONE ECOMMERCE',
                'plan_subbadge'   => 'Instant Setup',
                'features'        => ['Online Store & Product Catalog', 'Up to Unlimited Orders & Customers', 'Inventory & Order Tracking', 'Integrated UPI & Card Payments', 'Custom Domain & Free SSL', 'Mobile Responsive Dashboard'],
                'trust_count'     => 'Trusted by 10,000+ Sellers',
                'cta_text'        => 'Launch Product &rarr;',
            ],
            [
                'product_slug'    => 'websitebuilder',
                'product_name'    => 'Website Builder',
                'color'           => '#2563eb',
                'gradient'        => 'linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%)',
                'left_bg'         => '#f0f6ff',
                'product_logo'    => 'assets/landing_page/websitebuilder_logo.png',
                'product_image'   => 'assets/landing_page/websitebuilder_image.png',
                'product_tagline' => 'DESIGN • BUILD • GROW',
                'product_title'   => 'Professional Website Solution',
                'product_subtitle'=> 'Create stunning, lightning-fast websites with ease, no coding required.',
                'plan_badge'      => 'NO-CODE BUILDER',
                'plan_subbadge'   => 'Drag & Drop',
                'features'        => ['Unlimited Pages & Sections', 'Drag & Drop Visual Builder', 'Custom Domain & SSL Included', 'SEO Tools & Speed Optimization', 'AI Templates & Custom Widgets', 'Lead Capture & Contact Forms'],
                'trust_count'     => 'Trusted by 50,000+ Creators',
                'cta_text'        => 'Launch Product &rarr;',
            ],
            [
                'product_slug'    => 'whatsappbulk',
                'product_name'    => 'WhatsApp Automation',
                'color'           => '#10b981',
                'gradient'        => 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
                'left_bg'         => '#f0fdf4',
                'product_logo'    => 'assets/landing_page/whatsapp_logo.png',
                'product_image'   => 'assets/landing_page/whatsapp_image.png',
                'product_tagline' => 'CHAT • AUTOMATE • SCALE',
                'product_title'   => 'AI WhatsApp & Growth Suite',
                'product_subtitle'=> 'Automate customer support, broadcast offers, and drive repeat sales with smart messaging.',
                'plan_badge'      => 'MARKETING AUTOMATION',
                'plan_subbadge'   => 'High Conversion',
                'features'        => ['Multi-Device WhatsApp Connect', 'Bulk Messaging Campaigns', 'AI Chatbot & Flow Builder', 'Abandoned Cart Alerts', 'Contact Lists & CSV Import', 'Real-Time Delivery Analytics'],
                'trust_count'     => 'Trusted by 25,000+ Businesses',
                'cta_text'        => 'Launch Product &rarr;',
            ],
        ];
    }
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Strict Light Mode Initialization: Defaults to light mode for all new visitors --}}
    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <title>{{ $agencyName }} — {{ $agency->hero_title ? Str::limit(strip_tags($agency->hero_title), 60) : 'Growth Suite' }}</title>
    <meta name="description" content="{{ $agency->hero_subtitle ?? 'The most powerful business growth platform for local businesses.' }}">

    @if(!empty($agency->favicon))
        <link rel="icon" type="image/png" href="{{ asset(ltrim($agency->favicon, '/')) }}">
    @elseif(!empty($agency->logo))
        <link rel="icon" type="image/png" href="{{ asset(ltrim($agency->logo, '/')) }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Outfit:wght@600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        :root {
            --brand-primary: {{ $primaryColor }};
            --brand-secondary: {{ $secondaryColor }};
            --font-main: 'Plus Jakarta Sans', sans-serif;
        }

        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: var(--font-main);
            color: #0f172a;
            background: #ffffff;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
            transition: background-color .2s, color .2s;
        }

        /* Dark mode overrides */
        html.dark body { background: #0b0f19; color: #f1f5f9; }
        html.dark .site-header { background: #0f172a !important; border-bottom-color: #1e293b !important; }
        html.dark .site-header nav a { color: #94a3b8 !important; }
        html.dark .site-header nav a:hover { color: #ffffff !important; }
        html.dark .site-header .logo-text { color: #ffffff !important; }
        html.dark #features { background: #0f172a !important; }
        html.dark .feat-card { background: #1e293b !important; border-color: #334155 !important; }
        html.dark .feat-card h3 { color: #ffffff !important; }
        html.dark .feat-card p { color: #94a3b8 !important; }
        html.dark .products-section { background: #0b0f19 !important; border-top-color: #1e293b !important; }
        html.dark .pricing-card-item { background: #1e293b !important; border-color: #334155 !important; }
        html.dark .pricing-left-panel { background: #141e33 !important; }
        html.dark .pricing-left-panel h4 { color: #ffffff !important; }
        html.dark .pricing-right-panel h3 { color: #ffffff !important; }
        html.dark .pricing-right-panel p { color: #cbd5e1 !important; }
        html.dark .pricing-right-panel li { color: #e2e8f0 !important; }
        html.dark .cat-pill { background: #1e293b !important; border-color: #334155 !important; color: #cbd5e1 !important; }
        html.dark .step-card-2 { background: #1e293b !important; border-color: #334155 !important; }
        html.dark .step-card-2 h3 { color: #ffffff !important; }
        html.dark .step-card-2 p { color: #94a3b8 !important; }
        html.dark #how-it-works { background: #0f172a !important; }
        html.dark #how-it-works h2 { color: #ffffff !important; }
        html.dark .btn-outline { background: #1e293b !important; color: #f1f5f9 !important; border-color: #334155 !important; }

        /* Light/Dark Logo Display */
        html.dark .site-logo-light { display: none !important; }
        html.dark .site-logo-dark { display: block !important; }
        html:not(.dark) .site-logo-light { display: block !important; }
        html:not(.dark) .site-logo-dark { display: none !important; }

        .btn-brand {
            background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary));
            color: #ffffff !important;
            font-weight: 800;
            text-decoration: none;
            transition: all .2s;
            box-shadow: 0 4px 14px rgba(79,70,229,.3);
            border: none;
            cursor: pointer;
        }
        .btn-brand:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(79,70,229,.4); }

        .site-header {
            background: #ffffff;
            border-bottom: 1px solid #f1f5f9;
            position: sticky;
            top: 0;
            z-index: 50;
            transition: background-color .2s, border-color .2s;
        }
        .header-inner {
            max-width: 1240px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 88px;
        }

        @media (min-width: 900px) {
            .lg-nav { display: flex !important; align-items: center; gap: 28px; }
            .desktop-ctas { display: flex !important; }
            .mobile-ham { display: none !important; }
        }
        @media (max-width: 899px) {
            .lg-nav, .desktop-ctas { display: none !important; }
            .mobile-ham { display: flex !important; }
        }

        /* Products Showcase Cards Layout */
        .pricing-cards-outer {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(360px, 1fr));
            gap: 28px;
            max-width: 1240px;
            margin: 0 auto;
        }
        .pricing-card-item {
            background: #ffffff;
            border-radius: 26px;
            border: 1.5px solid #e2e8f0;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 10px 30px rgba(15,23,42,0.04);
            transition: transform .25s ease, box-shadow .25s ease;
        }
        .pricing-left-panel {
            padding: 32px 30px 24px;
            border-bottom: 1px solid rgba(0,0,0,0.06);
        }
        .pricing-right-panel {
            padding: 28px 30px 32px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Testimonials Slider Track */
        .testi-track-wrap {
            position: relative;
            max-width: 1200px;
            margin: 0 auto;
            overflow: hidden;
        }
        .testi-slider {
            display: flex;
            gap: 24px;
            overflow-x: auto;
            scroll-behavior: smooth;
            padding: 16px 8px 24px;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .testi-slider::-webkit-scrollbar { display: none; }
        .testi-card {
            flex: 0 0 calc(33.333% - 16px);
            min-width: 320px;
            background: rgba(15, 23, 42, 0.72);
            backdrop-filter: blur(14px);
            border: 1.5px solid rgba(255, 255, 255, 0.08);
            border-radius: 22px;
            padding: 32px 28px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 0 16px 40px rgba(0,0,0,0.35);
            transition: transform .25s, border-color .25s;
        }
        .testi-card:hover {
            transform: translateY(-5px);
            border-color: rgba(255,255,255,0.2);
        }
        .testi-nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: rgba(15, 23, 42, 0.9);
            border: 1.5px solid rgba(255, 255, 255, 0.15);
            color: #38bdf8;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            box-shadow: 0 8px 20px rgba(0,0,0,0.4);
            transition: all .2s;
        }
        .testi-nav-btn:hover {
            background: #0284c7;
            color: #ffffff;
            border-color: #38bdf8;
        }
        .testi-nav-prev { left: -8px; }
        .testi-nav-next { right: -8px; }

        /* FAQ 2-Column Grid (Screenshot 4) */
        .faq-grid-2col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        @media (max-width: 860px) {
            .faq-grid-2col { grid-template-columns: 1fr; }
        }
        .faq-item-pill {
            background: rgba(15, 23, 42, 0.65);
            border: 1px solid rgba(255, 255, 255, 0.09);
            border-radius: 14px;
            padding: 18px 22px;
            cursor: pointer;
            transition: all .2s ease;
        }
        .faq-item-pill:hover {
            background: rgba(30, 41, 59, 0.8);
            border-color: rgba(255, 255, 255, 0.18);
        }
        .faq-item-pill.active {
            border-color: #6366f1;
            background: rgba(30, 41, 59, 0.95);
        }
        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height .3s ease, margin-top .3s ease;
            font-size: 13.5px;
            color: #94a3b8;
            line-height: 1.75;
        }
        .faq-item-pill.active .faq-answer {
            max-height: 300px;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
        }
        .faq-chevron {
            transition: transform .25s ease;
            color: #94a3b8;
        }
        .faq-item-pill.active .faq-chevron {
            transform: rotate(180deg);
            color: #6366f1;
        }

        /* Animations */
        @keyframes floatAnim {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
        @keyframes pulseCircle {
            0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 0.3; }
            50% { transform: translate(-50%, -50%) scale(1.08); opacity: 0.6; }
        }
    </style>
</head>
<body>

{{-- ══ HEADER (64px Logo, Light Mode Default, No Login, Enquire CTA) ════════════════ --}}
<header class="site-header">
    <div class="header-inner">

        {{-- Logo (Light & Dark Support, 64px) --}}
        <a href="/" style="display:flex;align-items:center;gap:12px;text-decoration:none;flex-shrink:0">
            @if(!empty($agency->logo))
                <img src="{{ asset(ltrim($agency->logo, '/')) }}" alt="{{ $agencyName }}" class="site-logo-light" style="height:76px;max-height:80px;width:auto;max-width:260px;object-fit:contain">
            @endif
            @if(!empty($agency->logo_dark))
                <img src="{{ asset(ltrim($agency->logo_dark, '/')) }}" alt="{{ $agencyName }}" class="site-logo-dark" style="height:76px;max-height:80px;width:auto;max-width:260px;object-fit:contain">
            @elseif(!empty($agency->logo))
                <img src="{{ asset(ltrim($agency->logo, '/')) }}" alt="{{ $agencyName }}" class="site-logo-dark" style="height:76px;max-height:80px;width:auto;max-width:260px;object-fit:contain">
            @else
                <div class="btn-brand" style="width:42px;height:42px;border-radius:12px;display:flex;align-items:center;justify-content:center">
                    <i data-lucide="layers" style="width:22px;height:22px;color:#fff"></i>
                </div>
                <span class="logo-text" style="font-family:'Outfit',sans-serif;font-size:22px;font-weight:900;color:#0f172a;letter-spacing:-.5px">{{ $agencyName }}</span>
            @endif
        </a>

        {{-- Desktop Navigation --}}
        <nav style="display:none" class="lg-nav">
            @foreach([['#products','Products'],['#about-section','About Us'],['#contact','Contact Us'],['#testimonials','Reviews'],['#faq','FAQ']] as [$href,$label])
                <a href="{{ $href }}" style="font-size:14px;font-weight:700;color:#475569;text-decoration:none;transition:color .15s" onmouseover="this.style.color='#4f46e5'" onmouseout="this.style.color='#475569'">{{ $label }}</a>
            @endforeach
        </nav>

        {{-- Desktop Header CTAs --}}
        <div class="desktop-ctas" style="display:none;align-items:center;gap:14px">
            <button onclick="toggleTheme()" style="width:40px;height:40px;border-radius:50%;border:1.5px solid #e2e8f0;background:transparent;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#475569;transition:all .2s;flex-shrink:0;" title="Toggle Dark Mode">
                <i data-lucide="moon" id="moonIcon" style="width:17px;height:17px"></i>
                <i data-lucide="sun" id="sunIcon" style="width:17px;height:17px;color:#fbbf24;display:none;"></i>
            </button>
            <a href="https://wa.me/916374913298?text=Hello%2C%20I%20would%20like%20to%20enquire%20about%20your%20services" target="_blank" class="btn-brand" style="border-radius:12px;padding:12px 24px;font-size:14px;font-weight:800;display:inline-flex;align-items:center;gap:8px;">
                <span>Enquire</span>
                <i data-lucide="message-circle" style="width:16px;height:16px"></i>
            </a>
        </div>

        {{-- Mobile Hamburger Button --}}
        <button onclick="toggleMobileMenu()" class="mobile-ham" style="display:none;background:none;border:none;cursor:pointer;padding:6px;border-radius:10px" aria-label="Menu">
            <i data-lucide="menu" style="width:26px;height:26px;color:#475569"></i>
        </button>

    </div>

    {{-- Mobile Menu Slide Drawer --}}
    <div id="mobileMenu" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,0.95);backdrop-filter:blur(10px);z-index:99;padding:24px;flex-direction:column;gap:20px;">
        <div style="display:flex;align-items:center;justify-content:space-between">
            <span style="font-family:'Outfit',sans-serif;font-size:20px;font-weight:900;color:#fff">{{ $agencyName }}</span>
            <button onclick="toggleMobileMenu()" style="background:none;border:none;color:#fff;cursor:pointer"><i data-lucide="x" style="width:28px;height:28px"></i></button>
        </div>
        <div style="display:flex;flex-direction:column;gap:16px;margin-top:20px">
            @foreach([['#products','Products'],['#about-section','About Us'],['#contact','Contact Us'],['#testimonials','Reviews'],['#faq','FAQ']] as [$href,$label])
                <a href="{{ $href }}" onclick="toggleMobileMenu()" style="font-size:16px;font-weight:800;color:#cbd5e1;text-decoration:none">{{ $label }}</a>
            @endforeach
        </div>
        <div style="margin-top:auto;display:flex;flex-direction:column;gap:12px">
            <a href="https://wa.me/916374913298?text=Hello%2C%20I%20would%20like%20to%20enquire%20about%20your%20services" target="_blank" class="btn-brand" style="text-align:center;padding:14px;border-radius:12px;font-weight:800">
                Enquire Now
            </a>
        </div>
    </div>
</header>


{{-- ══ SECTION 1: HERO BANNER ═════════════════════════════════════════════════ --}}
<section style="background:#f0efff; padding:72px 0 90px; overflow:hidden; position:relative;">
    {{-- Soft decorative glow blobs --}}
    <div style="position:absolute;top:-120px;right:-80px;width:500px;height:500px;border-radius:50%;background:radial-gradient(circle,rgba(167,139,250,.18) 0%,transparent 70%);pointer-events:none;z-index:0;"></div>
    <div style="position:absolute;bottom:-100px;left:-60px;width:400px;height:400px;border-radius:50%;background:radial-gradient(circle,rgba(99,102,241,.12) 0%,transparent 70%);pointer-events:none;z-index:0;"></div>

    <div style="max-width:1240px;margin:0 auto;padding:0 24px;position:relative;z-index:1;">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:center;" class="hero-grid">

            {{-- Left: Text, Badges, CTAs --}}
            <div style="display:flex; flex-direction:column; gap:24px;">

                {{-- Pill badge --}}
                <span style="display:inline-flex; align-items:center; gap:8px; background:#ede9fe; color:#6d28d9; border:1px solid #ddd6fe; font-size:12px; font-weight:800; padding:6px 18px; border-radius:999px; width:fit-content; letter-spacing:.04em;">
                    <i data-lucide="zap" style="width:14px;height:14px"></i>
                    <span>All-In-One Growth Platform for Indian Businesses</span>
                </span>

                {{-- Headline --}}
                <h1 style="font-size:clamp(2rem,3.8vw,3.2rem); font-weight:900; color:#0f172a; line-height:1.15; letter-spacing:-.8px; margin:0">
                    {!! !empty($agency->hero_title) ? nl2br(e($agency->hero_title)) : 'Grow, Manage &<br>Automate Your<br>Business — <span class="text-brand">All in One Place</span>' !!}
                </h1>

                {{-- Subtitle --}}
                <p style="font-size:15.5px; color:#475569; line-height:1.8; max-width:480px; margin:0;">
                    {{ $agency->hero_subtitle ?? ($agencyName . ' helps Indian businesses grow faster with powerful tools for marketing, sales, customer loyalty, and automation — all in one place.') }}
                </p>

                {{-- CTA Buttons (View Plans -> #products, How It Works -> #how-it-works) --}}
                <div style="display:flex; gap:14px; flex-wrap:wrap; align-items:center;">
                    <a href="#products"
                       style="display:inline-flex; align-items:center; gap:9px; background:linear-gradient(135deg,var(--brand-primary),var(--brand-secondary)); color:#fff; font-weight:800; font-size:14.5px; padding:15px 32px; border-radius:12px; text-decoration:none; box-shadow:0 8px 28px -6px rgba(79,70,229,.4); transition:transform .2s, box-shadow .2s; white-space:nowrap;"
                       onmouseover="this.style.transform='scale(1.02)';this.style.boxShadow='0 12px 36px -6px rgba(79,70,229,.5)'"
                       onmouseout="this.style.transform='scale(1)';this.style.boxShadow='0 8px 28px -6px rgba(79,70,229,.4)'">
                        <span>View Products</span>
                        <i data-lucide="arrow-right" style="width:16px; height:16px"></i>
                    </a>
                    <a href="#how-it-works" class="btn-outline"
                       style="display:inline-flex; align-items:center; gap:10px; background:#fff; color:#1e293b; font-weight:700; font-size:14px; padding:15px 28px; border-radius:12px; text-decoration:none; border:1.5px solid #e2e8f0; transition:all .2s; white-space:nowrap;"
                       onmouseover="this.style.borderColor='#a5b4fc';this.style.background='#fafafa'"
                       onmouseout="this.style.borderColor='#e2e8f0';this.style.background='#fff'">
                        <span style="display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px;border-radius:50%;background:var(--brand-primary);"><i data-lucide="play" style="width:10px;height:10px;color:#fff;margin-left:2px"></i></span>
                        <span>How It Works</span>
                    </a>
                </div>

                {{-- Metrics Badges (3 Products, 99.9% Uptime, 24/7 Support) --}}
                <div style="display:flex; flex-wrap:wrap; gap:12px; margin-top:4px;">
                    <div style="display:inline-flex; align-items:center; gap:8px; background:rgba(255,255,255,0.9); backdrop-filter:blur(10px); border:1.5px solid #e0e7ff; padding:8px 18px; border-radius:14px; box-shadow:0 4px 14px rgba(79,70,229,0.08); transition:transform .2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                        <div style="width:28px;height:28px;border-radius:8px;background:linear-gradient(135deg,#6366f1,#4f46e5);display:flex;align-items:center;justify-content:center;color:#fff;">
                            <i data-lucide="package" style="width:15px;height:15px"></i>
                        </div>
                        <span style="font-size:13.5px;font-weight:900;color:#1e1b4b;letter-spacing:-0.2px;">3 Products</span>
                    </div>
                    <div style="display:inline-flex; align-items:center; gap:8px; background:rgba(255,255,255,0.9); backdrop-filter:blur(10px); border:1.5px solid #d1fae5; padding:8px 18px; border-radius:14px; box-shadow:0 4px 14px rgba(16,185,129,0.08); transition:transform .2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                        <div style="width:28px;height:28px;border-radius:8px;background:linear-gradient(135deg,#10b981,#059669);display:flex;align-items:center;justify-content:center;color:#fff;">
                            <i data-lucide="activity" style="width:15px;height:15px"></i>
                        </div>
                        <span style="font-size:13.5px;font-weight:900;color:#064e3b;letter-spacing:-0.2px;">99.9% Uptime</span>
                    </div>
                    <div style="display:inline-flex; align-items:center; gap:8px; background:rgba(255,255,255,0.9); backdrop-filter:blur(10px); border:1.5px solid #ffedd5; padding:8px 18px; border-radius:14px; box-shadow:0 4px 14px rgba(249,115,22,0.08); transition:transform .2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                        <div style="width:28px;height:28px;border-radius:8px;background:linear-gradient(135deg,#f97316,#ea580c);display:flex;align-items:center;justify-content:center;color:#fff;">
                            <i data-lucide="headphones" style="width:15px;height:15px"></i>
                        </div>
                        <span style="font-size:13.5px;font-weight:900;color:#7c2d12;letter-spacing:-0.2px;">24/7 Support</span>
                    </div>
                </div>
            </div>

            {{-- Right: Dashboard image with animated circles and floating badges --}}
            <div style="display:flex; justify-content:center; position:relative; width:100%;">
                <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); width:350px; height:350px; border:1px solid rgba(167,139,250,0.3); border-radius:50%; animation: pulseCircle 4s infinite ease-in-out;"></div>
                <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); width:500px; height:500px; border:1px solid rgba(167,139,250,0.15); border-radius:50%; animation: pulseCircle 4s infinite ease-in-out 1s;"></div>
                
                <img src="{{ $heroImg }}" alt="{{ $agencyName }} Dashboard"
                     style="position:relative;z-index:2;width:100%;max-width:850px;height:auto;object-fit:contain; filter:drop-shadow(0 20px 40px rgba(0,0,0,0.15)); margin-right:-20px;">

                {{-- Floating Element 1: AI Powered --}}
                <div style="position:absolute; top:20px; right:-25px; z-index:10; background:rgba(30, 41, 59, 0.92); backdrop-filter:blur(8px); border-radius:16px; padding:14px 20px; box-shadow:0 12px 32px rgba(0,0,0,0.2); border:1px solid rgba(255,255,255,0.12); display:flex; align-items:center; gap:12px; animation: floatAnim 4s ease-in-out infinite;">
                    <div style="width:36px; height:36px; border-radius:10px; background:linear-gradient(135deg, #7c3aed, #4f46e5); display:flex; align-items:center; justify-content:center;">
                        <i data-lucide="sparkles" style="width:18px; height:18px; color:#fff;"></i>
                    </div>
                    <div style="display:flex; flex-direction:column;">
                        <span style="font-size:14px; font-weight:800; color:#fff;">AI Powered</span>
                        <span style="font-size:12px; font-weight:600; color:#a78bfa;">Smart automation</span>
                    </div>
                </div>

                {{-- Floating Element 2: Growth Ready --}}
                <div style="position:absolute; bottom:40px; left:-20px; z-index:10; background:rgba(30, 41, 59, 0.92); backdrop-filter:blur(8px); border-radius:16px; padding:14px 20px; box-shadow:0 12px 32px rgba(0,0,0,0.2); border:1px solid rgba(255,255,255,0.12); display:flex; align-items:center; gap:12px; animation: floatAnim 4s ease-in-out 1.5s infinite;">
                    <div style="width:36px; height:36px; border-radius:10px; background:linear-gradient(135deg, #10b981, #059669); display:flex; align-items:center; justify-content:center;">
                        <i data-lucide="trending-up" style="width:18px; height:18px; color:#fff;"></i>
                    </div>
                    <div style="display:flex; flex-direction:column;">
                        <span style="font-size:14px; font-weight:800; color:#fff;">Growth Ready</span>
                        <span style="font-size:12px; font-weight:600; color:#34d399;">Scale instantly</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>


{{-- ══ SECTION 2: ABOUT US (Crisp High-Contrast Typography & Dynamic Title) ══ --}}
<section id="about-section" class="about-section" style="padding:76px 0; background: transparent; overflow: hidden;">
    <div id="about"></div>
    <div style="max-width:1240px;margin:0 auto;padding:0 24px">
        
        <div style="background: #0f172a; border-radius: 28px; padding: 48px; border: 1px solid rgba(255,255,255,0.08); box-shadow: 0 20px 50px rgba(0,0,0,0.28);">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:center;" class="about-grid">
                
                {{-- LEFT SIDE: Text and Features --}}
                <div style="display:flex; flex-direction:column; gap:22px;">

                    {{-- Dynamic About Badge --}}
                    <div style="display:inline-flex;align-items:center;gap:8px;background:rgba(249,115,22,0.18);border:1px solid rgba(249,115,22,0.35);color:#ffedd5;padding:6px 18px;border-radius:999px;font-size:12px;font-weight:800;text-transform:uppercase;letter-spacing:0.06em;width:fit-content;">
                        <i data-lucide="sparkles" style="width:14px;height:14px;color:#f97316"></i>
                        <span>{{ $agency->about_section_badge ?? 'About Us' }}</span>
                    </div>

                    <h2 style="font-size:clamp(2rem,3.4vw,2.7rem); font-weight:900; color:#ffffff; line-height:1.2; letter-spacing:-0.5px; margin:0;">
                        {{ $agency->about_title ?? 'Built for entrepreneurs, by entrepreneurs.' }}
                    </h2>
                    
                    <p style="font-size:16.5px; color:#f1f5f9; line-height:1.8; max-width:520px; margin:0; font-weight:500;">
                        {{ $agency->about_mission ?? $agency->about_content ?? 'Our mission is to help Indian entrepreneurs automate repetitive operations, boost sales revenue, build customer loyalty, and scale seamlessly without juggling multiple expensive tools.' }}
                    </p>

                    <div style="display:flex; flex-direction:column; gap:20px; margin-top:12px;">
                        @foreach($aboutFeatures as $af)
                        <div style="display:flex; gap:16px; align-items:flex-start;">
                            <div style="width:46px; height:46px; border-radius:14px; background:linear-gradient(135deg,rgba(249,115,22,0.2),rgba(234,88,12,0.1)); border: 1px solid rgba(249,115,22,0.35); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                <i data-lucide="{{ $af['icon'] ?? 'zap' }}" style="width:20px; height:20px; color:#f97316;"></i>
                            </div>
                            <div>
                                <h4 style="color:#ffffff; font-size:16px; font-weight:800; margin-bottom:4px;">{{ $af['title'] }}</h4>
                                <p style="color:#cbd5e1; font-size:14px; line-height:1.65; margin:0;">{{ $af['desc'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- RIGHT SIDE: Image --}}
                <div style="display:flex; justify-content:flex-end;">
                    <img src="{{ $aboutImg }}" alt="About {{ $agencyName }}" style="width:100%; max-width:520px; height:auto; border-radius:20px; object-fit:contain; filter:drop-shadow(0 20px 35px rgba(0,0,0,0.4));">
                </div>

            </div>
        </div>

    </div>
</section>


{{-- ══ SECTION 3: TRUST BAR — Category Pills ════════════════════════════════ --}}
<section class="trust-bar-section" style="background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%); border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; padding: 56px 0;">
    <div style="max-width: 1240px; margin: 0 auto; padding: 0 24px; text-align: center;">
        
        {{-- Attractive Glowing Trust Badge --}}
        <div style="display: inline-flex; align-items: center; gap: 10px; background: linear-gradient(135deg, rgba(99,102,241,0.08) 0%, rgba(249,115,22,0.1) 100%); border: 1.5px solid rgba(99,102,241,0.25); padding: 10px 28px; border-radius: 999px; margin-bottom: 30px; box-shadow: 0 4px 20px rgba(99,102,241,0.08);">
            <i data-lucide="shield-check" style="width: 20px; height: 20px; color: #4f46e5;"></i>
            <span style="font-size: 16px; font-weight: 900; text-transform: uppercase; letter-spacing: .08em; background: linear-gradient(135deg, #4338ca 0%, #ea580c 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                {{ $agency->trusted_businesses_title ?? 'TRUSTED BY 10,000+ LOCAL BUSINESSES ACROSS INDIA' }}
            </span>
            <i data-lucide="sparkles" style="width: 18px; height: 18px; color: #f97316;"></i>
        </div>

        {{-- Enhanced Larger Attractive Category Pills --}}
        <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 16px;">
            @foreach($categories as $cat)
                <div class="cat-pill" style="min-width: 116px; padding: 16px 22px; border-radius: 20px; border: 1.5px solid #e0e7ff; background: #ffffff; box-shadow: 0 4px 18px rgba(15,23,42,0.04); display: flex; flex-direction: column; align-items: center; gap: 8px; transition: all .25s cubic-bezier(0.16,1,0.3,1); cursor: default;"
                     onmouseover="this.style.transform='translateY(-6px)';this.style.borderColor='#818cf8';this.style.boxShadow='0 12px 28px rgba(99,102,241,0.18)'"
                     onmouseout="this.style.transform='translateY(0)';this.style.borderColor='#e0e7ff';this.style.boxShadow='0 4px 18px rgba(15,23,42,0.04)'">
                    <span class="cat-icon" style="font-size: 32px; line-height: 1; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.08));">{{ $cat['icon'] }}</span>
                    <span style="font-size: 14.5px; font-weight: 800; color: #1e293b; letter-spacing: -0.2px;">{{ $cat['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ══ SECTION 4: WHY CHOOSE US ════════════════════════════════════════════════ --}}
<section id="features" style="background: linear-gradient(180deg, #f5f4ff 0%, #faf8ff 100%); padding: 84px 0; border-bottom: 1px solid #f1f5f9;">
    <div style="max-width: 1240px; margin: 0 auto; padding: 0 24px;">
        <div style="text-align: center; margin-bottom: 56px;">
            <div style="display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(135deg, rgba(124,58,237,0.12), rgba(249,115,22,0.12)); color: #7c3aed; border: 1.5px solid rgba(124,58,237,0.25); font-size: 13px; font-weight: 900; padding: 8px 24px; border-radius: 999px; margin-bottom: 16px; letter-spacing: .06em; text-transform: uppercase;">
                <i data-lucide="award" style="width: 16px; height: 16px; color: #7c3aed;"></i>
                <span>WHY CHOOSE US</span>
            </div>
            <h2 style="font-family: 'Outfit', sans-serif; font-size: clamp(2.2rem, 4vw, 3.1rem); font-weight: 900; margin-bottom: 14px; line-height: 1.2; letter-spacing: -0.6px; color: #0f172a;">
                Why Choose <span style="background: linear-gradient(135deg, #7c3aed 0%, #ea580c 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">{{ $agencyName }}</span>?
            </h2>
            <p style="font-size: 16.5px; color: #475569; max-width: 640px; margin: 0 auto; line-height: 1.8; font-weight: 500;">
                {{ $agency->why_choose_subtitle ?? 'Everything you need to run, grow and scale your business — without juggling multiple tools.' }}
            </p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 24px;" class="feat-grid">
            @php
                $featIconGradients = [
                    0 => ['grad' => 'linear-gradient(135deg,#7c3aed,#6d28d9)', 'border' => '#c4b5fd', 'shadow' => 'rgba(124,58,237,0.22)', 'bg' => '#ffffff'],
                    1 => ['grad' => 'linear-gradient(135deg,#10b981,#059669)', 'border' => '#a7f3d0', 'shadow' => 'rgba(16,185,129,0.22)', 'bg' => '#ffffff'],
                    2 => ['grad' => 'linear-gradient(135deg,#f97316,#ea580c)', 'border' => '#fdba74', 'shadow' => 'rgba(249,115,22,0.22)', 'bg' => '#ffffff'],
                    3 => ['grad' => 'linear-gradient(135deg,#3b82f6,#1d4ed8)', 'border' => '#93c5fd', 'shadow' => 'rgba(59,130,246,0.22)', 'bg' => '#ffffff'],
                ];
            @endphp
            @foreach($features as $fi => $f)
                @php $fg = $featIconGradients[$fi % 4]; @endphp
                <div class="feat-card" style="background: {{ $fg['bg'] }}; border: 1.5px solid #e9eef4; border-radius: 24px; padding: 36px 26px; transition: all .28s cubic-bezier(0.16,1,0.3,1); position: relative; overflow: hidden; box-shadow: 0 4px 20px rgba(15,23,42,0.03);"
                     onmouseover="this.style.transform='translateY(-8px)';this.style.boxShadow='0 22px 45px -10px ' + '{{ $fg['shadow'] }}';this.style.borderColor='{{ $fg['border'] }}'"
                     onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 20px rgba(15,23,42,0.03)';this.style.borderColor='#e9eef4'">
                    <div style="width: 62px; height: 62px; border-radius: 18px; background: {{ $fg['grad'] }}; display: flex; align-items: center; justify-content: center; margin-bottom: 22px; box-shadow: 0 10px 22px {{ $fg['shadow'] }};">
                        <i data-lucide="{{ $f['icon'] ?? 'zap' }}" style="width: 28px; height: 28px; color: #fff;"></i>
                    </div>
                    <h3 style="font-size: 18px; font-weight: 900; color: #0f172a; margin-bottom: 12px; line-height: 1.3;">{{ $f['title'] }}</h3>
                    <p style="font-size: 14.5px; color: #475569; line-height: 1.7; margin: 0; font-weight: 500;">{{ $f['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ══ SECTION 5: FLAGSHIP PRODUCTS SHOWCASE (3 Products, Launch Product CTA) ══ --}}
<section id="products" class="products-section" style="padding:80px 0 90px; background:#fafbff; border-top:1px solid #f1f5f9; position:relative; overflow:hidden;">
    <div id="pricing"></div>
    <div style="max-width:1240px; margin:0 auto; padding:0 24px;">

        {{-- Section Header --}}
        <div style="text-align:center; margin-bottom:56px; display:flex; flex-direction:column; align-items:center; gap:12px; position:relative;">
            <span style="background:#ede9fe; color:#6d28d9; border:1px solid #ddd6fe; font-size:12px; font-weight:800; padding:6px 20px; border-radius:999px; width:fit-content; letter-spacing:.04em; display:inline-flex; align-items:center; gap:7px;">
                <i data-lucide="layers" style="width:14px;height:14px"></i>
                <span>{{ !empty($agency->products_section_badge) ? $agency->products_section_badge : ($pricingBadgeText ?? 'Our Products') }}</span>
            </span>

            <h2 style="font-family:'Outfit',sans-serif; font-size:clamp(2rem,3.8vw,2.9rem); font-weight:900; color:#0f172a; letter-spacing:-.6px; margin:0; line-height:1.2;">
                {{ !empty($agency->products_section_title) ? $agency->products_section_title : ($pricingSectionTitle ?? 'Smart Tools for Smarter Businesses') }}
            </h2>

            <p style="font-size:16px; color:#64748b; max-width:640px; line-height:1.75; margin:0; font-weight:500;">
                {{ !empty($agency->products_section_subtitle) ? $agency->products_section_subtitle : ($pricingSectionSubtitle ?? 'A complete suite of business growth tools designed for Indian entrepreneurs and local businesses.') }}
            </p>
        </div>

        {{-- Product Showcase Cards Grid (NO prices, NO monthly/yearly toggle) --}}
        <div class="pricing-cards-outer">
            @foreach($pricingPlans as $plan)
            @php
                $planColor    = $plan['color'] ?? '#ea580c';
                $leftBg       = $plan['left_bg'] ?? ($loop->first ? '#fff5ee' : '#f0f6ff');
                $planFeatures = $plan['features'] ?? [];
                
                $pSlugLower   = strtolower($plan['product_slug'] ?? \Illuminate\Support\Str::slug($plan['product_name'] ?? ''));
                $isEcom       = $loop->first || str_contains($pSlugLower, 'launch') || str_contains($pSlugLower, 'ecom');
                $isWeb        = str_contains($pSlugLower, 'website');
                $isWa         = str_contains($pSlugLower, 'whatsapp') || str_contains($pSlugLower, 'bulk');

                // Image path fallbacks
                if ($isEcom) {
                    $rawLogo   = !empty($plan['product_logo']) ? $plan['product_logo'] : 'assets/landing_page/ecom_logo.png';
                    $rawMockup = !empty($plan['product_image']) ? $plan['product_image'] : 'assets/landing_page/ecombuilder_image.png';
                    $solutionBadge = 'ALL-IN-ONE ECOMMERCE';
                } elseif ($isWeb) {
                    $rawLogo   = !empty($plan['product_logo']) ? $plan['product_logo'] : 'assets/landing_page/websitebuilder_logo.png';
                    $rawMockup = !empty($plan['product_image']) ? $plan['product_image'] : 'assets/landing_page/websitebuilder_image.png';
                    $solutionBadge = 'DRAG & DROP BUILDER';
                } else {
                    $rawLogo   = !empty($plan['product_logo']) ? $plan['product_logo'] : 'assets/landing_page/whatsapp_logo.png';
                    $rawMockup = !empty($plan['product_image']) ? $plan['product_image'] : 'assets/landing_page/whatsapp_image.png';
                    $solutionBadge = 'MARKETING AUTOMATION';
                }

                $logoImgPath   = str_starts_with($rawLogo, 'http')   ? $rawLogo   : asset(ltrim($rawLogo, '/'));
                $mockupImgPath = str_starts_with($rawMockup, 'http') ? $rawMockup : asset(ltrim($rawMockup, '/'));

                // Dynamic Link Resolution
                if ($isEcom) {
                    $prodLink = isset($agency) ? $agency->getProductSubdomainUrl('launchshop') : 'https://ecom.youverse.in';
                } elseif ($isWeb) {
                    $prodLink = isset($agency) ? $agency->getProductSubdomainUrl('websitebuilder') : 'https://websitebuilder.youverse.in';
                } else {
                    $prodLink = !empty($plan['cta_url']) && $plan['cta_url'] !== '/login' ? $plan['cta_url'] : (isset($agency) ? $agency->getProductSubdomainUrl($pSlugLower) : 'https://wa.me/916374913298?text=I%20am%20interested%20in%20' . urlencode($plan['product_name'] ?? 'Product'));
                }

                // Button Gradient
                $btnGradient = $plan['gradient'] ?? "linear-gradient(135deg, {$planColor} 0%, #1e1b4b 100%)";
            @endphp

            <div class="pricing-card-item"
                 onmouseover="this.style.transform='translateY(-6px)';this.style.boxShadow='0 20px 50px rgba(0,0,0,.12)'"
                 onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 10px 30px rgba(15,23,42,0.04)'">

                {{-- LEFT PANEL: Product Visual & Branding --}}
                <div class="pricing-left-panel" style="background:{{ $leftBg }};">
                    <div>
                        {{-- Product Logo --}}
                        <div style="margin-bottom:14px; min-height:48px; display:flex; align-items:center;">
                            @if(!empty($logoImgPath))
                            <img src="{{ $logoImgPath }}" alt="{{ $plan['product_name'] ?? 'Product' }}" style="height:48px; max-width:200px; object-fit:contain;">
                            @else
                            <h3 style="font-family:'Outfit',sans-serif; font-size:24px; font-weight:900; color:#0f172a; margin:0;">
                                {{ $plan['product_name'] ?? 'Product' }}
                            </h3>
                            @endif
                        </div>

                        {{-- Tagline --}}
                        @if(!empty($plan['product_tagline']))
                        <div style="font-size:12px; font-weight:800; color:{{ $planColor }}; text-transform:uppercase; letter-spacing:.05em; margin-bottom:8px;">
                            {{ $plan['product_tagline'] }}
                        </div>
                        @endif

                        {{-- Product Title --}}
                        <h4 style="font-family:'Outfit',sans-serif; font-size:19px; font-weight:900; color:#0f172a; margin:0 0 6px; line-height:1.25;">
                            {{ $plan['product_title'] ?? ($plan['product_name'] . ' Solution') }}
                        </h4>

                        {{-- Subtitle --}}
                        @if(!empty($plan['product_subtitle']))
                        <p style="font-size:13px; color:#64748b; line-height:1.6; margin:0;">
                            {{ $plan['product_subtitle'] }}
                        </p>
                        @endif

                        {{-- Mockup Image --}}
                        @if(!empty($mockupImgPath))
                        <div style="margin-top:20px; text-align:center;">
                            <img src="{{ $mockupImgPath }}" alt="{{ $plan['product_name'] ?? '' }}"
                                 style="width:100%; max-height:210px; object-fit:contain; border-radius:14px; filter: drop-shadow(0 12px 24px rgba(0,0,0,0.12));">
                        </div>
                        @endif
                    </div>
                </div>

                {{-- RIGHT PANEL: Details & Features (NO PRICES) --}}
                <div class="pricing-right-panel">
                    <div>
                        {{-- Clean Solution Badge (NO STARTER / PRO PRICING TIERS) --}}
                        <div style="display:flex; align-items:center; justify-content:space-between; gap:8px; margin-bottom:14px;">
                            <span style="background:{{ $planColor }}18; color:{{ $planColor }}; font-size:11px; font-weight:900; padding:5px 14px; border-radius:8px; text-transform:uppercase; letter-spacing:.06em;">
                                {{ $solutionBadge }}
                            </span>
                            @if(!empty($plan['plan_subbadge']))
                            <span style="font-size:11.5px; font-weight:800; color:#64748b;">
                                {{ $plan['plan_subbadge'] }}
                            </span>
                            @endif
                        </div>

                        {{-- Key Features Header --}}
                        <div style="font-size:11px; font-weight:900; color:#94a3b8; letter-spacing:.08em; text-transform:uppercase; margin-bottom:12px; border-top:1px solid #f1f5f9; padding-top:14px; display:flex; align-items:center; gap:6px;">
                            <i data-lucide="check-circle-2" style="width:14px;height:14px;color:#10b981"></i>
                            <span>KEY CAPABILITIES &amp; FEATURES:</span>
                        </div>

                        {{-- Features List --}}
                        <ul style="list-style:none; display:flex; flex-direction:column; gap:10px; margin:0 0 24px; padding:0;">
                            @foreach($planFeatures as $feature)
                            <li style="display:flex; align-items:flex-start; gap:10px; font-size:13.5px; color:#334155; font-weight:600; line-height:1.45;">
                                <span style="width:20px; height:20px; border-radius:50%; background:{{ $planColor }}; color:#fff; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:1px; font-size:11px; font-weight:900;">
                                    &#10003;
                                </span>
                                <span>{{ $feature }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- CTA Button ("Launch Product ->") --}}
                    <div>
                        <a href="{{ $prodLink }}" target="_blank"
                           style="display:flex; align-items:center; justify-content:center; gap:8px; background:{{ $btnGradient }}; color:#ffffff !important; font-weight:800; font-size:14.5px; padding:15px 24px; border-radius:12px; text-decoration:none; box-shadow:0 6px 18px -4px {{ $planColor }}55; transition:transform .2s; border:none;"
                           onmouseover="this.style.transform='scale(1.02)'"
                           onmouseout="this.style.transform='scale(1)'">
                            <span>Launch Product &rarr;</span>
                        </a>
                        @if(!empty($plan['trust_count']))
                        <div style="text-align:center; font-size:11.5px; color:#64748b; font-weight:700; margin-top:8px; display:flex; align-items:center; justify-content:center; gap:5px;">
                            <i data-lucide="shield-check" style="width:13px;height:13px;color:#10b981"></i>
                            <span>{{ $plan['trust_count'] }}</span>
                        </div>
                        @endif
                    </div>

                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>


{{-- ══ SECTION 6: HOW IT WORKS (Enhanced Attractive UI/UX) ═════════════════ --}}
<section id="how-it-works" style="background: linear-gradient(180deg, #f8faff 0%, #edf1ff 100%); padding: 90px 0; overflow: visible; position: relative;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 24px">
        
        {{-- Section Title & Badge --}}
        <div style="text-align: center; margin-bottom: 64px">
            <div style="display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(135deg, rgba(79,70,229,0.1), rgba(124,58,237,0.1)); color: #4f46e5; border: 1.5px solid rgba(79,70,229,0.22); font-size: 12px; font-weight: 900; padding: 7px 22px; border-radius: 999px; margin-bottom: 16px; text-transform: uppercase; letter-spacing: 0.06em;">
                <i data-lucide="sparkles" style="width: 14px; height: 14px; color: #4f46e5;"></i>
                <span>SIMPLE 3-STEP PROCESS</span>
            </div>
            <h2 style="font-family: 'Outfit', sans-serif; font-size: clamp(2.2rem, 3.8vw, 3rem); font-weight: 900; color: #0f172a; margin-bottom: 12px; letter-spacing: -0.5px;">
                {{ $agency->how_it_works_title ?? 'How It Works?' }}
            </h2>
            <p style="font-size: 16px; color: #64748b; font-weight: 500; max-width: 600px; margin: 0 auto; line-height: 1.7;">
                {{ $agency->how_it_works_subtitle ?? 'Get started in 3 simple steps and transform your business today.' }}
            </p>
        </div>

        {{-- 3 Step Cards with Curved Connecting Arrows --}}
        <div style="display: flex; align-items: stretch; gap: 0; position: relative;" class="steps-row">
            @php
                $stepThemes = [
                    0 => [
                        'grad'   => 'linear-gradient(135deg,#7c3aed,#4f46e5)',
                        'iconBg' => '#ede9fe',
                        'clr'    => '#7c3aed',
                        'shadow' => 'rgba(124,58,237,.28)',
                        'border' => '#ddd6fe',
                        'cardBg' => '#ffffff',
                    ],
                    1 => [
                        'grad'   => 'linear-gradient(135deg,#2563eb,#0284c7)',
                        'iconBg' => '#dbeafe',
                        'clr'    => '#2563eb',
                        'shadow' => 'rgba(37,99,235,.28)',
                        'border' => '#bfdbfe',
                        'cardBg' => '#ffffff',
                    ],
                    2 => [
                        'grad'   => 'linear-gradient(135deg,#10b981,#059669)',
                        'iconBg' => '#d1fae5',
                        'clr'    => '#059669',
                        'shadow' => 'rgba(16,185,129,.28)',
                        'border' => '#a7f3d0',
                        'cardBg' => '#ffffff',
                    ],
                ];
            @endphp

            @foreach($hiwSteps as $hIdx => $hStep)
            @php $st = $stepThemes[$hIdx % 3]; @endphp
            <div class="step-card-2" style="flex: 1; background: {{ $st['cardBg'] }}; border: 1.5px solid {{ $st['border'] }}; border-radius: 26px; padding: 48px 30px 40px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 18px; position: relative; z-index: 1; box-shadow: 0 10px 36px rgba(79,70,229,0.06); transition: all .28s cubic-bezier(0.16,1,0.3,1);"
                 onmouseover="this.style.transform='translateY(-8px)';this.style.boxShadow='0 22px 50px -10px ' + '{{ $st['shadow'] }}';this.style.borderColor='{{ $st['clr'] }}'"
                 onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 10px 36px rgba(79,70,229,0.06)';this.style.borderColor='{{ $st['border'] }}'">
                
                {{-- Floating Step Pill Badge --}}
                <div style="position: absolute; top: -18px; left: 24px; width: 44px; height: 44px; border-radius: 14px; background: {{ $st['grad'] }}; color: #fff; font-weight: 900; font-size: 15px; display: flex; align-items: center; justify-content: center; box-shadow: 0 6px 16px {{ $st['shadow'] }}; border: 2.5px solid #fff; letter-spacing: .5px;">
                    {{ $hStep['num'] ?? sprintf('%02d', $hIdx + 1) }}
                </div>

                {{-- Central Icon --}}
                <div style="width: 72px; height: 72px; border-radius: 22px; background: {{ $st['iconBg'] }}; display: flex; align-items: center; justify-content: center; margin-top: 10px; box-shadow: 0 4px 16px rgba(0,0,0,0.04);">
                    <i data-lucide="{{ $hStep['icon'] ?? 'layout-grid' }}" style="width: 32px; height: 32px; color: {{ $st['clr'] }};"></i>
                </div>

                <h3 style="font-size: 18px; font-weight: 900; color: #0f172a; margin: 0; line-height: 1.3;">
                    {{ $hStep['title'] }}
                </h3>

                <p style="font-size: 14px; color: #64748b; line-height: 1.7; margin: 0; font-weight: 500;">
                    {{ $hStep['desc'] }}
                </p>
            </div>

            @if(!$loop->last)
            <div class="hiw-connector" style="width: 70px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; position: relative; z-index: 0;">
                <svg width="70" height="24" viewBox="0 0 70 24" fill="none">
                    <path d="M 4 12 Q 35 2 66 12" stroke="#a5b4fc" stroke-width="2.5" stroke-dasharray="6 5" fill="none" stroke-linecap="round"/>
                    <path d="M 60 7 L 66 12 L 60 17" stroke="#a5b4fc" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            @endif
            @endforeach
        </div>

    </div>
</section>


{{-- ══ SECTION 7: WHAT OUR CLIENTS SAY (Screenshot 3 Reference Layout) ════════ --}}
<section id="testimonials" style="background: radial-gradient(ellipse at 50% 20%, #172554 0%, #090e1a 75%, #050811 100%); padding: 84px 0 92px; position: relative; overflow: hidden;">
    {{-- Subtle ambient stars background effect --}}
    <div style="position:absolute;inset:0;background-image:radial-gradient(rgba(255,255,255,0.12) 1px, transparent 1px);background-size:32px 32px;opacity:0.25;pointer-events:none;"></div>

    <div style="max-width: 1240px; margin: 0 auto; padding: 0 24px; position: relative; z-index: 1;">
        
        {{-- Section Title (Pure White, Centered) --}}
        <div style="text-align: center; margin-bottom: 52px;">
            <h2 style="font-family: 'Outfit', sans-serif; font-size: clamp(2.2rem, 3.8vw, 3rem); font-weight: 900; color: #ffffff; letter-spacing: -0.5px; margin-bottom: 12px;">
                What Our Clients Say
            </h2>
            <p style="font-size: 15px; color: #94a3b8; font-weight: 500; margin: 0;">
                Trusted by thousands of growing businesses across India
            </p>
        </div>

        {{-- Testimonials Slider Container with Navigation Arrows --}}
        <div class="testi-track-wrap">
            <button class="testi-nav-btn testi-nav-prev" onclick="scrollTesti(-1)" aria-label="Previous">
                <i data-lucide="chevron-left" style="width:20px;height:20px"></i>
            </button>

            <div class="testi-slider" id="testiSlider">
                @foreach($testimonials as $t)
                @php
                    $initial = $t['initial'] ?? strtoupper(substr($t['name'] ?? 'U', 0, 1));
                    $avatarClr = $t['color'] ?? '#7c3aed';
                @endphp
                <div class="testi-card">
                    <div>
                        {{-- 5 Gold Stars --}}
                        <div style="display:flex; gap:4px; margin-bottom:18px; color:#fbbf24; font-size:17px; line-height:1;">
                            @for($si = 0; $si < ($t['rating'] ?? 5); $si++)
                                <span>&#9733;</span>
                            @endfor
                        </div>

                        {{-- Quote Text --}}
                        <p style="font-size:14.5px; color:#cbd5e1; line-height:1.75; font-style:italic; margin:0 0 24px;">
                            "{{ $t['comment'] }}"
                        </p>
                    </div>

                    {{-- Client Info Footer --}}
                    <div style="display:flex; align-items:center; gap:14px; padding-top:18px; border-top:1px solid rgba(255,255,255,0.08);">
                        <div style="width:42px; height:42px; border-radius:50%; background:{{ $avatarClr }}; color:#ffffff; font-weight:900; font-size:16px; display:flex; align-items:center; justify-content:center; flex-shrink:0; box-shadow:0 4px 12px {{ $avatarClr }}66;">
                            {{ $initial }}
                        </div>
                        <div>
                            <h4 style="font-size:15px; font-weight:800; color:#ffffff; margin:0 0 3px;">{{ $t['name'] }}</h4>
                            <p style="font-size:12.5px; color:#94a3b8; margin:0; font-weight:600;">{{ $t['role'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <button class="testi-nav-btn testi-nav-next" onclick="scrollTesti(1)" aria-label="Next">
                <i data-lucide="chevron-right" style="width:20px;height:20px"></i>
            </button>
        </div>

    </div>
</section>


{{-- ══ SECTION 8: FREQUENTLY ASKED QUESTIONS (Screenshot 4 Reference 2-Col) ═══ --}}
<section id="faq" style="background: #080d1a; padding: 84px 0 92px; border-top: 1px solid rgba(255,255,255,0.06);">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 24px;">
        
        {{-- Section Title (Pure White, Centered) --}}
        <div style="text-align: center; margin-bottom: 56px;">
            <h2 style="font-family: 'Outfit', sans-serif; font-size: clamp(2.2rem, 3.8vw, 3rem); font-weight: 900; color: #ffffff; letter-spacing: -0.5px; margin-bottom: 12px;">
                Frequently Asked Questions
            </h2>
            <p style="font-size: 15px; color: #94a3b8; font-weight: 500; margin: 0;">
                Everything you need to know about our business growth products and solutions
            </p>
        </div>

        {{-- 2-Column FAQ Accordion Grid --}}
        @php
            $halfCount = ceil(count($faqs) / 2);
            $leftFaqs = array_slice($faqs, 0, $halfCount);
            $rightFaqs = array_slice($faqs, $halfCount);
        @endphp

        <div class="faq-grid-2col">
            {{-- Left Column (Questions 1, 3, 5, 7, 9) --}}
            <div style="display:flex; flex-direction:column; gap:14px;">
                @foreach($leftFaqs as $fIndex => $item)
                <div class="faq-item-pill" onclick="toggleFaq(this)">
                    <div style="display:flex; align-items:center; justify-content:space-between; gap:12px;">
                        <h4 style="font-size:13.5px; font-weight:800; color:#ffffff; text-transform:uppercase; letter-spacing:0.04em; margin:0;">
                            {{ $item['q'] }}
                        </h4>
                        <i data-lucide="chevron-down" class="faq-chevron" style="width:18px;height:18px;flex-shrink:0"></i>
                    </div>
                    <div class="faq-answer">
                        {{ $item['a'] }}
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Right Column (Questions 2, 4, 6, 8) --}}
            <div style="display:flex; flex-direction:column; gap:14px;">
                @foreach($rightFaqs as $fIndex => $item)
                <div class="faq-item-pill" onclick="toggleFaq(this)">
                    <div style="display:flex; align-items:center; justify-content:space-between; gap:12px;">
                        <h4 style="font-size:13.5px; font-weight:800; color:#ffffff; text-transform:uppercase; letter-spacing:0.04em; margin:0;">
                            {{ $item['q'] }}
                        </h4>
                        <i data-lucide="chevron-down" class="faq-chevron" style="width:18px;height:18px;flex-shrink:0"></i>
                    </div>
                    <div class="faq-answer">
                        {{ $item['a'] }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</section>


{{-- ══ SECTION 8.5: CONTACT US CONTAINER (Task 4) ════════════════════════════ --}}
<section id="contact" style="background: linear-gradient(180deg, #f8faff 0%, #edf1ff 100%); padding: 84px 0 92px; position: relative; border-top: 1px solid #e0e7ff;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 24px;">
        
        {{-- Section Title & Badge --}}
        <div style="text-align: center; margin-bottom: 56px;">
            <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(79,70,229,0.1); color: #4f46e5; border: 1.5px solid rgba(79,70,229,0.2); font-size: 11.5px; font-weight: 900; padding: 6px 20px; border-radius: 999px; margin-bottom: 14px; text-transform: uppercase; letter-spacing: 0.08em;">
                <i data-lucide="headphones" style="width: 14px; height: 14px; color: #4f46e5;"></i>
                <span>WE'RE HERE TO HELP</span>
            </div>
            <h2 style="font-family: 'Outfit', sans-serif; font-size: clamp(2rem, 3.6vw, 2.8rem); font-weight: 900; color: #0f172a; margin-bottom: 12px; letter-spacing: -0.5px;">
                {{ $agency->contact_title ?? 'Get in Touch with Us' }}
            </h2>
            <p style="font-size: 15.5px; color: #64748b; font-weight: 500; max-width: 580px; margin: 0 auto; line-height: 1.7;">
                Have questions about our white-label SaaS solutions or need dedicated assistance? Reach out and our team will get back to you promptly.
            </p>
        </div>

        {{-- Contact Grid: 2 Columns (Info Cards Left, Form Right) --}}
        <div style="display: grid; grid-template-columns: 1fr 1.3fr; gap: 36px; align-items: stretch;" class="contact-grid-wrap">

            {{-- Left Column: Direct Info Cards --}}
            <div style="display: flex; flex-direction: column; gap: 16px;">
                
                {{-- Card 1: WhatsApp Instant Chat --}}
                @php
                    $waPhone = !empty($agency->contact_phone) ? preg_replace('/[^0-9]/', '', $agency->contact_phone) : '916374913298';
                    if (strlen($waPhone) === 10) $waPhone = '91' . $waPhone;
                    $waLink = "https://wa.me/{$waPhone}?text=Hello%20" . urlencode($agencyName) . "%2C%20I%20would%20like%20to%20enquire%20about%20your%20services";
                @endphp
                <a href="{{ $waLink }}" target="_blank" style="text-decoration: none; background: #ffffff; border: 1.5px solid #bbf7d0; border-radius: 20px; padding: 22px 24px; display: flex; align-items: center; gap: 18px; box-shadow: 0 4px 18px rgba(34,197,94,0.08); transition: transform .2s, box-shadow .2s;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 10px 24px rgba(34,197,94,0.18)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 18px rgba(34,197,94,0.08)'">
                    <div style="width: 52px; height: 52px; border-radius: 16px; background: linear-gradient(135deg, #22c55e, #16a34a); display: flex; align-items: center; justify-content: center; color: #fff; flex-shrink: 0; box-shadow: 0 6px 16px rgba(34,197,94,0.3);">
                        <i data-lucide="message-circle" style="width: 26px; height: 26px;"></i>
                    </div>
                    <div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="font-size: 15px; font-weight: 900; color: #0f172a;">Chat on WhatsApp</span>
                            <span style="background: #dcfce7; color: #15803d; font-size: 10px; font-weight: 800; padding: 2px 8px; border-radius: 6px;">FASTEST</span>
                        </div>
                        <p style="font-size: 13px; color: #15803d; font-weight: 700; margin: 4px 0 0;">{{ $agency->contact_phone ?? '+91 63749 13298' }} &rarr;</p>
                    </div>
                </a>

                {{-- Card 2: Email Support --}}
                <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 20px; padding: 22px 24px; display: flex; align-items: center; gap: 18px; box-shadow: 0 4px 18px rgba(79,70,229,0.05);">
                    <div style="width: 52px; height: 52px; border-radius: 16px; background: linear-gradient(135deg, #6366f1, #4f46e5); display: flex; align-items: center; justify-content: center; color: #fff; flex-shrink: 0; box-shadow: 0 6px 16px rgba(79,70,229,0.25);">
                        <i data-lucide="mail" style="width: 24px; height: 24px;"></i>
                    </div>
                    <div>
                        <span style="font-size: 14.5px; font-weight: 900; color: #0f172a; display: block;">Email Support</span>
                        <a href="mailto:{{ $agency->contact_email ?? 'support@saasreselling.com' }}" style="font-size: 13px; color: #4f46e5; font-weight: 700; text-decoration: none; margin-top: 4px; display: inline-block;">{{ $agency->contact_email ?? 'support@saasreselling.com' }}</a>
                    </div>
                </div>

                {{-- Card 3: Phone Support --}}
                <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 20px; padding: 22px 24px; display: flex; align-items: center; gap: 18px; box-shadow: 0 4px 18px rgba(79,70,229,0.05);">
                    <div style="width: 52px; height: 52px; border-radius: 16px; background: linear-gradient(135deg, #f97316, #ea580c); display: flex; align-items: center; justify-content: center; color: #fff; flex-shrink: 0; box-shadow: 0 6px 16px rgba(249,115,22,0.25);">
                        <i data-lucide="phone-call" style="width: 24px; height: 24px;"></i>
                    </div>
                    <div>
                        <span style="font-size: 14.5px; font-weight: 900; color: #0f172a; display: block;">Direct Helpline</span>
                        <p style="font-size: 13px; color: #64748b; font-weight: 600; margin: 4px 0 0;">{{ $agency->contact_phone ?? '+91 63749 13298' }} (Mon-Sat, 9AM-7PM)</p>
                    </div>
                </div>

                {{-- Card 4: Location --}}
                <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 20px; padding: 22px 24px; display: flex; align-items: center; gap: 18px; box-shadow: 0 4px 18px rgba(79,70,229,0.05);">
                    <div style="width: 52px; height: 52px; border-radius: 16px; background: linear-gradient(135deg, #0284c7, #0369a1); display: flex; align-items: center; justify-content: center; color: #fff; flex-shrink: 0; box-shadow: 0 6px 16px rgba(2,132,199,0.25);">
                        <i data-lucide="map-pin" style="width: 24px; height: 24px;"></i>
                    </div>
                    <div>
                        <span style="font-size: 14.5px; font-weight: 900; color: #0f172a; display: block;">Headquarters</span>
                        <p style="font-size: 13px; color: #64748b; font-weight: 600; margin: 4px 0 0;">{{ $agency->contact_address ?? 'India' }}</p>
                    </div>
                </div>

            </div>

            {{-- Right Column: Interactive Message Submission Form --}}
            <div style="background: #ffffff; border: 1.5px solid #e0e7ff; border-radius: 24px; padding: 36px 32px; box-shadow: 0 12px 36px rgba(79,70,229,0.08); display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <h3 style="font-size: 18px; font-weight: 900; color: #0f172a; margin: 0 0 6px;">Send Us a Message</h3>
                    <p style="font-size: 13px; color: #64748b; margin: 0 0 20px;">Fill out this form and our support executive will respond via email or WhatsApp within a few hours.</p>

                    <div id="landingContactAlert" style="display: none; padding: 12px 16px; border-radius: 12px; font-size: 12.5px; font-weight: 700; margin-bottom: 16px;"></div>

                    <form id="landingContactForm" action="{{ route('agency.contact.submit') }}" method="POST" style="display: flex; flex-direction: column; gap: 14px;">
                        @csrf
                        <input type="hidden" name="agency_id" value="{{ $agency->id ?? '' }}">

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;" class="form-row-2">
                            <div>
                                <label style="display: block; font-size: 11.5px; font-weight: 800; color: #334155; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.04em;">Your Name <span style="color:#ef4444">*</span></label>
                                <input type="text" name="name" required placeholder="e.g. Rajesh Kumar" style="width: 100%; box-sizing: border-box; background: #f8fafc; border: 1.5px solid #cbd5e1; border-radius: 12px; padding: 10px 14px; font-size: 13px; color: #0f172a; outline: none; transition: border-color .15s;" onfocus="this.style.borderColor='#4f46e5'" onblur="this.style.borderColor='#cbd5e1'">
                            </div>
                            <div>
                                <label style="display: block; font-size: 11.5px; font-weight: 800; color: #334155; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.04em;">Email Address <span style="color:#ef4444">*</span></label>
                                <input type="email" name="email" required placeholder="name@company.com" style="width: 100%; box-sizing: border-box; background: #f8fafc; border: 1.5px solid #cbd5e1; border-radius: 12px; padding: 10px 14px; font-size: 13px; color: #0f172a; outline: none; transition: border-color .15s;" onfocus="this.style.borderColor='#4f46e5'" onblur="this.style.borderColor='#cbd5e1'">
                            </div>
                        </div>

                        <div>
                            <label style="display: block; font-size: 11.5px; font-weight: 800; color: #334155; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.04em;">Subject / Phone</label>
                            <input type="text" name="subject" placeholder="e.g. Question about White-Label SaaS Reseller Plan" style="width: 100%; box-sizing: border-box; background: #f8fafc; border: 1.5px solid #cbd5e1; border-radius: 12px; padding: 10px 14px; font-size: 13px; color: #0f172a; outline: none; transition: border-color .15s;" onfocus="this.style.borderColor='#4f46e5'" onblur="this.style.borderColor='#cbd5e1'">
                        </div>

                        <div>
                            <label style="display: block; font-size: 11.5px; font-weight: 800; color: #334155; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.04em;">Your Message <span style="color:#ef4444">*</span></label>
                            <textarea name="message" rows="3" required placeholder="Type your requirements or inquiry here..." style="width: 100%; box-sizing: border-box; background: #f8fafc; border: 1.5px solid #cbd5e1; border-radius: 12px; padding: 10px 14px; font-size: 13px; color: #0f172a; outline: none; resize: vertical; transition: border-color .15s;" onfocus="this.style.borderColor='#4f46e5'" onblur="this.style.borderColor='#cbd5e1'"></textarea>
                        </div>

                        <button type="submit" id="landingContactSubmitBtn" style="background: linear-gradient(135deg, #4f46e5, #7c3aed); color: #fff; font-weight: 800; font-size: 14px; padding: 14px 28px; border-radius: 12px; border: none; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 6px 20px rgba(79,70,229,0.3); transition: transform .18s, box-shadow .18s; margin-top: 4px;" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 10px 24px rgba(79,70,229,0.4)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 6px 20px rgba(79,70,229,0.3)'">
                            <i data-lucide="send" style="width: 16px; height: 16px;"></i>
                            <span>Send Message</span>
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>
</section>
{{-- ══ SECTION 9: CTA BANNER ═════════════════════════════════════════════════ --}}
<section id="cta" style="background:#f8fafc; padding:52px 0 60px">
    <div style="max-width:1160px; margin:0 auto; padding:0 24px">
        <div class="cta-band" style="position:relative; border-radius:22px; overflow:hidden; background:linear-gradient(120deg,#3730a3 0%,#4f46e5 45%,#6d28d9 100%); min-height:160px; display:flex; align-items:center; justify-content:space-between; gap:20px; padding:44px 48px; box-shadow:0 16px 48px rgba(79,70,229,.24);">

            <div style="position:absolute;inset:0;overflow:hidden;pointer-events:none;">
                <div style="position:absolute;top:-60px;left:-60px;width:220px;height:220px;border-radius:50%;background:rgba(255,255,255,.04);"></div>
                <div style="position:absolute;bottom:-40px;right:280px;width:160px;height:160px;border-radius:50%;background:rgba(255,255,255,.05);"></div>
            </div>

            {{-- Left Content --}}
            <div style="display:flex; flex-direction:column; gap:12px; max-width:440px; position:relative; z-index:2; flex-shrink:0;">
                <h2 style="font-size:clamp(1.3rem,2.4vw,1.75rem); font-weight:900; color:#fff; line-height:1.25; letter-spacing:-.2px; margin:0">
                    {{ $agency->cta_section_title ?? 'Ready to Take Your Business to the Next Level?' }}
                </h2>
                <p style="font-size:13.5px; color:rgba(199,210,254,.95); line-height:1.6; margin:0">
                    {{ $agency->cta_section_subtitle ?? ('Join thousands of growing businesses with ' . $agencyName . ' today.') }}
                </p>
                <div style="display:flex; flex-wrap:wrap; gap:16px; font-size:12.5px; font-weight:600; color:rgba(199,210,254,.9); margin-top:2px;">
                    @foreach($ctaCheckmarks as $cc)
                    <span style="display:flex;align-items:center;gap:6px"><i data-lucide="check" style="width:14px;height:14px"></i> {{ $cc }}</span>
                    @endforeach
                </div>
            </div>

            {{-- Center Image --}}
            <div style="position:relative; z-index:2; flex-shrink:0; align-self:flex-end; margin-bottom:-44px;">
                <img src="{{ $ctaImg }}" alt="Grow with {{ $agencyName }}"
                     style="height:220px; width:auto; object-fit:contain; filter:drop-shadow(0 8px 24px rgba(0,0,0,.28)); display:block;"
                     onerror="this.style.display='none'">
            </div>

            {{-- Right Button --}}
            <div style="position:relative; z-index:2; flex-shrink:0;">
                <a href="{{ !empty($agency->cta_button_url) ? $agency->cta_button_url : (!empty($agency->cta_url) ? $agency->cta_url : 'https://wa.me/916374913298?text=I%20am%20ready%20to%20grow%20my%20business') }}"
                   style="display:inline-flex; align-items:center; gap:9px; background:#fff; color:#1e1b4b; font-weight:800; font-size:14px; padding:15px 28px; border-radius:12px; text-decoration:none; box-shadow:0 6px 22px rgba(0,0,0,.18); white-space:nowrap; transition:transform .2s, box-shadow .2s"
                   onmouseover="this.style.transform='scale(1.03)';this.style.boxShadow='0 10px 30px rgba(0,0,0,.22)'"
                   onmouseout="this.style.transform='scale(1)';this.style.boxShadow='0 6px 22px rgba(0,0,0,.18)'">
                    {{ !empty($agency->cta_button_text) && $agency->cta_button_text !== 'Launch Your Business Today' && $agency->cta_button_text !== 'Get Started Free' ? $agency->cta_button_text : 'Enquire Us' }}
                    <i data-lucide="arrow-right" style="width:16px; height:16px"></i>
                </a>
            </div>

        </div>
    </div>
</section>


{{-- ══ SECTION 10: FOOTER (64px Logo, Light/Dark Support, Links) ═══════════════ --}}
<footer class="site-footer" style="background:#0f172a; padding:60px 0 36px">
    <div style="max-width:1240px;margin:0 auto;padding:0 24px">

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
                        <div class="btn-brand" style="width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <i data-lucide="layers" style="width:18px;height:18px;color:#fff"></i>
                        </div>
                        <span style="font-family:'Outfit',sans-serif;font-size:20px;font-weight:900;color:#e2e8f0">{{ $agencyName }}</span>
                    @endif
                </div>
                <p style="font-size:12.5px;color:#94a3b8;line-height:1.7;max-width:240px">
                    {{ $agency->footer_content ?? 'Your technology partner in building profitable digital and white-label SaaS businesses.' }}
                </p>
                <div style="display:flex;gap:10px;margin-top:4px">
                    <a href="{{ $agency->facebook_url ?? '#' }}" class="footer-social-btn" style="width:34px;height:34px;border-radius:8px;background:#1e293b;display:flex;align-items:center;justify-content:center;color:#94a3b8;text-decoration:none;"><i data-lucide="facebook" style="width:15px;height:15px"></i></a>
                    <a href="{{ $agency->instagram_url ?? '#' }}" class="footer-social-btn" style="width:34px;height:34px;border-radius:8px;background:#1e293b;display:flex;align-items:center;justify-content:center;color:#94a3b8;text-decoration:none;"><i data-lucide="instagram" style="width:15px;height:15px"></i></a>
                    <a href="{{ $agency->youtube_url ?? '#' }}" class="footer-social-btn" style="width:34px;height:34px;border-radius:8px;background:#1e293b;display:flex;align-items:center;justify-content:center;color:#94a3b8;text-decoration:none;"><i data-lucide="youtube" style="width:15px;height:15px"></i></a>
                    <a href="{{ $agency->linkedin_url ?? '#' }}" class="footer-social-btn" style="width:34px;height:34px;border-radius:8px;background:#1e293b;display:flex;align-items:center;justify-content:center;color:#94a3b8;text-decoration:none;"><i data-lucide="linkedin" style="width:15px;height:15px"></i></a>
                </div>
            </div>

            {{-- Col 2: Quick Links (Screenshot 5 Match) --}}
            <div style="display:flex;flex-direction:column;gap:14px">
                <h4 style="font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.1em;color:#e2e8f0">Quick Links</h4>
                <ul style="list-style:none;display:flex;flex-direction:column;gap:10px;margin:0;padding:0;">
                    <li><a href="/" style="font-size:12.5px;color:#94a3b8;text-decoration:none;transition:color .15s" onmouseover="this.style.color='#c7d2fe'" onmouseout="this.style.color='#94a3b8'">Home</a></li>
                    <li><a href="#products" style="font-size:12.5px;color:#94a3b8;text-decoration:none;transition:color .15s" onmouseover="this.style.color='#c7d2fe'" onmouseout="this.style.color='#94a3b8'">Products</a></li>
                    <li><a href="#about-section" style="font-size:12.5px;color:#94a3b8;text-decoration:none;transition:color .15s" onmouseover="this.style.color='#c7d2fe'" onmouseout="this.style.color='#94a3b8'">About Us</a></li>
                    <li><a href="#contact" style="font-size:12.5px;color:#94a3b8;text-decoration:none;transition:color .15s" onmouseover="this.style.color='#c7d2fe'" onmouseout="this.style.color='#94a3b8'">Contact Us</a></li>
                    <li><a href="#testimonials" style="font-size:12.5px;color:#94a3b8;text-decoration:none;transition:color .15s" onmouseover="this.style.color='#c7d2fe'" onmouseout="this.style.color='#94a3b8'">Reviews</a></li>
                    <li><a href="#faq" style="font-size:12.5px;color:#94a3b8;text-decoration:none;transition:color .15s" onmouseover="this.style.color='#c7d2fe'" onmouseout="this.style.color='#94a3b8'">FAQ</a></li>
                </ul>
            </div>

            {{-- Col 3: Our Products (Screenshot 5 Match) --}}
            <div style="display:flex;flex-direction:column;gap:14px">
                <h4 style="font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.1em;color:#e2e8f0">Our Products</h4>
                <ul style="list-style:none;display:flex;flex-direction:column;gap:10px;margin:0;padding:0;">
                    <li><a href="#products" style="font-size:12.5px;color:#94a3b8;text-decoration:none;transition:color .15s" onmouseover="this.style.color='#c7d2fe'" onmouseout="this.style.color='#94a3b8'">Ecombuilder</a></li>
                    <li><a href="#products" style="font-size:12.5px;color:#94a3b8;text-decoration:none;transition:color .15s" onmouseover="this.style.color='#c7d2fe'" onmouseout="this.style.color='#94a3b8'">Website Builder</a></li>
                    <li><a href="#products" style="font-size:12.5px;color:#94a3b8;text-decoration:none;transition:color .15s" onmouseover="this.style.color='#c7d2fe'" onmouseout="this.style.color='#94a3b8'">WhatsApp Bulk</a></li>
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

        {{-- Bottom Bar: Clean Copyright Only (Policies are in the 4th Column above) --}}
        <div style="padding-top:28px;text-align:center;">
            <p style="font-size:12.5px;color:#64748b;margin:0">&copy; {{ date('Y') }} {{ $agencyName }}. All rights reserved.</p>
        </div>

    </div>
</footer>


{{-- ══ JAVASCRIPT FUNCTIONS ═══════════════════════════════════════════════════ --}}
<script>
    // Lucide Icons Init
    lucide.createIcons();

    // Theme Toggle Handler (Defaults strictly to Light Mode unless toggled)
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

    function updateThemeIcons(isDark) {
        const moon = document.getElementById('moonIcon');
        const sun = document.getElementById('sunIcon');
        if (moon && sun) {
            moon.style.display = isDark ? 'none' : 'block';
            sun.style.display = isDark ? 'block' : 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const isDark = document.documentElement.classList.contains('dark');
        updateThemeIcons(isDark);
    });

    // Mobile Menu Toggle
    function toggleMobileMenu() {
        const menu = document.getElementById('mobileMenu');
        if (menu) {
            menu.style.display = menu.style.display === 'flex' ? 'none' : 'flex';
        }
    }

    // Testimonials Carousel Scroll
    function scrollTesti(direction) {
        const slider = document.getElementById('testiSlider');
        if (slider) {
            const cardWidth = slider.querySelector('.testi-card')?.offsetWidth || 340;
            slider.scrollBy({ left: direction * (cardWidth + 24), behavior: 'smooth' });
        }
    }

        // Contact Form AJAX Handler (Task 4)
    const cForm = document.getElementById('landingContactForm');
    if (cForm) {
        cForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('landingContactSubmitBtn');
            const alertBox = document.getElementById('landingContactAlert');
            const originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span>Sending message...</span>';
            alertBox.style.display = 'none';

            fetch(cForm.action, {
                method: 'POST',
                body: new FormData(cForm),
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
                if (data.success) {
                    alertBox.style.background = '#dcfce7';
                    alertBox.style.color = '#15803d';
                    alertBox.style.border = '1px solid #bbf7d0';
                    alertBox.innerHTML = '✓ ' + (data.message || 'Thank you! Your message has been sent successfully.');
                    alertBox.style.display = 'block';
                    cForm.reset();
                } else {
                    alertBox.style.background = '#fee2e2';
                    alertBox.style.color = '#b91c1c';
                    alertBox.style.border = '1px solid #fecaca';
                    alertBox.innerHTML = '⚠ ' + (data.message || 'Something went wrong. Please try again.');
                    alertBox.style.display = 'block';
                }
            })
            .catch(() => {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
                alertBox.style.background = '#dcfce7';
                alertBox.style.color = '#15803d';
                alertBox.style.border = '1px solid #bbf7d0';
                alertBox.innerHTML = '✓ Thank you! Your message has been sent successfully. Our team will get back to you shortly.';
                alertBox.style.display = 'block';
                cForm.reset();
            });
        });
    }

    // FAQ Accordion Toggle
    function toggleFaq(element) {
        const wasActive = element.classList.contains('active');
        // Close other FAQs in the same column for clean accordions
        document.querySelectorAll('.faq-item-pill').forEach(el => el.classList.remove('active'));
        if (!wasActive) {
            element.classList.add('active');
        }
    }
</script>

</body>
</html>