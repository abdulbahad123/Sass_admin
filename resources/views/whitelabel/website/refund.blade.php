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
                <textarea name="refund_policy" rows="18" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-4 text-xs font-mono leading-relaxed focus:outline-none focus:border-blue-500">{{ old('refund_policy', $agency->refund_policy ?? "<h2>Cancellation & Refund Policy for {$agency->name}</h2>\n<p>At {$agency->name}, customer satisfaction is our top priority. This policy outlines the terms for subscription cancellations and refund requests.</p>\n\n<h3>1. Subscription Cancellation</h3>\n<p>You may cancel your recurring subscription at any time directly from your user dashboard or by contacting our support team. Upon cancellation, your account will remain active until the end of the current paid billing cycle, and no further recurring billing will occur.</p>\n\n<h3>2. Refund Eligibility & Money-Back Guarantee</h3>\n<p>We offer a <strong>7-Day Risk-Free Money-Back Guarantee</strong> for all new subscription signups. If you are dissatisfied with our software or services within the first 7 days of initial purchase, you are eligible for a 100% full refund.</p>\n\n<h3>3. Processing Refunds</h3>\n<p>Once your refund request is approved by our billing team, the amount will be automatically credited back to your original payment method (Credit/Debit Card, Net Banking, UPI, or Wallet) within 5 to 7 business days as per banking partner timelines.</p>\n\n<h3>4. Contact Support</h3>\n<p>To request a cancellation or refund, please send your order ID and invoice details to <strong>{$agency->contact_email}</strong> or call us at <strong>{$agency->contact_phone}</strong>.</p>") }}</textarea>
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
