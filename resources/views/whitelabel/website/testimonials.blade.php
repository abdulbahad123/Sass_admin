@extends('layouts.whitelabel')

@section('title', 'Website - Testimonials')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 font-heading">Client Testimonials</h1>
        <p class="text-xs text-slate-500">Manage reviews and social proof testimonials displayed under "Loved by Business Owners"</p>
    </div>

    <form action="{{ route('whitelabel.website.testimonials.update') }}" method="POST" class="space-y-6">
        @csrf
        <div class="card-white rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 font-heading border-b border-slate-100 pb-3 flex items-center space-x-2">
                <i data-lucide="message-square" class="w-4 h-4 text-amber-500"></i>
                <span>Customer Reviews</span>
            </h3>

            <div id="testimonialsContainer" class="space-y-4">
                @foreach($testimonials as $index => $t)
                    <div class="testimonial-item bg-slate-50 border border-slate-200 rounded-xl p-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-600">Review #{{ $index + 1 }}</span>
                            <button type="button" onclick="this.closest('.testimonial-item').remove()" class="text-rose-500 hover:text-rose-700 text-xs font-bold flex items-center space-x-1">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                <span>Remove</span>
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Customer Name</label>
                                <input type="text" name="name[]" value="{{ $t['name'] ?? '' }}" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Business / Role & Location</label>
                                <input type="text" name="role[]" value="{{ $t['role'] ?? '' }}" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Star Rating (1-5)</label>
                                <input type="number" min="1" max="5" name="rating[]" value="{{ $t['rating'] ?? 5 }}" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-bold">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Review Quote / Comment</label>
                            <textarea name="comment[]" rows="2" class="w-full bg-white border border-slate-200 rounded-lg p-2 text-xs focus:outline-none focus:border-blue-500">{{ $t['comment'] ?? '' }}</textarea>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" onclick="addTestimonialRow()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold rounded-xl text-xs flex items-center space-x-1.5 transition-all">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Add Review</span>
            </button>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs shadow-lg shadow-blue-600/30 transition-all">
                Save Testimonials
            </button>
        </div>
    </form>
</div>

<script>
    function addTestimonialRow() {
        const container = document.getElementById('testimonialsContainer');
        const count = container.children.length + 1;
        const html = `
            <div class="testimonial-item bg-slate-50 border border-slate-200 rounded-xl p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-600">Review #${count}</span>
                    <button type="button" onclick="this.closest('.testimonial-item').remove()" class="text-rose-500 hover:text-rose-700 text-xs font-bold flex items-center space-x-1">
                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                        <span>Remove</span>
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Customer Name</label>
                        <input type="text" name="name[]" placeholder="Rahul Sharma" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Business / Role & Location</label>
                        <input type="text" name="role[]" placeholder="Restaurant Owner, Delhi" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Star Rating (1-5)</label>
                        <input type="number" min="1" max="5" name="rating[]" value="5" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-bold">
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Review Quote / Comment</label>
                    <textarea name="comment[]" rows="2" placeholder="Write testimonial here..." class="w-full bg-white border border-slate-200 rounded-lg p-2 text-xs focus:outline-none focus:border-blue-500"></textarea>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        lucide.createIcons();
    }
</script>
@endsection
