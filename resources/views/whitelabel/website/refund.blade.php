@extends('layouts.whitelabel')

@section('title', 'Website - Cancellation & Refund Policy')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 font-heading">Cancellation & Refund Policy Editor</h1>
        <p class="text-xs text-slate-500">Configure Razorpay compliant Cancellation & Refund Policy displayed at <code>/refund-policy</code></p>
    </div>

    <form action="{{ route('whitelabel.website.refund.update') }}" method="POST" class="space-y-6">
        @csrf
        <div class="card-white rounded-2xl p-6 space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Cancellation & Refund Policy Text / HTML</label>
                <textarea name="refund_policy" rows="18" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-4 text-xs font-mono leading-relaxed focus:outline-none focus:border-blue-500">{{ old('refund_policy', $agency->refund_policy ?? "<h2>Cancellation & Refund Policy for {$agency->name}</h2>\n<p>Thank you for choosing {$agency->name}. We strive to deliver the highest quality digital products, software tools, and solutions.</p>\n\n<h3>1. No Refunds / All Sales Are Final</h3>\n<p>All purchases made through https://{$agency->custom_domain} or via manual payment methods are final and non-refundable once software access credentials or setup tools have been activated.</p>\n\n<h3>2. Why We Do Not Offer Refunds</h3>\n<p>Because our software products and services involve instant digital access, cloud resource allocation, and customized provisioning, digital content cannot be returned once delivered.</p>\n\n<h3>3. Technical Issues</h3>\n<p>If you experience any technical difficulties accessing your services, please contact our support desk immediately and our team will work to resolve the issue promptly.</p>\n\n<h3>4. Contact Support</h3>\n<p>Website: https://{$agency->custom_domain}<br>Phone / WhatsApp: {$agency->contact_phone}<br>Email: {$agency->contact_email}</p>") }}</textarea>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs shadow-lg shadow-blue-600/30 transition-all">
                Save Cancellation & Refund Policy
            </button>
        </div>
    </form>
</div>
@endsection
