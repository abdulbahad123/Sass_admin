@extends('layouts.whitelabel')

@section('title', 'Website - Cookie Policy')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 font-heading">Cookie Policy Editor</h1>
        <p class="text-xs text-slate-500">Configure white-label agency Cookie Policy displayed at <code>/cookie-policy</code></p>
    </div>

    <form action="{{ route('whitelabel.website.cookies.update') }}" method="POST" class="space-y-6">
        @csrf
        <div class="card-white rounded-2xl p-6 space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Cookie Policy Document Text / HTML</label>
                <textarea name="cookie_policy" rows="18" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-4 text-xs font-mono leading-relaxed focus:outline-none focus:border-blue-500">{{ old('cookie_policy', $agency->cookie_policy ?? "<h2>Cookie Policy for {$agency->name}</h2>\n<p>This is the Cookie Policy for {$agency->name}, accessible from {$agency->custom_domain}.</p>\n\n<h3>What Are Cookies</h3>\n<p>As is common practice with almost all professional websites, this site uses cookies, which are tiny files downloaded to your computer, to improve your experience.</p>\n\n<h3>How We Use Cookies</h3>\n<p>We use cookies for a variety of reasons detailed below. Unfortunately, in most cases, there are no industry standard options for disabling cookies without completely disabling the functionality and features they add to this site.</p>\n\n<h3>Essential Cookies</h3>\n<p>We use essential cookies to manage user authentication sessions and remember user preferences across visits.</p>\n\n<h3>Contact Us</h3>\n<p>For more information, feel free to reach out via <strong>{$agency->contact_email}</strong>.</p>") }}</textarea>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs shadow-lg shadow-blue-600/30 transition-all">
                Save Cookie Policy
            </button>
        </div>
    </form>
</div>
@endsection
