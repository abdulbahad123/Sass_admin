<?php

namespace App\Http\Controllers\WhiteLabel;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WhiteLabelWebsiteController extends Controller
{
    protected function getAgency()
    {
        return Agency::resolveCurrent(request(), Auth::user());
    }

    protected function makeImageValidationRule(int $maxKb, array $allowed = ['jpeg', 'png', 'jpg', 'webp', 'svg'])
    {
        if (extension_loaded('fileinfo')) {
            return 'nullable|image|mimes:' . implode(',', $allowed) . '|max:' . $maxKb;
        }

        return ['nullable', 'file', function ($attr, $val, $fail) use ($maxKb, $allowed) {
            if (!$val) return;
            if ($val->getSize() > $maxKb * 1024) {
                $fail(ucfirst(str_replace('_', ' ', $attr)) . ' must not exceed ' . round($maxKb / 1024, 1) . 'MB.');
                return;
            }
            $ext = strtolower($val->getClientOriginalExtension() ?: pathinfo($val->getClientOriginalName(), PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed, true)) {
                $fail(ucfirst(str_replace('_', ' ', $attr)) . ' must be a valid file (' . implode(', ', $allowed) . ').');
            }
        }];
    }

    protected function saveSecureUpload($file, string $prefix, string $subDir = 'uploads/agency'): ?string
    {
        if (!$file || !$file->isValid()) {
            return null;
        }

        $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'svg', 'ico'];

        $ext = '';
        if (extension_loaded('fileinfo')) {
            try {
                $ext = strtolower($file->guessExtension() ?: '');
            } catch (\Throwable $e) {
                $ext = '';
            }
        }
        if (empty($ext)) {
            $ext = strtolower($file->getClientOriginalExtension() ?: pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION));
        }

        if (!in_array($ext, $allowedExts, true)) {
            return null;
        }

        $uploadDir = public_path($subDir);
        if (!file_exists($uploadDir)) {
            @mkdir($uploadDir, 0755, true);
        }

        $fileName = $prefix . '_' . time() . '_' . \Illuminate\Support\Str::random(6) . '.' . $ext;
        $file->move($uploadDir, $fileName);

        return $subDir . '/' . $fileName;
    }

    public function landing()
    {
        $user = Auth::user();
        $agency = $this->getAgency();
        if (!$agency) {
            return redirect()->route('whitelabel.dashboard')->with('error', 'Agency profile not found for this account.');
        }
        return view('whitelabel.website.landing', compact('user', 'agency'));
    }

    public function updateLanding(Request $request)
    {
        $agency = $this->getAgency();
        if (!$agency) return back()->with('error', 'Agency profile not found for this account.');

        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'custom_domain'    => 'nullable|string|max:255',
            'primary_color'    => 'nullable|string|max:20',
            'secondary_color'  => 'nullable|string|max:20',
            'hero_badge'       => 'nullable|string|max:255',
            'hero_title'       => 'nullable|string',
            'hero_subtitle'    => 'nullable|string',
            'hero_description' => 'nullable|string',
            'cta_text'         => 'nullable|string|max:255',
            'cta_url'          => 'nullable|string|max:255',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'footer_content'   => 'nullable|string',
            'about_title'      => 'nullable|string',
            'about_mission'    => 'nullable|string',
            'about_content'    => 'nullable|string',
            'why_choose_title'         => 'nullable|string|max:255',
            'why_choose_subtitle'      => 'nullable|string',
            'products_section_badge'   => 'nullable|string|max:255',
            'products_section_title'   => 'nullable|string|max:255',
            'products_section_subtitle'=> 'nullable|string',
            'products_cta_text'        => 'nullable|string|max:255',
            'products_cta_url'         => 'nullable|string|max:500',
            'how_it_works_title'       => 'nullable|string|max:255',
            'how_it_works_subtitle'    => 'nullable|string',
            'cta_section_title'        => 'nullable|string|max:255',
            'cta_section_subtitle'     => 'nullable|string',
            'cta_button_text'          => 'nullable|string|max:255',
            'cta_button_url'           => 'nullable|string|max:500',
            'contact_email'    => 'nullable|string|max:255',
            'contact_phone'    => 'nullable|string|max:255',
            'facebook_url'     => 'nullable|string|max:500',
            'instagram_url'    => 'nullable|string|max:500',
            'youtube_url'      => 'nullable|string|max:500',
            'linkedin_url'     => 'nullable|string|max:500',
            'twitter_url'      => 'nullable|string|max:500',
            'logo'             => $this->makeImageValidationRule(4096, ['jpeg', 'png', 'jpg', 'webp', 'svg']),
            'logo_dark'        => $this->makeImageValidationRule(4096, ['jpeg', 'png', 'jpg', 'webp', 'svg']),
            'footer_logo'      => $this->makeImageValidationRule(4096, ['jpeg', 'png', 'jpg', 'webp', 'svg']),
            'about_section_badge' => 'nullable|string|max:255',
            'favicon'          => $this->makeImageValidationRule(2048, ['ico', 'png', 'jpg', 'svg']),
            'hero_image'       => $this->makeImageValidationRule(8192, ['jpeg', 'png', 'jpg', 'webp', 'svg']),
            'about_image'      => $this->makeImageValidationRule(8192, ['jpeg', 'png', 'jpg', 'webp', 'svg']),
            'cta_image'        => $this->makeImageValidationRule(8192, ['jpeg', 'png', 'jpg', 'webp', 'svg']),
        ]);

        if ($request->hasFile('logo')) {
            $saved = $this->saveSecureUpload($request->file('logo'), 'logo');
            if ($saved) $validated['logo'] = $saved;
        }

        if ($request->hasFile('logo_dark')) {
            $saved = $this->saveSecureUpload($request->file('logo_dark'), 'logo_dark');
            if ($saved) $validated['logo_dark'] = $saved;
        }

        if ($request->hasFile('footer_logo')) {
            $saved = $this->saveSecureUpload($request->file('footer_logo'), 'footer_logo');
            if ($saved) $validated['footer_logo'] = $saved;
        }

        if ($request->hasFile('favicon')) {
            $saved = $this->saveSecureUpload($request->file('favicon'), 'favicon');
            if ($saved) $validated['favicon'] = $saved;
        }

        if ($request->hasFile('hero_image')) {
            $saved = $this->saveSecureUpload($request->file('hero_image'), 'hero');
            if ($saved) $validated['hero_image'] = $saved;
        }

        if ($request->hasFile('about_image')) {
            $saved = $this->saveSecureUpload($request->file('about_image'), 'about');
            if ($saved) $validated['about_image'] = $saved;
        }

        if ($request->hasFile('cta_image')) {
            $saved = $this->saveSecureUpload($request->file('cta_image'), 'cta');
            if ($saved) $validated['cta_image'] = $saved;
        }

        // 1. About Features repeater
        if ($request->has('about_feature_title')) {
            $afTitles = $request->input('about_feature_title', []);
            $afDescs  = $request->input('about_feature_desc', []);
            $afIcons  = $request->input('about_feature_icon', []);
            $aboutFeatures = [];
            for ($i = 0; $i < count($afTitles); $i++) {
                if (!empty(trim($afTitles[$i] ?? ''))) {
                    $aboutFeatures[] = [
                        'title' => trim($afTitles[$i]),
                        'desc'  => trim($afDescs[$i] ?? ''),
                        'icon'  => trim($afIcons[$i] ?? 'zap'),
                    ];
                }
            }
            $validated['about_features_data'] = json_encode($aboutFeatures);
        }

        // 2. Why Choose Us (features_data) repeater
        if ($request->has('why_choose_item_title')) {
            $wcTitles = $request->input('why_choose_item_title', []);
            $wcDescs  = $request->input('why_choose_item_desc', []);
            $wcIcons  = $request->input('why_choose_item_icon', []);
            $whyChoose = [];
            for ($i = 0; $i < count($wcTitles); $i++) {
                if (!empty(trim($wcTitles[$i] ?? ''))) {
                    $whyChoose[] = [
                        'title' => trim($wcTitles[$i]),
                        'desc'  => trim($wcDescs[$i] ?? ''),
                        'icon'  => trim($wcIcons[$i] ?? 'rocket'),
                    ];
                }
            }
            $validated['features_data'] = json_encode($whyChoose);
        }

        // 3. How It Works (how_it_works_steps) repeater
        if ($request->has('hiw_step_title')) {
            $hiwTitles = $request->input('hiw_step_title', []);
            $hiwDescs  = $request->input('hiw_step_desc', []);
            $hiwNums   = $request->input('hiw_step_num', []);
            $hiwIcons  = $request->input('hiw_step_icon', []);
            $hiwSteps = [];
            for ($i = 0; $i < count($hiwTitles); $i++) {
                if (!empty(trim($hiwTitles[$i] ?? ''))) {
                    $hiwSteps[] = [
                        'num'   => trim($hiwNums[$i] ?? sprintf('%02d', $i + 1)),
                        'title' => trim($hiwTitles[$i]),
                        'desc'  => trim($hiwDescs[$i] ?? ''),
                        'icon'  => trim($hiwIcons[$i] ?? 'user-plus'),
                    ];
                }
            }
            $validated['how_it_works_steps'] = json_encode($hiwSteps);
        }

                // 5. Trusted Businesses Bar & Category Pills
        if ($request->has('trusted_businesses_title')) {
            $validated['trusted_businesses_title'] = $request->input('trusted_businesses_title');
        }
        if ($request->has('category_label')) {
            $catLabels = $request->input('category_label', []);
            $catIcons  = $request->input('category_icon', []);
            $categories = [];
            for ($i = 0; $i < count($catLabels); $i++) {
                if (!empty(trim($catLabels[$i] ?? ''))) {
                    $categories[] = [
                        'label' => trim($catLabels[$i]),
                        'icon'  => trim($catIcons[$i] ?? '✨'),
                    ];
                }
            }
            $validated['categories_data'] = json_encode($categories);
        }

        // 4. CTA Banner Features / Checkmarks
        if ($request->has('cta_feature_text')) {
            $ctaTexts = array_filter(array_map('trim', explode(',', $request->input('cta_feature_text', ''))));
            $validated['cta_features_data'] = json_encode(array_values($ctaTexts));
        }

        // Section toggles
        if ($request->has('sections')) {
            $validated['sections_enabled'] = json_encode($request->input('sections'));
        }

        // Auto-safeguard for logo_dark, footer_logo & about_section_badge if migration has not run on production DB yet
        try {
            if (isset($validated['logo_dark']) && !\Illuminate\Support\Facades\Schema::hasColumn('agencies', 'logo_dark')) {
                \Illuminate\Support\Facades\Schema::table('agencies', function ($table) {
                    $table->string('logo_dark')->nullable()->after('logo');
                });
            }
        } catch (\Throwable $e) {
            if (!\Illuminate\Support\Facades\Schema::hasColumn('agencies', 'logo_dark')) {
                unset($validated['logo_dark']);
            }
        }

        try {
            if (isset($validated['footer_logo']) && !\Illuminate\Support\Facades\Schema::hasColumn('agencies', 'footer_logo')) {
                \Illuminate\Support\Facades\Schema::table('agencies', function ($table) {
                    $table->string('footer_logo')->nullable()->after('logo_dark');
                });
            }
        } catch (\Throwable $e) {
            if (!\Illuminate\Support\Facades\Schema::hasColumn('agencies', 'footer_logo')) {
                unset($validated['footer_logo']);
            }
        }

        try {
            if (isset($validated['about_section_badge']) && !\Illuminate\Support\Facades\Schema::hasColumn('agencies', 'about_section_badge')) {
                \Illuminate\Support\Facades\Schema::table('agencies', function ($table) {
                    $table->string('about_section_badge')->nullable()->after('about_title');
                });
            }
        } catch (\Throwable $e) {
            if (!\Illuminate\Support\Facades\Schema::hasColumn('agencies', 'about_section_badge')) {
                unset($validated['about_section_badge']);
            }
        }

        $agency->update($validated);

        AuditLog::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name ?? 'Agency Owner',
            'action' => "Updated landing page settings & hero for {$agency->name}",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Landing page configuration saved successfully!');
    }

    public function about()
    {
        $user = Auth::user();
        $agency = $this->getAgency();
        return view('whitelabel.website.about', compact('user', 'agency'));
    }

    public function updateAbout(Request $request)
    {
        $agency = $this->getAgency();
        if (!$agency) return back()->with('error', 'Agency profile not found.');

        $validated = $request->validate([
            'about_title'   => 'nullable|string',
            'about_mission' => 'nullable|string',
            'about_content' => 'required|string',
            'about_image'   => $this->makeImageValidationRule(8192, ['jpeg', 'png', 'jpg', 'webp', 'svg']),
        ]);

        if ($request->hasFile('about_image')) {
            $saved = $this->saveSecureUpload($request->file('about_image'), 'about');
            if ($saved) $validated['about_image'] = $saved;
        }

        $agency->update($validated);

        return back()->with('success', 'About section updated successfully!');
    }

    public function services()
    {
        $user = Auth::user();
        $agency = $this->getAgency();
        $services = $agency->parsed_services;
        return view('whitelabel.website.services', compact('user', 'agency', 'services'));
    }

    public function updateServices(Request $request)
    {
        $agency = $this->getAgency();
        if (!$agency) return back()->with('error', 'Agency profile not found.');

        $titles = $request->input('title', []);
        $descs = $request->input('desc', []);
        $icons = $request->input('icon', []);
        $links = $request->input('link', []);

        $servicesData = [];
        for ($i = 0; $i < count($titles); $i++) {
            if (!empty($titles[$i])) {
                $servicesData[] = [
                    'title' => $titles[$i],
                    'desc' => $descs[$i] ?? '',
                    'icon' => $icons[$i] ?? 'box',
                    'link' => $links[$i] ?? '#',
                ];
            }
        }

        $agency->update(['services_data' => json_encode($servicesData)]);

        return back()->with('success', 'Agency services updated successfully!');
    }

    public function testimonials()
    {
        $user = Auth::user();
        $agency = $this->getAgency();
        if (!$agency) {
            return redirect()->route('whitelabel.dashboard')->with('error', 'Agency profile not found. Please contact your administrator.');
        }
        // Reload from DB to ensure we get the latest saved state (not cached model)
        $agency->refresh();
        $testimonials = $agency->parsed_testimonials;
        return view('whitelabel.website.testimonials', compact('user', 'agency', 'testimonials'));
    }

    public function updateTestimonials(Request $request)
    {
        $agency = $this->getAgency();
        if (!$agency) return back()->with('error', 'Agency profile not found.');

        // Reload fresh from DB to avoid stale model state (important on first save after fresh launch)
        $agency = $agency->fresh();

        $names = $request->input('name', []);
        $roles = $request->input('role', []);
        $comments = $request->input('comment', []);
        $ratings = $request->input('rating', []);

        $testimonialsData = [];
        for ($i = 0; $i < count($names); $i++) {
            if (!empty($names[$i])) {
                $testimonialsData[] = [
                    'name'    => $names[$i],
                    'role'    => $roles[$i] ?? '',
                    'comment' => $comments[$i] ?? '',
                    'rating'  => (int)($ratings[$i] ?? 5),
                ];
            }
        }

        $agency->update(['testimonials_data' => json_encode($testimonialsData)]);

        return back()->with('success', 'Testimonials updated successfully!');
    }

    public function faq()
    {
        $user = Auth::user();
        $agency = $this->getAgency();
        $faq = $agency->parsed_faq;
        return view('whitelabel.website.faq', compact('user', 'agency', 'faq'));
    }

    public function updateFaq(Request $request)
    {
        $agency = $this->getAgency();
        if (!$agency) return back()->with('error', 'Agency profile not found.');

        $questions = $request->input('q', []);
        $answers = $request->input('a', []);

        $faqData = [];
        for ($i = 0; $i < count($questions); $i++) {
            if (!empty($questions[$i])) {
                $faqData[] = [
                    'q' => $questions[$i],
                    'a' => $answers[$i] ?? '',
                ];
            }
        }

        $agency->update(['faq_data' => json_encode($faqData)]);

        return back()->with('success', 'FAQ section updated successfully!');
    }

    public function contact(Request $request)
    {
        $user = Auth::user();
        $agency = $this->getAgency();
        $socialLinks = is_array($agency->social_links) ? $agency->social_links : (json_decode($agency->social_links, true) ?: []);

        // Ensure table exists defensively
        if (!\Illuminate\Support\Facades\Schema::hasTable('agency_contact_submissions')) {
            try {
                \Illuminate\Support\Facades\Schema::create('agency_contact_submissions', function ($table) {
                    $table->id();
                    $table->unsignedBigInteger('agency_id')->index();
                    $table->string('name');
                    $table->string('email');
                    $table->string('subject')->nullable();
                    $table->text('message');
                    $table->string('ip_address', 45)->nullable();
                    $table->text('user_agent')->nullable();
                    $table->string('status')->default('unread');
                    $table->timestamps();
                });
            } catch (\Throwable $e) {}
        }

        $activeTab = $request->input('tab', 'submitted_data');

        // Submissions Query with filters and pagination
        $submissionsQuery = \App\Models\AgencyContactSubmission::where('agency_id', $agency->id);

        // Date filter: today, yesterday, custom
        $filter = $request->input('filter', 'all');
        if ($filter === 'today') {
            $submissionsQuery->whereDate('created_at', \Carbon\Carbon::today());
        } elseif ($filter === 'yesterday') {
            $submissionsQuery->whereDate('created_at', \Carbon\Carbon::yesterday());
        } elseif ($filter === 'custom' && $request->filled('start_date') && $request->filled('end_date')) {
            $submissionsQuery->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        // Status filter
        if ($request->filled('status')) {
            $submissionsQuery->where('status', $request->status);
        }

        // Keyword search
        if ($request->filled('search')) {
            $s = trim($request->search);
            $submissionsQuery->where(function ($q) use ($s) {
                $q->where('name', 'LIKE', "%{$s}%")
                  ->orWhere('email', 'LIKE', "%{$s}%")
                  ->orWhere('subject', 'LIKE', "%{$s}%")
                  ->orWhere('message', 'LIKE', "%{$s}%");
            });
        }

        $submissions = $submissionsQuery->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Lead metrics
        $unreadCount = \App\Models\AgencyContactSubmission::where('agency_id', $agency->id)->where('status', 'unread')->count();
        $todayCount  = \App\Models\AgencyContactSubmission::where('agency_id', $agency->id)->whereDate('created_at', \Carbon\Carbon::today())->count();
        $totalCount  = \App\Models\AgencyContactSubmission::where('agency_id', $agency->id)->count();

        // Settings arrays
        $smtpSettings = is_array($agency->smtp_settings) ? $agency->smtp_settings : (json_decode($agency->smtp_settings ?? '[]', true) ?: []);
        $recaptchaSettings = is_array($agency->recaptcha_settings) ? $agency->recaptcha_settings : (json_decode($agency->recaptcha_settings ?? '[]', true) ?: []);

        return view('whitelabel.website.contact', compact(
            'user',
            'agency',
            'socialLinks',
            'submissions',
            'unreadCount',
            'todayCount',
            'totalCount',
            'smtpSettings',
            'recaptchaSettings',
            'activeTab'
        ));
    }

    public function updateContact(Request $request)
    {
        $agency = $this->getAgency();
        if (!$agency) return back()->with('error', 'Agency profile not found.');

        $validated = $request->validate([
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'contact_address' => 'nullable|string',
            'footer_content' => 'nullable|string',
        ]);

        $social = [
            'facebook' => $request->input('facebook'),
            'instagram' => $request->input('instagram'),
            'youtube' => $request->input('youtube'),
            'linkedin' => $request->input('linkedin'),
            'twitter' => $request->input('twitter'),
        ];
        $validated['social_links'] = json_encode($social);

        $agency->update($validated);

        return redirect()->route('whitelabel.website.contact', ['tab' => 'contact_info'])
            ->with('success', 'Contact details & footer updated successfully!');
    }

    public function updateSmtp(Request $request)
    {
        $agency = $this->getAgency();
        if (!$agency) return back()->with('error', 'Agency profile not found.');

        $smtp = [
            'smtp_enabled'      => (bool)$request->input('smtp_enabled', false),
            'smtp_host'         => trim($request->input('smtp_host', '')),
            'smtp_port'         => (int)$request->input('smtp_port', 587),
            'smtp_encryption'   => trim($request->input('smtp_encryption', 'tls')),
            'smtp_username'     => trim($request->input('smtp_username', '')),
            'smtp_password'     => $request->input('smtp_password', ''),
            'smtp_from_address' => trim($request->input('smtp_from_address', '')),
            'smtp_from_name'    => trim($request->input('smtp_from_name', '')),
            'smtp_to_email'     => trim($request->input('smtp_to_email', '')),
        ];

        $agency->update(['smtp_settings' => json_encode($smtp)]);

        return redirect()->route('whitelabel.website.contact', ['tab' => 'smtp'])
            ->with('success', 'SMTP settings & inquiry email forwarding updated successfully!');
    }

    public function testSmtp(Request $request)
    {
        $agency = $this->getAgency();
        if (!$agency) return back()->with('error', 'Agency profile not found.');

        $smtp = is_array($agency->smtp_settings) ? $agency->smtp_settings : (json_decode($agency->smtp_settings ?? '[]', true) ?: []);
        $recipient = $request->input('test_email', $smtp['smtp_to_email'] ?? ($agency->contact_email ?? $agency->email));

        if (empty($recipient)) {
            return redirect()->route('whitelabel.website.contact', ['tab' => 'smtp'])
                ->with('error', 'Please provide a valid recipient email address for testing.');
        }

        try {
            if (!empty($smtp['smtp_host']) && !empty($smtp['smtp_username'])) {
                $transport = new \Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport(
                    $smtp['smtp_host'],
                    (int)($smtp['smtp_port'] ?? 587),
                    ($smtp['smtp_encryption'] ?? 'tls') === 'ssl'
                );
                $transport->setUsername($smtp['smtp_username']);
                $transport->setPassword($smtp['smtp_password'] ?? '');

                $mailer = new \Symfony\Component\Mailer\Mailer($transport);
                $fromAddress = !empty($smtp['smtp_from_address']) ? $smtp['smtp_from_address'] : $smtp['smtp_username'];
                $defaultAgencyName = $agency->name ?? 'White Label Agency';
                $fromName    = !empty($smtp['smtp_from_name']) ? $smtp['smtp_from_name'] : $defaultAgencyName;

                $email = (new \Symfony\Component\Mime\Email())
                    ->from(new \Symfony\Component\Mime\Address($fromAddress, $fromName))
                    ->to(new \Symfony\Component\Mime\Address($recipient))
                    ->subject("SMTP Test Email from " . $defaultAgencyName)
                    ->html("<h3>SMTP Connected Successfully!</h3><p>This is a test email sent from your white-label dashboard at " . date('Y-m-d H:i:s') . ".</p>");

                $mailer->send($email);
            } else {
                \Illuminate\Support\Facades\Mail::html(
                    "<h3>Test Notification</h3><p>This is a default server email notification test from {$agency->name}.</p>",
                    function ($msg) use ($recipient, $agency) {
                        $msg->to($recipient)->subject("Test Email from {$agency->name}");
                    }
                );
            }

            return redirect()->route('whitelabel.website.contact', ['tab' => 'smtp'])
                ->with('success', "Test email sent successfully to {$recipient}!");
        } catch (\Throwable $e) {
            return redirect()->route('whitelabel.website.contact', ['tab' => 'smtp'])
                ->with('error', "SMTP Connection Error: " . $e->getMessage());
        }
    }

    public function updateRecaptcha(Request $request)
    {
        $agency = $this->getAgency();
        if (!$agency) return back()->with('error', 'Agency profile not found.');

        $recaptcha = [
            'recaptcha_enabled'    => (bool)$request->input('recaptcha_enabled', false),
            'recaptcha_site_key'   => trim($request->input('recaptcha_site_key', '')),
            'recaptcha_secret_key' => trim($request->input('recaptcha_secret_key', '')),
            'recaptcha_version'    => trim($request->input('recaptcha_version', 'v2')),
        ];

        $agency->update(['recaptcha_settings' => json_encode($recaptcha)]);

        return redirect()->route('whitelabel.website.contact', ['tab' => 'recaptcha'])
            ->with('success', 'Google reCAPTCHA configuration saved successfully!');
    }

    public function updateSubmissionStatus(Request $request, $id)
    {
        $agency = $this->getAgency();
        if (!$agency) return response()->json(['error' => 'Agency not found'], 404);

        $submission = \App\Models\AgencyContactSubmission::where('agency_id', $agency->id)->findOrFail($id);
        $newStatus = $request->input('status', ($submission->status === 'read' ? 'unread' : 'read'));
        $submission->update(['status' => $newStatus]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'status' => $newStatus]);
        }
        return back()->with('success', 'Status updated successfully.');
    }

    public function deleteSubmission(Request $request, $id)
    {
        $agency = $this->getAgency();
        if (!$agency) return back()->with('error', 'Agency profile not found.');

        \App\Models\AgencyContactSubmission::where('agency_id', $agency->id)->where('id', $id)->delete();

        return redirect()->route('whitelabel.website.contact', ['tab' => 'submitted_data'])
            ->with('success', 'Contact submission deleted successfully.');
    }

    public function submitContactForm(Request $request)
    {
        // 1. Identify agency
        $agency = null;
        if ($request->filled('agency_id')) {
            $agency = \App\Models\Agency::find($request->agency_id);
        }
        if (!$agency) {
            $agency = \App\Models\Agency::resolveCurrent($request);
        }

        if (!$agency) {
            return response()->json(['success' => false, 'message' => 'Agency not found.'], 404);
        }

        // 2. Validate input
        $request->validate([
            'name'    => 'required|string|max:150',
            'email'   => 'required|email|max:150',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // Optional reCAPTCHA verification if enabled
        $recaptcha = is_array($agency->recaptcha_settings) ? $agency->recaptcha_settings : (json_decode($agency->recaptcha_settings ?? '[]', true) ?: []);
        if (!empty($recaptcha['recaptcha_enabled']) && !empty($recaptcha['recaptcha_secret_key'])) {
            $token = $request->input('g-recaptcha-response');
            if (empty($token)) {
                return response()->json(['success' => false, 'message' => 'Please complete the reCAPTCHA challenge.'], 422);
            }
            try {
                $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha['recaptcha_secret_key']}&response={$token}");
                $resp = json_decode($verify, true);
                if (empty($resp['success'])) {
                    return response()->json(['success' => false, 'message' => 'reCAPTCHA validation failed. Please try again.'], 422);
                }
            } catch (\Throwable $e) {}
        }

        // 3. Store submission in database
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('agency_contact_submissions')) {
                \Illuminate\Support\Facades\Schema::create('agency_contact_submissions', function ($table) {
                    $table->id();
                    $table->unsignedBigInteger('agency_id')->index();
                    $table->string('name');
                    $table->string('email');
                    $table->string('subject')->nullable();
                    $table->text('message');
                    $table->string('ip_address', 45)->nullable();
                    $table->text('user_agent')->nullable();
                    $table->string('status')->default('unread');
                    $table->timestamps();
                });
            }

            $submission = \App\Models\AgencyContactSubmission::create([
                'agency_id'  => $agency->id,
                'name'       => $request->name,
                'email'      => $request->email,
                'subject'    => $request->subject ?: 'General Inquiry',
                'message'    => $request->message,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status'     => 'unread',
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Failed to store contact submission: " . $e->getMessage());
        }

        // 4. Forward email copy to agency email / SMTP if enabled
        $smtp = is_array($agency->smtp_settings) ? $agency->smtp_settings : (json_decode($agency->smtp_settings ?? '[]', true) ?: []);
        $toEmail = !empty($smtp['smtp_to_email']) ? $smtp['smtp_to_email'] : ($agency->contact_email ?? $agency->email);
        $smtpEnabled = isset($smtp['smtp_enabled']) ? (bool)$smtp['smtp_enabled'] : true;

        if ($smtpEnabled && !empty($toEmail)) {
            try {
                $emailSubject = "New Contact Inquiry from {$request->name}: " . ($request->subject ?: 'Contact Form');
                $htmlBody = "
                    <div style='font-family:sans-serif;max-width:600px;margin:0 auto;padding:24px;border:1px solid #e2e8f0;border-radius:12px;'>
                        <h2 style='color:#0f172a;margin-top:0;'>New Lead Submitted on {$agency->name}</h2>
                        <p style='color:#64748b;font-size:13px;'>A visitor submitted an inquiry through your website's Contact Us form.</p>
                        <table style='width:100%;border-collapse:collapse;margin:20px 0;'>
                            <tr><td style='padding:8px 0;color:#64748b;width:120px;'><strong>Name:</strong></td><td style='padding:8px 0;color:#0f172a;'>{$request->name}</td></tr>
                            <tr><td style='padding:8px 0;color:#64748b;'><strong>Email:</strong></td><td style='padding:8px 0;color:#0f172a;'><a href='mailto:{$request->email}'>{$request->email}</a></td></tr>
                            <tr><td style='padding:8px 0;color:#64748b;'><strong>Subject:</strong></td><td style='padding:8px 0;color:#0f172a;'>{$request->subject}</td></tr>
                            <tr><td style='padding:8px 0;color:#64748b;vertical-align:top;'><strong>Message:</strong></td><td style='padding:8px 0;color:#0f172a;line-height:1.6;white-space:pre-line;'>{$request->message}</td></tr>
                            <tr><td style='padding:8px 0;color:#64748b;'><strong>IP Address:</strong></td><td style='padding:8px 0;color:#64748b;font-size:12px;'>{$request->ip()}</td></tr>
                            <tr><td style='padding:8px 0;color:#64748b;'><strong>Date:</strong></td><td style='padding:8px 0;color:#64748b;font-size:12px;'>" . date('F d, Y h:i A') . "</td></tr>
                        </table>
                        <div style='margin-top:24px;padding-top:16px;border-top:1px solid #f1f5f9;text-align:center;'>
                            <a href='https://{$agency->clean_domain}/whitelabel/website/contact?tab=submitted_data' style='display:inline-block;padding:10px 20px;background:#4f46e5;color:#fff;border-radius:8px;text-decoration:none;font-weight:bold;font-size:13px;'>View in Dashboard</a>
                        </div>
                    </div>
                ";

                if (!empty($smtp['smtp_host']) && !empty($smtp['smtp_username'])) {
                    $transport = new \Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport(
                        $smtp['smtp_host'],
                        (int)($smtp['smtp_port'] ?? 587),
                        ($smtp['smtp_encryption'] ?? 'tls') === 'ssl'
                    );
                    $transport->setUsername($smtp['smtp_username']);
                    $transport->setPassword($smtp['smtp_password'] ?? '');

                    $mailer = new \Symfony\Component\Mailer\Mailer($transport);
                    $fromAddress = !empty($smtp['smtp_from_address']) ? $smtp['smtp_from_address'] : $smtp['smtp_username'];
                    $fromName    = !empty($smtp['smtp_from_name']) ? $smtp['smtp_from_name'] : (!empty($agency->name) ? $agency->name . ' Contact' : 'Contact Support');

                    $email = (new \Symfony\Component\Mime\Email())
                        ->from(new \Symfony\Component\Mime\Address($fromAddress, $fromName))
                        ->to(new \Symfony\Component\Mime\Address($toEmail))
                        ->subject($emailSubject)
                        ->html($htmlBody);

                    $mailer->send($email);
                } else {
                    \Illuminate\Support\Facades\Mail::html($htmlBody, function ($msg) use ($toEmail, $emailSubject, $agency) {
                        $msg->to($toEmail)->subject($emailSubject);
                    });
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning("Contact form email notification error: " . $e->getMessage());
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you! Your message has been sent successfully to the ' . ($agency->name ?? 'support') . ' team. We will get back to you shortly.'
            ]);
        }

        return back()->with('success', 'Thank you! Your message has been sent successfully.');
    }

    public function privacy()
    {
        $user = Auth::user();
        $agency = $this->getAgency();
        return view('whitelabel.website.privacy', compact('user', 'agency'));
    }

    public function updatePrivacy(Request $request)
    {
        $agency = $this->getAgency();
        if (!$agency) return back()->with('error', 'Agency profile not found.');

        $validated = $request->validate([
            'privacy_policy' => 'required|string',
        ]);

        $agency->update($validated);

        return back()->with('success', 'Privacy Policy updated successfully!');
    }

    public function terms()
    {
        $user = Auth::user();
        $agency = $this->getAgency();
        return view('whitelabel.website.terms', compact('user', 'agency'));
    }

    public function updateTerms(Request $request)
    {
        $agency = $this->getAgency();
        if (!$agency) return back()->with('error', 'Agency profile not found.');

        $validated = $request->validate([
            'terms_conditions' => 'required|string',
        ]);

        $agency->update($validated);

        return back()->with('success', 'Terms & Conditions updated successfully!');
    }

    public function shipping()
    {
        $user = Auth::user();
        $agency = $this->getAgency();
        return view('whitelabel.website.shipping', compact('user', 'agency'));
    }

    public function updateShipping(Request $request)
    {
        $agency = $this->getAgency();
        if (!$agency) return back()->with('error', 'Agency profile not found.');

        $validated = $request->validate([
            'shipping_policy' => 'required|string',
        ]);

        $agency->update($validated);

        return back()->with('success', 'Shipping & Delivery Policy updated successfully!');
    }

    public function refund()
    {
        $user = Auth::user();
        $agency = $this->getAgency();
        return view('whitelabel.website.refund', compact('user', 'agency'));
    }

    public function updateRefund(Request $request)
    {
        $agency = $this->getAgency();
        if (!$agency) return back()->with('error', 'Agency profile not found.');

        $validated = $request->validate([
            'refund_policy' => 'required|string',
        ]);

        $agency->update($validated);

        return back()->with('success', 'Cancellation & Refund Policy updated successfully!');
    }

    public function cookies()
    {
        $user = Auth::user();
        $agency = $this->getAgency();
        return view('whitelabel.website.cookies', compact('user', 'agency'));
    }

    public function updateCookies(Request $request)
    {
        $agency = $this->getAgency();
        if (!$agency) return back()->with('error', 'Agency profile not found.');

        $validated = $request->validate([
            'cookie_policy' => 'required|string',
        ]);

        $agency->update($validated);

        return back()->with('success', 'Cookie Policy updated successfully!');
    }

    public function pricing()
    {
        $user = Auth::user();
        $agency = $this->getAgency();
        $pricingPlans = $agency ? $agency->parsed_pricing_plans : [];
        return view('whitelabel.website.pricing', compact('user', 'agency', 'pricingPlans'));
    }

    public function updatePricing(Request $request)
    {
        $agency = $this->getAgency();
        if (!$agency) return back()->with('error', 'Agency profile not found.');

        $sectionTitle    = $request->input('pricing_section_title', 'Choose Your Perfect Plan');
        $sectionSubtitle = $request->input('pricing_section_subtitle', 'Scale seamlessly with zero hidden fees.');
        $badgeText       = $request->input('pricing_badge_text', 'Flexible Pricing Plans');
        $yearlyDiscount  = $request->input('pricing_yearly_discount', 'Save 20%');
        $trustBar        = $request->input('pricing_trust_bar', '🔒 Secure & Reliable,📞 24/7 Support,❤️ Trusted by 10,000+ Businesses');

        // Parse Trust Elements (3 cards below pricing)
        $trustIcons   = $request->input('trust_item_icon', []);
        $trustTitles  = $request->input('trust_item_title', []);
        $trustDescs   = $request->input('trust_item_desc', []);
        $trustBgs     = $request->input('trust_item_bg', []);
        $trustColors  = $request->input('trust_item_color', []);
        $trustElements = [];
        for ($t = 0; $t < count($trustTitles); $t++) {
            if (!empty(trim($trustTitles[$t] ?? ''))) {
                $trustElements[] = [
                    'icon'  => trim($trustIcons[$t] ?? 'shield-check'),
                    'title' => trim($trustTitles[$t]),
                    'desc'  => trim($trustDescs[$t] ?? ''),
                    'bg'    => trim($trustBgs[$t] ?? '#d1fae5'),
                    'color' => trim($trustColors[$t] ?? '#059669'),
                ];
            }
        }

        // Build per-product plan array from repeater inputs
        $productNames     = $request->input('product_name', []);
        $productLogos     = $request->input('product_logo', []);
        $productImages    = $request->input('product_image', []);
        $productTaglines  = $request->input('product_tagline', []);
        $productTitles    = $request->input('product_title', []);
        $productSubtitles = $request->input('product_subtitle', []);
        $leftBgs          = $request->input('left_bg', []);
        $productColors    = $request->input('product_color', []);
        $productGrads     = $request->input('product_gradient', []);
        $productIcons     = $request->input('product_icon', []);
        $planBadges       = $request->input('plan_badge', []);
        $planSubbadges    = $request->input('plan_subbadge', []);
        $planNames        = $request->input('plan_name', []);
        $planSubtitles    = $request->input('plan_subtitle', []);
        $pricesMonthly    = $request->input('price_monthly', []);
        $pricesYearly     = $request->input('price_yearly', []);
        $trustCounts      = $request->input('trust_count', []);
        $ctaTexts         = $request->input('cta_text', []);
        $ctaSubnotes      = $request->input('cta_subnote', []);
        $ctaUrls          = $request->input('cta_url', []);
        $isPopulars       = $request->input('is_popular', []);
        $featuresRaw      = $request->input('features', []);

        $uploadDir = public_path('uploads/agency_pricing');
        if (!file_exists($uploadDir)) {
            @mkdir($uploadDir, 0777, true);
        }

        $plans = [];
        for ($i = 0; $i < count($productNames); $i++) {
            if (!empty($productNames[$i])) {
                $featureLines = array_filter(
                    array_map('trim', explode("\n", $featuresRaw[$i] ?? ''))
                );

                // Handle product_logo file upload if provided
                // Handle product_logo file upload if provided
                $logoPath = $productLogos[$i] ?? '';
                if ($request->hasFile("product_logo_file.{$i}")) {
                    $saved = $this->saveSecureUpload($request->file("product_logo_file.{$i}"), 'logo_' . $i, 'uploads/agency_pricing');
                    if ($saved) $logoPath = $saved;
                }

                // Handle product_image mockup file upload if provided
                $imagePath = $productImages[$i] ?? '';
                if ($request->hasFile("product_image_file.{$i}")) {
                    $saved = $this->saveSecureUpload($request->file("product_image_file.{$i}"), 'mockup_' . $i, 'uploads/agency_pricing');
                    if ($saved) $imagePath = $saved;
                }

                $plans[] = [
                    'product_name'     => $productNames[$i],
                    'product_logo'     => $logoPath,
                    'product_image'    => $imagePath,
                    'product_tagline'  => $productTaglines[$i]  ?? '',
                    'product_title'    => $productTitles[$i]    ?? '',
                    'product_subtitle' => $productSubtitles[$i] ?? '',
                    'left_bg'          => $leftBgs[$i]          ?? '#fff5ee',
                    'color'            => $productColors[$i]    ?? '#ea580c',
                    'gradient'         => $productGrads[$i]     ?? 'linear-gradient(135deg,#f97316,#ea580c)',
                    'icon'             => $productIcons[$i]     ?? 'layers',
                    'plan_badge'       => $planBadges[$i]       ?? 'PRO PLAN',
                    'plan_subbadge'    => $planSubbadges[$i]    ?? '',
                    'plan_name'        => $planNames[$i]        ?? '',
                    'plan_subtitle'    => $planSubtitles[$i]    ?? '',
                    'price_monthly'    => $pricesMonthly[$i]    ?? '0',
                    'price_yearly'     => $pricesYearly[$i]     ?? '0',
                    'trust_count'      => $trustCounts[$i]      ?? '',
                    'cta_text'         => $ctaTexts[$i]         ?? 'Get Started',
                    'cta_subnote'      => $ctaSubnotes[$i]      ?? '',
                    'cta_url'          => $ctaUrls[$i]          ?? ($agency->cta_url ?? '/login'),
                    'is_popular'       => !empty($isPopulars[$i]),
                    'features'         => array_values($featureLines),
                ];
            }
        }

        $agency->update([
            'pricing_plans_data'     => json_encode($plans),
            'pricing_section_title'  => $sectionTitle,
            'pricing_section_subtitle' => $sectionSubtitle,
            'pricing_trust_bar'      => $trustBar,
        ]);

        AuditLog::create([
            'user_id'   => auth()->id(),
            'user_name' => auth()->user()->name ?? 'Agency Owner',
            'action'    => "Updated pricing plans for {$agency->name}",
            'ip_address'=> $request->ip(),
        ]);

        return back()->with('success', 'Pricing plans updated successfully!');
    }

    public function preview()
    {
        $agency = $this->getAgency();
        if ($agency && !empty($agency->custom_domain)) {
            $domain = preg_replace('#^https?://#', '', trim($agency->custom_domain));
            return redirect()->away("https://{$domain}");
        }
        return redirect()->route('whitelabel.website.landing')->with('info', 'Please set up a Custom Domain first to preview your website live.');
    }
}
