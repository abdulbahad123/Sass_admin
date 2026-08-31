@extends('layouts.whitelabel')

@section('title', 'Website - Contact Information')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 font-heading">Contact Details & Social Links</h1>
        <p class="text-xs text-slate-500">Configure business address, phone, email, and social profiles for public website footer</p>
    </div>

    <form action="{{ route('whitelabel.website.contact.update') }}" method="POST" class="space-y-6">
        @csrf
        <div class="card-white rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 font-heading border-b border-slate-100 pb-3 flex items-center space-x-2">
                <i data-lucide="phone" class="w-4 h-4 text-emerald-600"></i>
                <span>Direct Contact Channels</span>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Contact Email</label>
                    <input type="email" name="contact_email" value="{{ old('contact_email', $agency->contact_email ?? $agency->email) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Contact Phone</label>
                    <input type="text" name="contact_phone" value="{{ old('contact_phone', $agency->contact_phone ?? $agency->phone) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold focus:outline-none focus:border-blue-500">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Office Address</label>
                <textarea name="contact_address" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs focus:outline-none focus:border-blue-500">{{ old('contact_address', $agency->contact_address) }}</textarea>
            </div>
        </div>

        <div class="card-white rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 font-heading border-b border-slate-100 pb-3 flex items-center space-x-2">
                <i data-lucide="share-2" class="w-4 h-4 text-blue-600"></i>
                <span>Social Media Links</span>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Facebook Page URL</label>
                    <input type="url" name="facebook" value="{{ $socialLinks['facebook'] ?? '' }}" placeholder="https://facebook.com/youragency" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Instagram URL</label>
                    <input type="url" name="instagram" value="{{ $socialLinks['instagram'] ?? '' }}" placeholder="https://instagram.com/youragency" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">YouTube Channel URL</label>
                    <input type="url" name="youtube" value="{{ $socialLinks['youtube'] ?? '' }}" placeholder="https://youtube.com/@youragency" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">LinkedIn Company Page</label>
                    <input type="url" name="linkedin" value="{{ $socialLinks['linkedin'] ?? '' }}" placeholder="https://linkedin.com/company/youragency" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">X (Twitter) Profile</label>
                    <input type="url" name="twitter" value="{{ $socialLinks['twitter'] ?? '' }}" placeholder="https://x.com/youragency" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs">
                </div>
            </div>
        </div>

        <div class="card-white rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 font-heading border-b border-slate-100 pb-3 flex items-center space-x-2">
                <i data-lucide="copyright" class="w-4 h-4 text-purple-600"></i>
                <span>Footer Copyright & Summary</span>
            </h3>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Footer Tagline / Short Summary</label>
                <textarea name="footer_content" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs focus:outline-none focus:border-blue-500">{{ old('footer_content', $agency->footer_content ?? 'Powering the growth of local businesses with smart digital solutions.') }}</textarea>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs shadow-lg shadow-blue-600/30 transition-all">
                Save Contact & Social Info
            </button>
        </div>
    </form>
</div>
@endsection
