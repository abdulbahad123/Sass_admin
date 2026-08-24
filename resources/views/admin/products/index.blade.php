@extends('layouts.admin')

@section('title', 'SaaS Products Catalog')
@section('page_title', 'SaaS Products Directory & Launchshop')

@section('header_actions')
<button onclick="document.getElementById('createProductModal').classList.remove('hidden')" 
        class="px-2.5 py-1.5 sm:px-4 sm:py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-600/30 flex items-center space-x-1.5 sm:space-x-2 transition-all whitespace-nowrap">
    <i data-lucide="plus" class="w-4 h-4"></i>
    <span>+ Add Product</span>
</button>
@endsection

@section('content')
<div class="space-y-8" x-data="{ editingProduct: null }">

    <!-- Products Header Banner -->
    <div class="p-6 rounded-2xl bg-gradient-to-r from-indigo-900 via-indigo-800 to-violet-900 text-white shadow-lg flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-start space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center text-white font-bold text-xl flex-shrink-0 border border-white/20">
                <i data-lucide="shopping-bag" class="w-6 h-6"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold font-heading">Product Catalog & Launchshop Control</h2>
                <p class="text-xs text-indigo-200 mt-1 max-w-2xl">
                    Super Admin defines core SaaS products (like <b>Launchshop</b> e-commerce builder). Assign products to pricing plans, edit product parameters, or launch direct single-click credential-free Admin Access.
                </p>
            </div>
        </div>
    </div>

    <!-- Product Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($products as $product)
            <div class="card-white rounded-2xl p-6 flex flex-col justify-between hover:shadow-md transition-all duration-300 relative">
                <div>
                    <!-- Card Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-bold">
                                <i data-lucide="{{ $product->icon ?? 'box' }}" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <div class="flex items-center space-x-2">
                                    <h3 class="text-lg font-bold text-slate-900 font-heading">{{ $product->name }}</h3>
                                    @if($product->is_featured)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700">Featured</span>
                                    @endif
                                </div>
                                <p class="text-[11px] text-indigo-600 font-mono">slug: {{ $product->slug }}</p>
                            </div>
                        </div>

                        <!-- Status Toggle Form -->
                        <form action="{{ route('admin.products.toggle-status', $product) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-2.5 py-1 rounded-full text-[10px] font-bold transition-colors border {{ $product->is_active ? 'bg-emerald-100 text-emerald-700 border-emerald-200 hover:bg-emerald-200' : 'bg-rose-100 text-rose-700 border-rose-200 hover:bg-rose-200' }}" title="Click to toggle status">
                                {{ $product->is_active ? 'Active' : 'Disabled' }}
                            </button>
                        </form>
                    </div>

                    <p class="text-xs font-semibold text-slate-800 mb-1.5">{{ $product->tagline }}</p>
                    <p class="text-xs text-slate-500 leading-relaxed mb-4 line-clamp-3">{{ $product->description }}</p>
                </div>

                <!-- Footer Metadata & Actions -->
                <div>
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 mb-4">
                        <span class="flex items-center font-medium">
                            <i data-lucide="building-2" class="w-3.5 h-3.5 mr-1.5 text-indigo-600"></i>
                            {{ $product->agencies_count }} Agencies
                        </span>
                        <span class="flex items-center font-medium">
                            <i data-lucide="layers" class="w-3.5 h-3.5 mr-1.5 text-violet-600"></i>
                            {{ $product->plans_count }} Plans Included
                        </span>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-2">
                        <div class="p-2 bg-slate-50 border border-slate-100 rounded-xl">
                            <span class="text-[10px] text-slate-400 font-semibold block">Subdomain App Preview:</span>
                            <a href="{{ $product->getSubdomainPreviewUrl() }}" target="_blank" class="text-xs font-mono font-bold text-indigo-600 hover:underline flex items-center truncate">
                                <i data-lucide="globe" class="w-3.5 h-3.5 mr-1 flex-shrink-0 text-indigo-500"></i>
                                <span class="truncate">{{ $product->getSubdomainPreviewUrl() }}</span>
                            </a>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ $product->getSubdomainPreviewUrl() }}" target="_blank" 
                               class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl text-center border border-slate-200 transition-colors flex items-center justify-center space-x-1.5">
                                <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                <span>Live Preview</span>
                            </a>

                            <a href="{{ route('admin.products.admin-launch', $product) }}" target="_blank"
                               class="py-2 px-3 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl text-center shadow-md shadow-indigo-600/20 transition-colors flex items-center justify-center space-x-1.5">
                                <i data-lucide="key" class="w-3.5 h-3.5"></i>
                                <span>Admin Access</span>
                            </a>
                        </div>

                        <!-- Edit Product Button (Task 1) -->
                        <div class="flex items-center justify-between pt-2 border-t border-slate-100 text-xs">
                            <button onclick="openEditProductModal({{ json_encode($product) }})" 
                                    class="text-indigo-600 hover:text-indigo-800 font-bold flex items-center space-x-1">
                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                <span>Edit Product</span>
                            </button>

                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete product {{ $product->name }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-slate-400 hover:text-rose-600 transition-colors flex items-center space-x-1">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    <span>Remove</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Add Product Modal -->
<div id="createProductModal" class="fixed inset-0 z-50 hidden bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-slate-900 font-heading">Add SaaS Product (e.g. Launchshop)</h3>
            <button onclick="document.getElementById('createProductModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('admin.products.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Product Name</label>
                <input type="text" name="name" placeholder="e.g. Launchshop E-Commerce" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:bg-white focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Tagline</label>
                <input type="text" name="tagline" placeholder="Multi-vendor store builder" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:bg-white focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Description</label>
                <textarea name="description" rows="3" placeholder="Explain product features and client capabilities..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:bg-white focus:border-indigo-500"></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Lucide Icon Name</label>
                    <input type="text" name="icon" value="shopping-bag" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">App Endpoint URL</label>
                    <input type="url" name="app_url" placeholder="https://launchshop.app" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                </div>
            </div>

            <div class="flex items-center space-x-2 pt-2">
                <input type="checkbox" name="is_featured" id="is_featured" class="rounded bg-slate-100 border-slate-300 text-indigo-600">
                <label for="is_featured" class="text-xs text-slate-700 font-medium">Feature this product on platform overview</label>
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3">
                <button type="button" onclick="document.getElementById('createProductModal').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-slate-500 hover:text-slate-800">Cancel</button>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-600/30">Save Product</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Product Modal (Task 1 Implementation) -->
<div id="editProductModal" class="fixed inset-0 z-50 hidden bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-slate-900 font-heading">Edit Product Parameters</h3>
            <button onclick="document.getElementById('editProductModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="editProductForm" action="" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Product Name</label>
                <input type="text" id="ep_name" name="name" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Tagline</label>
                <input type="text" id="ep_tagline" name="tagline" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Description</label>
                <textarea id="ep_description" name="description" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900"></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Lucide Icon Name</label>
                    <input type="text" id="ep_icon" name="icon" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">App Endpoint URL</label>
                    <input type="url" id="ep_app_url" name="app_url" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                </div>
            </div>

            <div class="flex items-center space-x-2 pt-2">
                <input type="checkbox" name="is_featured" id="ep_is_featured" value="1" class="rounded bg-slate-100 border-slate-300 text-indigo-600">
                <label for="ep_is_featured" class="text-xs text-slate-700 font-medium">Feature product on dashboard</label>
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3">
                <button type="button" onclick="document.getElementById('editProductModal').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-slate-500 hover:text-slate-800">Cancel</button>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-600/30">Update Product</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openEditProductModal(product) {
        document.getElementById('editProductForm').action = "/admin/products/" + product.id;
        document.getElementById('ep_name').value = product.name || '';
        document.getElementById('ep_tagline').value = product.tagline || '';
        document.getElementById('ep_description').value = product.description || '';
        document.getElementById('ep_icon').value = product.icon || 'box';
        document.getElementById('ep_app_url').value = product.app_url || '';
        document.getElementById('ep_is_featured').checked = !!product.is_featured;

        document.getElementById('editProductModal').classList.remove('hidden');
    }
</script>
@endpush
@endsection
