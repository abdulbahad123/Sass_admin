@extends('layouts.whitelabel')

@section('title', 'Website - Shipping & Delivery Policy')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 font-heading">Shipping & Delivery Policy Editor</h1>
        <p class="text-xs text-slate-500">Configure Razorpay compliant Shipping & Delivery Policy displayed at <code>/shipping-policy</code></p>
    </div>

    <form action="{{ route('whitelabel.website.shipping.update') }}" method="POST" class="space-y-6">
        @csrf
        <div class="card-white rounded-2xl p-6 space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Shipping & Delivery Policy Text / HTML</label>
                <textarea name="shipping_policy" rows="18" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-4 text-xs font-mono leading-relaxed focus:outline-none focus:border-blue-500">{{ old('shipping_policy', $agency->shipping_policy ?? "<h2>Shipping & Delivery Policy for {$agency->name}</h2>\n<p>This Shipping & Delivery Policy explains the terms and timelines associated with the fulfillment of services and digital software products provided by {$agency->name}.</p>\n\n<h3>1. Service & Digital Product Delivery</h3>\n<p>All software access, digital tools, user accounts, and SaaS subscriptions purchased through {$agency->name} are delivered electronically. Upon successful payment verification, login credentials and dashboard access will be dispatched to your registered email address instantly (within 5 to 15 minutes).</p>\n\n<h3>2. Physical Goods & Hardware (If Applicable)</h3>\n<p>For any physical collateral or hardware accessories (such as QR standees or POS terminals), orders are dispatched within 2 to 3 business days through registered courier partners. Delivery typically takes 5 to 7 business days depending on destination pincode.</p>\n\n<h3>3. Delivery Delays & Tracking</h3>\n<p>If you do not receive your digital account access or order confirmation within 24 hours, please reach out directly to our support team with your transaction reference number.</p>\n\n<h3>4. Support & Contact</h3>\n<p>For any queries regarding fulfillment and delivery, contact us at <strong>{$agency->contact_email}</strong> or phone <strong>{$agency->contact_phone}</strong>.</p>") }}</textarea>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs shadow-lg shadow-blue-600/30 transition-all">
                Save Shipping Policy
            </button>
        </div>
    </form>
</div>
@endsection
