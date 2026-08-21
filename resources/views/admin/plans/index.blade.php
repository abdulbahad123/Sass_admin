@extends('layouts.admin')

@section('title', 'Plans & Pricing Matrix')
@section('page_title', 'Subscription Plans & Product Packages')

@section('header_actions')
<button onclick="document.getElementById('createPlanModal').classList.remove('hidden')" 
        class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-600/30 flex items-center space-x-2 transition-all">
    <i data-lucide="plus" class="w-4 h-4"></i>
    <span>+ Create New Plan</span>
</button>
@endsection

@section('content')
<div class="space-y-8">

    <!-- Plans Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($plans as $plan)
            <div class="card-white rounded-2xl p-6 flex flex-col justify-between hover:shadow-md transition-all relative">
                @if($plan->tier === 'master')
                    <div class="absolute top-4 right-4 px-2.5 py-1 rounded-full text-[10px] font-bold bg-violet-100 text-violet-700 uppercase tracking-wider">
                        Master Agency Tier
                    </div>
                @endif

                <div>
                    <h3 class="text-xl font-bold text-slate-900 font-heading">{{ $plan->name }}</h3>
                    <p class="text-xs text-slate-500 mt-1 min-h-[36px]">{{ $plan->description }}</p>

                    <div class="my-6">
                        <span class="text-4xl font-extrabold text-slate-900 font-heading">{{ \App\Models\Setting::getCurrencySymbol() }}{{ number_format($plan->price_monthly, 0) }}</span>
                        <span class="text-xs text-slate-500">/ month</span>
                        <p class="text-[11px] text-indigo-600 font-semibold mt-1">Or {{ \App\Models\Setting::getCurrencySymbol() }}{{ number_format($plan->price_yearly, 0) }}/year (20% off)</p>
                    </div>

                    <!-- Quota Specifications -->
                    <div class="space-y-2.5 text-xs text-slate-700 mb-6">
                        <div class="flex items-center space-x-2">
                            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
                            <span>Max <b>{{ $plan->max_clients }}</b> End-Clients</span>
                        </div>
                        @if($plan->tier === 'master')
                            <div class="flex items-center space-x-2">
                                <i data-lucide="check-circle" class="w-4 h-4 text-violet-600"></i>
                                <span>Max <b>{{ $plan->max_sub_agencies }}</b> Sub-Agencies</span>
                            </div>
                        @endif
                        <div class="flex items-center space-x-2">
                            <i data-lucide="check-circle" class="w-4 h-4 text-indigo-600"></i>
                            <span>White-Label Branding Allowed</span>
                        </div>
                    </div>

                    <!-- Included Products -->
                    <div class="pt-4 border-t border-slate-100">
                        <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Included SaaS Products</span>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($plan->products as $product)
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    {{ $product->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-slate-500 font-medium">{{ $plan->subscriptions_count }} Active Subscriptions</span>

                    <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST" onsubmit="return confirm('Delete plan {{ $plan->name }}?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-rose-100 text-slate-500 hover:text-rose-700 border border-slate-200 transition-colors">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Create Plan Modal -->
<div id="createPlanModal" class="fixed inset-0 z-50 hidden bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-slate-900 font-heading">Create Pricing Plan</h3>
            <button onclick="document.getElementById('createPlanModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('admin.plans.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Plan Name</label>
                <input type="text" name="name" placeholder="e.g. Master Reseller Tier" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Description</label>
                <input type="text" name="description" placeholder="Ideal for agencies..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Monthly Price ({{ \App\Models\Setting::getCurrencySymbol() }})</label>
                    <input type="number" step="0.01" name="price_monthly" value="199.00" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Yearly Price ({{ \App\Models\Setting::getCurrencySymbol() }})</label>
                    <input type="number" step="0.01" name="price_yearly" value="1990.00" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Target Role</label>
                    <select name="tier" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                        <option value="agency">Agency</option>
                        <option value="master">Master Agency</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Max Clients</label>
                    <input type="number" name="max_clients" value="50" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Sub-Agencies</label>
                    <input type="number" name="max_sub_agencies" value="0" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">Include Products in Plan</label>
                <div class="grid grid-cols-2 gap-2">
                    @foreach($products as $prod)
                        <label class="flex items-center space-x-2 text-xs text-slate-700 p-2 rounded-xl bg-slate-50 border border-slate-200 cursor-pointer">
                            <input type="checkbox" name="products[]" value="{{ $prod->id }}" checked class="rounded bg-slate-100 border-slate-300 text-indigo-600">
                            <span>{{ $prod->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3">
                <button type="button" onclick="document.getElementById('createPlanModal').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-slate-500 hover:text-slate-800">Cancel</button>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-600/30">Save Plan</button>
            </div>
        </form>
    </div>
</div>
@endsection
