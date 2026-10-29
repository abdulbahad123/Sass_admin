<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agency->meta_title ?? ($agency->name . ' — All-in-One Business Growth Platform') }}</title>
    <meta name="description" content="{{ $agency->meta_description ?? ($agency->hero_subtitle ?? 'Empowering Indian businesses with smart digital tools.') }}">

    @if(!empty($agency->favicon))
        <link rel="icon" type="image/png" href="{{ asset($agency->favicon) }}">
    @endif
    <meta property="og:title" content="{{ $agency->meta_title ?? $agency->name }}">
    <meta property="og:description" content="{{ $agency->meta_description ?? $agency->hero_subtitle }}">
    @if(!empty($agency->og_image ?? $agency->hero_image))
        <meta property="og:image" content="{{ asset($agency->og_image ?? $agency->hero_image) }}">
    @endif

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
        // Strict light mode default — ONLY activate dark mode if user explicitly selected it
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @php
        $agencyGet = fn($key) => $agency->$key ?? null;
        $primaryColor = $agency->primary_color ?? '#f97316';
        $secondaryColor = !empty($agency->secondary_color) && $agency->secondary_color != '#9333ea' ? $agency->secondary_color : '#ea580c';

        $heroImg  = !empty($agency->hero_image)  ? asset(ltrim($agency->hero_image, '/'))  : asset('assets/images/agency_herobanner.png');
        $aboutImg = !empty($agency->about_image) ? asset(ltrim($agency->about_image, '/')) : asset('assets/images/about_right.png');
        $ctaImg   = !empty($agency->cta_image)   ? asset(ltrim($agency->cta_image, '/'))   : asset('assets/landing_page/footer_card.png');

        /* KB stats — dynamically editable */
        $kbStatsRaw = $agencyGet('kb_stats');
        $kbStats = is_array($kbStatsRaw)
            ? $kbStatsRaw
            : (json_decode($kbStatsRaw ?? '[]', true) ?: [
                ['value' => '10,000+', 'label' => 'Happy Businesses',  'icon' => 'users'],
                ['value' => '1M+',     'label' => 'Orders Processed',  'icon' => 'package'],
                ['value' => '500K+',   'label' => 'Active Customers',  'icon' => 'shield'],
                ['value' => '99.8%',   'label' => 'Uptime & Secure',   'icon' => 'sparkles'],
            ]);

        $kbFloatingRaw = $agencyGet('kb_floating_data');
        $kbFloating = is_array($kbFloatingRaw)
            ? $kbFloatingRaw
            : (json_decode($kbFloatingRaw ?? '[]', true) ?: [
                'order_num'       => '#ORD-125',
                'revenue'         => '₹24,50,000',
                'revenue_growth'  => '12.5%',
                'customers'       => '1,245',
                'customer_growth' => '18.2%',
                'customer_month'  => '+192 this month',
            ]);

        try {
            $dynamicProducts = ($agency && method_exists($agency, 'getEnabledProductsAttribute')) ? ($agency->enabled_products ?? collect()) : collect();
        } catch (\Throwable $e) {
            $dynamicProducts = collect();
        }
        if ($dynamicProducts->isNotEmpty() && empty($agency->services_data)) {
            $services = [];
            foreach ($dynamicProducts as $dp) {
                $services[] = [
                    'title' => $dp->name,
                    'desc'  => $dp->tagline ?? $dp->description ?? 'White-label SaaS product suite enabled for your end-clients.',
                    'icon'  => $dp->icon ?? 'box',
                    'link'  => method_exists($agency, 'getProductSubdomainUrl') ? $agency->getProductSubdomainUrl($dp->slug ?? $dp->name) : '#',
                ];
            }
        } else {
            $services = is_array($agency->services_data ?? null)
                ? $agency->services_data
                : (json_decode($agency->services_data ?? '[]', true) ?: [
                    ['title' => 'AI Reviews + CRM',    'desc' => 'Get more 5-star reviews & manage customers easily',     'icon' => 'star'],
                    ['title' => 'Website Builder',      'desc' => 'Create stunning websites in minutes with AI',           'icon' => 'monitor'],
                    ['title' => 'Digital V-Card',       'desc' => 'Share your business digitally, smartly',               'icon' => 'user'],
                    ['title' => 'QR Menu & Ordering',   'desc' => 'Contactless menu for restaurants & cafes',             'icon' => 'qr-code'],
                    ['title' => 'Loyalty Program',      'desc' => 'Reward your customers and increase repeat sales',      'icon' => 'gift'],
                    ['title' => 'Business Analytics',   'desc' => 'Track growth with real-time insights',                 'icon' => 'bar-chart-3'],
                ]);
        }

        $aboutFeatures = is_array($agency->about_features_data ?? null)
            ? $agency->about_features_data
            : (json_decode($agency->about_features_data ?? '[]', true) ?: [
                ['title' => 'Lightning Performance', 'desc' => 'Modern architecture delivering sub-second load times and 99.9% uptime.', 'icon' => 'zap'],
                ['title' => 'Enterprise Security',   'desc' => 'Bank-grade encryption and industry-leading security protocols to protect your data.', 'icon' => 'shield-check'],
                ['title' => 'Scale Without Limits',   'desc' => 'Infrastructure that grows with your business — from 10 to 10,000 clients.', 'icon' => 'trending-up'],
            ]);

        $hiwSteps = is_array($agency->how_it_works_steps ?? null)
            ? $agency->how_it_works_steps
            : (json_decode($agency->how_it_works_steps ?? '[]', true) ?: [
                ['num' => '01', 'title' => 'Sign Up',              'desc' => 'Create your account in less than 2 minutes.',               'icon' => 'user-plus'],
                ['num' => '02', 'title' => 'Set Up Your Business', 'desc' => 'Choose the tools you need and customize in minutes.',      'icon' => 'monitor'],
                ['num' => '03', 'title' => 'Grow Faster',          'desc' => 'Get more customers, more reviews and more revenue.',         'icon' => 'bar-chart-2'],
            ]);

        $ctaCheckmarks = is_array($agency->cta_features_data ?? null)
            ? $agency->cta_features_data
            : (json_decode($agency->cta_features_data ?? '[]', true) ?: ['Quick Setup', 'No Credit Card Required', '24/7 Support']);

        $testimonials = is_array($agency->testimonials_data ?? null)
            ? $agency->testimonials_data
            : (json_decode($agency->testimonials_data ?? '[]', true) ?: [
                ['name' => 'Rahul Sharma', 'role' => 'Restaurant Owner, Delhi',  'rating' => 5, 'comment' => "{$agency->name} helped us get 3x more online orders in just 2 months. The QR menu and reviews feature is amazing!"],
                ['name' => 'Priya Mehta',  'role' => 'Salon Owner, Mumbai',      'rating' => 5, 'comment' => 'Super easy to use and really effective. Our customer engagement has never been better!'],
                ['name' => 'Amit Verma',   'role' => 'Clinic Owner, Bengaluru',  'rating' => 5, 'comment' => 'The digital tools, CRM and reminders have saved us hours of work every week.'],
            ]);

        $features = is_array($agency->features_data ?? null)
            ? $agency->features_data
            : (json_decode($agency->features_data ?? '[]', true) ?: [
                ['title' => 'Get More Customers', 'desc' => 'Build trust with reviews, smart websites and digital presence.', 'icon' => 'rocket',       'bg' => '#ede9fe', 'color' => '#7c3aed'],
                ['title' => 'Save Time & Effort',  'desc' => 'Automate repetitive tasks and focus on what matters most.',      'icon' => 'clock',        'bg' => '#d1fae5', 'color' => '#059669'],
                ['title' => 'Increase Revenue',    'desc' => 'Drive repeat business with loyalty programs & digital tools.',   'icon' => 'trending-up',  'bg' => '#ffedd5', 'color' => '#ea580c'],
                ['title' => 'Reliable & Secure',   'desc' => 'Your business data is safe with enterprise-grade security.',     'icon' => 'shield-check', 'bg' => '#dbeafe', 'color' => '#1d4ed8'],
            ]);

        $faqs = is_array($agency->faq_data ?? null)
            ? $agency->faq_data
            : (json_decode($agency->faq_data ?? '[]', true) ?: [
                ['q' => 'How does the platform work?',                   'a' => 'Our platform provides an all-in-one suite of growth tools to help local businesses manage orders, reviews, websites, and customer retention from one place.'],
                ['q' => 'Can I customize the features for my business?', 'a' => 'Yes, you can enable and configure the exact tools you need in just a few clicks from your dashboard.'],
                ['q' => 'Is technical knowledge required?',              'a' => 'Not at all! Our software is built for non-technical business owners with clean, easy-to-use interfaces.'],
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
                ['label' => 'Real Estate',    'icon' => '🏠'],
                ['label' => '& Many More',    'icon' => '✨'],
            ];

        $trustElements = ($agency && method_exists($agency, 'getParsedTrustElementsAttribute'))
            ? $agency->parsed_trust_elements
            : [
                ['icon' => 'shield-check', 'title' => 'Secure & Reliable', 'desc' => 'Your data is always safe with us', 'bg' => '#d1fae5', 'color' => '#059669'],
                ['icon' => 'headphones',   'title' => '24/7 Support',       'desc' => "We're here whenever you need help",  'bg' => '#ede9fe', 'color' => '#7c3aed'],
                ['icon' => 'heart',        'title' => 'Trusted by 10,000+ Businesses', 'desc' => 'Growing together, every day', 'bg' => '#fee2e2', 'color' => '#dc2626'],
            ];

        /* product icon map */
        $pIconMap = [
            'AI Reviews + CRM'  => ['icon' => 'star',         'bg' => '#ede9fe', 'clr' => '#7c3aed'],
            'Website Builder'   => ['icon' => 'monitor',      'bg' => '#dbeafe', 'clr' => '#1d4ed8'],
            'Digital V-Card'    => ['icon' => 'user',         'bg' => '#d1fae5', 'clr' => '#059669'],
            'QR Menu & Ordering'=> ['icon' => 'qr-code',     'bg' => '#fce7f3', 'clr' => '#be185d'],
            'Loyalty Program'   => ['icon' => 'gift',         'bg' => '#fef3c7', 'clr' => '#d97706'],
            'Business Analytics'=> ['icon' => 'bar-chart-3',  'bg' => '#ccfbf1', 'clr' => '#0d9488'],
        ];

        /* Pricing plans — from agency dashboard or auto-generated */
        $pricingPlans = ($agency && method_exists($agency, 'getParsedPricingPlansAttribute'))
            ? $agency->parsed_pricing_plans
            : [];
        if (empty($pricingPlans)) {
            $pricingPlans = [
                ['product_name' => 'ECOM BUILDER', 'product_tagline' => 'Online Store Builder', 'product_subtitle' => 'Launch your digital store in minutes.', 'color' => '#f97316', 'gradient' => 'linear-gradient(135deg,#f97316,#ea580c)', 'icon' => 'shopping-bag', 'plan_badge' => 'STARTER TIER', 'plan_name' => 'Starter Growth', 'price_monthly' => '499', 'price_yearly' => '4999', 'trust_count' => '10,000+ Sellers Trust Us', 'is_popular' => false, 'features' => ['1 Online Store', 'Up to 1,000 Products', 'Basic Analytics & Reports', 'Standard Email Support', 'Custom Subdomain Setup'], 'cta_text' => 'Get Started Free', 'cta_url' => $agency->cta_url ?? '/login'],
                ['product_name' => 'Website Builder', 'product_tagline' => 'Professional Website Builder', 'product_subtitle' => 'Create stunning websites with AI in minutes.', 'color' => '#2563eb', 'gradient' => 'linear-gradient(135deg,#2563eb,#1d4ed8)', 'icon' => 'monitor', 'plan_badge' => 'MOST POPULAR', 'plan_name' => 'Professional Pro', 'price_monthly' => '1499', 'price_yearly' => '14999', 'trust_count' => '5,000+ Websites Live', 'is_popular' => true, 'features' => ['Drag & Drop Builder', 'Custom Domain', 'SEO Optimization', 'AI Content Generation', '24/7 Priority Support'], 'cta_text' => 'Start 14-Day Free Trial', 'cta_url' => $agency->cta_url ?? '/login'],
            ];
        }
        $pricingSectionTitle    = $agency->pricing_section_title    ?? 'Choose Your Perfect Plan';
        $pricingSectionSubtitle = $agency->pricing_section_subtitle ?? 'Scale seamlessly with zero hidden fees.';
        $pricingBadgeText       = $agency->pricing_badge_text       ?? 'Flexible Pricing Plans';
        $pricingYearlyDiscount  = $agency->pricing_yearly_discount  ?? 'Save 20%';
        $pricingTrustBarRaw     = $agency->pricing_trust_bar        ?? '🔒 Secure & Reliable,📞 24/7 Support,❤️ Trusted by 10,000+ Businesses';
        $pricingTrustItems      = array_map('trim', explode(',', $pricingTrustBarRaw));
    @endphp

    <style>
        :root {
            --brand-primary:   {{ $primaryColor }};
            --brand-secondary: {{ $secondaryColor }};
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body  { font-family: 'Plus Jakarta Sans', sans-serif; color: #1e293b; background: #fff; overflow-x: hidden; }
        h1, h2, h3, h4, h5 { font-family: 'Outfit', sans-serif; }

        /* ── BRAND UTILITIES ── */
        .bg-brand  { background: linear-gradient(135deg, var(--brand-primary) 0%, var(--brand-secondary) 100%); }
        .text-brand {
            background: linear-gradient(135deg, var(--brand-primary) 0%, var(--brand-secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .btn-brand {
            display: inline-flex; align-items: center; gap: 8px;
            background: linear-gradient(135deg, var(--brand-primary) 0%, var(--brand-secondary) 100%);
            color: #fff; font-weight: 800; border-radius: 999px;
            padding: 12px 28px; font-size: 13px; line-height: 1;
            box-shadow: 0 8px 24px -6px rgba(79,70,229,.38);
            transition: transform .2s, box-shadow .2s;
            text-decoration: none; white-space: nowrap;
        }
        .btn-brand:hover { transform: scale(1.03); box-shadow: 0 12px 32px -6px rgba(79,70,229,.5); }
        .btn-outline {
            display: inline-flex; align-items: center; gap: 8px;
            background: #fff; color: #1e293b; font-weight: 700;
            border: 1.5px solid #e2e8f0; border-radius: 999px;
            padding: 12px 26px; font-size: 13px; line-height: 1;
            text-decoration: none; transition: background .2s;
        }
        .btn-outline:hover { background: #f8fafc; }

        /* ── HEADER ── */
        /* ── LOGO SWITCHING (Light default & Dark mode support) ── */
        .site-logo-dark { display: none !important; }
        .site-logo-light { display: block !important; }
        html.dark .site-logo-dark { display: block !important; }
        html.dark .site-logo-light { display: none !important; }

        .site-header {
            position: sticky; top: 0; z-index: 50;
            background: rgba(255,255,255,.96);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid #f1f5f9;
            box-shadow: 0 1px 3px 0 rgba(0,0,0,.04);
        }
        .header-inner { max-width: 1200px; margin: 0 auto; padding: 0 24px; min-height: 76px; height: 76px; display: flex; align-items: center; justify-content: space-between; gap: 20px; }

        /* ── CATEGORY PILL ── */
        .cat-pill {
            display: flex; flex-direction: column; align-items: center; gap: 5px;
            min-width: 76px; padding: 10px 14px; border-radius: 14px;
            border: 1px solid #e2e8f0; background: #fff;
            font-size: 11px; font-weight: 700; color: #64748b;
            transition: all .2s; cursor: default;
        }
        .cat-pill:hover { background: #eff6ff; border-color: #bfdbfe; color: #2563eb; transform: translateY(-2px); }
        .cat-icon { font-size: 22px; line-height: 1; }

        /* ── FEATURE CARDS ── */
        .feat-card {
            background: #fff; border: 1px solid #e9eef4; border-radius: 18px;
            padding: 28px 24px;
            transition: transform .25s, box-shadow .25s;
        }
        .feat-card:hover { transform: translateY(-4px); box-shadow: 0 16px 40px -12px rgba(79,70,229,.12); }
        .feat-icon-box { width: 52px; height: 52px; border-radius: 14px; display: flex; align-items: center; justify-content: center; margin-bottom: 16px; }

        /* ── PRODUCT CARD ── */
        .prod-card {
            background: #fff; border: 1px solid #e9eef4; border-radius: 16px;
            padding: 20px; transition: transform .25s, box-shadow .25s;
            display: flex; flex-direction: column; gap: 12px;
        }
        .prod-card:hover { transform: translateY(-3px); box-shadow: 0 10px 28px -8px rgba(79,70,229,.11); }
        .prod-card-top { display: flex; align-items: center; justify-content: space-between; }
        .prod-icon { width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; }
        .prod-arrow {
            width: 30px; height: 30px; border-radius: 50%; background: #f1f5f9;
            display: flex; align-items: center; justify-content: center; color: #94a3b8;
            transition: background .2s, color .2s;
        }
        .prod-card:hover .prod-arrow { background: var(--brand-primary); color: #fff; }

        /* ── STEP CARD (How It Works) ── */
        .step-card {
            background: #fff; border: 1.5px solid #e9eef4; border-radius: 20px;
            padding: 32px 20px 28px; text-align: center;
            display: flex; flex-direction: column; align-items: center; gap: 14px;
            position: relative; z-index: 1;
        }
        .step-num {
            width: 38px; height: 38px; border-radius: 50%; color: #fff;
            font-weight: 900; font-size: 13px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 14px -4px rgba(79,70,229,.4);
        }
        .step-icon-wrap { width: 64px; height: 64px; border-radius: 18px; background: #f4f3ff; display: flex; align-items: center; justify-content: center; }

        /* ── REVIEW CARD ── */
        .review-card {
            background: #fff; border: 1px solid #e9eef4; border-radius: 18px;
            padding: 22px;
            transition: transform .25s, box-shadow .25s;
        }
        .review-card:hover { transform: translateY(-3px); box-shadow: 0 12px 32px -8px rgba(0,0,0,.08); }
        .rev-avatar {
            width: 42px; height: 42px; border-radius: 50%;
            object-fit: cover; border: 2px solid #e2e8f0; flex-shrink: 0;
        }
        .rev-initials {
            width: 42px; height: 42px; border-radius: 50%; flex-shrink: 0;
            background: linear-gradient(135deg, var(--brand-primary) 0%, var(--brand-secondary) 100%);
            color: #fff; font-weight: 900; font-size: 12px;
            display: flex; align-items: center; justify-content: center;
        }

        /* ── CTA BANNER ── */
        .cta-band {
            border-radius: 22px;
            background: linear-gradient(120deg, #1a237e 0%, #283593 35%, #4527a0 100%);
            position: relative; overflow: hidden;
        }
        .cta-band::before {
            content: ''; position: absolute; inset: 0;
            background: radial-gradient(circle at 25% 60%, rgba(99,102,241,.28) 0%, transparent 65%);
        }

        /* ── FEATURES/ABOUT SECTION ── */
        .about-section { background: linear-gradient(135deg, #f5f4ff 0%, #eef2ff 100%); }
        .stat-divider { width: 1px; background: #d4d4d8; height: 48px; flex-shrink: 0; }

        /* ── FOOTER ── */
        .site-footer { background: #0f172a; color: #94a3b8; }
        .footer-social-btn {
            width: 32px; height: 32px; border-radius: 50%; background: #1e293b;
            display: inline-flex; align-items: center; justify-content: center;
            color: #64748b; transition: background .2s, color .2s;
        }
        .footer-social-btn:hover { background: var(--brand-primary); color: #fff; }
        .newsletter-wrap { display: flex; gap: 8px; }
        .newsletter-input {
            flex: 1; background: #1e293b; border: 1px solid #334155;
            color: #e2e8f0; padding: 10px 14px; border-radius: 10px;
            font-size: 12px; outline: none; font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .newsletter-input::placeholder { color: #64748b; }
        .newsletter-send {
            width: 40px; height: 40px; border-radius: 10px; border: none; cursor: pointer;
            flex-shrink: 0; display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, var(--brand-primary) 0%, var(--brand-secondary) 100%);
        }

        /* ── MOBILE MENU ── */
        #mobile-menu { display: none; }
        #mobile-menu.open { display: block; }

        @media(max-width: 640px) {
            .cat-pill { min-width: 62px; padding: 8px 10px; font-size: 10px; }
            .cat-icon { font-size: 18px; }
            .header-inner { padding: 0 16px; }
        }

        /* ── PRICING CARDS & RESPONSIVENESS ── */
        .pricing-cards-outer {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 28px;
            width: 100%;
        }
        .pricing-card-item {
            border-radius: 24px;
            overflow: hidden;
            border: 1.5px solid #e2e8f0;
            box-shadow: 0 12px 40px rgba(0,0,0,.06);
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: #fff;
            transition: transform .3s, box-shadow .3s;
            width: 100%;
        }
        .pricing-left-panel {
            padding: 36px 28px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border-right: 1px solid rgba(226,232,240,0.8);
            position: relative;
        }
        .pricing-right-panel {
            background: #fff;
            padding: 32px 24px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }
        .trust-bar-grid {
            margin-top: 44px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            width: 100%;
        }

                /* ── DARK MODE OVERRIDES (DEEP GRADIENT & ANIMATION) ── */
        html.dark body { 
            background: transparent !important; 
            color: #ffffff !important; 
        }
        /* Animated Background Gradient */
        html.dark body::before {
            content: "";
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            background: radial-gradient(circle at top right, #1e1b4b 0%, #020617 60%, #000000 100%) !important;
            z-index: -2;
        }

        html.dark .site-header { background: rgba(2, 6, 23, 0.7) !important; backdrop-filter: blur(12px); border-bottom: 1px solid rgba(255,255,255,0.05) !important; }
        
        /* Glassmorphism for Cards */
        html.dark .feat-card, html.dark .prod-card, html.dark .step-card, html.dark .review-card, html.dark .pricing-card-item, html.dark .pricing-right-panel, html.dark .cat-pill { 
            background: rgba(15, 23, 42, 0.5) !important; 
            backdrop-filter: blur(10px);
            border-color: rgba(255,255,255,0.08) !important; 
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1) !important;
        }
        html.dark .pricing-left-panel { border-color: rgba(255,255,255,0.08) !important; background: rgba(15, 23, 42, 0.3) !important; }
        
        /* Transparent sections to show animated background */
        html.dark section, html.dark .about-section, html.dark div[id="mobile-menu"], html.dark div[style*="background:#f8fafc"] { 
            background: transparent !important; 
        }
        
        /* Text Color Fixes */
        html.dark h1, html.dark h2, html.dark h3, html.dark h4, html.dark h5, html.dark p, html.dark a:not(.btn-brand), html.dark li { 
            color: #ffffff !important; 
        }
        html.dark [style*="color:#0f172a"], html.dark [style*="color:#1e293b"], html.dark [style*="color:#334155"] {
            color: #ffffff !important;
        }
        html.dark p, html.dark .text-slate-600, html.dark .text-slate-500, html.dark [style*="color:#64748b"], html.dark [style*="color:#475569"] {
            color: #94a3b8 !important;
        }
        html.dark .cat-pill:hover { background: rgba(30, 41, 59, 0.8) !important; border-color: rgba(255,255,255,0.15) !important; color: #fff !important; }
        html.dark .site-footer { background: rgba(2, 6, 23, 0.9) !important; border-top: 1px solid rgba(255,255,255,0.05); }
        html.dark .btn-outline { background: rgba(15, 23, 42, 0.6) !important; border-color: rgba(255,255,255,0.1) !important; color: #fff !important; }
        html.dark .btn-outline:hover { background: rgba(30, 41, 59, 0.8) !important; }
        html.dark .badge-top-left, html.dark .badge-top-right, html.dark .badge-mid-left, html.dark .badge-bot-left {
            background: rgba(15, 23, 42, 0.8) !important; border-color: rgba(255,255,255,0.1) !important; backdrop-filter: blur(8px);
        }
        html.dark .badge-top-left span[style*="color:#0f172a"], html.dark .badge-top-right span[style*="color:#0f172a"], html.dark .badge-bot-left span[style*="color:#0f172a"] {
            color: #fff !important;
        }
        html.dark div[style*="background:#fff"] { background: transparent !important; border-color: rgba(255,255,255,0.08) !important; }
        html.dark section[style*="background:#fff"], html.dark section[style*="background:#f5f4ff"], html.dark section[style*="background:#f0efff"] { 
            background: transparent !important; border-color: rgba(255,255,255,0.05) !important; 
        }
        /* Removed .text-brand color override to allow gradient in dark mode */
        html.dark button[title="Toggle Dark Mode"] { background: transparent !important; border-color: rgba(255,255,255,0.2) !important; color: #fff !important; }
        html.dark button[title="Toggle Dark Mode"]:hover { border-color: #fff !important; }
        
        /* FAQ Card Background Override Fixes */
        html.dark .faq-item { background: rgba(15, 23, 42, 0.5) !important; border-color: rgba(255,255,255,0.08) !important; }
        html.dark .faq-item h3 { color: #fff !important; }
        
        /* Background Animation Dots */
        @keyframes floatUpDots {
            0% { transform: translateY(100vh) translateX(0); opacity: 0; }
            10% { opacity: 0.8; }
            90% { opacity: 0.8; }
            100% { transform: translateY(-10vh) translateX(30px); opacity: 0; }
        }
        .dark-mode-stars {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            z-index: -1; pointer-events: none; overflow: hidden;
        }
        html.dark .dark-mode-stars { display: block; }
        .star-dot {
            position: absolute;
            background: #ffffff;
            border-radius: 50%;
            opacity: 0.5;
            animation: floatUpDots linear infinite;
            box-shadow: 0 0 4px #fff;
        }
        /* ── RESPONSIVE GRIDS ── */
        @media(max-width: 1024px) {
            .feat-grid { grid-template-columns: repeat(2,1fr) !important; }
            .products-grid { grid-template-columns: 1fr !important; }
            .products-grid > div:first-child { position: static !important; }
            .pricing-cards-outer { grid-template-columns: 1fr !important; gap: 24px !important; }
        }
        @media(max-width: 860px) {
            .hero-grid, .about-grid { grid-template-columns: 1fr !important; }
            .rev-cards-3 .review-card { flex: 0 0 calc((100% - 16px) / 2) !important; min-width: 240px !important; max-width: calc((100% - 16px) / 2) !important; }
            .reviews-grid { grid-template-columns: 1fr !important; }
            .steps-row { flex-direction: column !important; gap: 32px !important; }
            .hiw-connector, .hiw-arrow-end { display: none !important; }
            .pricing-card-item { grid-template-columns: 1fr !important; }
            .pricing-left-panel {
                border-right: none !important;
                border-bottom: 1px solid rgba(226,232,240,0.8) !important;
                padding: 24px 20px !important;
            }
            .pricing-right-panel { padding: 24px 20px !important; }
            .trust-bar-grid { grid-template-columns: 1fr !important; margin-top: 24px !important; }
        }
        @media(max-width: 640px) {
            .feat-grid { grid-template-columns: 1fr !important; }
            .desktop-ctas > a:first-of-type { display: none; }
        }
        @media(min-width: 860px) {
            .lg-nav { display: flex !important; align-items: center; gap: 24px; }
            .mobile-ham { display: none !important; }
            .desktop-ctas { display: flex !important; }
        }
        @media(max-width: 859px) {
            .lg-nav { display: none !important; }
            .mobile-ham { display: block !important; }
        }

        html { scroll-behavior: smooth; }
        .footer-social-btn svg { width:16px; height:16px; stroke:#ffffff !important; color:#ffffff !important; opacity: 1 !important; visibility: visible !important; }
</style>
<style>
        .footer-social-btn {
            display:flex; align-items:center; justify-content:center;
            width:32px; height:32px; border-radius:50%;
            background: rgba(255,255,255,0.05); color: #fff;
            transition: all 0.2s ease;
        }
        .footer-social-btn:hover {
            background: var(--brand-primary); color: #fff;
        }
        html.dark .products-container-card { 
            background: rgba(15, 23, 42, 0.5) !important; 
            backdrop-filter: blur(10px) !important;
            border-color: rgba(255,255,255,0.08) !important; 
        }
        @keyframes pulseCircle { 
            0% { transform: translate(-50%, -50%) scale(0.95); opacity: 0.5; } 
            50% { transform: translate(-50%, -50%) scale(1.05); opacity: 1; } 
            100% { transform: translate(-50%, -50%) scale(0.95); opacity: 0.5; } 
        }
        .footer-social-btn svg { width:16px; height:16px; stroke:#ffffff !important; color:#ffffff !important; opacity: 1 !important; visibility: visible !important; }
</style>
</head>
<body>

<!-- Floating Background Animation -->
<div class="dark-mode-stars">
    <div class="star-dot" style="left: 10%; width: 3px; height: 3px; animation-duration: 15s; animation-delay: 0s;"></div>
    <div class="star-dot" style="left: 25%; width: 2px; height: 2px; animation-duration: 22s; animation-delay: 2s;"></div>
    <div class="star-dot" style="left: 40%; width: 4px; height: 4px; animation-duration: 18s; animation-delay: 5s;"></div>
    <div class="star-dot" style="left: 60%; width: 2px; height: 2px; animation-duration: 25s; animation-delay: 1s;"></div>
    <div class="star-dot" style="left: 80%; width: 3px; height: 3px; animation-duration: 17s; animation-delay: 3s;"></div>
    <div class="star-dot" style="left: 90%; width: 2px; height: 2px; animation-duration: 28s; animation-delay: 6s;"></div>
    <div class="star-dot" style="left: 35%; width: 1.5px; height: 1.5px; animation-duration: 19s; animation-delay: 8s;"></div>
    <div class="star-dot" style="left: 75%; width: 3.5px; height: 3.5px; animation-duration: 24s; animation-delay: 4s;"></div>
    <div class="star-dot" style="left: 50%; width: 2.5px; height: 2.5px; animation-duration: 16s; animation-delay: 7s;"></div>
    <div class="star-dot" style="left: 15%; width: 1px; height: 1px; animation-duration: 21s; animation-delay: 9s;"></div>
    <div class="star-dot" style="left: 5%; width: 2px; height: 2px; animation-duration: 14s; animation-delay: 11s;"></div>
    <div class="star-dot" style="left: 85%; width: 3px; height: 3px; animation-duration: 26s; animation-delay: 12s;"></div>
</div>

{{-- ══ WHY CHOOSE — 4 Feature Cards ═════════════════════════ --}}
<section id="features" style="background: linear-gradient(180deg, #f5f4ff 0%, #faf8ff 100%); padding: 84px 0; border-bottom: 1px solid #f1f5f9;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 24px;">
        <div style="text-align: center; margin-bottom: 56px;">
            <div style="display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(135deg, rgba(124,58,237,0.12), rgba(249,115,22,0.12)); color: #7c3aed; border: 1.5px solid rgba(124,58,237,0.25); font-size: 13px; font-weight: 900; padding: 8px 24px; border-radius: 999px; margin-bottom: 16px; letter-spacing: .06em; text-transform: uppercase;">
                <i data-lucide="award" style="width: 16px; height: 16px; color: #7c3aed;"></i>
                <span>WHY CHOOSE US</span>
            </div>
            <h2 style="font-family: 'Outfit', sans-serif; font-size: clamp(2.2rem, 4vw, 3.1rem); font-weight: 900; margin-bottom: 14px; line-height: 1.2; letter-spacing: -0.6px; color: #0f172a;">
                Why Choose <span style="background: linear-gradient(135deg, #7c3aed 0%, #ea580c 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">{{ $agency->name }}</span>?
            </h2>
            <p style="font-size: 16.5px; color: #475569; max-width: 640px; margin: 0 auto; line-height: 1.8; font-weight: 500;">
                {{ $agency->why_choose_subtitle ?? 'Everything you need to run, grow and scale your business — without juggling multiple tools.' }}
            </p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(4,1fr); gap: 24px;" class="feat-grid">
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

{{-- ══ FLAGSHIP PRODUCTS SHOWCASE (Converted from Pricing Plans Section) ══ --}}
<section id="products" class="products-section" style="padding:76px 0 84px; background:#fafbff; border-top:1px solid #f1f5f9; position:relative; overflow:hidden;">
    <div id="pricing"></div>
    <div style="max-width:1200px; margin:0 auto; padding:0 24px;">

        {{-- Section Header --}}
        <div style="text-align:center; margin-bottom:52px; display:flex; flex-direction:column; align-items:center; gap:12px; position:relative;">
            
            {{-- Badge --}}
            <span style="background:#ede9fe; color:#6d28d9; border:1px solid #ddd6fe; font-size:12px; font-weight:800; padding:6px 20px; border-radius:999px; width:fit-content; letter-spacing:.04em; display:inline-flex; align-items:center; gap:7px;">
                <i data-lucide="layers" style="width:14px;height:14px"></i>
                <span>{{ !empty($agency->products_section_badge) ? $agency->products_section_badge : ($pricingBadgeText ?? 'Our Products') }}</span>
            </span>

            {{-- Main Title --}}
            <h2 style="font-family:'Outfit',sans-serif; font-size:clamp(2rem,3.8vw,2.8rem); font-weight:900; color:#0f172a; letter-spacing:-.6px; margin:0; line-height:1.2;">
                {{ !empty($agency->products_section_title) ? $agency->products_section_title : ($pricingSectionTitle ?? 'Powerful Solutions Built For Your Business') }}
            </h2>

            {{-- Subtitle --}}
            <p style="font-size:15px; color:#64748b; max-width:620px; line-height:1.75; margin:0; font-weight:500;">
                {{ !empty($agency->products_section_subtitle) ? $agency->products_section_subtitle : ($pricingSectionSubtitle ?? 'Explore our enterprise-grade tools designed to automate, streamline, and grow your operations.') }}
            </p>
        </div>

        {{-- Product Showcase Cards Grid (NO prices, NO monthly/yearly toggle) --}}
        <div class="pricing-cards-outer">
            @foreach($pricingPlans as $plan)
            @php
                $planColor    = $plan['color'] ?? '#ea580c';
                $leftBg       = $plan['left_bg'] ?? ($loop->first ? '#fff5ee' : '#f0f6ff');
                $isPopular    = !empty($plan['is_popular']);
                $planFeatures = $plan['features'] ?? [];
                
                $pSlugLower   = strtolower($plan['product_slug'] ?? \Illuminate\Support\Str::slug($plan['product_name'] ?? ''));
                $isEcom       = $loop->first || str_contains($pSlugLower, 'launch') || str_contains($pSlugLower, 'ecom') || str_contains(strtolower($plan['product_name'] ?? ''), 'ecom');

                // Image path fallbacks
                $rawLogo   = !empty($plan['product_logo'])  ? $plan['product_logo']  : ($isEcom ? 'assets/landing_page/ecom_logo.png' : 'assets/landing_page/websitebuilder_logo.png');
                $rawMockup = !empty($plan['product_image']) ? $plan['product_image'] : ($isEcom ? 'assets/landing_page/ecombuilder_image.png' : 'assets/landing_page/websitebuilder_image.png');

                $logoImgPath   = str_starts_with($rawLogo, 'http')   ? $rawLogo   : asset(ltrim($rawLogo, '/'));
                $mockupImgPath = str_starts_with($rawMockup, 'http') ? $rawMockup : asset(ltrim($rawMockup, '/'));

                // Dynamic Product Landing Page Link Resolution
                if ($isEcom) {
                    $prodLink = isset($agency) ? $agency->getProductSubdomainUrl('launchshop') : 'https://ecom.youverse.in';
                } elseif (str_contains($pSlugLower, 'website')) {
                    $prodLink = isset($agency) ? $agency->getProductSubdomainUrl('websitebuilder') : 'https://websitebuilder.youverse.in';
                } else {
                    $prodLink = !empty($plan['cta_url']) && $plan['cta_url'] !== '/login' ? $plan['cta_url'] : (isset($agency) ? $agency->getProductSubdomainUrl($pSlugLower) : 'https://wa.me/916374913298?text=I%20am%20interested%20in%20' . urlencode($plan['product_name'] ?? 'Product'));
                }

                // Vibrant CTA Button Gradient
                $btnGradient = $isEcom 
                    ? 'linear-gradient(135deg, #f97316 0%, #ea580c 100%)' 
                    : 'linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%)';
                if (!empty($plan['gradient']) && !str_contains($plan['gradient'], '#fff') && !str_contains($plan['gradient'], '#ffedd5') && !str_contains($plan['gradient'], '#f0f6ff') && !str_contains($plan['gradient'], '#e0e7ff')) {
                    $btnGradient = $plan['gradient'];
                }
            @endphp

            <div class="pricing-card-item" style="border-color: {{ $isPopular ? '#3b82f6' : '#e2e8f0' }};"
                 onmouseover="this.style.transform='translateY(-6px)';this.style.boxShadow='0 20px 50px rgba(0,0,0,.12)'"
                 onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 12px 40px rgba(0,0,0,.06)'">

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

                        {{-- Tagline Bullets --}}
                        @if(!empty($plan['product_tagline']))
                        <div style="font-size:12px; font-weight:800; color:{{ $planColor }}; text-transform:uppercase; letter-spacing:.05em; margin-bottom:8px;">
                            {{ $plan['product_tagline'] }}
                        </div>
                        @endif

                        {{-- Product Title --}}
                        <h4 style="font-family:'Outfit',sans-serif; font-size:19px; font-weight:900; color:#0f172a; margin:0 0 6px; line-height:1.25;">
                            {{ $plan['product_title'] ?? ($plan['product_name'] . ' Solution') }}
                        </h4>

                        {{-- Subtitle Paragraph --}}
                        @if(!empty($plan['product_subtitle']))
                        <p style="font-size:13px; color:#64748b; line-height:1.6; margin:0;">
                            {{ $plan['product_subtitle'] }}
                        </p>
                        @endif

                        {{-- Center Product Image Mockup --}}
                        @if(!empty($mockupImgPath))
                        <div style="margin-top:20px; text-align:center;">
                            <img src="{{ $mockupImgPath }}" alt="{{ $plan['product_name'] ?? '' }}"
                                 style="width:100%; max-height:210px; object-fit:contain; border-radius:14px; filter: drop-shadow(0 12px 24px rgba(0,0,0,0.12));">
                        </div>
                        @endif
                    </div>
                </div>

                {{-- RIGHT PANEL: Solution Details & Features (NO PRICES) --}}
                <div class="pricing-right-panel" style="display:flex; flex-direction:column; justify-content:space-between;">
                    <div>
                        {{-- Badges Row --}}
                        <div style="display:flex; align-items:center; justify-content:space-between; gap:8px; margin-bottom:12px;">
                            <span style="background:{{ $plan['plan_badge_bg'] ?? ($planColor . '1a') }}; color:{{ $plan['plan_badge_color'] ?? $planColor }}; font-size:10.5px; font-weight:900; padding:4px 12px; border-radius:6px; text-transform:uppercase; letter-spacing:.06em;">
                                {{ $plan['plan_badge'] ?? 'ALL-IN-ONE SOLUTION' }}
                            </span>
                            @if(!empty($plan['plan_subbadge']))
                            <span style="font-size:11.5px; font-weight:800; color:{{ $isPopular ? '#4f46e5' : '#64748b' }};">
                                {{ $plan['plan_subbadge'] }}
                            </span>
                            @endif
                        </div>

                        {{-- Product Plan Name / Title --}}
                        <h3 style="font-family:'Outfit',sans-serif; font-size:22px; font-weight:900; color:#0f172a; margin:0 0 6px;">
                            {{ $plan['plan_name'] ?? $plan['product_name'] }}
                        </h3>

                        {{-- Description --}}
                        @if(!empty($plan['plan_subtitle']))
                        <p style="font-size:13.5px; color:#64748b; margin:0 0 16px; line-height:1.55;">
                            {{ $plan['plan_subtitle'] }}
                        </p>
                        @endif

                        {{-- Included Features Header --}}
                        <div style="font-size:11px; font-weight:900; color:#94a3b8; letter-spacing:.08em; text-transform:uppercase; margin-bottom:12px; border-top:1px solid #f1f5f9; padding-top:16px; display:flex; align-items:center; gap:6px;">
                            <i data-lucide="check-circle-2" style="width:14px;height:14px;color:#10b981"></i>
                            <span>KEY FEATURES &amp; CAPABILITIES:</span>
                        </div>

                        {{-- Features List --}}
                        <ul style="list-style:none; display:flex; flex-direction:column; gap:10px; margin:0 0 24px; padding:0;">
                            @foreach($planFeatures as $feature)
                            <li style="display:flex; align-items:flex-start; gap:10px; font-size:13.5px; color:#334155; font-weight:600; line-height:1.45;">
                                <span style="width:18px; height:18px; border-radius:50%; background:{{ $planColor }}; color:#fff; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:1px; font-size:10px; font-weight:900;">
                                    ✓
                                </span>
                                <span>{{ $feature }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- CTA Button (Explore Product) --}}
                    <div>
                        <a href="{{ $prodLink }}" target="_blank"
                           style="display:flex; align-items:center; justify-content:center; gap:8px; background:{{ $btnGradient }}; color:#ffffff !important; font-weight:800; font-size:14px; padding:15px 24px; border-radius:12px; text-decoration:none; box-shadow:0 6px 18px -4px {{ $planColor }}55; transition:transform .2s; border:none;"
                           onmouseover="this.style.transform='scale(1.02)'"
                           onmouseout="this.style.transform='scale(1)'">
                            <span style="color:#ffffff !important; font-weight:800; font-size:14px;">{{ !empty($plan['cta_text']) && $plan['cta_text'] !== 'Get Started Free' && $plan['cta_text'] !== 'Start 14-Day Free Trial' ? $plan['cta_text'] : 'Explore Product &rarr;' }}</span>
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

        {{-- 3-Column Trust Bar --}}
        <div class="trust-bar-grid">
            @foreach($trustElements as $te)
            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:16px 20px; display:flex; align-items:center; gap:14px; box-shadow:0 2px 8px rgba(0,0,0,0.03);">
                <div style="width:42px; height:42px; border-radius:50%; background:{{ $te['bg'] ?? '#d1fae5' }}; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <i data-lucide="{{ $te['icon'] ?? 'shield-check' }}" style="width:22px; height:22px; color:{{ $te['color'] ?? '#059669' }};"></i>
                </div>
                <div>
                    <h5 style="font-size:13px; font-weight:800; color:#0f172a; margin:0;">{{ $te['title'] ?? '' }}</h5>
                    <p style="font-size:11px; color:#64748b; margin:2px 0 0;">{{ $te['desc'] ?? '' }}</p>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>

{{-- ══ HOW IT WORKS — 3 Steps (Pixel-Perfect 3rd Reference) ══════════════════════ --}}
<section id="how-it-works" style="background:#f0efff; padding:80px 0; overflow:visible;">
    <div style="max-width:1100px; margin:0 auto; padding:0 24px">
        <div style="text-align:center; margin-bottom:60px">
            <h2 style="font-size:clamp(1.75rem,3.2vw,2.4rem); font-weight:900; color:#0f172a; margin-bottom:12px; letter-spacing:-0.5px;">{{ $agency->how_it_works_title ?? 'How It Works?' }}</h2>
            <p style="font-size:14px; color:#64748b; font-weight:500;">{{ $agency->how_it_works_subtitle ?? 'Get started in 3 simple steps and transform your business today.' }}</p>
        </div>

        {{-- Steps row with connector & end arrow --}}
        <div style="display:flex; align-items:stretch; gap:0; position:relative;" class="steps-row">
            @php
                $stepColors = [
                    0 => ['grad' => 'linear-gradient(135deg,#7c3aed,#6d28d9)', 'bg' => '#ede9fe', 'clr' => '#7c3aed', 'shadow' => 'rgba(124,58,237,.4)'],
                    1 => ['grad' => 'linear-gradient(135deg,#2563eb,#1d4ed8)', 'bg' => '#dbeafe', 'clr' => '#2563eb', 'shadow' => 'rgba(37,99,235,.4)'],
                    2 => ['grad' => 'linear-gradient(135deg,#10b981,#059669)', 'bg' => '#d1fae5', 'clr' => '#059669', 'shadow' => 'rgba(16,185,129,.4)'],
                ];
            @endphp
            @foreach($hiwSteps as $hIdx => $hStep)
            @php
                $sc = $stepColors[$hIdx % 3];
            @endphp
            <div class="step-card-2" style="flex:1; background:#fff; border:1px solid rgba(220,224,238,0.6); border-radius:22px; padding:44px 28px 38px; text-align:center; display:flex; flex-direction:column; align-items:center; gap:18px; position:relative; z-index:1; box-shadow:0 8px 32px rgba(79,70,229,0.05); transition:transform .25s, box-shadow .25s;" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 16px 40px rgba(79,70,229,.1)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 8px 32px rgba(79,70,229,.05)'">
                <div style="position:absolute; top:-17px; left:20px; width:38px; height:38px; border-radius:50%; background:{{ $sc['grad'] }}; color:#fff; font-weight:900; font-size:13px; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 14px {{ $sc['shadow'] }}; border:2px solid #fff; letter-spacing:.5px;">{{ $hStep['num'] ?? sprintf('%02d', $hIdx + 1) }}</div>
                <div style="width:64px; height:64px; border-radius:50%; background:{{ $sc['bg'] }}; display:flex; align-items:center; justify-content:center; margin-top:8px;">
                    <i data-lucide="{{ $hStep['icon'] ?? 'user-plus' }}" style="width:28px; height:28px; color:{{ $sc['clr'] }}"></i>
                </div>
                <h3 style="font-size:16px; font-weight:900; color:#0f172a; margin:0;">{{ $hStep['title'] }}</h3>
                <p style="font-size:13px; color:#64748b; line-height:1.7; margin:0">{{ $hStep['desc'] }}</p>
            </div>
            @if(!$loop->last)
            <div class="hiw-connector" style="width:80px; flex-shrink:0; display:flex; align-items:center; justify-content:center; position:relative; z-index:0;">
                <svg width="80" height="20" viewBox="0 0 80 20" fill="none">
                    <path d="M 4 10 Q 20 3 40 10 T 76 10" stroke="#a5b4fc" stroke-width="2" stroke-dasharray="5 4" fill="none" stroke-linecap="round"/>
                    <path d="M 72 7 L 76 10 L 72 13" stroke="#a5b4fc" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            @endif
            @endforeach

            {{-- End dark arrow circle --}}
            <div class="hiw-arrow-end" style="width:64px; flex-shrink:0; display:flex; align-items:center; justify-content:center;">
                <div style="width:44px; height:44px; border-radius:50%; background:#1e1b4b; display:flex; align-items:center; justify-content:center; box-shadow:0 6px 20px rgba(30,27,75,.3);">
                    <i data-lucide="arrow-up-right" style="width:20px;height:20px;color:#fff"></i>
                </div>
            </div>

        </div>
    </div>
</section>


{{-- ══ TESTIMONIALS — Pixel-Perfect 2nd Reference Match ════════════ --}}
<section id="testimonials" style="padding:56px 0; background:#f0efff;">
    <div style="max-width:1160px; margin:0 auto; padding:0 24px;">
        <div class="reviews-container-card" style="background:#fff; border-radius:24px; padding:44px 40px; box-shadow:0 6px 32px rgba(79,70,229,0.07); border:1px solid rgba(220,220,255,0.4);">
            <div style="display:grid; grid-template-columns:240px 1fr; gap:40px; align-items:start" class="reviews-grid">

                {{-- Left heading + arrows --}}
                <div style="display:flex; flex-direction:column; gap:18px;">
                    <h2 style="font-size:clamp(1.6rem,2.6vw,2.1rem); font-weight:900; color:#0f172a; line-height:1.18; margin:0">
                        Loved by<br>Business Owners
                    </h2>
                    <p style="font-size:13px; color:#64748b; line-height:1.75; margin:0">See what our customers say about their growth with {{ $agency->name }}.</p>
                    <div style="display:flex; gap:10px; margin-top:4px">
                        <button onclick="prevRev()" style="width:38px; height:38px; border-radius:50%; border:2px solid #e2e8f0; background:#fff; cursor:pointer; display:flex; align-items:center; justify-content:center; color:#64748b; transition:all .2s; flex-shrink:0;" onmouseover="this.style.borderColor='#6d28d9';this.style.color='#6d28d9'" onmouseout="this.style.borderColor='#e2e8f0';this.style.color='#64748b'">
                            <i data-lucide="chevron-left" style="width:18px; height:18px"></i>
                        </button>
                        <button onclick="nextRev()" style="width:38px; height:38px; border-radius:50%; border:none; background:linear-gradient(135deg,#7c3aed,#6d28d9); cursor:pointer; display:flex; align-items:center; justify-content:center; color:#fff; box-shadow:0 4px 14px rgba(109,40,217,.35); flex-shrink:0; transition:transform .2s" onmouseover="this.style.transform='scale(1.07)'" onmouseout="this.style.transform='scale(1)'">
                            <i data-lucide="chevron-right" style="width:18px; height:18px"></i>
                        </button>
                    </div>
                </div>

                {{-- Right: horizontal review cards carousel --}}
                <div style="display:flex; flex-wrap:nowrap; gap:16px; overflow-x:auto; scroll-behavior:smooth; scrollbar-width:none; -webkit-overflow-scrolling:touch; padding-bottom:8px;" id="rev-grid" class="rev-cards-3">
                    @foreach($testimonials as $t)
                        @php
                            $initials = strtoupper(substr(trim($t['name'] ?? 'U'), 0, 1));
                            $parts = explode(' ', trim($t['name'] ?? 'U'));
                            $initials = strtoupper(substr($parts[0],0,1) . (isset($parts[1]) ? substr($parts[1],0,1) : ''));
                        @endphp
                        <div class="review-card" style="flex:0 0 calc((100% - 32px)/3); min-width:280px; max-width:calc((100% - 32px)/3); box-sizing:border-box; background:#fff; border:1px solid #f1f5f9; border-radius:18px; padding:22px 20px; box-shadow:0 4px 20px rgba(0,0,0,0.04); transition:transform .25s, box-shadow .25s; display:flex; flex-direction:column; gap:0;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 10px 30px rgba(79,70,229,.09)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 20px rgba(0,0,0,0.04)'">
                            {{-- Reviewer top: avatar + name/role --}}
                            <div style="display:flex; align-items:center; gap:12px; margin-bottom:10px">
                                @if(!empty($t['avatar']))
                                    <img src="{{ asset($t['avatar']) }}" alt="{{ $t['name'] }}"
                                         style="width:46px; height:46px; border-radius:50%; object-fit:cover; flex-shrink:0; border:2px solid #f1f5f9;">
                                @else
                                    <div style="width:46px; height:46px; border-radius:50%; flex-shrink:0; background:linear-gradient(135deg,#7c3aed 0%,#6d28d9 100%); color:#fff; font-weight:900; font-size:14px; display:flex; align-items:center; justify-content:center; letter-spacing:.5px;">{{ $initials }}</div>
                                @endif
                                <div style="min-width:0;">
                                    <div style="font-size:13.5px; font-weight:800; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $t['name'] }}</div>
                                    <div style="font-size:11px; color:#64748b; font-weight:500; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $t['role'] ?? $t['designation'] ?? '' }}</div>
                                </div>
                            </div>
                            {{-- Stars --}}
                            <div style="display:flex; gap:2px; margin-bottom:10px">
                                @for($i = 0; $i < ($t['rating'] ?? 5); $i++)
                                    <span style="color:#f59e0b; font-size:14px; line-height:1">★</span>
                                @endfor
                            </div>
                            {{-- Quote --}}
                            <p style="font-size:12.5px; color:#475569; line-height:1.75; margin:0;">"{{ $t['comment'] }}"</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══ FAQ ════════════════════════════════════════════════ --}}
<section id="faq" style="background:#fff;padding:64px 0;border-top:1px solid #f1f5f9">
    <div style="max-width:760px;margin:0 auto;padding:0 24px">
        <div style="text-align:center;margin-bottom:40px">
            <h2 style="font-size:clamp(1.4rem,2.5vw,2rem);font-weight:800;color:#0f172a;margin-bottom:8px">Frequently Asked Questions</h2>
            <p style="font-size:14px;color:#64748b">Have questions? We are here to help.</p>
        </div>
        <div style="display:flex;flex-direction:column;gap:12px">
            @foreach($faqs as $item)
                <div style="background:#f8fafc;border:1px solid #e9eef4;border-radius:16px;padding:20px 22px">
                    <h4 style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:8px">{{ $item['q'] }}</h4>
                    <p style="font-size:13px;color:#64748b;line-height:1.75">{{ $item['a'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ CTA BANNER — Pixel-Perfect 2nd Reference Match ══════════════════════════════════════════ --}}
<section id="cta" style="background:#f8fafc; padding:48px 0 56px">
    <div style="max-width:1160px; margin:0 auto; padding:0 24px">
        <div class="cta-band" style="position:relative; border-radius:22px; overflow:hidden; background:linear-gradient(120deg,#3730a3 0%,#4f46e5 45%,#6d28d9 100%); min-height:160px; display:flex; align-items:center; justify-content:space-between; gap:20px; padding:40px 48px; box-shadow:0 16px 48px rgba(79,70,229,.22);">

            {{-- Decorative circles --}}
            <div style="position:absolute;inset:0;overflow:hidden;pointer-events:none;">
                <div style="position:absolute;top:-60px;left:-60px;width:220px;height:220px;border-radius:50%;background:rgba(255,255,255,.04);"></div>
                <div style="position:absolute;bottom:-40px;right:280px;width:160px;height:160px;border-radius:50%;background:rgba(255,255,255,.05);"></div>
            </div>

            {{-- Left: heading + sub + checkmarks --}}
            <div style="display:flex; flex-direction:column; gap:12px; max-width:380px; position:relative; z-index:2; flex-shrink:0;">
                <h2 style="font-size:clamp(1.2rem,2.2vw,1.65rem); font-weight:900; color:#fff; line-height:1.25; letter-spacing:-.2px; margin:0">
                    {{ $agency->cta_section_title ?? 'Ready to Take Your Business to the Next Level?' }}
                </h2>
                <p style="font-size:13px; color:rgba(199,210,254,.9); line-height:1.6; margin:0">
                    {{ $agency->cta_section_subtitle ?? ('Join thousands of growing businesses with ' . $agency->name . ' today.') }}
                </p>
                <div style="display:flex; flex-wrap:wrap; gap:16px; font-size:12px; font-weight:600; color:rgba(199,210,254,.85); margin-top:2px;">
                    @foreach($ctaCheckmarks as $cc)
                    <span style="display:flex;align-items:center;gap:5px"><i data-lucide="check" style="width:13px;height:13px"></i> {{ $cc }}</span>
                    @endforeach
                </div>
            </div>

            {{-- Center: woman image (cuts into section, overflows bottom) --}}
            <div style="position:relative; z-index:2; flex-shrink:0; align-self:flex-end; margin-bottom:-40px;">
                <img src="{{ $ctaImg }}" alt="Grow with {{ $agency->name }}"
                     style="height:220px; width:auto; object-fit:contain; filter:drop-shadow(0 8px 24px rgba(0,0,0,.28)); display:block;"
                     onerror="this.style.display='none'">
            </div>

            {{-- Right: CTA button --}}
            <div style="position:relative; z-index:2; flex-shrink:0;">
                <a href="{{ !empty($agency->cta_button_url) ? $agency->cta_button_url : (!empty($agency->cta_url) ? $agency->cta_url : 'https://wa.me/916374913298?text=I%20am%20interested%20on%20that%20business') }}"
                   style="display:inline-flex; align-items:center; gap:9px; background:#fff; color:#1e1b4b; font-weight:800; font-size:14px; padding:14px 26px; border-radius:12px; text-decoration:none; box-shadow:0 6px 22px rgba(0,0,0,.18); white-space:nowrap; transition:transform .2s, box-shadow .2s"
                   onmouseover="this.style.transform='scale(1.03)';this.style.boxShadow='0 10px 30px rgba(0,0,0,.22)'"
                   onmouseout="this.style.transform='scale(1)';this.style.boxShadow='0 6px 22px rgba(0,0,0,.18)'">
                    {{ $agency->cta_button_text ?? 'Get Started Free' }}
                    <i data-lucide="arrow-right" style="width:15px; height:15px"></i>
                </a>
            </div>

        </div>
    </div>
</section>

{{-- ══ FOOTER ══════════════════════════════════════════════ --}}
<footer class="site-footer" style="padding:56px 0 32px">
    <div style="max-width:1200px;margin:0 auto;padding:0 24px">

        {{-- 4-column grid: Brand | Products | Legal | Newsletter --}}
        <div style="display:grid;grid-template-columns:200px 1fr 1fr 200px;gap:32px;padding-bottom:40px;border-bottom:1px solid #1e293b" class="footer-grid">

            {{-- Col 1: Brand + Social --}}
            <div style="display:flex;flex-direction:column;gap:14px">
                <div style="display:flex;align-items:center;gap:12px">
                    @if(!empty($agency->logo_dark))
                        <img src="{{ asset(ltrim($agency->logo_dark, '/')) }}" alt="{{ $agency->name }}" class="site-logo-dark" style="height:60px;max-height:64px;width:auto;object-fit:contain">
                        <img src="{{ asset(ltrim($agency->logo, '/')) }}" alt="{{ $agency->name }}" class="site-logo-light" style="height:60px;max-height:64px;width:auto;object-fit:contain">
                    @elseif(!empty($agency->logo))
                        <img src="{{ asset(ltrim($agency->logo, '/')) }}" alt="{{ $agency->name }}" style="height:60px;max-height:64px;width:auto;object-fit:contain">
                    @else
                        <div class="bg-brand" style="width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <i data-lucide="layers" style="width:18px;height:18px;color:#fff"></i>
                        </div>
                        <span style="font-family:'Outfit',sans-serif;font-size:20px;font-weight:900;color:#e2e8f0">{{ $agency->name }}</span>
                    @endif
                </div>
                <p style="font-size:12px;color:#64748b;line-height:1.7;max-width:190px">
                    {{ $agency->footer_content ?? 'Powering the growth of Indian local businesses with smart digital solutions.' }}
                </p>
                <div style="display:flex;gap:8px;margin-top:4px">
                    <a href="{{ $agency->facebook_url ?? '#' }}" class="footer-social-btn"><i data-lucide="facebook" style="width:14px;height:14px"></i></a>
                    <a href="{{ $agency->instagram_url ?? '#' }}" class="footer-social-btn"><i data-lucide="instagram" style="width:14px;height:14px"></i></a>
                    <a href="{{ $agency->youtube_url ?? '#' }}" class="footer-social-btn"><i data-lucide="youtube" style="width:14px;height:14px"></i></a>
                    <a href="{{ $agency->linkedin_url ?? '#' }}" class="footer-social-btn"><i data-lucide="linkedin" style="width:14px;height:14px"></i></a>
                    <a href="{{ $agency->twitter_url ?? '#' }}" class="footer-social-btn"><i data-lucide="twitter" style="width:14px;height:14px"></i></a>
                </div>
            </div>

            {{-- Col 2: Products --}}
            <div style="display:flex;flex-direction:column;gap:14px">
                <h4 style="font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.1em;color:#e2e8f0">Products</h4>
                <ul style="list-style:none;display:flex;flex-direction:column;gap:9px">
                    @foreach($services as $s)
                        <li>
                            <a href="#products" style="display:flex;align-items:center;gap:6px;font-size:12px;color:#64748b;text-decoration:none;transition:color .15s" onmouseover="this.style.color='#c7d2fe'" onmouseout="this.style.color='#64748b'">
                                <i data-lucide="chevron-right" style="width:12px;height:12px;flex-shrink:0"></i>
                                {{ $s['title'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Col 3: Legal Policies --}}
            <div style="display:flex;flex-direction:column;gap:14px">
                <h4 style="font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.1em;color:#e2e8f0">Legal</h4>
                <ul style="list-style:none;display:flex;flex-direction:column;gap:9px">
                    @foreach([
                        ['/about',           'About Us'],
                        ['/contact',         'Contact Us'],
                        ['/privacy-policy',  'Privacy Policy'],
                        ['/terms',           'Terms & Conditions'],
                        ['/shipping-policy', 'Shipping Policy'],
                        ['/refund-policy',   'Refund Policy'],
                    ] as [$href, $label])
                        <li>
                            <a href="{{ $href }}" style="display:flex;align-items:center;gap:6px;font-size:12px;color:#64748b;text-decoration:none;transition:color .15s" onmouseover="this.style.color='#c7d2fe'" onmouseout="this.style.color='#64748b'">
                                <i data-lucide="chevron-right" style="width:12px;height:12px;flex-shrink:0"></i>
                                {{ $label }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Col 4: Newsletter --}}
            <div style="display:flex;flex-direction:column;gap:14px">
                <h4 style="font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.1em;color:#e2e8f0">Subscribe to our newsletter</h4>
                <p style="font-size:12px;color:#64748b">Get updates, tips and offers.</p>
                <div class="newsletter-wrap">
                    <input type="email" class="newsletter-input" placeholder="Enter your email">
                    <button class="newsletter-send" onclick="alert('Thank you for subscribing!')" type="button">
                        <i data-lucide="send" style="width:16px;height:16px;color:#fff"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Bottom bar --}}
        <div style="padding-top:24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <p style="font-size:11px;color:#475569">© {{ date('Y') }} {{ $agency->name }}. All rights reserved.</p>
            <p style="font-size:11px;color:#475569;display:flex;align-items:center;gap:4px">Made with <span style="color:#f43f5e">❤️</span> in India 🇮🇳</p>
        </div>
    </div>
</footer>

<style>
    /* ── Responsive overrides ── */
    @media (max-width: 1280px) {
        .footer-grid  { grid-template-columns: 1fr 1fr 1fr !important; }
    }
    @media (max-width: 1024px) {
        .about-grid { grid-template-columns: 1fr !important; gap: 36px !important; }
        .about-grid > div:first-child { min-height: 400px !important; }
        .products-container-card { padding: 36px 24px !important; }
        .products-grid { grid-template-columns: 1fr !important; gap: 32px !important; }
        .products-grid > div:first-child { position: static !important; }
        .reviews-container-card { padding: 36px 24px !important; }
        .reviews-grid { grid-template-columns: 1fr !important; gap: 28px !important; }
        .reviews-grid > div:first-child { position: static !important; }
        .footer-grid { grid-template-columns: 1fr 1fr 1fr !important; }
        .step-connector-svg { display: none !important; }
        .hiw-connector { display: none !important; }
        .hiw-arrow-end { display: none !important; }
        .steps-row { flex-direction: column !important; gap: 28px !important; }
        .step-card-2 { flex: none !important; }
    }
    @media (max-width: 768px) {
        .hero-grid { grid-template-columns: 1fr !important; text-align: center; }
        .hero-grid > div:first-child { align-items: center !important; }
        .hero-grid > div:last-child { justify-content: center !important; margin-top: 24px; }
        .feat-grid { grid-template-columns: repeat(2, 1fr) !important; gap: 12px !important; }
        .feat-grid > div { padding: 18px 14px !important; }
        .steps-grid { grid-template-columns: 1fr !important; gap: 24px !important; }
        .prod-cards-3x2 { grid-template-columns: repeat(2, 1fr) !important; gap: 12px !important; }
        .prod-cards-3x2 .prod-card { padding: 16px 14px !important; }

        /* Single row slider for review section */
        .rev-cards-3 {
            display: flex !important;
            flex-wrap: nowrap !important;
            grid-template-columns: none !important;
            overflow-x: auto !important;
            scroll-behavior: smooth !important;
            -webkit-overflow-scrolling: touch !important;
            gap: 16px !important;
            padding-bottom: 8px !important;
        }
        .rev-cards-3::-webkit-scrollbar { display: none !important; }
        .rev-cards-3 .review-card {
            flex: 0 0 88% !important;
            min-width: 88% !important;
        }

        /* Ready to take your business CTA container image centered & larger on mobile only */
        .cta-band {
            flex-direction: column !important;
            padding: 36px 20px !important;
            align-items: center !important;
            text-align: center !important;
        }
        .cta-band > div:nth-child(2) {
            max-width: 100% !important;
            align-items: center !important;
            text-align: center !important;
        }
        .cta-band > div:nth-child(2) > div {
            justify-content: center !important;
        }
        .cta-band > div:nth-child(3) {
            align-self: center !important;
            margin: 20px auto 12px auto !important;
            width: 100% !important;
            display: flex !important;
            justify-content: center !important;
        }
        .cta-band img {
            height: 290px !important;
            max-height: 320px !important;
            width: auto !important;
            margin: 0 auto !important;
            align-self: center !important;
            display: block !important;
        }

        .footer-grid { grid-template-columns: 1fr 1fr !important; gap: 24px !important; }
        .lg-nav, .desktop-ctas { display: none !important; }
        .mobile-ham { display: flex !important; }
        .kb-stats-container { justify-content: center !important; }
        .stat-divider { display: none !important; }
    }
    @media (max-width: 520px) {
        .feat-grid { grid-template-columns: repeat(2, 1fr) !important; gap: 10px !important; }
        .feat-grid > div { padding: 16px 10px !important; }
        .prod-cards-3x2 { grid-template-columns: repeat(2, 1fr) !important; gap: 10px !important; }
        .prod-cards-3x2 .prod-card { padding: 14px 10px !important; }
        .footer-grid { grid-template-columns: 1fr !important; }
        .badge-top-left { top: 10px !important; left: -5px !important; transform: scale(0.85); transform-origin: top left; }
        .badge-top-right { top: 70px !important; right: -5px !important; transform: scale(0.85); transform-origin: top right; }
        .badge-mid-left { top: 180px !important; left: -10px !important; transform: scale(0.85); transform-origin: middle left; }
        .badge-bot-left { bottom: 10px !important; left: -5px !important; transform: scale(0.85); transform-origin: bottom left; }
    }
    @media (min-width: 1025px) {
        .lg-nav { display: flex !important; align-items: center; gap: 28px; }
        .desktop-ctas { display: flex !important; }
        .mobile-ham { display: none !important; }
    }
    .footer-social-btn svg { width:16px; height:16px; stroke:#ffffff !important; color:#ffffff !important; opacity: 1 !important; visibility: visible !important; }
</style>

<script>
    lucide.createIcons();

    function toggleMobileMenu() {
        const m = document.getElementById('mobile-menu');
        m.classList.toggle('open');
    }

    function nextRev() {
        const revGrid = document.getElementById('rev-grid');
        if (!revGrid) return;
        const cardWidth = revGrid.children[0] ? (revGrid.children[0].offsetWidth + 16) : 320;
        if (revGrid.scrollLeft + revGrid.clientWidth >= revGrid.scrollWidth - 15) {
            revGrid.scrollTo({ left: 0, behavior: 'smooth' });
        } else {
            revGrid.scrollBy({ left: cardWidth, behavior: 'smooth' });
        }
    }
    function prevRev() {
        const revGrid = document.getElementById('rev-grid');
        if (!revGrid) return;
        const cardWidth = revGrid.children[0] ? (revGrid.children[0].offsetWidth + 16) : 320;
        if (revGrid.scrollLeft <= 15) {
            revGrid.scrollTo({ left: revGrid.scrollWidth, behavior: 'smooth' });
        } else {
            revGrid.scrollBy({ left: -cardWidth, behavior: 'smooth' });
        }
    }

    // Auto-slide reviews every 3.5 seconds
    let autoRevTimer = setInterval(nextRev, 3500);
    const revGridEl = document.getElementById('rev-grid');
    if (revGridEl) {
        revGridEl.addEventListener('touchstart', () => clearInterval(autoRevTimer), {passive: true});
        revGridEl.addEventListener('mouseenter', () => clearInterval(autoRevTimer));
        revGridEl.addEventListener('mouseleave', () => {
            clearInterval(autoRevTimer);
            autoRevTimer = setInterval(nextRev, 3500);
        });
    }
</script>
<script>
    function toggleTheme() {
        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
            document.getElementById('moonIcon').style.display = 'block';
            document.getElementById('sunIcon').style.display = 'none';
        } else {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
            document.getElementById('moonIcon').style.display = 'none';
            document.getElementById('sunIcon').style.display = 'block';
        }
    }
    
    document.addEventListener('DOMContentLoaded', () => {
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
            if (document.getElementById('moonIcon')) document.getElementById('moonIcon').style.display = 'none';
            if (document.getElementById('sunIcon')) document.getElementById('sunIcon').style.display = 'block';
        } else {
            document.documentElement.classList.remove('dark');
            if (document.getElementById('moonIcon')) document.getElementById('moonIcon').style.display = 'block';
            if (document.getElementById('sunIcon')) document.getElementById('sunIcon').style.display = 'none';
        }
    });
</script>
</body>
</html>
