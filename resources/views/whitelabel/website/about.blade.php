@extends('layouts.whitelabel')

@section('title', 'Website - About Us')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 font-heading">About Section Configuration</h1>
        <p class="text-xs text-slate-500">Manage the story, background, and features of your agency for the public website</p>
    </div>

    <form action="{{ route('whitelabel.website.about.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <div class="card-white rounded-2xl p-6 space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">About Main Heading</label>
                <input type="text" name="about_title" value="{{ old('about_title', $agency->about_title ?? 'Built for entrepreneurs, by entrepreneurs.') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold focus:outline-none focus:border-blue-500" placeholder="e.g. Built for entrepreneurs, by entrepreneurs.">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">About Mission Statement / Subheading</label>
                <textarea name="about_mission" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3.5 text-xs focus:outline-none focus:border-blue-500" placeholder="e.g. Our mission is to help Indian entrepreneurs automate repetitive operations...">{{ old('about_mission', $agency->about_mission ?? 'Our mission is to help Indian entrepreneurs automate repetitive operations, boost sales revenue, build customer loyalty, and scale seamlessly without juggling multiple expensive tools.') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">About Section Detailed Content</label>
                <textarea name="about_content" rows="6" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-4 text-xs leading-relaxed focus:outline-none focus:border-blue-500" placeholder="Describe your agency mission, values, and client approach...">{{ old('about_content', $agency->about_content) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">About Banner Image</label>
                <input type="file" name="about_image" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                @if($agency->about_image)
                    <img src="{{ asset(ltrim($agency->about_image, '/')) }}" alt="About" class="h-28 mt-2 rounded-xl object-cover border border-slate-200">
                @endif
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs shadow-lg shadow-blue-600/30 transition-all">
                Save About Section
            </button>
        </div>
    </form>
</div>
@endsection
