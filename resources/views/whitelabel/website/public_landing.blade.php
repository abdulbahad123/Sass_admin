<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agency->meta_title ?? ($agency->name . ' — Launch Your Own SaaS Business Under Your Brand') }}</title>
    <meta name="description" content="{{ $agency->meta_description ?? ($agency->hero_subtitle ?? 'White Label SaaS Platform — Launch your own SaaS business with 5 powerful products, custom branding and complete white-label control.') }}">

    @if(!empty($agency->favicon))
        <link rel="icon" type="image/png" href="{{ asset($agency->favicon) }}">
    @endif
    <meta property="og:title" content="{{ $agency->meta_title ?? $agency->name }}">
    <meta property="og:description" content="{{ $agency->meta_description ?? $agency->hero_subtitle }}">
    @if(!empty($agency->og_image ?? $agency->hero_image))
        <meta property="og:image" content="{{ asset($agency->og_image ?? $agency->hero_image) }}">
    @endif

    {{-- Nooryak Font Stack --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Space+Grotesk:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700;800;900&family=Onest:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @php
        $primaryColor   = $agency->primary_color   ?? '#2563eb';
        $secondaryColor = $agency->secondary_color ?? '#1d4ed8';

        $heroImg  = !empty($agency->hero_image)  ? asset(ltrim($agency->hero_image, '/'))  : asset('assets/landing_page/herobanner_dashboard.png');
        $aboutImg = !empty($agency->about_image) ? asset(ltrim($agency->about_image, '/')) : asset('assets/landing_page/features_leftside.png');
        $ctaImg   = !empty($agency->cta_image)   ? asset(ltrim($agency->cta_image, '/'))   : asset('assets/landing_page/footer_card.png');

        // Stats Bar
        $statsBar = $agency->parsed_stats_bar;

        // Model Cards
        $modelCards = $agency->parsed_model_cards;

        // How It Works
        $howItWorks = $agency->parsed_how_it_works;

        // Growth Path
        $growthPath = $agency->parsed_growth_path;

        // Revenue Calculator
        $revCalc = $agency->parsed_revenue_calculator;

        // Services/Products
        $services = is_array($agency->services_data ?? null)
            ? $agency->services_data
            : (json_decode($agency->services_data ?? '[]', true) ?: [
                ['title' => 'AI Reviews & GMS Automation', 'desc' => 'Generate reviews, automate replies and manage your online reputation.', 'icon' => 'star', 'color' => '#7c3aed', 'bg' => '#ede9fe'],
                ['title' => 'AI Single Page Website Builder', 'desc' => 'Create stunning websites in minutes with AI-powered content generation.', 'icon' => 'monitor', 'color' => '#2563eb', 'bg' => '#dbeafe'],
                ['title' => 'Restaurant QR Menu & Order Management', 'desc' => 'Digital menu, take orders and manage your restaurant operations effortlessly.', 'icon' => 'qr-code', 'color' => '#d97706', 'bg' => '#fef3c7'],
                ['title' => 'Digital V-Card & NFC', 'desc' => 'Increase repeat customers instantly with smart digital business cards.', 'icon' => 'credit-card', 'color' => '#059669', 'bg' => '#d1fae5'],
                ['title' => 'Loyalty & Rewards Platform', 'desc' => 'Increase repeat customers with digital loyalty programs and rewards.', 'icon' => 'gift', 'color' => '#db2777', 'bg' => '#fce7f3'],
            ]);

        // Testimonials
        $testimonials = is_array($agency->testimonials_data ?? null)
            ? $agency->testimonials_data
            : (json_decode($agency->testimonials_data ?? '[]', true) ?: [
                ['name' => 'Rahul Sharma', 'role' => 'Digital Agency Owner', 'rating' => 5, 'comment' => '"Nooryak helped me launch my own SaaS business in just a few days. The platform is powerful and super easy to use."'],
                ['name' => 'Priya Mehta',  'role' => 'Company Founder',     'rating' => 5, 'comment' => '"The Master Panel gives me complete control to manage multiple partners. Highly recommended for anyone looking to create recurring revenue."'],
                ['name' => 'Amit Verma',   'role' => 'Entrepreneur',        'rating' => 5, 'comment' => '"Amazing product and a feature-rich platform. Highly recommended for anyone looking to create recurring revenue."'],
            ]);

        // Features
        $features = is_array($agency->features_data ?? null)
            ? $agency->features_data
            : (json_decode($agency->features_data ?? '[]', true) ?: [
                ['title' => '100% White Label',       'desc' => 'Your brand, your identity. We stay behind the scenes.',     'icon' => 'tag',          'color' => '#2563eb'],
                ['title' => 'White Label Ready',       'desc' => 'Custom logo, domain, and branding.',                       'icon' => 'shield-check',  'color' => '#7c3aed'],
                ['title' => 'Scalable Ecosystem',     'desc' => 'Grow from smaller to network builder.',                     'icon' => 'trending-up',   'color' => '#059669'],
                ['title' => 'Go Live in Days',        'desc' => 'Go live in days, not months.',                             'icon' => 'rocket',        'color' => '#d97706'],
                ['title' => 'Recurring Income',       'desc' => 'Build predictable monthly income.',                        'icon' => 'refresh-cw',    'color' => '#db2777'],
                ['title' => 'Dedicated Support',      'desc' => 'With your technology partner, always.',                   'icon' => 'headphones',    'color' => '#0891b2'],
            ]);

        // FAQs
        $faqs = is_array($agency->faq_data ?? null)
            ? $agency->faq_data
            : (json_decode($agency->faq_data ?? '[]', true) ?: [
                ['q' => 'What is ' . $agency->name . '?',                          'a' => $agency->name . ' is an All-in-One White Label SaaS Platform that helps agencies, freelancers, IT companies and entrepreneurs who want to launch their own SaaS business under their own brand.'],
                ['q' => 'How does the pricing work?',                              'a' => 'We offer flexible monthly and yearly pricing plans. You can choose the plan that fits your business needs.'],
                ['q' => 'Can I use my own domain and branding?',                   'a' => 'Yes! You can fully white-label the platform with your own domain, logo, and brand colors.'],
                ['q' => 'Is there a knowledge base / support?',                    'a' => 'Yes, we offer 24/7 customer support along with a comprehensive knowledge base and video tutorials.'],
                ['q' => 'Do you provide support?',                                 'a' => 'Absolutely. Our dedicated support team is available round the clock to help you with any queries.'],
            ]);

        // Pricing
        $pricingPlans = $agency->parsed_pricing_plans;
        $pricingSectionTitle    = $agency->pricing_section_title    ?? 'Transparent Pricing for Every Stage';
        $pricingSectionSubtitle = $agency->pricing_section_subtitle ?? 'Choose the path that fits your SaaS business.';

        // Social links
        $socialLinks = is_array($agency->social_links ?? null)
            ? $agency->social_links
            : (json_decode($agency->social_links ?? '{}', true) ?: []);
        $fbUrl  = $agency->facebook_url  ?? ($socialLinks['facebook']  ?? '#');
        $igUrl  = $agency->instagram_url ?? ($socialLinks['instagram'] ?? '#');
        $ytUrl  = $agency->youtube_url   ?? ($socialLinks['youtube']   ?? '#');
        $liUrl  = $agency->linkedin_url  ?? ($socialLinks['linkedin']  ?? '#');
        $twUrl  = $agency->twitter_url   ?? ($socialLinks['twitter']   ?? '#');

        $ctaUrl  = $agency->cta_url  ?? '/login';
        $ctaText = $agency->cta_text ?? 'Start Your SaaS Business';
        $cta2Text = $agency->cta2_text ?? 'Book a Demo';
        $cta2Url  = $agency->cta2_url  ?? '/login';

        $announcementText = $agency->announcement_bar_text ?? 'YOUR BRAND. OUR TECHNOLOGY. UNLIMITED GROWTH.';
        $ctaBannerHeading = $agency->cta_banner_heading ?? ('Ready to Launch Your ' . $agency->name . ' Business?');
        $ctaBannerSubtext = $agency->cta_banner_subtext ?? 'Your Success Starts Here!';
    @endphp

    <style>
        :root {
            --brand-primary:   {{ $primaryColor }};
            --brand-secondary: {{ $secondaryColor }};
            --brand-gradient: linear-gradient(135deg, var(--brand-primary) 0%, var(--brand-secondary) 100%);
            --nooryak-blue: #2563eb;
            --nooryak-dark: #0f172a;
            --nooryak-text: #2e2d2d;
            --nooryak-gray: #64748b;
            --nooryak-light: #f8f8f8;
            --nooryak-border: #EAEBED;
            --nooryak-orange: #FF5722;
            --nooryak-orange-dark: #FF3D00;
            --radius-card: 16px;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; font-size: 16px; }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--nooryak-text);
            background: #fff;
            overflow-x: hidden;
            line-height: 1.6;
        }

        h1, h2, h3, h4, h5 {
            font-family: 'Space Grotesk', sans-serif;
            line-height: 1.2;
            color: var(--nooryak-dark);
        }

        a { text-decoration: none; color: inherit; }
        img { max-width: 100%; display: block; }
        ul { list-style: none; }

        /* ── ANNOUNCEMENT BAR ── */
        .announcement-bar {
            background: linear-gradient(90deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            color: #94a3b8;
            text-align: center;
            padding: 9px 16px;
            font-family: 'Poppins', sans-serif;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        /* ── NAVBAR ── */
        .site-header {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: rgba(255,255,255,0.97);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--nooryak-border);
            box-shadow: 0 1px 4px rgba(0,0,0,0.05);
        }
        .header-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
            height: 68px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }
        .nav-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }
        .nav-logo img { height: 38px; width: auto; object-fit: contain; }
        .nav-logo-text {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 22px;
            font-weight: 800;
            color: var(--nooryak-dark);
            letter-spacing: -0.5px;
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .nav-links a {
            font-size: 13.5px;
            font-weight: 600;
            color: #334155;
            padding: 6px 12px;
            border-radius: 8px;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .nav-links a:hover { color: var(--brand-primary); background: #f1f5f9; }
        .nav-links .has-dropdown { position: relative; }
        .nav-links .dropdown-icon { font-size: 11px; opacity: 0.6; }
        .nav-ctas {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }
        .btn-demo {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 18px;
            border-radius: 10px;
            border: 1.5px solid var(--nooryak-border);
            background: #fff;
            font-size: 13px;
            font-weight: 700;
            color: var(--nooryak-dark);
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
        }
        .btn-demo:hover { border-color: var(--brand-primary); color: var(--brand-primary); }
        .btn-primary-nav {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 10px;
            background: var(--brand-gradient);
            color: #fff;
            font-size: 13px;
            font-weight: 800;
            transition: all 0.2s;
            box-shadow: 0 4px 14px rgba(37,99,235,0.3);
            font-family: 'Inter', sans-serif;
            white-space: nowrap;
        }
        .btn-primary-nav:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(37,99,235,0.4); }
        .btn-primary-nav i { font-size: 12px; }
        .nav-icon-btn {
            width: 36px; height: 36px; border-radius: 8px;
            border: 1.5px solid var(--nooryak-border);
            background: #fff;
            display: flex; align-items: center; justify-content: center;
            color: #64748b; cursor: pointer; transition: all 0.2s;
        }
        .nav-icon-btn:hover { border-color: var(--brand-primary); color: var(--brand-primary); }

        /* Mobile Nav */
        .mobile-ham {
            display: none;
            background: none;
            border: 1.5px solid var(--nooryak-border);
            border-radius: 8px;
            width: 38px; height: 38px;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            color: #334155;
        }
        #mobile-menu { display: none; }
        #mobile-menu.open { display: block; }

        /* ── HERO SECTION ── */
        .hero-section {
            background: linear-gradient(160deg, #f0f7ff 0%, #f8f4ff 50%, #fff 100%);
            padding: 80px 0 90px;
            position: relative;
            overflow: hidden;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            top: -100px; right: -100px;
            width: 600px; height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(37,99,235,0.08) 0%, transparent 70%);
            pointer-events: none;
        }
        .hero-section::after {
            content: '';
            position: absolute;
            bottom: -80px; left: -60px;
            width: 400px; height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(124,58,237,0.06) 0%, transparent 70%);
            pointer-events: none;
        }
        .hero-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 56px;
            align-items: center;
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(37,99,235,0.08);
            border: 1px solid rgba(37,99,235,0.18);
            color: #1d4ed8;
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 11.5px;
            font-weight: 700;
            font-family: 'Poppins', sans-serif;
            letter-spacing: 0.02em;
            width: fit-content;
            margin-bottom: 20px;
        }
        .hero-headline {
            font-size: clamp(2.4rem, 4.5vw, 3.8rem);
            font-weight: 800;
            color: var(--nooryak-dark);
            line-height: 1.1;
            letter-spacing: -1px;
            margin-bottom: 20px;
        }
        .hero-headline .text-blue {
            color: var(--brand-primary);
        }
        .hero-subtitle {
            font-size: 15.5px;
            color: #475569;
            line-height: 1.75;
            max-width: 480px;
            margin-bottom: 32px;
        }
        .hero-ctas {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
            align-items: center;
            margin-bottom: 28px;
        }
        .btn-hero-primary {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            background: var(--brand-gradient);
            color: #fff;
            font-weight: 800;
            font-size: 14px;
            padding: 14px 28px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(37,99,235,0.35);
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
        }
        .btn-hero-primary:hover { transform: translateY(-2px); box-shadow: 0 12px 32px rgba(37,99,235,0.45); }
        .btn-hero-secondary {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #fff;
            color: var(--nooryak-dark);
            font-weight: 700;
            font-size: 14px;
            padding: 13px 24px;
            border-radius: 12px;
            border: 1.5px solid var(--nooryak-border);
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
        }
        .btn-hero-secondary:hover { border-color: var(--brand-primary); background: #f8fafc; }
        .hero-trust-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            font-size: 12.5px;
            font-weight: 600;
            color: #64748b;
        }
        .hero-trust-badges span {
            display: flex;
            align-items: center;
            gap: 7px;
        }
        .hero-trust-badges i { color: var(--brand-primary); font-size: 13px; }
        .hero-img-wrapper {
            position: relative;
            display: flex;
            justify-content: flex-end;
        }
        .hero-img-wrapper img {
            width: 100%;
            max-width: 580px;
            border-radius: 18px;
            box-shadow: 0 24px 64px rgba(37,99,235,0.15), 0 0 0 1px rgba(226,232,240,0.6);
            position: relative;
            z-index: 2;
        }
        .hero-float-card {
            position: absolute;
            top: 24px;
            right: -16px;
            z-index: 10;
            background: #fff;
            border-radius: 14px;
            padding: 14px 18px;
            box-shadow: 0 12px 36px rgba(0,0,0,0.12);
            border: 1px solid var(--nooryak-border);
            min-width: 160px;
            animation: floatY 3.5s ease-in-out infinite;
        }
        .hero-float-card .card-label { font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; }
        .hero-float-card .card-value { font-family: 'Onest', sans-serif; font-size: 20px; font-weight: 900; color: var(--nooryak-dark); margin-top: 2px; }
        .hero-float-card .card-growth { font-size: 11px; font-weight: 700; color: #10b981; margin-top: 4px; }
        .hero-float-brand {
            position: absolute;
            bottom: -14px;
            left: 24px;
            z-index: 10;
            background: var(--brand-gradient);
            width: 52px; height: 52px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 6px 20px rgba(37,99,235,0.35);
            border: 3px solid #fff;
            animation: floatY 4s ease-in-out 1s infinite;
        }
        .hero-float-brand img { width: 28px; height: 28px; border-radius: 50%; object-fit: contain; }
        .hero-float-brand i { color: #fff; font-size: 20px; }

        @keyframes floatY {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-8px); }
        }

        /* ── CONTAINER ── */
        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* ── SECTION COMMON ── */
        .section-label {
            font-family: 'Poppins', sans-serif;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--brand-primary);
            margin-bottom: 10px;
        }
        .section-heading {
            font-family: 'Space Grotesk', sans-serif;
            font-size: clamp(1.8rem, 3vw, 2.6rem);
            font-weight: 800;
            color: var(--nooryak-dark);
            line-height: 1.15;
        }
        .section-subheading {
            font-size: 15px;
            color: #475569;
            line-height: 1.7;
            margin-top: 12px;
            max-width: 600px;
        }

        /* ── STATS BAR ── */
        .stats-bar {
            background: #fff;
            border-top: 1px solid var(--nooryak-border);
            border-bottom: 1px solid var(--nooryak-border);
            padding: 0;
        }
        .stats-bar-inner {
            display: flex;
            align-items: stretch;
            justify-content: space-between;
        }
        .stat-item {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 24px 28px;
            border-right: 1px solid var(--nooryak-border);
            transition: background 0.2s;
        }
        .stat-item:last-child { border-right: none; }
        .stat-item:hover { background: #f8fafc; }
        .stat-icon {
            width: 44px; height: 44px;
            border-radius: 12px;
            background: rgba(37,99,235,0.08);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            color: var(--brand-primary);
            font-size: 18px;
        }
        .stat-value {
            font-family: 'Onest', sans-serif;
            font-size: 1.7rem;
            font-weight: 900;
            color: var(--nooryak-dark);
            line-height: 1;
        }
        .stat-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--nooryak-gray);
            margin-top: 3px;
        }

        /* ── ABOUT/PARTNER SECTION ── */
        .about-section {
            background: #f8f9fb;
            padding: 80px 0;
        }
        .about-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }
        .about-img-wrapper {
            position: relative;
        }
        .about-img-wrapper img {
            width: 100%;
            border-radius: var(--radius-card);
            box-shadow: 0 20px 50px rgba(0,0,0,0.1);
        }
        .about-float-card {
            position: absolute;
            bottom: 24px;
            right: -20px;
            background: #fff;
            border-radius: 14px;
            padding: 16px 20px;
            box-shadow: 0 12px 36px rgba(0,0,0,0.12);
            border: 1px solid var(--nooryak-border);
            animation: floatY 4s ease-in-out 1s infinite;
        }
        .about-float-card .af-label { font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; }
        .about-float-card .af-title { font-family: 'Space Grotesk', sans-serif; font-size: 16px; font-weight: 800; color: var(--nooryak-dark); margin-top: 4px; }
        .about-float-card .af-sub { font-size: 12px; color: var(--brand-primary); font-weight: 600; margin-top: 2px; }

        /* ── MODEL CARDS ── */
        .models-section {
            background: #fff;
            padding: 80px 0;
        }
        .models-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 28px;
            margin-top: 48px;
        }
        .model-card {
            border-radius: 20px;
            border: 1.5px solid var(--nooryak-border);
            overflow: hidden;
            transition: transform 0.25s, box-shadow 0.25s;
            background: #fff;
        }
        .model-card:hover { transform: translateY(-4px); box-shadow: 0 20px 50px rgba(0,0,0,0.09); }
        .model-card-header {
            padding: 24px 28px 20px;
            background: linear-gradient(135deg, #f0f7ff 0%, #e8f0fe 100%);
            border-bottom: 1px solid var(--nooryak-border);
        }
        .model-card-header.purple-header { background: linear-gradient(135deg, #f5f0ff 0%, #ede8fe 100%); }
        .model-badge {
            display: inline-block;
            font-family: 'Poppins', sans-serif;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--brand-primary);
            background: rgba(37,99,235,0.1);
            padding: 3px 10px;
            border-radius: 999px;
            margin-bottom: 10px;
        }
        .model-badge.purple { color: #7c3aed; background: rgba(124,58,237,0.1); }
        .model-card-title {
            font-size: 20px;
            font-weight: 800;
            color: var(--nooryak-dark);
            margin-bottom: 8px;
        }
        .model-card-desc {
            font-size: 13px;
            color: #475569;
            line-height: 1.65;
        }
        .model-card-body {
            padding: 24px 28px;
        }
        .model-feature-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 24px;
        }
        .model-feature-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13.5px;
            font-weight: 500;
            color: #334155;
        }
        .model-feature-item i {
            color: var(--brand-primary);
            font-size: 13px;
            flex-shrink: 0;
            width: 18px;
            text-align: center;
        }
        .model-feature-item.purple-check i { color: #7c3aed; }
        .btn-model {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 22px;
            border-radius: 10px;
            background: var(--brand-gradient);
            color: #fff;
            font-weight: 700;
            font-size: 13px;
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
        }
        .btn-model:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(37,99,235,0.3); }
        .btn-model.purple-btn { background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%); }
        .btn-model.purple-btn:hover { box-shadow: 0 6px 18px rgba(124,58,237,0.3); }

        /* ── PRODUCTS SECTION ── */
        .products-section {
            background: var(--nooryak-light);
            padding: 80px 0;
        }
        .products-layout {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 32px;
            margin-top: 48px;
            align-items: start;
        }
        .products-grid-inner {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        .product-card {
            background: #fff;
            border-radius: var(--radius-card);
            border: 1px solid var(--nooryak-border);
            padding: 22px 20px;
            transition: transform 0.25s, box-shadow 0.25s;
            cursor: pointer;
        }
        .product-card:hover { transform: translateY(-4px); box-shadow: 0 14px 36px rgba(0,0,0,0.08); }
        .product-icon {
            width: 48px; height: 48px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            margin-bottom: 14px;
        }
        .product-card-title {
            font-size: 14px;
            font-weight: 800;
            color: var(--nooryak-dark);
            margin-bottom: 8px;
            font-family: 'Space Grotesk', sans-serif;
        }
        .product-card-desc {
            font-size: 12.5px;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 14px;
        }
        .product-learn-more {
            font-size: 12px;
            font-weight: 700;
            color: var(--brand-primary);
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .product-learn-more:hover { gap: 8px; }

        /* Revenue Calculator */
        .rev-calc-card {
            background: #fff;
            border-radius: 20px;
            border: 1.5px solid var(--nooryak-border);
            padding: 28px 24px;
            box-shadow: 0 8px 28px rgba(0,0,0,0.06);
        }
        .rev-calc-title {
            font-size: 16px;
            font-weight: 800;
            color: var(--nooryak-dark);
            margin-bottom: 4px;
            font-family: 'Space Grotesk', sans-serif;
        }
        .rev-calc-subtitle {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 20px;
        }
        .rev-slider-label {
            display: flex;
            justify-content: space-between;
            font-size: 12.5px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 8px;
        }
        .rev-slider {
            width: 100%;
            -webkit-appearance: none;
            appearance: none;
            height: 6px;
            border-radius: 999px;
            background: linear-gradient(90deg, var(--brand-primary) 0%, #e2e8f0 0%);
            outline: none;
            cursor: pointer;
            margin-bottom: 20px;
        }
        .rev-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 20px; height: 20px;
            border-radius: 50%;
            background: var(--brand-primary);
            box-shadow: 0 2px 8px rgba(37,99,235,0.4);
            border: 3px solid #fff;
            cursor: pointer;
        }
        .rev-result-box {
            background: linear-gradient(135deg, #f0f7ff 0%, #eff6ff 100%);
            border-radius: 12px;
            padding: 18px;
            margin-bottom: 16px;
            text-align: center;
        }
        .rev-result-label { font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; }
        .rev-result-value {
            font-family: 'Onest', sans-serif;
            font-size: 2rem;
            font-weight: 900;
            color: var(--brand-primary);
            margin: 6px 0 2px;
            line-height: 1;
        }
        .rev-note { font-size: 10px; color: #94a3b8; }
        .rev-badges {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }
        .rev-badge {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 11px;
            font-weight: 700;
            color: #059669;
            background: #d1fae5;
            padding: 4px 10px;
            border-radius: 999px;
        }
        .rev-badge i { font-size: 10px; }
        .btn-rev-cta {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            background: var(--brand-gradient);
            color: #fff;
            font-weight: 800;
            font-size: 13px;
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
        }
        .btn-rev-cta:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(37,99,235,0.35); }

        /* ── HOW IT WORKS ── */
        .hiw-section {
            background: #fff;
            padding: 80px 0;
        }
        .hiw-steps {
            display: flex;
            align-items: flex-start;
            gap: 0;
            margin-top: 52px;
            position: relative;
        }
        .hiw-step {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 0 20px;
            position: relative;
            z-index: 2;
        }
        .hiw-connector {
            flex: 0 0 60px;
            height: 2px;
            background: linear-gradient(90deg, var(--brand-primary), var(--brand-secondary));
            margin-top: 32px;
            border-radius: 999px;
            opacity: 0.3;
            z-index: 1;
        }
        .hiw-step-num {
            width: 40px; height: 40px;
            border-radius: 50%;
            background: var(--brand-gradient);
            color: #fff;
            font-family: 'Onest', sans-serif;
            font-weight: 900;
            font-size: 16px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 16px;
            box-shadow: 0 4px 14px rgba(37,99,235,0.3);
        }
        .hiw-icon-wrap {
            width: 64px; height: 64px;
            border-radius: 18px;
            background: #f0f7ff;
            display: flex; align-items: center; justify-content: center;
            font-size: 26px;
            color: var(--brand-primary);
            margin-bottom: 16px;
            transition: background 0.2s;
        }
        .hiw-step:hover .hiw-icon-wrap { background: var(--brand-gradient); color: #fff; }
        .hiw-step-title {
            font-size: 15px;
            font-weight: 800;
            color: var(--nooryak-dark);
            margin-bottom: 8px;
            font-family: 'Space Grotesk', sans-serif;
        }
        .hiw-step-desc { font-size: 13px; color: #64748b; line-height: 1.65; }

        /* ── WHY CHOOSE ── */
        .why-section {
            background: var(--nooryak-light);
            padding: 80px 0;
        }
        .why-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 56px;
            align-items: center;
            margin-top: 0;
        }
        .why-features-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-top: 32px;
        }
        .why-feat-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            background: #fff;
            border-radius: 14px;
            border: 1px solid var(--nooryak-border);
            padding: 16px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .why-feat-item:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.06); }
        .why-feat-icon {
            width: 40px; height: 40px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 17px;
            flex-shrink: 0;
        }
        .why-feat-title { font-size: 13px; font-weight: 800; color: var(--nooryak-dark); margin-bottom: 4px; }
        .why-feat-desc { font-size: 12px; color: #64748b; line-height: 1.55; }
        .why-img-wrapper { position: relative; }
        .why-img-wrapper img { border-radius: 18px; box-shadow: 0 20px 50px rgba(0,0,0,0.1); }

        /* ── PRICING ── */
        .pricing-section {
            background: #fff;
            padding: 80px 0;
        }
        .pricing-toggle-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            margin: 32px 0 44px;
        }
        .pricing-toggle-label { font-size: 14px; font-weight: 700; color: #334155; }
        .pricing-toggle-switch {
            position: relative;
            width: 52px; height: 28px;
        }
        .pricing-toggle-switch input { opacity: 0; width: 0; height: 0; }
        .toggle-track {
            position: absolute;
            inset: 0;
            background: #e2e8f0;
            border-radius: 999px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .toggle-track::before {
            content: '';
            position: absolute;
            width: 22px; height: 22px;
            left: 3px; top: 3px;
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
            transition: transform 0.3s;
        }
        .pricing-toggle-switch input:checked + .toggle-track { background: var(--brand-primary); }
        .pricing-toggle-switch input:checked + .toggle-track::before { transform: translateX(24px); }
        .pricing-save-badge {
            background: #d1fae5;
            color: #059669;
            font-size: 11px;
            font-weight: 800;
            padding: 3px 10px;
            border-radius: 999px;
        }
        .pricing-cards-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 28px;
        }
        .pricing-card {
            border-radius: 20px;
            border: 1.5px solid var(--nooryak-border);
            overflow: hidden;
            display: grid;
            grid-template-columns: 1fr 1fr;
            box-shadow: 0 8px 28px rgba(0,0,0,0.06);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .pricing-card:hover { transform: translateY(-4px); box-shadow: 0 20px 50px rgba(0,0,0,0.1); }
        .pricing-left {
            padding: 32px 28px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border-right: 1px solid var(--nooryak-border);
        }
        .pricing-plan-badge {
            display: inline-block;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 4px 12px;
            border-radius: 999px;
            margin-bottom: 12px;
            font-family: 'Poppins', sans-serif;
        }
        .pricing-plan-name {
            font-size: 21px;
            font-weight: 800;
            color: var(--nooryak-dark);
            margin-bottom: 8px;
            font-family: 'Space Grotesk', sans-serif;
        }
        .pricing-plan-sub { font-size: 12.5px; color: #64748b; line-height: 1.6; margin-bottom: 24px; }
        .pricing-price-wrap { margin-bottom: 24px; }
        .pricing-price-main {
            font-family: 'Onest', sans-serif;
            font-size: 2.4rem;
            font-weight: 900;
            color: var(--nooryak-dark);
            line-height: 1;
        }
        .pricing-price-main span { font-size: 1rem; font-weight: 500; color: #94a3b8; }
        .pricing-price-yearly { display: none; }
        .btn-pricing {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 22px;
            border-radius: 10px;
            background: var(--brand-gradient);
            color: #fff;
            font-weight: 800;
            font-size: 13px;
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
            text-align: center;
            justify-content: center;
        }
        .btn-pricing:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(37,99,235,0.3); }
        .pricing-right {
            padding: 28px 24px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .pricing-features-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .pricing-feat-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            font-weight: 500;
            color: #334155;
        }
        .pricing-feat-item i { color: var(--brand-primary); font-size: 12px; flex-shrink: 0; }

        /* ── GROWTH PATH ── */
        .growth-section {
            background: var(--nooryak-light);
            padding: 80px 0;
        }
        .growth-path {
            display: flex;
            align-items: center;
            gap: 0;
            margin-top: 52px;
            position: relative;
        }
        .growth-step {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 28px 20px;
            background: #fff;
            border-radius: 16px;
            border: 1.5px solid var(--nooryak-border);
            transition: transform 0.2s, box-shadow 0.2s;
            position: relative;
            z-index: 2;
        }
        .growth-step:hover { transform: translateY(-4px); box-shadow: 0 14px 36px rgba(0,0,0,0.08); }
        .growth-step-icon {
            width: 56px; height: 56px;
            border-radius: 50%;
            background: var(--brand-gradient);
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
            color: #fff;
            margin-bottom: 14px;
            box-shadow: 0 6px 18px rgba(37,99,235,0.3);
        }
        .growth-step-label {
            font-size: 16px;
            font-weight: 800;
            color: var(--nooryak-dark);
            margin-bottom: 6px;
            font-family: 'Space Grotesk', sans-serif;
        }
        .growth-step-desc { font-size: 12.5px; color: #64748b; line-height: 1.6; }
        .growth-arrow {
            flex: 0 0 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--brand-primary);
            font-size: 18px;
            opacity: 0.5;
            z-index: 1;
        }

        /* ── TESTIMONIALS ── */
        .testimonials-section {
            background: #fff;
            padding: 80px 0;
        }
        .testimonials-label {
            display: inline-block;
            font-size: 11px;
            font-weight: 700;
            font-family: 'Poppins', sans-serif;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 8px;
        }
        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-top: 44px;
        }
        .testimonial-card {
            background: #fff;
            border: 1px solid var(--nooryak-border);
            border-radius: 18px;
            padding: 24px 22px;
            transition: transform 0.25s, box-shadow 0.25s;
        }
        .testimonial-card:hover { transform: translateY(-4px); box-shadow: 0 14px 40px rgba(0,0,0,0.08); }
        .test-stars { color: #f59e0b; font-size: 14px; margin-bottom: 14px; }
        .test-quote { font-size: 13.5px; color: #334155; line-height: 1.7; margin-bottom: 18px; font-style: italic; }
        .test-author { display: flex; align-items: center; gap: 12px; }
        .test-avatar-initials {
            width: 42px; height: 42px; border-radius: 50%;
            background: var(--brand-gradient);
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 900; font-size: 13px;
            font-family: 'Onest', sans-serif;
            flex-shrink: 0;
        }
        .test-name { font-size: 14px; font-weight: 800; color: var(--nooryak-dark); }
        .test-role { font-size: 12px; color: #94a3b8; }

        /* ── FAQ ── */
        .faq-section {
            background: var(--nooryak-light);
            padding: 80px 0;
        }
        .faq-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 48px;
            margin-top: 48px;
            align-items: start;
        }
        .faq-heading-col {}
        .faq-list-col {}
        .faq-item {
            background: #fff;
            border: 1px solid var(--nooryak-border);
            border-radius: 14px;
            margin-bottom: 12px;
            overflow: hidden;
        }
        .faq-question {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 20px;
            background: none;
            border: none;
            cursor: pointer;
            text-align: left;
            gap: 12px;
        }
        .faq-q-text { font-size: 14px; font-weight: 700; color: var(--nooryak-dark); line-height: 1.4; }
        .faq-icon { color: var(--brand-primary); font-size: 14px; flex-shrink: 0; transition: transform 0.3s; }
        .faq-item.open .faq-icon { transform: rotate(45deg); }
        .faq-answer {
            display: none;
            padding: 0 20px 18px;
            font-size: 13.5px;
            color: #475569;
            line-height: 1.75;
        }
        .faq-item.open .faq-answer { display: block; }

        /* ── CTA BANNER ── */
        .cta-banner-section {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 40%, #1e1b4b 100%);
            padding: 80px 0;
            position: relative;
            overflow: hidden;
        }
        .cta-banner-section::before {
            content: '';
            position: absolute;
            top: -60px; right: -60px;
            width: 400px; height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(37,99,235,0.2) 0%, transparent 70%);
            pointer-events: none;
        }
        .cta-banner-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 40px;
            position: relative;
            z-index: 2;
        }
        .cta-banner-text { flex: 1; }
        .cta-banner-heading {
            font-family: 'Space Grotesk', sans-serif;
            font-size: clamp(1.8rem, 3vw, 2.8rem);
            font-weight: 800;
            color: #fff;
            margin-bottom: 10px;
        }
        .cta-banner-sub { font-size: 15px; color: #94a3b8; }
        .cta-banner-actions {
            display: flex;
            gap: 14px;
            flex-shrink: 0;
            flex-wrap: wrap;
        }
        .btn-cta-white {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 28px;
            border-radius: 12px;
            background: var(--brand-gradient);
            color: #fff;
            font-weight: 800;
            font-size: 14px;
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
            box-shadow: 0 6px 20px rgba(37,99,235,0.4);
        }
        .btn-cta-white:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(37,99,235,0.5); }
        .btn-cta-outline {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 13px 26px;
            border-radius: 12px;
            background: transparent;
            color: #fff;
            font-weight: 700;
            font-size: 14px;
            border: 1.5px solid rgba(255,255,255,0.3);
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
        }
        .btn-cta-outline:hover { border-color: #fff; background: rgba(255,255,255,0.08); }
        .cta-success-text {
            font-family: 'Satisfy', cursive, sans-serif;
            color: #ffd700;
            font-size: 22px;
        }

        /* ── FOOTER ── */
        .site-footer {
            background: var(--nooryak-dark);
            color: #94a3b8;
            padding: 64px 0 0;
        }
        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 1fr;
            gap: 40px;
            padding-bottom: 48px;
            border-bottom: 1px solid #1e293b;
        }
        .footer-logo-col {}
        .footer-logo {
            display: flex; align-items: center; gap: 10px;
            margin-bottom: 16px;
        }
        .footer-logo img { height: 34px; width: auto; object-fit: contain; }
        .footer-logo-text {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 20px;
            font-weight: 800;
            color: #fff;
        }
        .footer-tagline { font-size: 13.5px; color: #64748b; line-height: 1.7; margin-bottom: 20px; max-width: 280px; }
        .footer-socials { display: flex; gap: 10px; }
        .footer-social-btn {
            width: 34px; height: 34px;
            border-radius: 8px;
            background: #1e293b;
            display: flex; align-items: center; justify-content: center;
            color: #64748b;
            font-size: 14px;
            transition: all 0.2s;
        }
        .footer-social-btn:hover { background: var(--brand-primary); color: #fff; }
        .footer-col-title {
            font-size: 13px;
            font-weight: 800;
            color: #fff;
            margin-bottom: 16px;
            font-family: 'Space Grotesk', sans-serif;
        }
        .footer-links { display: flex; flex-direction: column; gap: 10px; }
        .footer-links a { font-size: 13px; color: #64748b; transition: color 0.2s; }
        .footer-links a:hover { color: #fff; }
        .footer-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 20px 0;
            font-size: 12.5px;
        }
        .footer-bottom-links { display: flex; gap: 20px; }
        .footer-bottom-links a { color: #64748b; transition: color 0.2s; }
        .footer-bottom-links a:hover { color: #fff; }

        /* ── RESPONSIVE ── */
        @media (max-width: 1200px) {
            .products-layout { grid-template-columns: 1fr; }
            .products-grid-inner { grid-template-columns: repeat(3, 1fr); }
            .footer-grid { grid-template-columns: 1fr 1fr 1fr; }
        }

        @media (max-width: 1024px) {
            .hero-grid, .about-grid, .why-grid, .faq-grid, .cta-banner-inner { grid-template-columns: 1fr; }
            .models-grid, .pricing-cards-grid, .testimonials-grid { grid-template-columns: 1fr; }
            .hero-img-wrapper { justify-content: center; }
            .hero-float-card { right: 8px; }
            .about-float-card { right: 8px; }
            .products-grid-inner { grid-template-columns: repeat(2, 1fr); }
            .cta-banner-actions { justify-content: center; }
        }

        @media (max-width: 768px) {
            .stats-bar-inner { flex-direction: column; }
            .stat-item { border-right: none; border-bottom: 1px solid var(--nooryak-border); }
            .stat-item:last-child { border-bottom: none; }
            .hiw-steps { flex-direction: column; gap: 24px; }
            .hiw-connector { display: none; }
            .growth-path { flex-direction: column; gap: 12px; }
            .growth-arrow { transform: rotate(90deg); }
            .footer-grid { grid-template-columns: 1fr 1fr; }
            .footer-logo-col { grid-column: 1/-1; }
            .footer-bottom { flex-direction: column; text-align: center; }
            .nav-links { display: none; }
            .nav-ctas { display: none; }
            .mobile-ham { display: flex; }
            .stats-bar-inner { display: grid; grid-template-columns: 1fr 1fr; }
            .stat-item:nth-child(even) { border-right: none; }
            .stat-item:nth-child(odd) { border-right: 1px solid var(--nooryak-border); }
            .hero-section { padding: 52px 0 60px; }
            .why-features-grid { grid-template-columns: 1fr; }
            .faq-grid { grid-template-columns: 1fr; }
            .cta-banner-inner { flex-direction: column; text-align: center; }
            .pricing-card { grid-template-columns: 1fr; }
            .pricing-left { border-right: none; border-bottom: 1px solid var(--nooryak-border); }
            .testimonials-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 480px) {
            .products-grid-inner { grid-template-columns: 1fr; }
            .hero-ctas { flex-direction: column; }
            .btn-hero-primary, .btn-hero-secondary { width: 100%; justify-content: center; }
            .stats-bar-inner { grid-template-columns: 1fr; }
            .stat-item { border-right: none !important; }
        }
    </style>
</head>
<body>

{{-- ══ ANNOUNCEMENT BAR ══ --}}
<div class="announcement-bar">
    {{ $announcementText }}
</div>

{{-- ══ NAVBAR ══ --}}
<header class="site-header">
    <div class="header-inner">
        {{-- Logo --}}
        <a href="/" class="nav-logo">
            @if(!empty($agency->logo))
                <img src="{{ asset($agency->logo) }}" alt="{{ $agency->name }}">
            @else
                <div style="width:36px;height:36px;border-radius:10px;background:var(--brand-gradient);display:flex;align-items:center;justify-content:center">
                    <i class="fas fa-layer-group" style="color:#fff;font-size:16px"></i>
                </div>
                <span class="nav-logo-text">{{ $agency->name }}</span>
            @endif
        </a>

        {{-- Desktop Nav --}}
        <nav class="nav-links">
            <a href="/">Home</a>
            <a href="#products">Solutions <i class="fas fa-chevron-down dropdown-icon"></i></a>
            <a href="#pricing">Pricing</a>
            <a href="#faq">Resources <i class="fas fa-chevron-down dropdown-icon"></i></a>
        </nav>

        {{-- Desktop CTAs --}}
        <div class="nav-ctas">
            <button class="nav-icon-btn" title="Toggle Theme"><i class="fas fa-moon" style="font-size:14px"></i></button>
            <a href="{{ $cta2Url }}" class="btn-demo">{{ $cta2Text }}</a>
            <a href="{{ $ctaUrl }}" class="btn-primary-nav">{{ $ctaText }} <i class="fas fa-arrow-right"></i></a>
        </div>

        {{-- Mobile hamburger --}}
        <button class="mobile-ham" onclick="toggleMobileMenu()" aria-label="Menu">
            <i class="fas fa-bars" id="ham-icon"></i>
        </button>
    </div>

    {{-- Mobile Menu --}}
    <div id="mobile-menu" style="background:#fff;border-top:1px solid var(--nooryak-border);padding:20px 24px">
        <nav style="display:flex;flex-direction:column;gap:14px;margin-bottom:20px">
            <a href="/" onclick="toggleMobileMenu()" style="font-size:14px;font-weight:700;color:#334155">Home</a>
            <a href="#products" onclick="toggleMobileMenu()" style="font-size:14px;font-weight:700;color:#334155">Solutions</a>
            <a href="#pricing" onclick="toggleMobileMenu()" style="font-size:14px;font-weight:700;color:#334155">Pricing</a>
            <a href="#faq" onclick="toggleMobileMenu()" style="font-size:14px;font-weight:700;color:#334155">Resources</a>
        </nav>
        <div style="display:flex;flex-direction:column;gap:10px">
            <a href="{{ $cta2Url }}" style="text-align:center;font-size:14px;font-weight:700;color:#334155;background:#f8fafc;border:1px solid var(--nooryak-border);border-radius:12px;padding:12px">{{ $cta2Text }}</a>
            <a href="{{ $ctaUrl }}" class="btn-hero-primary" style="justify-content:center">{{ $ctaText }} <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</header>


{{-- ══ HERO SECTION ══ --}}
<section class="hero-section" id="hero">
    <div class="container">
        <div class="hero-grid">
            {{-- Left --}}
            <div>
                <div class="hero-badge">
                    <i class="fas fa-bolt" style="font-size:10px"></i>
                    YOUR BRAND. OUR TECHNOLOGY. UNLIMITED GROWTH.
                </div>
                <h1 class="hero-headline">
                    {{ $agency->hero_title ?? 'Launch Your Own SaaS' }}
                    <br><span class="text-blue">Business Under Your Brand</span>
                </h1>
                <p class="hero-subtitle">
                    {{ $agency->hero_subtitle ?? ($agency->name . ' is an All-in-One White Label SaaS Platform that helps agencies, freelancers, IT companies and entrepreneurs who want to launch their own SaaS business with 5 powerful products, custom branding and complete white-label control.') }}
                </p>
                <div class="hero-ctas">
                    <a href="{{ $ctaUrl }}" class="btn-hero-primary">
                        {{ $ctaText }} <i class="fas fa-arrow-right"></i>
                    </a>
                    <a href="{{ $cta2Url }}" class="btn-hero-secondary">
                        <span style="width:28px;height:28px;border-radius:50%;background:var(--brand-gradient);display:inline-flex;align-items:center;justify-content:center">
                            <i class="fas fa-play" style="color:#fff;font-size:9px;margin-left:1px"></i>
                        </span>
                        {{ $cta2Text }}
                    </a>
                </div>
                <div class="hero-trust-badges">
                    <span><i class="fas fa-check-circle"></i> White Label Ready</span>
                    <span><i class="fas fa-check-circle"></i> Custom Branding</span>
                    <span><i class="fas fa-check-circle"></i> Unlimited Customers</span>
                </div>
            </div>

            {{-- Right --}}
            <div class="hero-img-wrapper">
                <div class="hero-float-card">
                    <div class="card-label">Revenue Overview</div>
                    <div class="card-value">₹4,98,320</div>
                    <div class="card-growth"><i class="fas fa-arrow-up"></i> +12.5% this month</div>
                </div>
                <img src="{{ $heroImg }}" alt="{{ $agency->name }} Dashboard"
                     style="width:100%;max-width:580px;border-radius:18px;box-shadow:0 24px 64px rgba(37,99,235,0.15)">
                <div class="hero-float-brand">
                    @if(!empty($agency->logo))
                        <img src="{{ asset($agency->logo) }}" alt="">
                    @else
                        <i class="fas fa-layer-group"></i>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ══ STATS BAR ══ --}}
<section class="stats-bar">
    <div class="container">
        <div class="stats-bar-inner">
            @foreach($statsBar as $stat)
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-{{ $stat['icon'] ?? 'check' }}"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ $stat['value'] }}</div>
                        <div class="stat-label">{{ $stat['label'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ══ ABOUT / PARTNER SECTION ══ --}}
<section class="about-section" id="about">
    <div class="container">
        <div class="about-grid">
            {{-- Left Text --}}
            <div>
                <div class="section-label">ABOUT {{ strtoupper($agency->name) }}</div>
                <h2 class="section-heading">{{ $agency->about_title ?? 'Your Partner in SaaS Success' }}</h2>
                <p class="section-subheading">
                    {{ $agency->about_mission ?? ($agency->name . ' is an All-in-One White Label SaaS Platform built for agencies, freelancers, IT companies and entrepreneurs who want to launch their own SaaS business under their own brand. With 5 powerful White Label SaaS products, a unified dashboard, custom branding and complete white-label control, we provide everything you need to build a scalable business and generate recurring revenue.') }}
                </p>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:28px">
                    @php
                        $aboutPillars = [
                            ['icon' => 'fa-shield-alt',   'label' => 'Trusted Technology'],
                            ['icon' => 'fa-chart-line',   'label' => 'Scalable Platform'],
                            ['icon' => 'fa-rocket',       'label' => 'Built for Entrepreneurs'],
                            ['icon' => 'fa-infinity',     'label' => 'Unlimited Growth'],
                        ];
                    @endphp
                    @foreach($aboutPillars as $p)
                        <div style="display:flex;align-items:center;gap:10px;background:#fff;border:1px solid var(--nooryak-border);border-radius:12px;padding:12px 16px">
                            <span style="width:36px;height:36px;border-radius:10px;background:rgba(37,99,235,0.08);display:flex;align-items:center;justify-content:center;color:var(--brand-primary);font-size:14px;flex-shrink:0">
                                <i class="fas {{ $p['icon'] }}"></i>
                            </span>
                            <span style="font-size:13px;font-weight:700;color:var(--nooryak-dark)">{{ $p['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Right Image --}}
            <div class="about-img-wrapper">
                <div style="position:absolute;top:-10px;right:10px;background:#fff;border-radius:14px;padding:12px 18px;box-shadow:0 12px 36px rgba(0,0,0,0.1);border:1px solid var(--nooryak-border);font-family:'Space Grotesk',sans-serif;font-size:15px;font-weight:800;color:var(--nooryak-dark);z-index:10;animation:floatY 3.5s ease-in-out infinite">
                    Same Platform<br><span style="color:var(--brand-primary)">More Possibilities</span>
                </div>
                <img src="{{ $aboutImg }}" alt="{{ $agency->name }} Features" style="border-radius:18px;box-shadow:0 20px 50px rgba(0,0,0,0.1)">
            </div>
        </div>
    </div>
</section>


{{-- ══ MODEL CARDS ══ --}}
<section class="models-section" id="models">
    <div class="container">
        <div style="text-align:center;margin-bottom:0">
            <div class="section-label">OUR MODELS</div>
            <h2 class="section-heading">Choose Your Business Model</h2>
            <p class="section-subheading" style="margin:12px auto 0">Pick the model that aligns with your vision and start your white-label SaaS journey today.</p>
        </div>
        <div class="models-grid">
            @foreach($modelCards as $mi => $card)
                @php
                    $isBlue   = $mi === 0;
                    $color    = $card['color'] ?? ($isBlue ? '#2563eb' : '#7c3aed');
                    $isPurple = !$isBlue;
                @endphp
                <div class="model-card">
                    <div class="model-card-header {{ $isPurple ? 'purple-header' : '' }}">
                        <div class="model-badge {{ $isPurple ? 'purple' : '' }}">{{ $card['badge'] ?? 'Model 0' . ($mi+1) }}</div>
                        <h3 class="model-card-title">{{ $card['title'] }}</h3>
                        <p class="model-card-desc">{{ $card['description'] }}</p>
                    </div>
                    <div class="model-card-body">
                        <ul class="model-feature-list">
                            @foreach(($card['features'] ?? []) as $feat)
                                <li class="model-feature-item {{ $isPurple ? 'purple-check' : '' }}">
                                    <i class="fas fa-check-circle"></i>
                                    {{ $feat }}
                                </li>
                            @endforeach
                        </ul>
                        <a href="{{ $card['cta_url'] ?? $ctaUrl }}" class="btn-model {{ $isPurple ? 'purple-btn' : '' }}">
                            {{ $card['cta_text'] ?? 'Get Started' }} <i class="fas fa-arrow-right" style="font-size:11px"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ══ PRODUCTS + REVENUE CALCULATOR ══ --}}
<section class="products-section" id="products">
    <div class="container">
        <div style="text-align:center">
            <div class="section-label">OUR PRODUCTS</div>
            <h2 class="section-heading">{{ $agency->products_section_title ?? 'Everything You Need to Build & Scale Your SaaS Business' }}</h2>
            <p class="section-subheading" style="margin:12px auto 0">{{ $agency->name }} combines 5 powerful White Label SaaS products, a unified dashboard, Single Sign-On (SSO), custom branding, and enterprise-grade management tools into one complete platform.</p>
        </div>
        <div class="products-layout">
            {{-- Products Grid --}}
            <div class="products-grid-inner">
                @php
                    $prodIconMap = [
                        'star'        => '#7c3aed', 'bg_star'   => '#ede9fe',
                        'monitor'     => '#2563eb', 'bg_monitor'=> '#dbeafe',
                        'qr-code'     => '#d97706', 'bg_qr'     => '#fef3c7',
                        'credit-card' => '#059669', 'bg_cc'     => '#d1fae5',
                        'gift'        => '#db2777', 'bg_gift'   => '#fce7f3',
                    ];
                    $defaultColors = [
                        ['#7c3aed','#ede9fe'],['#2563eb','#dbeafe'],
                        ['#d97706','#fef3c7'],['#059669','#d1fae5'],
                        ['#db2777','#fce7f3'],
                    ];
                @endphp
                @foreach($services as $si => $svc)
                    @php
                        [$sColor, $sBg] = $defaultColors[$si % count($defaultColors)];
                        $sColor = $svc['color'] ?? $sColor;
                        $sBg    = $svc['bg']    ?? $sBg;
                    @endphp
                    <div class="product-card">
                        <div class="product-icon" style="background:{{ $sBg }};color:{{ $sColor }}">
                            <i class="fas fa-{{ $svc['icon'] ?? 'box' }}"></i>
                        </div>
                        <div class="product-card-title">{{ $svc['title'] }}</div>
                        <div class="product-card-desc">{{ $svc['desc'] }}</div>
                        <a href="{{ $svc['link'] ?? '#' }}" class="product-learn-more" style="color:{{ $sColor }}">
                            Learn More <i class="fas fa-arrow-right" style="font-size:10px"></i>
                        </a>
                    </div>
                @endforeach
            </div>

            {{-- Revenue Calculator --}}
            <div class="rev-calc-card" id="revenue-calc">
                <div class="rev-calc-title">{{ $revCalc['title'] ?? 'Revenue Opportunity Calculator' }}</div>
                <div class="rev-calc-subtitle">{{ $revCalc['subtitle'] ?? 'See how much you can earn every month' }}</div>

                <div class="rev-slider-label">
                    <span>{{ $revCalc['active_label'] ?? 'Active Customers' }}</span>
                    <span id="calc-count">{{ $revCalc['default_count'] ?? 100 }}</span>
                </div>
                <input type="range" class="rev-slider" id="rev-slider"
                    min="1"
                    max="{{ $revCalc['max_count'] ?? 500 }}"
                    value="{{ $revCalc['default_count'] ?? 100 }}"
                    step="1">

                <div class="rev-result-box">
                    <div class="rev-result-label">Estimated Monthly Revenue</div>
                    <div class="rev-result-value" id="rev-result">
                        {{ $revCalc['currency_symbol'] ?? '₹' }}{{ number_format(($revCalc['default_count'] ?? 100) * ($revCalc['price_per_customer'] ?? 999)) }}<span style="font-size:1rem;font-weight:500;color:#94a3b8">/month</span>
                    </div>
                    <div class="rev-note">{{ $revCalc['note'] ?? '*Average price per customer/month' }}</div>
                </div>

                <div class="rev-badges">
                    <div class="rev-badge"><i class="fas fa-check"></i> {{ $revCalc['low_badge'] ?? 'Low Investment' }}</div>
                    <div class="rev-badge"><i class="fas fa-check"></i> {{ $revCalc['margin_badge'] ?? 'High Margin' }}</div>
                    <div class="rev-badge" style="color:#2563eb;background:#dbeafe"><i class="fas fa-check"></i> {{ $revCalc['potential_badge'] ?? 'Unlimited Potential' }}</div>
                </div>

                <a href="{{ $revCalc['cta_url'] ?? $ctaUrl }}" class="btn-rev-cta">
                    {{ $revCalc['cta_text'] ?? 'Start Building Your Revenue' }} <i class="fas fa-arrow-right" style="font-size:11px"></i>
                </a>
            </div>
        </div>
    </div>
</section>


{{-- ══ HOW IT WORKS ══ --}}
<section class="hiw-section" id="how-it-works">
    <div class="container">
        <div style="text-align:center">
            <div class="section-label">HOW {{ strtoupper($agency->name) }} WORKS</div>
            <h2 class="section-heading">Launch, Brand, Sell, Earn. Repeat.</h2>
            <p class="section-subheading" style="margin:12px auto 0">A simple 4-step process to launch your own branded SaaS business.</p>
        </div>
        <div class="hiw-steps">
            @foreach($howItWorks as $hi => $step)
                @if($hi > 0)
                    <div class="hiw-connector"></div>
                @endif
                <div class="hiw-step">
                    <div class="hiw-step-num">{{ $step['step'] ?? ($hi+1) }}</div>
                    <div class="hiw-icon-wrap">
                        <i class="fas fa-{{ $step['icon'] ?? 'check' }}"></i>
                    </div>
                    <div class="hiw-step-title">{{ $step['title'] }}</div>
                    <div class="hiw-step-desc">{{ $step['desc'] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ══ WHY CHOOSE ══ --}}
<section class="why-section" id="why-choose">
    <div class="container">
        <div class="why-grid">
            {{-- Left --}}
            <div>
                <div class="section-label">WHY CHOOSE {{ strtoupper($agency->name) }}?</div>
                <h2 class="section-heading">{{ $agency->why_choose_title ?? 'Built for Ambitious Entrepreneurs' }}</h2>
                <p class="section-subheading">Start small, dream big. {{ $agency->name }} grows with you — from launching your first SaaS brand to building a global network.</p>
                <div class="why-features-grid">
                    @php
                        $featColorMap = ['#2563eb','#7c3aed','#059669','#d97706','#db2777','#0891b2'];
                        $featBgMap    = ['#dbeafe','#ede9fe','#d1fae5','#fef3c7','#fce7f3','#cffafe'];
                    @endphp
                    @foreach($features as $fi => $feat)
                        <div class="why-feat-item">
                            <div class="why-feat-icon" style="background:{{ $featBgMap[$fi % count($featBgMap)] }};color:{{ $featColorMap[$fi % count($featColorMap)] }}">
                                <i class="fas fa-{{ $feat['icon'] ?? 'check' }}"></i>
                            </div>
                            <div>
                                <div class="why-feat-title">{{ $feat['title'] }}</div>
                                <div class="why-feat-desc">{{ $feat['desc'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Right image placeholder / about image --}}
            <div class="why-img-wrapper">
                <img src="{{ $aboutImg }}" alt="Built for Entrepreneurs" style="border-radius:18px;box-shadow:0 20px 50px rgba(0,0,0,0.1)">
                <div style="position:absolute;top:-14px;right:-14px;background:var(--brand-gradient);color:#fff;border-radius:14px;padding:14px 18px;box-shadow:0 8px 24px rgba(37,99,235,0.3);font-family:'Space Grotesk',sans-serif;font-size:14px;font-weight:800;animation:floatY 3.5s ease-in-out infinite">
                    Trusted By<br><span style="font-size:20px">10,000+</span><br>Agencies
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ══ PRICING ══ --}}
<section class="pricing-section" id="pricing">
    <div class="container">
        <div style="text-align:center">
            <div class="section-label">PRICING</div>
            <h2 class="section-heading">{{ $pricingSectionTitle }}</h2>
            <p class="section-subheading" style="margin:12px auto 0">{{ $pricingSectionSubtitle }}</p>
        </div>

        {{-- Monthly / Yearly Toggle --}}
        <div class="pricing-toggle-wrap">
            <span class="pricing-toggle-label">Monthly</span>
            <label class="pricing-toggle-switch">
                <input type="checkbox" id="pricing-toggle" onchange="togglePricing(this)">
                <span class="toggle-track"></span>
            </label>
            <span class="pricing-toggle-label">Yearly <span class="pricing-save-badge">Save 10%</span></span>
        </div>

        <div class="pricing-cards-grid">
            @foreach($pricingPlans as $plan)
                <div class="pricing-card">
                    <div class="pricing-left">
                        <div>
                            <div class="pricing-plan-badge" style="background:{{ $plan['plan_badge_bg'] ?? '#dbeafe' }};color:{{ $plan['plan_badge_color'] ?? '#1d4ed8' }}">
                                {{ $plan['plan_badge'] ?? 'PRO PLAN' }}
                            </div>
                            <div class="pricing-plan-name">{{ $plan['plan_name'] ?? $plan['product_name'] }}</div>
                            <div class="pricing-plan-sub">{{ $plan['plan_subtitle'] ?? $plan['product_subtitle'] ?? '' }}</div>
                            <div class="pricing-price-wrap">
                                <div class="pricing-price-main pricing-monthly-price">
                                    ₹{{ number_format($plan['price_monthly'] ?? 999) }}<span>/month</span>
                                </div>
                                <div class="pricing-price-main pricing-yearly-price pricing-price-yearly">
                                    ₹{{ number_format(($plan['price_yearly'] ?? 9999)) }}<span>/year</span>
                                </div>
                            </div>
                        </div>
                        <a href="{{ $plan['cta_url'] ?? $ctaUrl }}" class="btn-pricing" style="background:linear-gradient(135deg,{{ $plan['color'] ?? $primaryColor }},{{ $plan['color'] ?? $secondaryColor }})">
                            {{ $plan['cta_text'] ?? 'Get Started' }} <i class="fas fa-arrow-right" style="font-size:11px"></i>
                        </a>
                    </div>
                    <div class="pricing-right">
                        <ul class="pricing-features-list">
                            @foreach(($plan['features'] ?? []) as $feat)
                                <li class="pricing-feat-item" style="--pfeat-color:{{ $plan['color'] ?? $primaryColor }}">
                                    <i class="fas fa-check-circle" style="color:{{ $plan['color'] ?? $primaryColor }}"></i>
                                    {{ $feat }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ══ GROWTH PATH ══ --}}
<section class="growth-section" id="growth">
    <div class="container">
        <div style="text-align:center">
            <div class="section-label">YOUR GROWTH PATH</div>
            <h2 class="section-heading">From One Business to a Global Brand</h2>
            <p class="section-subheading" style="margin:12px auto 0">Start small, dream big. {{ $agency->name }} grows with you.</p>
        </div>
        <div class="growth-path">
            @foreach($growthPath as $gi => $gStep)
                @if($gi > 0)
                    <div class="growth-arrow"><i class="fas fa-chevron-right"></i></div>
                @endif
                <div class="growth-step">
                    <div class="growth-step-icon">
                        <i class="fas fa-{{ $gStep['icon'] ?? 'flag' }}"></i>
                    </div>
                    <div class="growth-step-label">{{ $gStep['label'] }}</div>
                    <div class="growth-step-desc">{{ $gStep['desc'] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ══ TESTIMONIALS ══ --}}
<section class="testimonials-section" id="testimonials">
    <div class="container">
        <div style="text-align:center">
            <div class="testimonials-label">TRUSTED BY GROWING AGENCIES</div>
            <h2 class="section-heading">What Our Partners Say</h2>
            <p class="section-subheading" style="margin:12px auto 0">Real success stories from real entrepreneurs.</p>
        </div>
        <div class="testimonials-grid">
            @foreach($testimonials as $t)
                <div class="testimonial-card">
                    <div class="test-stars">
                        @for($s=0; $s < ($t['rating'] ?? 5); $s++) ⭐ @endfor
                    </div>
                    <div class="test-quote">{{ $t['comment'] }}</div>
                    <div class="test-author">
                        @if(!empty($t['avatar']))
                            <img src="{{ asset($t['avatar']) }}" style="width:42px;height:42px;border-radius:50%;object-fit:cover;flex-shrink:0" alt="{{ $t['name'] }}">
                        @else
                            <div class="test-avatar-initials">{{ substr($t['name'] ?? 'U', 0, 1) }}</div>
                        @endif
                        <div>
                            <div class="test-name">{{ $t['name'] }}</div>
                            <div class="test-role">{{ $t['role'] }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ══ FAQ ══ --}}
<section class="faq-section" id="faq">
    <div class="container">
        <div class="faq-grid">
            <div class="faq-heading-col">
                <div class="section-label">FAQ</div>
                <h2 class="section-heading">Frequently Asked Questions</h2>
                <p class="section-subheading">Still have questions? We're here to help.</p>
                <a href="{{ $ctaUrl }}" class="btn-hero-primary" style="margin-top:28px;display:inline-flex">
                    View All FAQs <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="faq-list-col">
                @foreach($faqs as $fi => $faq)
                    <div class="faq-item" id="faq-{{ $fi }}">
                        <button class="faq-question" onclick="toggleFaq({{ $fi }})">
                            <span class="faq-q-text">{{ $faq['q'] }}</span>
                            <i class="fas fa-plus faq-icon"></i>
                        </button>
                        <div class="faq-answer">{{ $faq['a'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>


{{-- ══ CTA BANNER ══ --}}
<section class="cta-banner-section">
    <div class="container">
        <div class="cta-banner-inner">
            <div class="cta-banner-text">
                <h2 class="cta-banner-heading">{{ $ctaBannerHeading }}</h2>
                <p class="cta-banner-sub">{{ $ctaBannerSubtext }}</p>
            </div>
            <div class="cta-banner-actions">
                <a href="{{ $ctaUrl }}" class="btn-cta-white">
                    {{ $ctaText }} <i class="fas fa-arrow-right"></i>
                </a>
                <a href="{{ $cta2Url }}" class="btn-cta-outline">
                    <i class="fas fa-calendar"></i> {{ $cta2Text }}
                </a>
            </div>
        </div>
        @if(!empty($ctaBannerSubtext))
            <div style="text-align:center;margin-top:32px">
                <span class="cta-success-text">Your Success Starts Here!</span>
            </div>
        @endif
    </div>
</section>


{{-- ══ FOOTER ══ --}}
<footer class="site-footer" id="footer">
    <div class="container">
        <div class="footer-grid">
            {{-- Brand Col --}}
            <div class="footer-logo-col">
                <div class="footer-logo">
                    @if(!empty($agency->logo))
                        <img src="{{ asset($agency->logo) }}" alt="{{ $agency->name }}" style="filter:brightness(10)">
                    @else
                        <div style="width:32px;height:32px;border-radius:8px;background:var(--brand-gradient);display:flex;align-items:center;justify-content:center">
                            <i class="fas fa-layer-group" style="color:#fff;font-size:14px"></i>
                        </div>
                    @endif
                    <span class="footer-logo-text">{{ $agency->name }}</span>
                </div>
                <p class="footer-tagline">{{ $agency->footer_content ?? 'Your technology partner in building profitable SaaS businesses worldwide.' }}</p>
                <div class="footer-socials">
                    @if($fbUrl && $fbUrl !== '#')
                        <a href="{{ $fbUrl }}" class="footer-social-btn" target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    @endif
                    @if($igUrl && $igUrl !== '#')
                        <a href="{{ $igUrl }}" class="footer-social-btn" target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a>
                    @endif
                    @if($twUrl && $twUrl !== '#')
                        <a href="{{ $twUrl }}" class="footer-social-btn" target="_blank" title="Twitter"><i class="fab fa-x-twitter"></i></a>
                    @endif
                    @if($ytUrl && $ytUrl !== '#')
                        <a href="{{ $ytUrl }}" class="footer-social-btn" target="_blank" title="YouTube"><i class="fab fa-youtube"></i></a>
                    @endif
                    @if($liUrl && $liUrl !== '#')
                        <a href="{{ $liUrl }}" class="footer-social-btn" target="_blank" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    @endif
                </div>
            </div>

            {{-- Products --}}
            <div>
                <div class="footer-col-title">Our Products</div>
                <div class="footer-links">
                    @foreach(array_slice($services, 0, 5) as $svc)
                        <a href="{{ $svc['link'] ?? '#' }}">{{ $svc['title'] }}</a>
                    @endforeach
                </div>
            </div>

            {{-- Company --}}
            <div>
                <div class="footer-col-title">Company</div>
                <div class="footer-links">
                    <a href="/about">About Us</a>
                    <a href="/contact">Contact</a>
                    <a href="#pricing">Pricing</a>
                    <a href="#faq">FAQ</a>
                    <a href="#testimonials">Success Stories</a>
                </div>
            </div>

            {{-- Support --}}
            <div>
                <div class="footer-col-title">Support</div>
                <div class="footer-links">
                    <a href="/privacy-policy">Privacy Policy</a>
                    <a href="/terms-conditions">Terms & Conditions</a>
                    <a href="/refund-policy">Refund Policy</a>
                    <a href="/shipping-policy">Shipping Policy</a>
                    <a href="/contact">Help Center</a>
                </div>
            </div>

            {{-- Subscribe / Contact --}}
            <div>
                <div class="footer-col-title">Subscribe</div>
                <p style="font-size:12.5px;color:#64748b;margin-bottom:14px;line-height:1.6">Get the latest updates and growth tips.</p>
                <form onsubmit="return false;" style="display:flex;gap:8px;margin-bottom:20px">
                    <input type="email" placeholder="Your email" style="flex:1;background:#1e293b;border:1px solid #334155;color:#e2e8f0;padding:10px 14px;border-radius:10px;font-size:12px;outline:none;font-family:'Inter',sans-serif">
                    <button type="submit" style="width:40px;height:40px;border-radius:10px;border:none;cursor:pointer;flex-shrink:0;display:flex;align-items:center;justify-content:center;background:var(--brand-gradient)">
                        <i class="fas fa-paper-plane" style="color:#fff;font-size:13px"></i>
                    </button>
                </form>
                @if(!empty($agency->contact_email))
                    <div style="font-size:12.5px;color:#64748b;display:flex;align-items:center;gap:8px">
                        <i class="fas fa-envelope" style="color:var(--brand-primary)"></i>
                        {{ $agency->contact_email }}
                    </div>
                @endif
                @if(!empty($agency->contact_phone))
                    <div style="font-size:12.5px;color:#64748b;display:flex;align-items:center;gap:8px;margin-top:8px">
                        <i class="fas fa-phone" style="color:var(--brand-primary)"></i>
                        {{ $agency->contact_phone }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Footer Bottom --}}
        <div class="footer-bottom">
            <span>© {{ date('Y') }} {{ $agency->name }}. All rights reserved.</span>
            <div class="footer-bottom-links">
                <a href="/terms-conditions">Terms</a>
                <a href="/privacy-policy">Privacy</a>
            </div>
        </div>
    </div>
</footer>


<script>
    // Mobile menu toggle
    function toggleMobileMenu() {
        const menu = document.getElementById('mobile-menu');
        const icon = document.getElementById('ham-icon');
        menu.classList.toggle('open');
        icon.classList.toggle('fa-bars');
        icon.classList.toggle('fa-times');
    }

    // FAQ accordion
    function toggleFaq(index) {
        const item = document.getElementById('faq-' + index);
        item.classList.toggle('open');
    }

    // Revenue calculator
    const slider    = document.getElementById('rev-slider');
    const countEl   = document.getElementById('calc-count');
    const resultEl  = document.getElementById('rev-result');
    const pricePerCustomer = {{ $revCalc['price_per_customer'] ?? 999 }};
    const currencySymbol   = '{{ $revCalc['currency_symbol'] ?? '₹' }}';

    function formatRevenue(n) {
        if (n >= 100000) return currencySymbol + (n/100000).toFixed(1) + 'L';
        if (n >= 1000)   return currencySymbol + (n/1000).toFixed(0)   + ',000';
        return currencySymbol + n.toLocaleString('en-IN');
    }

    function updateRevCalc() {
        const count   = parseInt(slider.value);
        const revenue = count * pricePerCustomer;
        countEl.textContent = count;
        resultEl.innerHTML  = formatRevenue(revenue) + '<span style="font-size:1rem;font-weight:500;color:#94a3b8">/month</span>';
        const pct = ((count - slider.min) / (slider.max - slider.min)) * 100;
        slider.style.background = `linear-gradient(90deg, var(--brand-primary) ${pct}%, #e2e8f0 ${pct}%)`;
    }

    if (slider) {
        slider.addEventListener('input', updateRevCalc);
        updateRevCalc();
    }

    // Pricing toggle
    function togglePricing(el) {
        const monthly = document.querySelectorAll('.pricing-monthly-price');
        const yearly  = document.querySelectorAll('.pricing-yearly-price');
        if (el.checked) {
            monthly.forEach(m => m.style.display = 'none');
            yearly.forEach(y => { y.style.display = 'block'; });
        } else {
            monthly.forEach(m => m.style.display = 'block');
            yearly.forEach(y => { y.style.display = 'none'; });
        }
    }

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
</script>

</body>
</html>
