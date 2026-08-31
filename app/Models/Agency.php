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
        'about_content',
        'about_image',
        'services_data',
        'features_data',
        'testimonials_data',
        'faq_data',
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

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
