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
                <textarea name="terms_conditions" rows="18" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-4 text-xs font-mono leading-relaxed focus:outline-none focus:border-blue-500">{{ old('terms_conditions', $agency->terms_conditions ?? "<h2>Terms & Conditions for {$agency->name}</h2>\n<p>Welcome to {$agency->name}! These terms and conditions outline the rules and regulations for the use of {$agency->name}'s Website and SaaS products.</p>\n\n<h3>1. Acceptance of Terms</h3>\n<p>By accessing this website and using our products, you accept these terms and conditions in full. Do not continue to use {$agency->name} if you do not accept all of the terms stated on this page.</p>\n\n<h3>2. Account Registration</h3>\n<p>You agree to provide true, accurate, current, and complete information when registering an account and using our platform.</p>\n\n<h3>3. Usage & Intellectual Property</h3>\n<p>Unless otherwise stated, {$agency->name} and/or its licensors own the intellectual property rights for all material and technology on {$agency->name}. All intellectual property rights are reserved.</p>\n\n<h3>4. Contact Support</h3>\n<p>If you have any questions regarding these Terms & Conditions, please contact us at <strong>{$agency->contact_email}</strong>.</p>") }}</textarea>
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
