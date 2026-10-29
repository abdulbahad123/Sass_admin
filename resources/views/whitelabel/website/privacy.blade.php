@extends('layouts.whitelabel')

@section('title', 'Website - Privacy Policy')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 font-heading">Dynamic Privacy Policy Editor</h1>
        <p class="text-xs text-slate-500">Configure white-label agency Privacy Policy text displayed at <code>/privacy-policy</code></p>
    </div>

    <form action="{{ route('whitelabel.website.privacy.update') }}" method="POST" class="space-y-6">
        @csrf
        <div class="card-white rounded-2xl p-6 space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Privacy Policy Document Text / HTML</label>
                <textarea name="privacy_policy" rows="18" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-4 text-xs font-mono leading-relaxed focus:outline-none focus:border-blue-500">{{ old('privacy_policy', $agency->privacy_policy ?? "<h2>Privacy Policy for {$agency->name}</h2>\n<p>At {$agency->name} (accessible at https://{$agency->custom_domain}), we respect your privacy and are committed to protecting the personal information you share with us. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website or use our services.</p>\n\n<p>By accessing or using our website and services, you agree to the terms of this Privacy Policy.</p>\n\n<h3>1. Information We Collect</h3>\n<p>We may collect the following types of information:</p>\n<h4>1.1 Personal Information</h4>\n<p>When you register, contact us, or make a purchase, we may collect: Name, Email address, Phone number, Business name, and Billing/payment-related verification details.</p>\n<h4>1.2 Non-Personal Information</h4>\n<p>IP address, Browser type, Device information, Pages visited, and Referring URLs for security and performance optimization.</p>\n\n<h3>2. How We Use Your Information</h3>\n<ul>\n<li>Provide and deliver our software products and services</li>\n<li>Verify payments and setup portal access</li>\n<li>Communicate regarding inquiries, orders, and support</li>\n<li>Improve website functionality and prevent unauthorized abuse</li>\n</ul>\n\n<h3>3. Manual Payments & Data Usage</h3>\n<p>For manual payments (UPI/bank transfer), payment reference details or screenshots are collected strictly for verification and accounting purposes.</p>\n\n<h3>4. Cookies & Tracking Technologies</h3>\n<p>We use essential cookies to maintain user sessions, enhance performance, and improve user experience.</p>\n\n<h3>5. Sharing of Information</h3>\n<p>We do not sell, trade, or rent personal information to third parties.</p>\n\n<h3>6. Data Security</h3>\n<p>We implement industry-standard security protocols to safeguard your information.</p>\n\n<h3>7. Contact Us</h3>\n<p>Website: https://{$agency->custom_domain}<br>Phone / WhatsApp: {$agency->contact_phone}<br>Email: {$agency->contact_email}</p>") }}</textarea>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs shadow-lg shadow-blue-600/30 transition-all">
                Save Privacy Policy
            </button>
        </div>
    </form>
</div>
@endsection
