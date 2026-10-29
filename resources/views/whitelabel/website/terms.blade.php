@extends('layouts.whitelabel')

@section('title', 'Website - Terms & Conditions')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 font-heading">Terms & Conditions Editor</h1>
        <p class="text-xs text-slate-500">Configure white-label agency Terms & Conditions displayed at <code>/terms-conditions</code></p>
    </div>

    <form action="{{ route('whitelabel.website.terms.update') }}" method="POST" class="space-y-6">
        @csrf
        <div class="card-white rounded-2xl p-6 space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Terms & Conditions Document Text / HTML</label>
                <textarea name="terms_conditions" rows="18" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-4 text-xs font-mono leading-relaxed focus:outline-none focus:border-blue-500">{{ old('terms_conditions', $agency->terms_conditions ?? "<h2>Terms and Conditions for {$agency->name}</h2>\n<p>Welcome to {$agency->name} (accessible at https://{$agency->custom_domain}). These Terms and Conditions govern your access to and use of our website, software products, and services. By accessing our platform or purchasing a plan, you agree to these Terms.</p>\n\n<h3>1. About Our Services</h3>\n<p>{$agency->name} provides digital products, software tools, white-label SaaS solutions, and related digital services.</p>\n\n<h3>2. Eligibility</h3>\n<p>You confirm that you are at least 18 years old and eligible to enter into a binding agreement.</p>\n\n<h3>3. Pricing & Payments</h3>\n<p>We accept payments through online payment gateways and manual transactions (UPI / Bank transfer). All payments are for {$agency->name}'s software services.</p>\n\n<h3>4. No Refund & No Cancellation Policy</h3>\n<p>All sales of digital software access and white-label packages are final and non-refundable once service access is provisioned.</p>\n\n<h3>5. Service Delivery</h3>\n<p>Software credentials and platform access are provisioned electronically following payment verification.</p>\n\n<h3>6. User Responsibilities</h3>\n<p>You agree not to misuse, copy, or redistribute our products unless authorized, and to maintain the confidentiality of your account credentials.</p>\n\n<h3>7. Source Code & Infrastructure</h3>\n<p>All systems and backend infrastructure remain hosted on our cloud servers. Source code is proprietary and not transferred or sold.</p>\n\n<h3>8. Contact Us</h3>\n<p>Website: https://{$agency->custom_domain}<br>Phone / WhatsApp: {$agency->contact_phone}<br>Email: {$agency->contact_email}</p>") }}</textarea>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs shadow-lg shadow-blue-600/30 transition-all">
                Save Terms & Conditions
            </button>
        </div>
    </form>
</div>
@endsection
