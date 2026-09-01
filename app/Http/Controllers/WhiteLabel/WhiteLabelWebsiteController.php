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
        $user = Auth::user();
        return $user->agency ?? Agency::where('type', 'white_label')->first();
    }

    public function landing()
    {
        $user = Auth::user();
        $agency = $this->getAgency();
        return view('whitelabel.website.landing', compact('user', 'agency'));
    }

    public function updateLanding(Request $request)
    {
        $agency = $this->getAgency();
        if (!$agency) return back()->with('error', 'Agency profile not found.');

        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'custom_domain'    => 'nullable|string|max:255',
            'primary_color'    => 'nullable|string|max:20',
            'secondary_color'  => 'nullable|string|max:20',
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
            'contact_email'    => 'nullable|string|max:255',
            'contact_phone'    => 'nullable|string|max:255',
            'facebook_url'     => 'nullable|string|max:500',
            'instagram_url'    => 'nullable|string|max:500',
            'youtube_url'      => 'nullable|string|max:500',
            'linkedin_url'     => 'nullable|string|max:500',
            'twitter_url'      => 'nullable|string|max:500',
        ]);

        $uploadDir = public_path('uploads/agency');
        if (!file_exists($uploadDir)) {
            @mkdir($uploadDir, 0777, true);
        }

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $fileName = 'logo_' . time() . '_' . \Illuminate\Support\Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $fileName);
            $validated['logo'] = 'uploads/agency/' . $fileName;
        }

        if ($request->hasFile('favicon')) {
            $file = $request->file('favicon');
            $fileName = 'favicon_' . time() . '_' . \Illuminate\Support\Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $fileName);
            $validated['favicon'] = 'uploads/agency/' . $fileName;
        }

        if ($request->hasFile('hero_image')) {
            $file = $request->file('hero_image');
            $fileName = 'hero_' . time() . '_' . \Illuminate\Support\Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $fileName);
            $validated['hero_image'] = 'uploads/agency/' . $fileName;
        }

        if ($request->hasFile('about_image')) {
            $file = $request->file('about_image');
            $fileName = 'about_' . time() . '_' . \Illuminate\Support\Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $fileName);
            $validated['about_image'] = 'uploads/agency/' . $fileName;
        }

        if ($request->hasFile('cta_image')) {
            $file = $request->file('cta_image');
            $fileName = 'cta_' . time() . '_' . \Illuminate\Support\Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $fileName);
            $validated['cta_image'] = 'uploads/agency/' . $fileName;
        }

        // Section toggles
        if ($request->has('sections')) {
            $validated['sections_enabled'] = json_encode($request->input('sections'));
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
        ]);

        if ($request->hasFile('about_image')) {
            $uploadDir = public_path('uploads/agency');
            if (!file_exists($uploadDir)) {
                @mkdir($uploadDir, 0777, true);
            }
            $file = $request->file('about_image');
            $fileName = 'about_' . time() . '_' . \Illuminate\Support\Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $fileName);
            $validated['about_image'] = 'uploads/agency/' . $fileName;
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
        $testimonials = $agency->parsed_testimonials;
        return view('whitelabel.website.testimonials', compact('user', 'agency', 'testimonials'));
    }

    public function updateTestimonials(Request $request)
    {
        $agency = $this->getAgency();
        if (!$agency) return back()->with('error', 'Agency profile not found.');

        $names = $request->input('name', []);
        $roles = $request->input('role', []);
        $comments = $request->input('comment', []);
        $ratings = $request->input('rating', []);

        $testimonialsData = [];
        for ($i = 0; $i < count($names); $i++) {
            if (!empty($names[$i])) {
                $testimonialsData[] = [
                    'name' => $names[$i],
                    'role' => $roles[$i] ?? '',
                    'comment' => $comments[$i] ?? '',
                    'rating' => (int)($ratings[$i] ?? 5),
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

    public function contact()
    {
        $user = Auth::user();
        $agency = $this->getAgency();
        $socialLinks = is_array($agency->social_links) ? $agency->social_links : (json_decode($agency->social_links, true) ?: []);
        return view('whitelabel.website.contact', compact('user', 'agency', 'socialLinks'));
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

        return back()->with('success', 'Contact details & footer updated successfully!');
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
