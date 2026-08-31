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
                <textarea name="privacy_policy" rows="18" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-4 text-xs font-mono leading-relaxed focus:outline-none focus:border-blue-500">{{ old('privacy_policy', $agency->privacy_policy ?? "<h2>Privacy Policy for {$agency->name}</h2>\n<p>At {$agency->name}, accessible from {$agency->custom_domain}, one of our main priorities is the privacy of our visitors and clients. This Privacy Policy document contains types of information that is collected and recorded by {$agency->name} and how we use it.</p>\n\n<h3>1. Information We Collect</h3>\n<p>We collect information you provide directly to us when registering for services, requesting support, or filling contact forms (such as name, email address, phone number, and business details).</p>\n\n<h3>2. How We Use Your Information</h3>\n<p>We use the collected information to provide, operate, maintain, improve, and personalize our software products and services for your business.</p>\n\n<h3>3. Data Protection & Security</h3>\n<p>We take industry-standard measures to protect your data against unauthorized access, alteration, disclosure, or destruction.</p>\n\n<h3>4. Contact Us</h3>\n<p>If you have any questions or require more information about our Privacy Policy, do not hesitate to contact us at <strong>{$agency->contact_email}</strong>.</p>") }}</textarea>
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
