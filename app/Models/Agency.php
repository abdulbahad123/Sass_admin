<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Agency extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'parent_id',
        'owner_name',
        'email',
        'phone',
        'logo',
        'favicon',
        'custom_domain',
        'primary_color',
        'secondary_color',
        'accent_color',
        'bg_color',
        'text_color',
        'status',
        'max_clients',
        'max_products',
        'hero_title',
        'hero_subtitle',
        'hero_description',
        'cta_text',
        'cta_url',
        'hero_image',
        'cta_image',
        'about_title',
        'about_mission',
        'about_content',
        'about_image',
        'facebook_url',
        'instagram_url',
        'youtube_url',
        'linkedin_url',
        'twitter_url',
        'services_data',
        'features_data',
        'testimonials_data',
        'faq_data',
        'pricing_plans_data',
        'pricing_section_title',
        'pricing_section_subtitle',
        'pricing_trust_bar',
        'contact_email',
        'contact_phone',
        'contact_address',
        'social_links',
        'footer_content',
        'privacy_policy',
        'terms_conditions',
        'cookie_policy',
        'shipping_policy',
        'refund_policy',
        'disclaimer',
        'meta_title',
        'meta_description',
        'og_image',
        'sections_enabled',
        'gemini_api_key',
        'openai_api_key',
        'is_gemini_active',
        'is_openai_active',
    ];

    public function getParsedServicesAttribute()
    {
        if (empty($this->services_data)) {
            return [
                ['title' => 'AI Reviews + CRM', 'desc' => 'Get more 5-star reviews & manage customers easily', 'icon' => 'star', 'link' => '#'],
                ['title' => 'Website Builder', 'desc' => 'Create stunning websites with AI', 'icon' => 'globe', 'link' => '#'],
                ['title' => 'Digital V-Card', 'desc' => 'Share your business digitally, smartly', 'icon' => 'credit-card', 'link' => '#'],
                ['title' => 'QR Menu & Ordering', 'desc' => 'Contactless menu for restaurants & cafes', 'icon' => 'qr-code', 'link' => '#'],
                ['title' => 'Loyalty Program', 'desc' => 'Reward your customers and increase repeat sales', 'icon' => 'gift', 'link' => '#'],
                ['title' => 'Business Analytics', 'desc' => 'Track growth with real-time insights', 'icon' => 'bar-chart', 'link' => '#'],
            ];
        }
        return is_array($this->services_data) ? $this->services_data : (json_decode($this->services_data, true) ?: []);
    }

    public function getParsedFeaturesAttribute()
    {
        if (empty($this->features_data)) {
            return [
                ['title' => 'Get More Customers', 'desc' => 'Build trust with reviews, smart websites and digital presence.'],
                ['title' => 'Save Time & Effort', 'desc' => 'Automate repetitive tasks and focus on what matters most.'],
                ['title' => 'Increase Revenue', 'desc' => 'Drive repeat business with loyalty programs & digital tools.'],
                ['title' => 'Reliable & Secure', 'desc' => 'Your business data is safe with enterprise-grade security.'],
            ];
        }
        return is_array($this->features_data) ? $this->features_data : (json_decode($this->features_data, true) ?: []);
    }

    public function getParsedTestimonialsAttribute()
    {
        if (empty($this->testimonials_data)) {
            return [
                [
                    'name' => 'Rahul Sharma',
                    'role' => 'Restaurant Owner, Delhi',
                    'rating' => 5,
                    'comment' => "{$this->name} helped us get 3x more online orders in just 2 months. The QR menu and reviews feature is amazing!",
                    'avatar' => null
                ],
                [
                    'name' => 'Priya Mehta',
                    'role' => 'Salon Owner, Mumbai',
                    'rating' => 5,
                    'comment' => "Super easy to use and really effective. Our customer engagement has never been better!",
                    'avatar' => null
                ],
                [
                    'name' => 'Amit Verma',
                    'role' => 'Clinic Owner, Bengaluru',
                    'rating' => 5,
                    'comment' => "The digital tools, CRM and reminders have saved us hours of work every week.",
                    'avatar' => null
                ],
            ];
        }
        return is_array($this->testimonials_data) ? $this->testimonials_data : (json_decode($this->testimonials_data, true) ?: []);
    }

    public function getParsedFaqAttribute()
    {
        if (empty($this->faq_data)) {
            return [
                ['q' => 'How does the platform work?', 'a' => 'Our platform provides an all-in-one suite of growth tools designed to help local businesses manage orders, reviews, websites, and customer retention from a single place.'],
                ['q' => 'Can I customize the features for my business?', 'a' => 'Yes, you can enable and configure the exact tools you need in just a few clicks from your dashboard.'],
                ['q' => 'Is technical knowledge required?', 'a' => 'Not at all! Our software is built for non-technical business owners with clean, easy-to-use interfaces.'],
            ];
        }
        return is_array($this->faq_data) ? $this->faq_data : (json_decode($this->faq_data, true) ?: []);
    }

    public function getParsedSectionsAttribute()
    {
        $defaults = [
            'hero' => true,
            'trust_bar' => true,
            'why_choose' => true,
            'products' => true,
            'how_it_works' => true,
            'testimonials' => true,
            'cta_banner' => true,
            'faq' => true,
            'footer' => true,
        ];
        if (empty($this->sections_enabled)) {
            return $defaults;
        }
        $decoded = is_array($this->sections_enabled) ? $this->sections_enabled : (json_decode($this->sections_enabled, true) ?: []);
        return array_merge($defaults, $decoded);
    }

    public function getCleanDomainAttribute()
    {
        if (empty($this->custom_domain)) {
            return 'nooryak.in';
        }
        $domain = preg_replace('#^https?://#', '', trim($this->custom_domain));
        return rtrim($domain, '/');
    }

    public function getWhitelabelLoginUrlAttribute()
    {
        $domain = $this->clean_domain;
        return "https://{$domain}/whitelabel-panel/login";
    }

    public function getProductSubdomainUrl($productSlug)
    {
        $domain = $this->clean_domain;
        $cleanProductSlug = Str::slug($productSlug);
        $rootDomain = preg_replace('/^(app|www)\./i', '', $domain);

        if ($cleanProductSlug === 'website-builder' || $cleanProductSlug === 'websitebuilder') {
            return "https://websitebuilder.{$rootDomain}";
        }

        return "https://{$cleanProductSlug}.{$rootDomain}";
    }

    // Master agency has sub-agencies
    public function subAgencies()
    {
        return $this->hasMany(Agency::class, 'parent_id');
    }

    // Sub agency belongs to parent master agency
    public function parentAgency()
    {
        return $this->belongsTo(Agency::class, 'parent_id');
    }

    // Users linked to this agency
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Active subscription
    public function subscription()
    {
        return $this->hasOne(Subscription::class)->latestOfMany();
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    // Products directly entitled to this agency
    public function products()
    {
        $pivotColumns = ['status'];
        if (\Illuminate\Support\Facades\Schema::hasColumn('agency_products', 'db_name')) {
            $pivotColumns[] = 'db_name';
            $pivotColumns[] = 'db_status';
        }
        return $this->belongsToMany(Product::class, 'agency_products')->withPivot($pivotColumns)->withTimestamps();
    }

    public function getEnabledProductsAttribute()
    {
        $enabled = $this->products()
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('agency_products.status')
                  ->orWhere('agency_products.status', 'enabled');
            })
            ->get();

        if ($enabled->isNotEmpty()) {
            return $enabled;
        }

        return Product::where('is_active', true)->get();
    }

    public function getParsedPricingPlansAttribute()
    {
        if (!empty($this->pricing_plans_data)) {
            $decoded = is_array($this->pricing_plans_data)
                ? $this->pricing_plans_data
                : (json_decode($this->pricing_plans_data, true) ?: []);
            if (!empty($decoded)) {
                return $decoded;
            }
        }

        // Auto-generate defaults from enabled products
        $productDefaults = [
            'launchshop'       => ['color' => '#f97316', 'gradient' => 'linear-gradient(135deg,#f97316,#ea580c)', 'icon' => 'shopping-bag', 'plan_badge' => 'STARTER TIER',   'plan_badge_bg' => '#ffedd5', 'plan_badge_color' => '#c2410c', 'plan_name' => 'Starter Growth',    'price_monthly' => '499',   'price_yearly' => '4999',   'trust_count' => '10,000+ Sellers Trust Us',    'is_popular' => false, 'features' => ['1 Online Store', 'Up to 1,000 Products', 'Basic Analytics & Reports', 'Standard Support', 'Custom Subdomain Setup']],
            'website-builder'  => ['color' => '#2563eb', 'gradient' => 'linear-gradient(135deg,#2563eb,#1d4ed8)', 'icon' => 'monitor',      'plan_badge' => 'MOST POPULAR',   'plan_badge_bg' => '#dbeafe', 'plan_badge_color' => '#1d4ed8', 'plan_name' => 'Professional Pro', 'price_monthly' => '1499',  'price_yearly' => '14999',  'trust_count' => '5,000+ Websites Live',       'is_popular' => true,  'features' => ['Drag & Drop Builder', 'Custom Domain', 'SEO Optimization', 'AI Content Generation', '24/7 Priority Support']],
            'websitebuilder'   => ['color' => '#2563eb', 'gradient' => 'linear-gradient(135deg,#2563eb,#1d4ed8)', 'icon' => 'monitor',      'plan_badge' => 'MOST POPULAR',   'plan_badge_bg' => '#dbeafe', 'plan_badge_color' => '#1d4ed8', 'plan_name' => 'Professional Pro', 'price_monthly' => '1499',  'price_yearly' => '14999',  'trust_count' => '5,000+ Websites Live',       'is_popular' => true,  'features' => ['Drag & Drop Builder', 'Custom Domain', 'SEO Optimization', 'AI Content Generation', '24/7 Priority Support']],
            'ai-reviews'       => ['color' => '#7c3aed', 'gradient' => 'linear-gradient(135deg,#7c3aed,#6d28d9)', 'icon' => 'star',         'plan_badge' => 'GROWTH PLAN',    'plan_badge_bg' => '#ede9fe', 'plan_badge_color' => '#6d28d9', 'plan_name' => 'Growth Plan',       'price_monthly' => '999',   'price_yearly' => '9999',   'trust_count' => '8,000+ Reviews Generated',   'is_popular' => false, 'features' => ['AI Review Responses', 'Multi-Platform Reviews', 'Customer CRM', 'Reputation Reports', 'Smart Automations']],
            'vcard'            => ['color' => '#059669', 'gradient' => 'linear-gradient(135deg,#059669,#047857)', 'icon' => 'user',         'plan_badge' => 'STARTER TIER',   'plan_badge_bg' => '#d1fae5', 'plan_badge_color' => '#059669', 'plan_name' => 'Business Card Pro', 'price_monthly' => '299',   'price_yearly' => '2999',   'trust_count' => '20,000+ Cards Shared',       'is_popular' => false, 'features' => ['Digital V-Card', 'QR Code Sharing', 'Analytics Dashboard', 'Custom Branding', 'Unlimited Shares']],
            'qr-menu'          => ['color' => '#d97706', 'gradient' => 'linear-gradient(135deg,#d97706,#b45309)', 'icon' => 'qr-code',     'plan_badge' => 'PRO PLAN',       'plan_badge_bg' => '#fef3c7', 'plan_badge_color' => '#b45309', 'plan_name' => 'Restaurant Pro',    'price_monthly' => '699',   'price_yearly' => '6999',   'trust_count' => '3,000+ Restaurants',         'is_popular' => false, 'features' => ['QR Menu Setup', 'Online Ordering', 'Table Management', 'Multi-Menu Support', 'POS Integration']],
            'loyalty'          => ['color' => '#dc2626', 'gradient' => 'linear-gradient(135deg,#dc2626,#b91c1c)', 'icon' => 'gift',         'plan_badge' => 'STARTER TIER',   'plan_badge_bg' => '#fee2e2', 'plan_badge_color' => '#dc2626', 'plan_name' => 'Loyalty Starter',   'price_monthly' => '499',   'price_yearly' => '4999',   'trust_count' => '15,000+ Loyal Customers',    'is_popular' => false, 'features' => ['Points & Rewards', 'Referral Programs', 'Push Notifications', 'Customer Tiers', 'Birthday Bonuses']],
        ];

        $enabledProds = $this->enabled_products;
        $plans = [];
        foreach ($enabledProds->take(4) as $prod) {
            $slug = strtolower(trim($prod->slug ?? Str::slug($prod->name)));
            $def  = $productDefaults[$slug] ?? [
                'color' => '#6366f1', 'gradient' => 'linear-gradient(135deg,#6366f1,#4f46e5)', 'icon' => 'layers',
                'plan_badge' => 'PRO PLAN', 'plan_badge_bg' => '#e0e7ff', 'plan_badge_color' => '#4f46e5',
                'plan_name' => 'Growth Plan', 'price_monthly' => '799', 'price_yearly' => '7999',
                'trust_count' => '5,000+ Users',
                'is_popular' => false,
                'features' => ['Full Product Access', 'Priority Support', 'Advanced Analytics', 'Custom Branding', 'Dedicated Manager'],
            ];
            $plans[] = array_merge([
                'product_name'     => $prod->name,
                'product_slug'     => $slug,
                'product_tagline'  => $prod->tagline ?? 'Smart Business Tool',
                'product_subtitle' => $prod->description ?? 'Powerful tools to grow your business.',
                'cta_text'         => 'Get Started Free',
                'cta_url'          => $this->cta_url ?? '/login',
            ], $def);
        }

        // Fallback to 2 hardcoded plans if no products configured
        if (empty($plans)) {
            $plans = [
                ['product_name' => 'ECOM BUILDER', 'product_slug' => 'launchshop', 'product_tagline' => 'Online Store Builder', 'product_subtitle' => 'Launch your digital store in minutes.', 'color' => '#f97316', 'gradient' => 'linear-gradient(135deg,#f97316,#ea580c)', 'icon' => 'shopping-bag', 'plan_badge' => 'STARTER TIER', 'plan_badge_bg' => '#ffedd5', 'plan_badge_color' => '#c2410c', 'plan_name' => 'Starter Growth', 'price_monthly' => '499', 'price_yearly' => '4999', 'trust_count' => '10,000+ Sellers Trust Us', 'is_popular' => false, 'features' => ['1 Online Store', 'Up to 1,000 Products', 'Basic Analytics & Reports', 'Standard Email Support', 'Custom Subdomain Setup'], 'cta_text' => 'Get Started Free', 'cta_url' => $this->cta_url ?? '/login'],
                ['product_name' => 'Website Builder', 'product_slug' => 'website-builder', 'product_tagline' => 'Professional Website Builder', 'product_subtitle' => 'Create stunning websites with AI in minutes.', 'color' => '#2563eb', 'gradient' => 'linear-gradient(135deg,#2563eb,#1d4ed8)', 'icon' => 'monitor', 'plan_badge' => 'MOST POPULAR', 'plan_badge_bg' => '#dbeafe', 'plan_badge_color' => '#1d4ed8', 'plan_name' => 'Professional Pro', 'price_monthly' => '1499', 'price_yearly' => '14999', 'trust_count' => '5,000+ Websites Live', 'is_popular' => true, 'features' => ['Drag & Drop Builder', 'Custom Domain', 'SEO Optimization', 'AI Content Generation', '24/7 Priority Support'], 'cta_text' => 'Start 14-Day Free Trial', 'cta_url' => $this->cta_url ?? '/login'],
            ];
        }

        return $plans;
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
