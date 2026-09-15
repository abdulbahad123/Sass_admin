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
            'lp_meta_description'
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
            'lp_hero_image',
            'lp_about_image',
            'lp_cta_banner_bg'
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
                    Setting::set($imageKey, '/uploads/landing/' . $filename);
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
            'image_file' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
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
    private function getLandingData(): array
    {
        return [
            // Announcement & Top Bar
            'lp_announcement_text' => Setting::get('lp_announcement_text', 'YOUR BRAND. OUR TECHNOLOGY. UNLIMITED GROWTH.'),
            'lp_announcement_badge' => Setting::get('lp_announcement_badge', 'NEW'),
            'lp_header_logo' => Setting::get('lp_header_logo', '/assets/images/Logo2.png'),
            'lp_book_demo_url' => Setting::get('lp_book_demo_url', '#book-demo'),
            'lp_cta_button_text' => Setting::get('lp_cta_button_text', 'Start Your SaaS Business'),
            'lp_cta_button_url' => Setting::get('lp_cta_button_url', '/login'),

            // Hero
            'lp_hero_title' => Setting::get('lp_hero_title', 'Launch Your Own SaaS Business Under Your Brand'),
            'lp_hero_subtitle' => Setting::get('lp_hero_subtitle', 'Nooryak is an All-In-One White Label SaaS Platform that helps agencies, freelancers, IT companies and entrepreneurs launch their own SaaS business with 5 powerful products, custom branding and complete white-label control.'),
            'lp_hero_cta1_text' => Setting::get('lp_hero_cta1_text', 'Start Your SaaS Business'),
            'lp_hero_cta1_url' => Setting::get('lp_hero_cta1_url', '/login'),
            'lp_hero_cta2_text' => Setting::get('lp_hero_cta2_text', 'Book Live Demo'),
            'lp_hero_cta2_url' => Setting::get('lp_hero_cta2_url', '#book-demo'),
            'lp_hero_badges' => json_decode(Setting::get('lp_hero_badges', json_encode([
                'White Label Ready',
                'Custom Branding',
                'Unlimited Customers'
            ])), true),
            'lp_hero_image' => Setting::get('lp_hero_image', '/assets/images/herobanner_right.png'),

            // Stats Bar
            'lp_stats_bar' => json_decode(Setting::get('lp_stats_bar', json_encode([
                ['count' => '500+', 'label' => 'Active Partners', 'icon' => 'fas fa-users'],
                ['count' => '50K+', 'label' => 'Businesses Served', 'icon' => 'fas fa-city'],
                ['count' => '5', 'label' => 'Powerful SaaS Products', 'icon' => 'fas fa-cube'],
                ['count' => '99.9%', 'label' => 'Uptime Guarantee', 'icon' => 'fas fa-shield-alt'],
                ['count' => '24/7', 'label' => 'Expert Support', 'icon' => 'fas fa-headset']
            ])), true),

            // About
            'lp_about_tag' => Setting::get('lp_about_tag', 'ABOUT NOORYAK'),
            'lp_about_title' => Setting::get('lp_about_title', 'Your Partner in SaaS Success'),
            'lp_about_desc' => Setting::get('lp_about_desc', 'Nooryak is an All-In-One White Label SaaS Platform built for agencies, freelancers, IT companies and entrepreneurs who want to launch their own SaaS business under their own brand. With 5 powerful White Label SaaS products, a unified dashboard, custom branding and complete white-label control, we provide everything you need to build a scalable business and generate recurring revenue.'),
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
                        'SaaS Products Included',
                        'Manage Your Customers & Business',
                        'Custom Domain & Branding',
                        'Build Recurring Revenue'
                    ],
                    'cta_text' => 'Start with White Label SaaS',
                    'cta_url' => '/login'
                ]
            ])), true),

            // Products Grid
            'lp_products_tag' => Setting::get('lp_products_tag', 'OUR PRODUCTS'),
            'lp_products_title' => Setting::get('lp_products_title', 'Everything You Need to Build & Scale Your SaaS Business'),
            'lp_products_desc' => Setting::get('lp_products_desc', 'Nooryak combines 5 powerful White Label SaaS products, a unified dashboard, Single Sign-On (SSO), custom branding, and enterprise-grade management tools into one complete platform.'),
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
            'lp_rev_calc_default_customers' => (int) Setting::get('lp_rev_calc_default_customers', 100),
            'lp_rev_calc_bullets' => json_decode(Setting::get('lp_rev_calc_bullets', json_encode([
                'Low Investment',
                'High Margin',
                'Recurring Income',
                'Unlimited Potential'
            ])), true),

            // How It Works
            'lp_how_works_tag' => Setting::get('lp_how_works_tag', 'HOW NOORYAK WORKS'),
            'lp_how_works_title' => Setting::get('lp_how_works_title', 'Launch, Brand, Sell, Earn, Repeat.'),
            'lp_how_works_steps' => json_decode(Setting::get('lp_how_works_steps', json_encode([
                ['step' => '1', 'title' => 'Choose Your Model', 'desc' => 'Pick White Label SaaS or Master Panel and get started.', 'icon' => 'fas fa-mouse-pointer'],
                ['step' => '2', 'title' => 'Launch Under Your Brand', 'desc' => 'We set everything up with your brand, domain & logo.', 'icon' => 'fas fa-magic'],
                ['step' => '3', 'title' => 'Sell & Onboard Customers', 'desc' => 'Sell subscriptions or White Label SaaS Panels to your customers.', 'icon' => 'fas fa-users'],
                ['step' => '4', 'title' => 'Earn Recurring Revenue', 'desc' => 'Get paid monthly and scale your SaaS business.', 'icon' => 'fas fa-chart-bar']
            ])), true),

            // Why Choose Nooryak
            'lp_why_choose_tag' => Setting::get('lp_why_choose_tag', 'WHY CHOOSE NOORYAK?'),
            'lp_why_choose_title' => Setting::get('lp_why_choose_title', 'Built for Ambitious Entrepreneurs'),
            'lp_why_choose_items' => json_decode(Setting::get('lp_why_choose_items', json_encode([
                ['title' => '100% White Label', 'desc' => 'Your brand, your identity. We stay behind the scenes.', 'icon' => 'fas fa-user-shield'],
                ['title' => 'White Label Ready', 'desc' => 'Custom logos, domain, and branding.', 'icon' => 'fas fa-layer-group'],
                ['title' => 'Launch Fast', 'desc' => 'Go live in days, not months.', 'icon' => 'fas fa-rocket'],
                ['title' => 'Scalable Ecosystem', 'desc' => 'Grow from smaller to network builder.', 'icon' => 'fas fa-sitemap'],
                ['title' => 'Recurring Revenue', 'desc' => 'Build predictable monthly income.', 'icon' => 'fas fa-coins'],
                ['title' => 'Dedicated Support', 'desc' => 'With your technology partner, always.', 'icon' => 'fas fa-headset']
            ])), true),

            // Pricing
            'lp_pricing_tag' => Setting::get('lp_pricing_tag', 'PRICING'),
            'lp_pricing_title' => Setting::get('lp_pricing_title', 'Transparent Pricing for Every Stage'),
            'lp_pricing_desc' => Setting::get('lp_pricing_desc', 'Choose the path that fits your SaaS business.'),
            'lp_pricing_plans' => json_decode(Setting::get('lp_pricing_plans', json_encode([
                [
                    'name' => 'White-Label Panel',
                    'badge' => 'Most Popular',
                    'price' => '999',
                    'period' => '/month',
                    'desc' => 'Perfect for agencies, freelancers and entrepreneurs',
                    'features' => [
                        '1 Platform',
                        '5 SaaS Products',
                        'Custom Branding',
                        'Unlimited Customers',
                        'Full Dashboard Access'
                    ],
                    'cta_text' => 'Get Started',
                    'cta_url' => '/login'
                ],
                [
                    'name' => 'White-Label Master Panel',
                    'badge' => 'Ideal for business network builders',
                    'price' => '2,499',
                    'period' => '/month',
                    'desc' => 'Ideal for businesses who want to create SaaS partners',
                    'features' => [
                        'Unlimited White Label Panels',
                        '5 SaaS Products',
                        'Partner Management',
                        'Centralized Dashboard',
                        'Complete Control'
                    ],
                    'cta_text' => 'Get Started',
                    'cta_url' => '/login'
                ]
            ])), true),

            // Growth Path
            'lp_growth_tag' => Setting::get('lp_growth_tag', 'YOUR GROWTH PATH'),
            'lp_growth_title' => Setting::get('lp_growth_title', 'From One Business to a Global Brand'),
            'lp_growth_subtitle' => Setting::get('lp_growth_subtitle', 'Start small, dream big, Nooryak grows with you at every stage.'),
            'lp_growth_steps' => json_decode(Setting::get('lp_growth_steps', json_encode([
                ['step' => 'Start', 'title' => 'Launch your own SaaS business', 'icon' => 'fas fa-flag-checkered'],
                ['step' => 'Grow', 'title' => 'Add more customers & scale revenue', 'icon' => 'fas fa-chart-line'],
                ['step' => 'Expand', 'title' => 'Become a Master Panel & create reseller partners', 'icon' => 'fas fa-users-gear'],
                ['step' => 'Global', 'title' => 'Build your SaaS network and reach new markets worldwide', 'icon' => 'fas fa-globe']
            ])), true),

            // Testimonials
            'lp_testimonials_tag' => Setting::get('lp_testimonials_tag', 'TRUSTED BY GROWING AGENCIES'),
            'lp_testimonials_title' => Setting::get('lp_testimonials_title', 'What Our Partners Say'),
            'lp_testimonials_subtitle' => Setting::get('lp_testimonials_subtitle', 'Real success stories from real entrepreneurs.'),
            'lp_testimonials_items' => json_decode(Setting::get('lp_testimonials_items', json_encode([
                [
                    'name' => 'Rohit Sharma',
                    'role' => 'Digital Agency Owner',
                    'avatar' => null,
                    'quote' => 'Nooryak helped me launch my own SaaS business in just a few days. The platform is powerful and super easy to use.',
                    'rating' => 5
                ],
                [
                    'name' => 'Priya Mehta',
                    'role' => 'IT Company Founder',
                    'avatar' => null,
                    'quote' => 'The Master Panel gives me complete control to manage multiple partners. It\'s a game changer for our business.',
                    'rating' => 5
                ],
                [
                    'name' => 'Amit Verma',
                    'role' => 'Freelancer',
                    'avatar' => null,
                    'quote' => 'Amazing support and a feature-rich platform. Highly recommended for anyone looking to create recurring revenue.',
                    'rating' => 5
                ]
            ])), true),

            // FAQ
            'lp_faqs_tag' => Setting::get('lp_faqs_tag', 'FAQ'),
            'lp_faqs_title' => Setting::get('lp_faqs_title', 'Frequently Asked Questions'),
            'lp_faqs_subtitle' => Setting::get('lp_faqs_subtitle', 'Still have questions? We\'re here to help.'),
            'lp_faqs_items' => json_decode(Setting::get('lp_faqs_items', json_encode([
                ['question' => 'What is Nooryak?', 'answer' => 'Nooryak is an All-In-One White Label SaaS Platform that allows you to launch and scale your own SaaS business under your own brand.'],
                ['question' => 'How does the pricing work?', 'answer' => 'We offer simple, transparent monthly pricing with no hidden fees. You keep 100% of the revenue you generate from your clients.'],
                ['question' => 'Can I use my own domain and branding?', 'answer' => 'Yes! You can connect your custom domain, upload your logo, select your color scheme, and customize all client-facing branding.'],
                ['question' => 'Is technical knowledge required?', 'answer' => 'No technical or coding knowledge is required. We handle hosting, security, product updates, and infrastructure.'],
                ['question' => 'Do you provide support?', 'answer' => 'Yes, we provide 24/7 technical and platform support to ensure your SaaS business runs smoothly.']
            ])), true),

            // CTA Banner & Footer
            'lp_cta_banner_title' => Setting::get('lp_cta_banner_title', 'Ready to Launch Your SaaS Business?'),
            'lp_cta_banner_subtitle' => Setting::get('lp_cta_banner_subtitle', 'Join hundreds of partners who are already building their success with Nooryak.'),
            'lp_cta_banner_button1_text' => Setting::get('lp_cta_banner_button1_text', 'Start Your SaaS Business'),
            'lp_cta_banner_button1_url' => Setting::get('lp_cta_banner_button1_url', '/login'),
            'lp_cta_banner_button2_text' => Setting::get('lp_cta_banner_button2_text', 'Book a Demo'),
            'lp_cta_banner_button2_url' => Setting::get('lp_cta_banner_button2_url', '#book-demo'),
            'lp_cta_banner_bg' => Setting::get('lp_cta_banner_bg', '/assets/images/cta_background.png'),
            'lp_footer_desc' => Setting::get('lp_footer_desc', 'Your technology partner in building profitable SaaS businesses.'),
            'lp_contact_email' => Setting::get('lp_contact_email', 'support@nooryak.in'),
            'lp_contact_phone' => Setting::get('lp_contact_phone', '+91 98765 43210'),
            'lp_copyright_text' => Setting::get('lp_copyright_text', '© 2026 Nooryak Technologies. All rights reserved.'),
            'lp_fb_url' => Setting::get('lp_fb_url', '#'),
            'lp_ig_url' => Setting::get('lp_ig_url', '#'),
            'lp_yt_url' => Setting::get('lp_yt_url', '#'),
            'lp_li_url' => Setting::get('lp_li_url', '#'),
            'lp_tw_url' => Setting::get('lp_tw_url', '#'),
            'lp_meta_title' => Setting::get('lp_meta_title', 'Nooryak - Launch Your Own SaaS Business Under Your Brand'),
            'lp_meta_description' => Setting::get('lp_meta_description', 'Nooryak is an All-In-One White Label SaaS Platform that helps agencies, freelancers, IT companies and entrepreneurs launch their own SaaS business.')
        ];
    }
}
