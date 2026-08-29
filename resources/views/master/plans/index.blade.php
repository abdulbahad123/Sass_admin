@extends('layouts.master')

@section('title', 'Plans & Pricing Management')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900 font-heading">White Label Plan</h2>
            <p class="text-xs text-slate-500">Edit and manage your white label plan package</p>
        </div>
        <button onclick="document.getElementById('createPlanModal').classList.remove('hidden')" 
                class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-600/30 flex items-center space-x-2 transition-all">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Create Agency Plan</span>
        </button>
    </div>

    @php
        // Filter to show only the "White Label Plan" - adjust the condition based on your plan name
        $whiteLabelPlan = $plans->first(function($plan) {
            return stripos($plan->name, 'white') !== false || stripos($plan->name, 'label') !== false;
        }) ?? $plans->first(); // Fallback to first plan if no match
    @endphp

    @if($whiteLabelPlan)
    <div class="grid grid-cols-1 max-w-xl">
        <div class="card-white rounded-2xl p-6 flex flex-col justify-between hover:shadow-md transition-all">
            <div>
                <h3 class="text-xl font-bold text-slate-900 font-heading">{{ $whiteLabelPlan->name }}</h3>
                <p class="text-xs text-slate-500 mt-1 min-h-[36px]">{{ $whiteLabelPlan->description }}</p>

                <div class="my-6">
                    <span class="text-4xl font-extrabold text-slate-900 font-heading">{{ \App\Models\Setting::getCurrencySymbol() }}{{ number_format($whiteLabelPlan->price_monthly, 0) }}</span>
                    <span class="text-xs text-slate-500">/ month</span>
                    <p class="text-[11px] text-indigo-600 font-semibold mt-1">Yearly: {{ \App\Models\Setting::getCurrencySymbol() }}{{ number_format($whiteLabelPlan->price_yearly, 0) }}/yr</p>
                </div>

                <div class="space-y-2 text-xs text-slate-700 mb-6">
                    <div class="flex items-center space-x-2">
                        <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
                        <span>Max <b>{{ $whiteLabelPlan->max_clients }}</b> End-Clients</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i data-lucide="check-circle" class="w-4 h-4 text-indigo-600"></i>
                        <span>White-Label Branding Suite</span>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100">
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Included Products</span>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($whiteLabelPlan->products as $prod)
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                {{ $prod->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 mt-6 flex items-center justify-between text-xs">
                <button onclick="document.getElementById('editPlanModal_{{ $whiteLabelPlan->id }}').classList.remove('hidden')" class="px-3 py-1.5 rounded-xl bg-indigo-100 hover:bg-indigo-200 text-indigo-700 border border-indigo-200 font-semibold">
                    Edit Package
                </button>

                <form action="{{ route('master.plans.destroy', $whiteLabelPlan) }}" method="POST" onsubmit="return confirm('Delete plan {{ $whiteLabelPlan->name }}?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-rose-100 text-slate-500 hover:text-rose-700 border border-slate-200">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Plan Modal -->
    <div id="editPlanModal_{{ $whiteLabelPlan->id }}" class="fixed inset-0 z-50 hidden bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl overflow-y-auto max-h-[90vh]">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-slate-900 font-heading">Edit {{ $whiteLabelPlan->name }}</h3>
                <button onclick="document.getElementById('editPlanModal_{{ $whiteLabelPlan->id }}').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('master.plans.update', $whiteLabelPlan) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Plan Name</label>
                    <input type="text" name="name" value="{{ $whiteLabelPlan->name }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Description</label>
                    <input type="text" name="description" value="{{ $whiteLabelPlan->description }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Monthly Price</label>
                        <input type="number" step="0.01" name="price_monthly" value="{{ $whiteLabelPlan->price_monthly }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Yearly Price</label>
                        <input type="number" step="0.01" name="price_yearly" value="{{ $whiteLabelPlan->price_yearly }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Max End-Clients Quota</label>
                    <input type="number" name="max_clients" value="{{ $whiteLabelPlan->max_clients }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">Include SaaS Products</label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($products as $prod)
                            <label class="flex items-center space-x-2 text-xs text-slate-700 p-2 rounded-xl bg-slate-50 border border-slate-200 cursor-pointer">
                                <input type="checkbox" name="products[]" value="{{ $prod->id }}" 
                                    {{ $whiteLabelPlan->products->contains($prod->id) ? 'checked' : '' }} 
                                    class="rounded bg-slate-100 border-slate-300 text-indigo-600">
                                <span>{{ $prod->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('editPlanModal_{{ $whiteLabelPlan->id }}').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-slate-500 hover:text-slate-800">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-600/30">Update Plan</button>
                </div>
            </form>
        </div>
    </div>
    @else
    <div class="text-center py-12">
        <p class="text-slate-500">No plans available. Create one to get started.</p>
    </div>
    @endif

</div>

@push('modals')
@if($whiteLabelPlan)
<!-- Edit Plan Modal -->
<div id="editPlanModal_{{ $whiteLabelPlan->id }}" class="fixed inset-0 z-[100] hidden bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl overflow-y-auto max-h-[90vh]">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-slate-900 font-heading">Edit {{ $whiteLabelPlan->name }}</h3>
            <button onclick="document.getElementById('editPlanModal_{{ $whiteLabelPlan->id }}').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('master.plans.update', $whiteLabelPlan) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Plan Name</label>
                <input type="text" name="name" value="{{ $whiteLabelPlan->name }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Description</label>
                <input type="text" name="description" value="{{ $whiteLabelPlan->description }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Monthly Price</label>
                    <input type="number" step="0.01" name="price_monthly" value="{{ $whiteLabelPlan->price_monthly }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Yearly Price</label>
                    <input type="number" step="0.01" name="price_yearly" value="{{ $whiteLabelPlan->price_yearly }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Max End-Clients Quota</label>
                <input type="number" name="max_clients" value="{{ $whiteLabelPlan->max_clients }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">Include SaaS Products</label>
                <div class="grid grid-cols-2 gap-2">
                    @foreach($products as $prod)
                        <label class="flex items-center space-x-2 text-xs text-slate-700 p-2 rounded-xl bg-slate-50 border border-slate-200 cursor-pointer">
                            <input type="checkbox" name="products[]" value="{{ $prod->id }}" 
                                {{ $whiteLabelPlan->products->contains($prod->id) ? 'checked' : '' }} 
                                class="rounded bg-slate-100 border-slate-300 text-indigo-600">
                            <span>{{ $prod->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3">
                <button type="button" onclick="document.getElementById('editPlanModal_{{ $whiteLabelPlan->id }}').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-slate-500 hover:text-slate-800">Cancel</button>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-600/30">Update Plan</button>
            </div>
        </form>
    </div>
</div>
@endif

<!-- Create Plan Modal -->
<div id="createPlanModal" class="fixed inset-0 z-[100] hidden bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl overflow-y-auto max-h-[90vh]">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-slate-900 font-heading">Create Pricing Plan</h3>
            <button onclick="document.getElementById('createPlanModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('master.plans.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Plan Name</label>
                <input type="text" name="name" placeholder="e.g. Enterprise Tier" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Description</label>
                <input type="text" name="description" placeholder="Ideal for high-volume sub-agencies..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Monthly Price</label>
                    <input type="number" step="0.01" name="price_monthly" value="299.00" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Yearly Price</label>
                    <input type="number" step="0.01" name="price_yearly" value="2990.00" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Max End-Clients Quota</label>
                <input type="number" name="max_clients" value="100" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">Include SaaS Products</label>
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
@endpush
@endsection
