@extends('layouts.whitelabel')

@section('title', 'Website - Services')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 font-heading">Services & Product Cards</h1>
            <p class="text-xs text-slate-500">Configure the tools and solutions displayed under "Smart Tools for Smarter Businesses"</p>
        </div>
    </div>

    <form action="{{ route('whitelabel.website.services.update') }}" method="POST" class="space-y-6">
        @csrf
        <div class="card-white rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 font-heading border-b border-slate-100 pb-3 flex items-center space-x-2">
                <i data-lucide="layers" class="w-4 h-4 text-blue-600"></i>
                <span>Agency Product Suite Items</span>
            </h3>

            <div id="servicesContainer" class="space-y-4">
                @foreach($services as $index => $s)
                    <div class="service-item bg-slate-50 border border-slate-200 rounded-xl p-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-600">Item #{{ $index + 1 }}</span>
                            <button type="button" onclick="this.closest('.service-item').remove()" class="text-rose-500 hover:text-rose-700 text-xs font-bold flex items-center space-x-1">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                <span>Remove</span>
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Title</label>
                                <input type="text" name="title[]" value="{{ $s['title'] ?? '' }}" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Lucide Icon Name</label>
                                <input type="text" name="icon[]" value="{{ $s['icon'] ?? 'box' }}" placeholder="star, globe, credit-card, etc." class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-mono">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Description</label>
                            <input type="text" name="desc[]" value="{{ $s['desc'] ?? '' }}" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs">
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" onclick="addServiceRow()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold rounded-xl text-xs flex items-center space-x-1.5 transition-all">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Add Another Service</span>
            </button>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs shadow-lg shadow-blue-600/30 transition-all">
                Save Services List
            </button>
        </div>
    </form>
</div>

<script>
    function addServiceRow() {
        const container = document.getElementById('servicesContainer');
        const count = container.children.length + 1;
        const html = `
            <div class="service-item bg-slate-50 border border-slate-200 rounded-xl p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-600">Item #${count}</span>
                    <button type="button" onclick="this.closest('.service-item').remove()" class="text-rose-500 hover:text-rose-700 text-xs font-bold flex items-center space-x-1">
                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                        <span>Remove</span>
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Title</label>
                        <input type="text" name="title[]" placeholder="Service title" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-semibold focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Lucide Icon Name</label>
                        <input type="text" name="icon[]" placeholder="star, globe, credit-card, etc." class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-mono">
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Description</label>
                    <input type="text" name="desc[]" placeholder="Short description" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs">
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        lucide.createIcons();
    }
</script>
@endsection
