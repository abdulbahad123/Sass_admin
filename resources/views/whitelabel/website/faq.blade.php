@extends('layouts.whitelabel')

@section('title', 'Website - FAQ')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 font-heading">Frequently Asked Questions (FAQ)</h1>
        <p class="text-xs text-slate-500">Manage Q&A items displayed on your agency website</p>
    </div>

    <form action="{{ route('whitelabel.website.faq.update') }}" method="POST" class="space-y-6">
        @csrf
        <div class="card-white rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 font-heading border-b border-slate-100 pb-3 flex items-center space-x-2">
                <i data-lucide="help-circle" class="w-4 h-4 text-indigo-600"></i>
                <span>FAQ Accordion List</span>
            </h3>

            <div id="faqContainer" class="space-y-4">
                @foreach($faq as $index => $item)
                    <div class="faq-item bg-slate-50 border border-slate-200 rounded-xl p-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-600">Question #{{ $index + 1 }}</span>
                            <button type="button" onclick="this.closest('.faq-item').remove()" class="text-rose-500 hover:text-rose-700 text-xs font-bold flex items-center space-x-1">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                <span>Remove</span>
                            </button>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Question</label>
                            <input type="text" name="q[]" value="{{ $item['q'] ?? '' }}" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Answer</label>
                            <textarea name="a[]" rows="2" class="w-full bg-white border border-slate-200 rounded-lg p-2 text-xs focus:outline-none focus:border-blue-500">{{ $item['a'] ?? '' }}</textarea>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" onclick="addFaqRow()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold rounded-xl text-xs flex items-center space-x-1.5 transition-all">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Add Question</span>
            </button>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs shadow-lg shadow-blue-600/30 transition-all">
                Save FAQ List
            </button>
        </div>
    </form>
</div>

<script>
    function addFaqRow() {
        const container = document.getElementById('faqContainer');
        const count = container.children.length + 1;
        const html = `
            <div class="faq-item bg-slate-50 border border-slate-200 rounded-xl p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-600">Question #${count}</span>
                    <button type="button" onclick="this.closest('.faq-item').remove()" class="text-rose-500 hover:text-rose-700 text-xs font-bold flex items-center space-x-1">
                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                        <span>Remove</span>
                    </button>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Question</label>
                    <input type="text" name="q[]" placeholder="Enter question..." class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Answer</label>
                    <textarea name="a[]" rows="2" placeholder="Enter detailed answer..." class="w-full bg-white border border-slate-200 rounded-lg p-2 text-xs focus:outline-none focus:border-blue-500"></textarea>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        lucide.createIcons();
    }
</script>
@endsection
