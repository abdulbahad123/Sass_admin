<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Plan;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::with('products')->withCount('subscriptions')->latest()->get();
        $products = Product::where('is_active', true)->get();

        return view('admin.plans.index', compact('plans', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly' => 'required|numeric|min:0',
            'tier' => 'required|in:agency,master',
            'max_clients' => 'required|integer|min:1',
            'max_sub_agencies' => 'required|integer|min:0',
            'products' => 'nullable|array',
            'products.*' => 'exists:products,id',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = true;

        $plan = Plan::create($validated);

        if (!empty($validated['products'])) {
            $plan->products()->sync($validated['products']);
        }

        AuditLog::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'action' => "Created Plan: {$plan->name}",
            'ip_address' => $request->ip(),
            'details' => $validated,
        ]);

        return redirect()->route('admin.plans.index')->with('success', "Plan '{$plan->name}' created successfully!");
    }

    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly' => 'required|numeric|min:0',
            'tier' => 'required|in:agency,master',
            'max_clients' => 'required|integer|min:1',
            'max_sub_agencies' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
            'products' => 'nullable|array',
            'products.*' => 'exists:products,id',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        $plan->update($validated);

        if (isset($validated['products'])) {
            $plan->products()->sync($validated['products']);
        }

        AuditLog::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'action' => "Updated Plan: {$plan->name}",
            'ip_address' => $request->ip(),
            'details' => $validated,
        ]);

        return redirect()->route('admin.plans.index')->with('success', "Plan '{$plan->name}' updated successfully!");
    }

    public function destroy(Request $request, Plan $plan)
    {
        $name = $plan->name;
        $plan->delete();

        AuditLog::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'action' => "Deleted Plan: {$name}",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.plans.index')->with('success', "Plan '{$name}' deleted!");
    }
}
