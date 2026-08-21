<?php

namespace App\Http\Controllers\MasterAgency;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\AuditLog;
use App\Models\Plan;
use App\Models\Product;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MasterSubAgencyController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $masterAgency = $user->agency ?? Agency::where('type', 'master')->first();
        $masterAgencyId = $masterAgency->id ?? 1;

        $subAgencies = Agency::where('parent_id', $masterAgencyId)
            ->with(['products', 'subscription.plan'])
            ->latest()
            ->paginate(10);

        $plans = Plan::where('is_active', true)->get();
        $products = Product::where('is_active', true)->get();

        return view('master.sub-agencies.index', compact('subAgencies', 'plans', 'products', 'masterAgency'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:agencies,email|unique:users,email',
            'password' => 'required|string|min:6',
            'custom_domain' => 'nullable|string|max:255',
            'max_clients' => 'required|integer|min:1',
            'plan_id' => 'required|exists:plans,id',
            'products' => 'nullable|array',
        ]);

        $user = Auth::user();
        $masterAgency = $user->agency ?? Agency::where('type', 'master')->first();

        $subAgency = Agency::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'type' => 'white_label',
            'parent_id' => $masterAgency->id ?? 1,
            'owner_name' => $validated['owner_name'],
            'email' => $validated['email'],
            'custom_domain' => $validated['custom_domain'],
            'max_clients' => $validated['max_clients'],
            'status' => 'active',
        ]);

        User::create([
            'name' => $validated['owner_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'white_label_agency',
            'agency_id' => $subAgency->id,
            'status' => 'active',
        ]);

        $plan = Plan::find($validated['plan_id']);
        Subscription::create([
            'agency_id' => $subAgency->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'billing_cycle' => 'monthly',
            'amount' => $plan->price_monthly ?? 199.00,
            'starts_at' => now(),
        ]);

        if (!empty($validated['products'])) {
            $syncData = [];
            foreach ($validated['products'] as $prodId) {
                $syncData[$prodId] = ['status' => 'enabled'];
            }
            $subAgency->products()->sync($syncData);
        }

        AuditLog::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'action' => "Master Agency onboarded Sub-Agency: {$subAgency->name}",
            'ip_address' => $request->ip(),
            'details' => ['sub_agency_id' => $subAgency->id],
        ]);

        return redirect()->route('master.sub-agencies.index')->with('success', "Sub-Agency '{$subAgency->name}' onboarded successfully under Master Agency!");
    }

    public function update(Request $request, Agency $agency)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'status' => 'required|string|in:active,pending,suspended',
            'custom_domain' => 'nullable|string|max:255',
            'max_clients' => 'required|integer|min:1',
        ]);

        $agency->update([
            'name' => $validated['name'],
            'owner_name' => $validated['owner_name'],
            'status' => $validated['status'],
            'custom_domain' => $validated['custom_domain'],
            'max_clients' => $validated['max_clients'],
        ]);

        return redirect()->route('master.sub-agencies.index')->with('success', "Sub-Agency '{$agency->name}' parameters updated!");
    }

    public function destroy(Agency $agency)
    {
        $agency->delete();
        return redirect()->route('master.sub-agencies.index')->with('success', "Sub-Agency removed successfully.");
    }

    public function toggleProduct(Request $request, Agency $agency, Product $product)
    {
        $assigned = $agency->products()->where('product_id', $product->id)->first();

        if ($assigned) {
            $newStatus = $assigned->pivot->status === 'enabled' ? 'disabled' : 'enabled';
            $agency->products()->updateExistingPivot($product->id, ['status' => $newStatus]);
        } else {
            $agency->products()->attach($product->id, ['status' => 'enabled']);
        }

        return redirect()->route('master.sub-agencies.index')->with('success', "Product access for '{$product->name}' updated!");
    }
}
