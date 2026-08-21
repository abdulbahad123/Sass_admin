<?php

namespace App\Http\Controllers\MasterAgency;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MasterPlanController extends Controller
{
    public function index()
    {
        $plans = Plan::where('tier', 'agency')->with('products')->get();
        $products = Product::where('is_active', true)->get();

        return view('master.plans.index', compact('plans', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly' => 'required|numeric|min:0',
            'max_clients' => 'required|integer|min:1',
            'products' => 'nullable|array',
        ]);

        $plan = Plan::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'],
            'price_monthly' => $validated['price_monthly'],
            'price_yearly' => $validated['price_yearly'],
            'tier' => 'agency',
            'max_clients' => $validated['max_clients'],
            'max_sub_agencies' => 0,
            'is_active' => true,
        ]);

        if (!empty($validated['products'])) {
            $plan->products()->sync($validated['products']);
        }

        return redirect()->route('master.plans.index')->with('success', "Sub-Agency pricing plan '{$plan->name}' created successfully!");
    }

    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly' => 'required|numeric|min:0',
            'max_clients' => 'required|integer|min:1',
            'products' => 'nullable|array',
        ]);

        $plan->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'],
            'price_monthly' => $validated['price_monthly'],
            'price_yearly' => $validated['price_yearly'],
            'max_clients' => $validated['max_clients'],
        ]);

        if (isset($validated['products'])) {
            $plan->products()->sync($validated['products']);
        }

        return redirect()->route('master.plans.index')->with('success', "Plan '{$plan->name}' updated successfully!");
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();
        return redirect()->route('master.plans.index')->with('success', 'Plan deleted.');
    }
}
