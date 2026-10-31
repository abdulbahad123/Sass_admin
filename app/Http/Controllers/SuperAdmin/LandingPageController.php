<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LandingPageController extends Controller
{
    /**
     * Display the public Nooryak.in landing page
     */
    public function show()
    {
        $data = $this->getLandingData();
        return view('landing.index', compact('data'));
    }

    /**
     * Display the Super Admin editor for landing page
     */
    public function edit()
    {
        $data = $this->getLandingData();
        return view('admin.landing.index', compact('data'));
    }

    /**
     * Update landing page settings from admin editor
     */
    public function update(Request $request)
    {
        // 1. Text & Simple String Fields
        $textKeys = [
            'lp_announcement_text',
            'lp_announcement_badge',
            'lp_book_demo_url',
            'lp_cta_button_text',
            'lp_cta_button_url',
            'lp_hero_announcement_badge',
            'lp_hero_title',
            'lp_hero_subtitle',
            'lp_hero_cta1_text',
            'lp_hero_cta1_url',
            'lp_hero_cta2_text',
            'lp_hero_cta2_url',
            'lp_about_tag',
            'lp_about_title',
            'lp_about_desc',
            'lp_products_tag',
            'lp_products_title',
            'lp_products_desc',
            'lp_rev_calc_title',
            'lp_rev_calc_subtitle',
            'lp_rev_calc_price_per_customer',
            'lp_rev_calc_default_customers',
            'lp_how_works_tag',
            'lp_how_works_title',
            'lp_why_choose_tag',
            'lp_why_choose_title',
            'lp_pricing_tag',
            'lp_pricing_title',
            'lp_pricing_badge',
            'lp_pricing_name',
            'lp_pricing_subtitle',
            'lp_pricing_monthly',
            'lp_pricing_yearly',
            'lp_pricing_cta_text',
            'lp_pricing_cta_url',
            'lp_pricing_mockup_badge',
            'lp_pricing_trophy_title',
            'lp_pricing_trophy_desc',
            'lp_pricing_desc',
            'lp_growth_tag',
            'lp_growth_title',
            'lp_growth_subtitle',
            'lp_testimonials_tag',
            'lp_testimonials_title',
            'lp_testimonials_subtitle',
            'lp_faqs_tag',
            'lp_faqs_title',
            'lp_faqs_subtitle',
            'lp_cta_banner_title',
            'lp_cta_banner_subtitle',
            'lp_cta_banner_button1_text',
            'lp_cta_banner_button1_url',
            'lp_cta_banner_button2_text',
            'lp_cta_banner_button2_url',
            'lp_footer_desc',
            'lp_contact_email',
            'lp_contact_phone',
            'lp_copyright_text',
            'lp_fb_url',
            'lp_ig_url',
            'lp_yt_url',
            'lp_li_url',
            'lp_tw_url',
            'lp_meta_title',
            'lp_meta_description',
            'hero_video_url',
            'recaptcha_site_key',
            'recaptcha_secret_key',
            'enable_recaptcha'
        ];

        foreach ($textKeys as $key) {
            if ($request->has($key)) {
                Setting::set($key, $request->input($key));
            }
        }

        // 2. JSON Array Fields
        $jsonKeys = [
            'lp_hero_badges',
            'lp_stats_bar',
            'lp_about_features',
            'lp_model_cards',
            'lp_products_grid',
            'lp_rev_calc_bullets',
            'lp_how_works_steps',
            'lp_why_choose_items',
            'lp_pricing_plans',
            'lp_growth_steps',
            'lp_testimonials_items',
            'lp_faqs_items'
        ];

        // Process multiline raw pricing features and trust badges
        if ($request->has('lp_pricing_features_raw')) {
            $pFeatures = array_filter(array_map('trim', explode("\n", str_replace("\r", "", $request->input('lp_pricing_features_raw')))));
            Setting::set('lp_pricing_features', json_encode(array_values($pFeatures)));
        }
        if ($request->has('lp_pricing_trust_badges_raw')) {
            $pBadges = array_filter(array_map('trim', explode("\n", str_replace("\r", "", $request->input('lp_pricing_trust_badges_raw')))));
            Setting::set('lp_pricing_trust_badges', json_encode(array_values($pBadges)));
        }

        foreach ($jsonKeys as $key) {
            if ($request->has($key)) {
                $val = $request->input($key);
                if ($key === 'lp_model_cards' && is_array($val)) {
                    foreach ($val as &$mc) {
                        if (isset($mc['features_raw'])) {
                            $mc['features'] = array_filter(array_map('trim', explode("\n", str_replace("\r", "", $mc['features_raw']))));
                            unset($mc['features_raw']);
                        }
                    }
                }
                if (is_array($val)) {
                    Setting::set($key, json_encode(array_values($val)));
                } elseif (is_string($val)) {
                    // If JSON string submitted directly
                    Setting::set($key, $val);
                }
            }
        }

        // 3. Image File Uploads
        $imageKeys = [
            'lp_header_logo',
            'lp_header_logo_dark',
            'lp_hero_image',
            'lp_about_image',
            'lp_cta_banner_bg', 'lp_pricing_image'
        ];

        foreach ($imageKeys as $imageKey) {
            if ($request->hasFile($imageKey)) {
                $file = $request->file($imageKey);
                if ($file->isValid()) {
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $destinationPath = public_path('uploads/landing');
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0777, true);
                    }
                    $file->move($destinationPath, $filename);
                    Setting::set($imageKey, 'uploads/landing/' . $filename);
                }
            }
        }

        return redirect()->back()->with('success', 'Landing page content updated successfully!');
    }

    /**
     * Upload an image via AJAX or standalone form
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image_key' => 'required|string',
            'image_file' => extension_loaded('fileinfo') ? 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120' : ['required', 'file', function($attr, $val, $fail) {
                if (!$val) return;
                if ($val->getSize() > 5120 * 1024) { $fail('Image must not exceed 5MB.'); return; }
                $ext = strtolower(pathinfo($val->getClientOriginalName(), PATHINFO_EXTENSION));
                if (!in_array($ext, ['jpeg', 'png', 'jpg', 'gif', 'svg', 'webp'])) { $fail('Image must be an image file (jpeg, png, jpg, gif, svg, webp).'); }
            }],
        ]);

        $key = $request->input('image_key');
        $file = $request->file('image_file');

        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $destinationPath = public_path('uploads/landing');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        $file->move($destinationPath, $filename);
        $path = '/uploads/landing/' . $filename;

        Setting::set($key, $path);

        return response()->json([
            'success' => true,
            'image_url' => asset($path),
            'image_path' => $path,
            'message' => 'Image uploaded successfully'
        ]);
    }

    /**
     * Delete an image and reset its setting to null
     */
    public function deleteImage(Request $request)
    {
        $request->validate([
            'image_key' => 'required|string'
        ]);

        $key = $request->input('image_key');
        $currentPath = Setting::get($key);

        if ($currentPath && file_exists(public_path($currentPath))) {
            @unlink(public_path($currentPath));
        }

        Setting::set($key, null);

        return response()->json([
            'success' => true,
            'message' => 'Image removed successfully'
        ]);
    }

    /**
     * Helper to load all landing settings with default fallback values
     */
    public function getLandingData(): array
    {
        // Auto-upgrade legacy database settings to reference design
        try {
            $curTitle = Setting::get('lp_hero_title');
            if (empty($curTitle) || $curTitle === 'Launch Your Own SaaS Business Under Your Brand' || $curTitle === 'Turn Your Agency Into a SaaS Software Brand') {
                Setting::set('lp_hero_title', 'Turn Your Agency Into a<br><span class="text-[#ff3d00]">SaaS Software Brand</span>');
            }

            $curSub = Setting::get('lp_hero_subtitle');
            if (empty($curSub) || stripos($curSub, 'Nooryak is an All-In-One') !== false) {
                Setting::set('lp_hero_subtitle', 'Turn your experties into your own SaaS business with 3 ready-to-sell white-label products, one unified platform, and your brand across every customer touchpoint. No coding or server management required. You stay in control of your brand while we handle the technology.');
            }

            $curBadge = Setting::get('lp_hero_announcement_badge');
            if (empty($curBadge) || strpos($curBadge, '·') !== false || (stripos($curBadge, 'FOR AGENCIES') !== false && strpos($curBadge, '|') === false)) {
                Setting::set('lp_hero_announcement_badge', 'BUILT FOR AGENCIES | FREELANCERS | RESELLERS | DIGITAL MARKETERS');
            }

            // Stats Bar auto-upgrade (Task 3: 100% WHITE-LABEL SOLUTION & 3 POWERFUL SAAS PRODUCTS)
            $curStats = Setting::get('lp_stats_bar');
            if (!empty($curStats)) {
                $statsArr = json_decode($curStats, true);
                if (is_array($statsArr)) {
                    $stUpdated = false;
                    foreach ($statsArr as &$st) {
                        if (isset($st['label']) && (stripos($st['label'], 'ACTIVE PARTNERS') !== false || stripos($st['label'], 'PARTNERS') !== false)) {
                            $st['count'] = '100%';
                            $st['label'] = 'WHITE-LABEL SOLUTION';
                            $st['icon'] = 'fas fa-shield-alt';
                            $stUpdated = true;
                        }
                        if (isset($st['label']) && stripos($st['label'], 'POWERFUL SAAS PRODUCTS') !== false) {
                            $st['count'] = '3';
                            $stUpdated = true;
                        }
                    }
                    if ($stUpdated) {
                        Setting::set('lp_stats_bar', json_encode($statsArr));
                    }
                }
            }

            $curAboutTag = Setting::get('lp_about_tag');
            if (empty($curAboutTag) || stripos($curAboutTag, 'NOORYAK') !== false) {
                Setting::set('lp_about_tag', 'ABOUT SAASRESELLING');
            }

            $curAboutTitle = Setting::get('lp_about_title');
            if (empty($curAboutTitle) || stripos($curAboutTitle, 'SaaS Success') !== false) {
                Setting::set('lp_about_title', 'Your Partner in <br><span class="text-[#ff3d00]">SaaS Growth</span>');
            }

            $curAboutDesc = Setting::get('lp_about_desc');
            $curGrowthSub = Setting::get('lp_growth_subtitle');
            if (empty($curGrowthSub) || stripos($curGrowthSub, 'Nooryak') !== false) {
                Setting::set('lp_growth_subtitle', 'Start small, dream big. SaaSReselling grows with you at every stage.');
            }
            if (empty($curAboutDesc) || stripos($curAboutDesc, 'Nooryak is an All-In-One') !== false) {
                Setting::set('lp_about_desc', 'SaaSReselling helps agencies, freelancers, and entrepreneurs launch their own software brand with 3 ready-to-sell white-label solutions. Manage your offerings through one unified platform, with your own branding and customer experience—without the complexity of building or maintaining the technology yourself.');
            }

            // Products tag & description auto-upgrade (Task 5)
            $curProdTag = Setting::get('lp_products_tag');
            if (empty($curProdTag) || $curProdTag === 'OUR PRODUCTS') {
                Setting::set('lp_products_tag', 'Our Advanced SAAS Products');
            }

            $curProdDesc = Setting::get('lp_products_desc');
            if (empty($curProdDesc) || stripos($curProdDesc, 'Nooryak') !== false || stripos($curProdDesc, '5 powerful') !== false) {
                Setting::set('lp_products_desc', 'SaaSReselling combines 3 powerful White Label SaaS products, a unified dashboard, Single Sign-On (SSO), custom branding, and enterprise-grade management tools into one complete platform.');
            }

            // Clean up other legacy Nooryak defaults in settings
            $curHowTag = Setting::get('lp_how_works_tag');
            if ($curHowTag === 'HOW NOORYAK WORKS') {
                Setting::set('lp_how_works_tag', 'HOW SAASRESELLING WORKS');
            }
            $curPriceCust = Setting::get('lp_rev_calc_price_per_customer');
            if (empty($curPriceCust) || $curPriceCust == 499) {
                Setting::set('lp_rev_calc_price_per_customer', 999);
            }
            $curDefaultCust = Setting::get('lp_rev_calc_default_customers');
            if (empty($curDefaultCust) || $curDefaultCust == 100 || $curDefaultCust == 10) {
                Setting::set('lp_rev_calc_default_customers', 1);
            }
            $curCtaSub = Setting::get('lp_cta_banner_subtitle');
            if (!empty($curCtaSub) && stripos($curCtaSub, 'Nooryak') !== false) {
                Setting::set('lp_cta_banner_subtitle', str_ireplace('Nooryak', 'SaaSReselling', $curCtaSub));
            }
            $curWhyTag = Setting::get('lp_why_choose_tag');
            if ($curWhyTag === 'WHY CHOOSE NOORYAK?') {
                Setting::set('lp_why_choose_tag', 'WHY CHOOSE SAASRESELLING?');
            }
            $defaultFaqs = [
                ['question' => '1. WHAT IS SAASRESELLING?', 'answer' => 'SaaSReselling is an All-In-One White Label SaaS Platform that empowers agencies, freelancers, and entrepreneurs to launch, market, and scale their own branded software business. You get access to 3 turnkey, high-demand SaaS products that you can sell to unlimited clients under your own custom domain, brand identity, and pricing—keeping 100% of your subscription revenue without managing any backend code or servers.'],
                ['question' => '2. WHAT SAAS PRODUCTS ARE INCLUDED?', 'answer' => "SaaSReselling includes 3 powerful SaaS products ready to sell immediately under your brand:\n• Ecom Builder (Launchshop): Complete e-commerce store platform with product catalogs, shopping cart, online payment gateways, and order management.\n• Website Builder: Dynamic drag-and-drop website and sales funnel builder with responsive designer templates and fast hosting.\n• WhatsApp Bulk Automation: Enterprise-grade WhatsApp marketing suite for bulk broadcasting, automated campaigns, chatbot flows, and contact list management."],
                ['question' => '3. CAN I LAUNCH THE PLATFORM UNDER MY OWN BRAND?', 'answer' => 'Yes, 100%. The entire platform is completely white-labeled. Your clients will only see your custom domain, your logo, your company colors, and your branding across all dashboards, client portals, system emails, and notification templates. There is zero reference to SaaSReselling anywhere.'],
                ['question' => '4. CAN I USE MY OWN CUSTOM DOMAIN?', 'answer' => 'Yes! You can connect your own domain (e.g. app.youragency.com or yourbrand.com) with automated free SSL certificate provisioning. All client login URLs, storefronts, and automated links will operate completely under your verified domain name.'],
                ['question' => '5. CAN I SELL UNLIMITED SUBSCRIPTIONS TO MY CUSTOMERS?', 'answer' => 'Yes. You have the freedom to onboard and sell to unlimited businesses and clients. You establish your own pricing packages (monthly, annual, or one-time setup fees) and collect 100% of customer payments directly through your own payment gateway or bank account.'],
                ['question' => '6. DO I NEED CODING OR TECHNICAL KNOWLEDGE TO START?', 'answer' => 'Not at all. SaaSReselling is fully turnkey and zero-code. Our engineering team handles all server hosting, infrastructure scaling, database backups, security maintenance, and software updates so you can focus 100% on marketing, sales, and client relationships.'],
                ['question' => '7. WHAT IS THE WHITE LABEL SAAS PARTNER MODEL?', 'answer' => 'The White Label SaaS Partner Model allows you to license a ready-made, fully developed software ecosystem as your own intellectual property. Instead of spending months and millions of rupees on engineering, you launch immediately with verified product-market fit and generate predictable recurring monthly revenue (MRR).'],
                ['question' => '8. CAN I CREATE AND MANAGE MULTIPLE WHITE LABEL PARTNERS?', 'answer' => 'Yes! You have full administrative control to provision, monitor, manage, and configure client workspaces and user accounts directly from your central administrative dashboard.'],
                ['question' => '9. HOW DO I GET STARTED WITH SAASRESELLING?', 'answer' => 'Getting started takes less than 24 hours: choose your plan, book your 1:1 onboarding walkthrough, connect your custom domain and brand assets, and your white-labeled SaaS suite will be launched and ready to onboard paying customers immediately!']
            ];
            $curFaqs = Setting::get('lp_faqs_items');
            if (empty($curFaqs) || stripos($curFaqs, 'Ecom Builder') === false || stripos($curFaqs, 'Nooryak') !== false || stripos($curFaqs, 'WHAT IS SAAS YAARI') !== false) {
                Setting::set('lp_faqs_items', json_encode($defaultFaqs));
            }
            $curTestTitle = Setting::get('lp_testimonials_title');
            if (empty($curTestTitle) || $curTestTitle === 'What Our Partners Say') {
                Setting::set('lp_testimonials_title', 'WHAT OUR WHITE LABEL PARTNERS SAY');
            }
            $curTestimonials = Setting::get('lp_testimonials_items');
            $genuinePartners = [
                [
                    'name' => 'SaaSReselling Agency Network',
                    'domain' => 'saasreselling.in',
                    'url' => 'https://saasreselling.in',
                    'role' => 'White Label Agency Portal',
                    'location' => 'Bangalore',
                    'badge' => 'Official White Label Agency',
                    'icon' => 'fas fa-shield-alt',
                    'color' => '#10b981',
                    'quote' => 'Operating on saasreselling.in with the full white-label agency architecture proves how powerful this ecosystem is. Delivering 3 turnkey software solutions—E-commerce Builder, Dynamic Website Builder, and WhatsApp Automation—under your own domain with full SSO and client management is the fastest way to build real enterprise value.'
                ],
                [
                    'name' => 'White Label SaaS Agency',
                    'domain' => 'saasreselling.in',
                    'url' => 'https://saasreselling.in',
                    'role' => 'Digital Tech & Automation Agency',
                    'location' => 'Mumbai',
                    'badge' => 'Active White Label Partner',
                    'icon' => 'fas fa-laptop-code',
                    'color' => '#3b82f6',
                    'quote' => 'Selling all 3 products—Launchshop E-commerce, Website Builder, and WhatsApp Bulk Automation—under our custom domain has completely transformed our business model. Our clients log in with our custom branding, and we keep 100% of our recurring subscription revenue.'
                ],
                [
                    'name' => 'Cloud Reseller Partner',
                    'domain' => 'saasreselling.in',
                    'url' => 'https://saasreselling.in',
                    'role' => 'Software & Growth Solutions',
                    'location' => 'Delhi',
                    'badge' => 'Verified Agency Reseller',
                    'icon' => 'fas fa-layer-group',
                    'color' => '#8b5cf6',
                    'quote' => 'Instead of spending months developing software from scratch, SaaSReselling gave us a complete, ready-to-sell 3-in-1 software suite. Our clients love building stores and running bulk campaigns on our portal, and automated client provisioning lets us focus purely on growth.'
                ]
            ];
            if (empty($curTestimonials) || stripos($curTestimonials, 'cockroach') !== false || stripos($curTestimonials, 'youverse') !== false || stripos($curTestimonials, 'bullionx') !== false || stripos($curTestimonials, 'nooryak') !== false || stripos($curTestimonials, 'tapnow') !== false) {
                Setting::set('lp_testimonials_items', json_encode($genuinePartners));
            }
        } catch (\Exception $e) {
            // Safe fallback if DB is temporarily unreachable
        }

        $rawPImg = Setting::get('lp_pricing_image');
        $cleanPImg = (!empty($rawPImg)) ? ltrim($rawPImg, '/') : 'assets/images/user_dashboard.png';
        if (!file_exists(public_path($cleanPImg)) && !file_exists(public_path('assets/images/user_dashboard.png'))) {
            $cleanPImg = 'assets/landing_page/herobanner_dashboard.png';
        }

        return [
            // Security & Integration
            'recaptcha_site_key' => Setting::get('recaptcha_site_key') ?: '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI',
            'recaptcha_secret_key',
            'enable_recaptcha' => Setting::get('recaptcha_secret_key',
            'enable_recaptcha', ''),
            'hero_video_url' => Setting::get('hero_video_url', 'https://www.youtube.com/embed/dQw4w9WgXcQ'),
            'has_custom_pricing_image' => (!empty($rawPImg) && !str_contains($rawPImg, 'user_dashboard.png')),
            'lp_pricing_image' => $cleanPImg,
            // Announcement & Top Bar
            'lp_announcement_text' => Setting::get('lp_announcement_text', 'YOUR BRAND. OUR TECHNOLOGY. UNLIMITED GROWTH.'),
            'lp_announcement_badge' => Setting::get('lp_announcement_badge', 'NEW'),
            'lp_header_logo' => Setting::get('lp_header_logo', '/assets/images/Logo2.png'),
            'lp_header_logo_dark' => Setting::get('lp_header_logo_dark', null),
            'lp_book_demo_url' => Setting::get('lp_book_demo_url', '#book-demo'),
            'lp_cta_button_text' => Setting::get('lp_cta_button_text', 'Start Your SaaS Business'),
            'lp_cta_button_url' => Setting::get('lp_cta_button_url', '/login'),

            // Hero
            'lp_hero_announcement_badge' => Setting::get('lp_hero_announcement_badge', 'BUILT FOR AGENCIES | FREELANCERS | RESELLERS | DIGITAL MARKETERS'),
            'lp_hero_title' => Setting::get('lp_hero_title', 'Turn Your Agency Into a <br><span class="text-[#ff3d00]">SaaS Software Brand</span>'),
            'lp_hero_subtitle' => Setting::get('lp_hero_subtitle', 'Turn your experties into your own SaaS business with 3 ready-to-sell white-label products, one unified platform, and your brand across every customer touchpoint. No coding or server management required. You stay in control of your brand while we handle the technology.'),
            'lp_hero_cta1_text' => Setting::get('lp_hero_cta1_text', 'Start Your SaaS Business'),
            'lp_hero_cta1_url' => Setting::get('lp_hero_cta1_url', 'https://wa.me/916374913298?text=I%20am%20interested%20on%20that%20business'),
            'lp_hero_cta2_text' => Setting::get('lp_hero_cta2_text', 'Book Live Demo'),
            'lp_hero_cta2_url' => Setting::get('lp_hero_cta2_url', 'https://wa.me/916374913298?text=I%20want%20the%20demo%20explaination'),
            'lp_hero_badges' => json_decode(Setting::get('lp_hero_badges', json_encode([
                'White Label Ready',
                'Custom Branding',
                'Unlimited Customers'
            ])), true),
            'lp_hero_image' => Setting::get('lp_hero_image', '/assets/images/herobanner_right.png'),

            // Stats Bar
            'lp_stats_bar' => json_decode(Setting::get('lp_stats_bar', json_encode([
                ['count' => '100%', 'label' => 'WHITE-LABEL SOLUTION', 'icon' => 'fas fa-shield-alt'],
                ['count' => '50K+', 'label' => 'BUSINESSES SERVED', 'icon' => 'fas fa-building'],
                ['count' => '3', 'label' => 'POWERFUL SAAS PRODUCTS', 'icon' => 'fas fa-cube'],
                ['count' => '99.9%', 'label' => 'UPTIME GUARANTEE', 'icon' => 'fas fa-shield-alt'],
                ['count' => '24/7', 'label' => 'EXPERT SUPPORT', 'icon' => 'fas fa-headset']
            ])), true),

            // About (Task 3: Reference Image Design)
            'lp_about_tag' => Setting::get('lp_about_tag', 'ABOUT SAASRESELLING'),
            'lp_about_title' => Setting::get('lp_about_title', 'Your Partner in <br><span class="text-[#ff3d00]">SaaS Growth</span>'),
            'lp_about_desc' => Setting::get('lp_about_desc', 'SaaSReselling helps agencies, freelancers, and entrepreneurs launch their own software brand with 3 ready-to-sell white-label solutions. Manage your offerings through one unified platform, with your own branding and customer experience—without the complexity of building or maintaining the technology yourself.'),
            'lp_about_tagline' => Setting::get('lp_about_tagline', 'Your Brand. Our Technology. Built for Recurring Revenue.'),
            'lp_about_image' => Setting::get('lp_about_image', '/assets/images/about_right.png'),
            'lp_about_features' => json_decode(Setting::get('lp_about_features', json_encode([
                ['title' => 'Trusted Technology', 'icon' => 'fas fa-shield-halved'],
                ['title' => 'Scalable Platform', 'icon' => 'fas fa-chart-line'],
                ['title' => 'Built for Entrepreneurs', 'icon' => 'fas fa-award'],
                ['title' => 'Unlimited Growth', 'icon' => 'fas fa-bolt']
            ])), true),

            // Live Products from Super Admin Catalog (Task 3)
            'db_products' => \App\Models\Product::where('is_active', true)->get(),

            // Model Cards (Task 2: Removed Master Panel Container)
            'lp_model_cards' => json_decode(Setting::get('lp_model_cards', json_encode([
                [
                    'badge' => 'White Label SaaS',
                    'title' => 'White Label SaaS Partner',
                    'desc' => 'Launch your own branded SaaS platform and sell subscriptions directly to business owners.',
                    'image' => '/assets/images/user_dashboard.png',
                    'features' => [
                        'Launch Your Own SaaS Brand',
                        'Sell Unlimited Subscriptions',
                        '3 SaaS Products Included',
                        'Manage Your Customers & Business',
                        'Custom Domain & Branding',
                        'Build Recurring Revenue'
                    ],
                    'cta_text' => 'Start with White Label SaaS',
                    'cta_url' => '/login'
                ]
            ])), true),

            // Products Grid
            'lp_products_tag' => Setting::get('lp_products_tag', 'Our Advanced SAAS Products'),
            'lp_products_title' => Setting::get('lp_products_title', 'Everything You Need to Build & Scale Your SaaS Business'),
            'lp_products_desc' => Setting::get('lp_products_desc', 'SaaSReselling combines 3 powerful White Label SaaS products, a unified dashboard, Single Sign-On (SSO), custom branding, and enterprise-grade management tools into one complete platform.'),
            'lp_products_grid' => json_decode(Setting::get('lp_products_grid', json_encode([
                [
                    'title' => 'AI Reviews & GMB Automation',
                    'desc' => 'Generate reviews, automate replies and manage your online reputation.',
                    'icon' => 'fas fa-star',
                    'color' => '#10b981',
                    'link' => '/login'
                ],
                [
                    'title' => 'AI Single Page Website Builder',
                    'desc' => 'Create stunning websites in minutes with AI-powered content generation.',
                    'icon' => 'fas fa-globe',
                    'color' => '#3b82f6',
                    'link' => '/login'
                ],
                [
                    'title' => 'Restaurant QR Menu & Order Management',
                    'desc' => 'Digitize menus, take orders and manage your restaurant operations effortlessly.',
                    'icon' => 'fas fa-utensils',
                    'color' => '#f59e0b',
                    'link' => '/login'
                ],
                [
                    'title' => 'Digital V-Card & NFC',
                    'desc' => 'Share your contact details instantly with smart digital business cards.',
                    'icon' => 'fas fa-address-card',
                    'color' => '#ec4899',
                    'link' => '/login'
                ],
                [
                    'title' => 'Loyalty & Rewards Platform',
                    'desc' => 'Increase repeat customers with digital loyalty programs and rewards.',
                    'icon' => 'fas fa-gift',
                    'color' => '#8b5cf6',
                    'link' => '/login'
                ]
            ])), true),

            // Revenue Calculator
            'lp_rev_calc_title' => Setting::get('lp_rev_calc_title', 'Revenue Opportunity Calculator'),
            'lp_rev_calc_subtitle' => Setting::get('lp_rev_calc_subtitle', 'See how much you can earn every month'),
            'lp_rev_calc_price_per_customer' => (int) Setting::get('lp_rev_calc_price_per_customer', 999),
            'lp_rev_calc_default_customers' => (int) Setting::get('lp_rev_calc_default_customers', 1),
            'lp_rev_calc_bullets' => json_decode(Setting::get('lp_rev_calc_bullets', json_encode([
                'Low Investment',
                'High Margin',
                'Recurring Income',
                'Unlimited Potential'
            ])), true),

            // How It Works
            'lp_how_works_tag' => Setting::get('lp_how_works_tag', 'HOW SAASRESELLING WORKS'),
            'lp_how_works_title' => Setting::get('lp_how_works_title', 'Launch, Brand, Sell, Earn, Repeat.'),
            'lp_how_works_steps' => json_decode(Setting::get('lp_how_works_steps', json_encode([
                ['step' => '1', 'title' => 'Choose Your Model', 'desc' => 'Pick White Label SaaS or Master Panel and get started.', 'icon' => 'fas fa-mouse-pointer'],
                ['step' => '2', 'title' => 'Launch Under Your Brand', 'desc' => 'We set everything up with your brand, domain & logo.', 'icon' => 'fas fa-magic'],
                ['step' => '3', 'title' => 'Sell & Onboard Customers', 'desc' => 'Sell subscriptions or White Label SaaS Panels to your customers.', 'icon' => 'fas fa-users'],
                ['step' => '4', 'title' => 'Earn Recurring Revenue', 'desc' => 'Get paid monthly and scale your SaaS business.', 'icon' => 'fas fa-chart-bar']
            ])), true),

            // Why Choose Nooryak
            'lp_why_choose_tag' => Setting::get('lp_why_choose_tag', 'WHY CHOOSE SAASRESELLING?'),
            'lp_why_choose_title' => Setting::get('lp_why_choose_title', 'Built for Ambitious Entrepreneurs'),
            'lp_why_choose_items' => json_decode(Setting::get('lp_why_choose_items', json_encode([
                ['title' => '100% White Label', 'desc' => 'Your brand, your identity. We stay behind the scenes.', 'icon' => 'fas fa-user-shield'],
                ['title' => 'White Label Ready', 'desc' => 'Custom logos, domain, and branding.', 'icon' => 'fas fa-layer-group'],
                ['title' => 'Launch Fast', 'desc' => 'Go live in days, not months.', 'icon' => 'fas fa-rocket'],
                ['title' => 'Scalable Ecosystem', 'desc' => 'Grow from smaller to network builder.', 'icon' => 'fas fa-sitemap'],
                ['title' => 'Recurring Revenue', 'desc' => 'Build predictable monthly income.', 'icon' => 'fas fa-coins'],
                ['title' => 'Dedicated Support', 'desc' => 'With your technology partner, always.', 'icon' => 'fas fa-headset']
            ])), true),

            // Pricing (Task 3: Reference Image 3 Design with Admin Controls)
            'lp_pricing_tag' => Setting::get('lp_pricing_tag', 'PRICING'),
            'lp_pricing_title' => Setting::get('lp_pricing_title', 'Transparent Pricing for Every Stage'),
            'lp_pricing_badge' => Setting::get('lp_pricing_badge', 'FOR AGENCIES & FREELANCERS'),
            'lp_pricing_name' => Setting::get('lp_pricing_name', 'White-Label Panel'),
            'lp_pricing_subtitle' => Setting::get('lp_pricing_subtitle', 'One platform. Your brand. Unlimited growth.'),
            'lp_pricing_desc' => Setting::get('lp_pricing_desc', 'Get 3 core SaaS products, unlimited client subscriptions, and complete white-label control under your own domain. Best for solo marketers and growth agencies.'),
            'lp_pricing_monthly' => in_array(Setting::get('lp_pricing_monthly'), ['999', '', null]) ? '4,000' : Setting::get('lp_pricing_monthly'),
            'lp_pricing_yearly' => in_array(Setting::get('lp_pricing_yearly'), ['799', '', null]) ? '35,000' : Setting::get('lp_pricing_yearly'),
            'lp_pricing_cta_text' => Setting::get('lp_pricing_cta_text', 'Get Started'),
            'lp_pricing_cta_url' => Setting::get('lp_pricing_cta_url', 'https://wa.me/916374913298?text=I%20am%20interested%20in%20White-Label%20Panel'),
            'lp_pricing_mockup_badge' => Setting::get('lp_pricing_mockup_badge', '3 Powerful Products / One Dashboard / Your Brand'),
            'lp_pricing_image' => $cleanPImg,
            'lp_pricing_trophy_title' => Setting::get('lp_pricing_trophy_title', 'Most Popular Choice'),
            'lp_pricing_trophy_desc' => Setting::get('lp_pricing_trophy_desc', 'Perfect for getting started and building your customer base.'),
            'lp_pricing_features' => json_decode(Setting::get('lp_pricing_features', json_encode([
                '1 White-Label Platform',
                'Unlimited End-Customers',
                '3 White-Label SaaS Products Included',
                'Full Partner Dashboard Access',
                'Custom Domain & Logo',
                'Lifetime Updates & Support'
            ])), true),
            'lp_pricing_trust_badges' => json_decode(Setting::get('lp_pricing_trust_badges', json_encode([
                '1:1 Demo on Google Meet',
                'No Credit Card',
                '100% Free Demo'
            ])), true),

            // Growth Path (Task 3: High-Converting Timeline UI & Increased Typography)
            'lp_growth_tag' => Setting::get('lp_growth_tag', 'YOUR GROWTH PATH'),
            'lp_growth_title' => Setting::get('lp_growth_title', 'From One Business to a Global Brand'),
            'lp_growth_subtitle' => (stripos(Setting::get('lp_growth_subtitle', ''), 'Nooryak') !== false || empty(Setting::get('lp_growth_subtitle')))
                ? 'Start small, dream big. SaaSReselling grows with you at every stage.'
                : Setting::get('lp_growth_subtitle'),
            'lp_growth_steps' => json_decode(Setting::get('lp_growth_steps', json_encode([
                [
                    'step' => '01 • START',
                    'title' => 'Launch Your Own SaaS',
                    'desc' => 'Get your white-label portal ready in 24 hours with 3 high-converting SaaS products.',
                    'icon' => 'fas fa-rocket',
                    'badge' => 'Day 1'
                ],
                [
                    'step' => '02 • GROW',
                    'title' => 'Add Paying Customers',
                    'desc' => 'Onboard local business clients, charge your own prices, and keep 100% of subscription revenue.',
                    'icon' => 'fas fa-chart-line',
                    'badge' => 'Scale Up'
                ],
                [
                    'step' => '03 • EXPAND',
                    'title' => 'Build Reseller Network',
                    'desc' => 'Upgrade to Master Panel to create sub-agency partners and unlock passive recurring overrides.',
                    'icon' => 'fas fa-users-gear',
                    'badge' => 'Agency Network'
                ],
                [
                    'step' => '04 • GLOBAL',
                    'title' => 'Scale to a Global Brand',
                    'desc' => 'Automate your software distribution and dominate new regional & international markets.',
                    'icon' => 'fas fa-globe',
                    'badge' => 'Global Leader'
                ]
            ])), true),

            // Testimonials
            'lp_testimonials_tag' => Setting::get('lp_testimonials_tag', 'TRUSTED BY GROWING AGENCIES'),
            'lp_testimonials_title' => Setting::get('lp_testimonials_title', 'WHAT OUR WHITE LABEL PARTNERS SAY'),
            'lp_testimonials_subtitle' => Setting::get('lp_testimonials_subtitle', 'Real success stories from real entrepreneurs.'),
            'lp_testimonials_items' => json_decode(Setting::get('lp_testimonials_items', json_encode($genuinePartners)), true),

            // FAQ
            'lp_faqs_tag' => Setting::get('lp_faqs_tag', 'FAQ'),
            'lp_faqs_title' => Setting::get('lp_faqs_title', 'Frequently Asked Questions'),
            'lp_faqs_subtitle' => Setting::get('lp_faqs_subtitle', 'Still have questions? We\'re here to help.'),
            'lp_faqs_items' => json_decode(Setting::get('lp_faqs_items', json_encode($defaultFaqs)), true),

            // CTA Banner & Footer
            'lp_cta_banner_title' => Setting::get('lp_cta_banner_title', 'Ready to Launch Your SaaS Business?'),
            'lp_cta_banner_subtitle' => str_ireplace(['Nooryak.in', 'nooryak.in', 'Nooryak', 'nooryak'], ['saasreselling.com', 'saasreselling.com', 'SaaSReselling', 'SaaSReselling'], Setting::get('lp_cta_banner_subtitle', 'Join hundreds of partners who are already building recurring revenue with SaaSReselling.')),
            'lp_cta_banner_button1_text' => Setting::get('lp_cta_banner_button1_text', 'Book Free 1:1 Live Demo'),
            'lp_cta_banner_button1_url' => Setting::get('lp_cta_banner_button1_url', '#book-demo'),
            'lp_cta_banner_button2_text' => Setting::get('lp_cta_banner_button2_text', 'See How It Works'),
            'lp_cta_banner_button2_url' => Setting::get('lp_cta_banner_button2_url', '#how-it-works'),
            'lp_cta_banner_bg' => Setting::get('lp_cta_banner_bg', '/images/revenue_calculator.png'),
            'lp_footer_desc' => Setting::get('lp_footer_desc', 'Your technology partner in building profitable white-label SaaS businesses.'),
            'lp_contact_email' => Setting::get('lp_contact_email', 'support@saasreselling.com'),
            'lp_contact_phone' => Setting::get('lp_contact_phone', '+91 63749 13298'),
            'lp_copyright_text' => str_ireplace(['Nooryak Technologies', 'Nooryak', 'nooryak'], ['saasreselling', 'saasreselling', 'saasreselling'], Setting::get('lp_copyright_text', '© 2026 saasreselling. All rights reserved.')),
            'lp_fb_url' => Setting::get('lp_fb_url', '#'),
            'lp_ig_url' => Setting::get('lp_ig_url', '#'),
            'lp_yt_url' => Setting::get('lp_yt_url', '#'),
            'lp_li_url' => Setting::get('lp_li_url', '#'),
            'lp_tw_url' => Setting::get('lp_tw_url', '#'),
            'lp_meta_title' => Setting::get('lp_meta_title', 'SaaSReselling - Launch Your Own SaaS Business Under Your Brand'),
            'lp_meta_description' => Setting::get('lp_meta_description', 'SaaSReselling is an All-In-One White Label SaaS Platform that helps agencies, freelancers, and entrepreneurs launch their own SaaS business.'),
            'hero_video_url' => Setting::get('hero_video_url', 'https://www.youtube.com/embed/dQw4w9WgXcQ'),
            'recaptcha_site_key' => Setting::get('recaptcha_site_key') ?: '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI',
            'recaptcha_secret_key',
            'enable_recaptcha' => Setting::get('recaptcha_secret_key',
            'enable_recaptcha', '')
        ];
    }
}
