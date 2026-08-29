<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\AuditLog;
use App\Models\Plan;
use App\Models\Product;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page on maturenature.in/checkout (Sass_admin).
     */
    public function showCheckout(Request $request)
    {
        $planId = $request->query('package_id') ?? $request->query('plan_id') ?? $request->input('package_id');
        $plan = null;

        if ($planId) {
            $plan = Plan::find($planId);
        }

        if (!$plan) {
            $plan = Plan::where('is_active', true)->first();
        }

        if (!$plan) {
            $plan = Plan::create([
                'name' => 'Starter LaunchShop Plan',
                'slug' => 'starter-launchshop-plan',
                'description' => 'Complete LaunchShop e-commerce store with domain & instant setup.',
                'price_monthly' => 2999.00,
                'price_yearly' => 29990.00,
                'max_clients' => 50,
                'is_active' => true,
            ]);
        }

        $plans = Plan::where('is_active', true)->get();
        $products = Product::where('is_active', true)->get();

        $data = [
            'first_name' => $request->input('first_name') ?? $request->query('first_name', ''),
            'username' => $request->input('username') ?? $request->query('username', ''),
            'shop_name' => $request->input('shop_name') ?? $request->query('shop_name', ''),
            'email' => $request->input('email') ?? $request->query('email', ''),
            'phone' => $request->input('phone') ?? $request->query('phone', ''),
            'country_code' => $request->input('country_code') ?? $request->query('country_code', '+91'),
            'category' => $request->input('category') ?? $request->query('category', ''),
            'status' => $request->input('package_type') ?? $request->query('status', 'regular'),
            'selected_template' => $request->input('selected_template') ?? $request->query('selected_template', 'vegetables'),
            'package' => $plan,
            'plans' => $plans,
            'products' => $products,
            'payment_methods' => [
                (object) ['name' => 'Razorpay'],
                (object) ['name' => 'Stripe'],
                (object) ['name' => 'Paypal'],
                (object) ['name' => 'Offline / Bank Transfer'],
            ]
        ];

        return view('checkout', compact('data'));
    }

    /**
     * Process place order from maturenature.in/checkout (Sass_admin).
     */
    public function processCheckout(Request $request)
    {
        $validated = $request->validate([
            'shop_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:50',
            'country_code' => 'nullable|string|max:10',
            'plan_id' => 'required|exists:plans,id',
            'password' => 'nullable|string|min:6',
            'payment_method' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'selected_template' => 'nullable|string|max:100',
        ]);

        $plan = Plan::findOrFail($validated['plan_id']);
        $password = $validated['password'] ?? '123456';
        $username = Str::slug($validated['shop_name']);

        // Check or create Agency
        $agency = Agency::where('email', $validated['email'])->first();

        if (!$agency) {
            $agency = Agency::create([
                'name' => $validated['shop_name'],
                'slug' => $username,
                'type' => 'white_label',
                'owner_name' => $validated['shop_name'],
                'email' => $validated['email'],
                'phone' => ($validated['country_code'] ?? '') . $validated['phone'],
                'custom_domain' => "{$username}.maturenature.in",
                'primary_color' => '#ff5a2c',
                'status' => 'active',
                'max_clients' => $plan->max_clients ?? 50,
                'max_products' => 10,
            ]);
        }

        // Check or create User account for Agency Owner
        $user = User::where('email', $validated['email'])->first();

        if (!$user) {
            $user = User::create([
                'name' => $validated['shop_name'],
                'email' => $validated['email'],
                'password' => Hash::make($password),
                'role' => 'white_label_agency',
                'agency_id' => $agency->id,
                'status' => 'active',
            ]);
        }

        // Create or update subscription
        $subscription = Subscription::create([
            'agency_id' => $agency->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'billing_cycle' => 'monthly',
            'amount' => $plan->price_monthly ?? 0,
            'starts_at' => now(),
            'ends_at' => now()->addMonth(),
        ]);

        // Attempt LaunchShop DB Provisioning if Service is present
        try {
            if (class_exists('\\App\\Services\\DatabaseProvisioningService')) {
                $dbService = new \App\Services\DatabaseProvisioningService();
                $launchProduct = Product::where('slug', 'launchshop')->first() ?? Product::first();
                if ($launchProduct) {
                    $dbService->provisionDatabaseForAgencyProduct($agency, $launchProduct);
                    $agency->products()->syncWithoutDetaching([
                        $launchProduct->id => ['status' => 'enabled', 'db_status' => 'active']
                    ]);
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Checkout provisioning notice: ' . $e->getMessage());
        }

        // Audit Log
        try {
            AuditLog::create([
                'user_id' => $user->id,
                'user_name' => $user->name,
                'action' => "Completed Checkout & Launched Store: {$agency->name}",
                'ip_address' => $request->ip(),
                'details' => ['plan' => $plan->name, 'amount' => $plan->price_monthly],
            ]);
        } catch (\Throwable $auditEx) {
            // Ignore audit log error if table differs
        }

        // Automatically log in user
        Auth::login($user);

        session()->flash('success', 'Your store has been created and launched successfully!');
        session()->put('checkout_store_url', "https://{$agency->slug}.maturenature.in");
        session()->put('checkout_agency_id', $agency->id);

        return redirect()->route('checkout.success');
    }

    /**
     * Display post-checkout store launch success page.
     */
    public function success()
    {
        $agencyId = session()->get('checkout_agency_id');
        $agency = null;
        
        if ($agencyId) {
            $agency = Agency::find($agencyId);
        }

        if (!$agency && Auth::check()) {
            $agency = Agency::where('email', Auth::user()->email)->first();
        }

        return view('checkout-success', compact('agency'));
    }
}
