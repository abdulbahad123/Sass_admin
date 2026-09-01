<?php

namespace App\Http\Controllers\SuperAdmin;

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

class AgencyController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type');
        $query = Agency::with(['parentAgency', 'subAgencies', 'subscription.plan', 'products']);

        if ($type === 'master') {
            $query->where('type', 'master');
        } elseif ($type === 'white_label') {
            $query->where('type', 'white_label');
        }

        $agencies = $query->latest()->get();
        $masterAgencies = Agency::where('type', 'master')->where('status', 'active')->get();
        $plans = Plan::where('is_active', true)->get();
        $products = Product::where('is_active', true)->get();

        return view('admin.agencies.index', compact('agencies', 'masterAgencies', 'plans', 'products', 'type'));
    }

    public function store(Request $request)
    {
        if ($request->input('parent_id') === '') {
            $request->merge(['parent_id' => null]);
        }

        // Ensure at least one default plan exists
        $firstPlan = Plan::first();
        if (!$firstPlan) {
            $firstPlan = Plan::create([
                'name' => 'Starter Agency Plan',
                'slug' => 'starter-agency-plan',
                'price_monthly' => 2999,
                'price_yearly' => 29990,
                'max_clients' => 100,
                'is_active' => true,
            ]);
        }

        if (!$request->filled('plan_id')) {
            $request->merge(['plan_id' => $firstPlan->id]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:master,white_label',
            'parent_id' => 'nullable|exists:agencies,id',
            'owner_name' => 'required|string|max:255',
            'email' => 'required|email|unique:agencies,email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:50',
            'custom_domain' => 'nullable|string|max:255',
            'primary_color' => 'nullable|string|max:20',
            'max_clients' => 'required|integer|min:1',
            'plan_id' => 'nullable|exists:plans,id',
            'products' => 'nullable|array',
            'products.*' => 'exists:products,id',
        ]);

        $rawDomain = $validated['custom_domain'] ?? null;
        $cleanDomain = $rawDomain ? rtrim(preg_replace('#^https?://#', '', trim($rawDomain)), '/') : null;

        $agency = Agency::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'type' => $validated['type'],
            'parent_id' => $validated['type'] === 'white_label' ? ($validated['parent_id'] ?? null) : null,
            'owner_name' => $validated['owner_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'custom_domain' => $cleanDomain,
            'primary_color' => $validated['primary_color'] ?? '#4f46e5',
            'secondary_color' => '#9333ea',
            'status' => 'active',
            'max_clients' => $validated['max_clients'],
            'max_products' => count($validated['products'] ?? []),
            'hero_title' => 'Grow, Manage & Automate Your Business — All in One Place',
            'hero_subtitle' => 'The most powerful SaaS platform for local businesses to get more customers, save time and grow faster.',
            'cta_text' => 'Start Free Today',
            'cta_url' => '/login',
            'contact_email' => $validated['email'],
            'contact_phone' => $validated['phone'] ?? null,
        ]);

        // Create initial agency user account
        User::create([
            'name' => $validated['owner_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['type'] === 'master' ? 'master_agency' : 'white_label_agency',
            'agency_id' => $agency->id,
            'status' => 'active',
        ]);

        // Assign plan subscription
        $plan = Plan::find($validated['plan_id'] ?? null) ?? $firstPlan;
        if ($plan) {
            Subscription::create([
                'agency_id' => $agency->id,
                'plan_id' => $plan->id,
                'status' => 'active',
                'billing_cycle' => 'monthly',
                'amount' => $plan->price_monthly ?? 2999,
                'starts_at' => now(),
            ]);
        }

        // Assign products & provision dynamic database + Launchshop tables
        $dbService = new \App\Services\DatabaseProvisioningService();
        $provisionErrors = [];
        if (!empty($validated['products'])) {
            $syncData = [];
            $hasDbCol = \Illuminate\Support\Facades\Schema::hasColumn('agency_products', 'db_name');

            foreach ($validated['products'] as $productId) {
                $product = Product::find($productId);
                $dbName = null;
                $dbStatus = 'pending';
                if ($product) {
                    try {
                        $dbName = $dbService->provisionDatabaseForAgencyProduct($agency, $product);
                        $dbStatus = 'active';
                    } catch (\Throwable $e) {
                        $provisionErrors[] = $e->getMessage();
                        $dbStatus = 'failed';
                    }
                }

                $pivotData = ['status' => 'enabled'];
                if ($hasDbCol) {
                    $pivotData['db_name'] = $dbName;
                    $pivotData['db_status'] = $dbStatus;
                }
                $syncData[$productId] = $pivotData;
            }
            $agency->products()->sync($syncData);
        }

        AuditLog::create([
            'user_id' => auth()->id() ?? 1,
            'user_name' => auth()->user()->name ?? 'Super Admin',
            'action' => "Created Agency: {$agency->name} ({$agency->type})",
            'ip_address' => $request->ip(),
            'details' => ['agency_id' => $agency->id, 'type' => $agency->type, 'owner' => $agency->owner_name],
        ]);

        if ($provisionErrors) {
            return redirect()->route('admin.agencies.index')->with(
                'error',
                "Agency '{$agency->name}' was created, but Launchshop tables were not imported: ".implode(' | ', $provisionErrors)
            );
        }

        return redirect()->route('admin.agencies.index')->with('success', "Agency '{$agency->name}' onboarded successfully with Launchshop tables!");
    }

    public function update(Request $request, Agency $agency)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:master,white_label',
            'parent_id' => 'nullable|exists:agencies,id',
            'owner_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'custom_domain' => 'nullable|string|max:255',
            'primary_color' => 'nullable|string|max:20',
            'status' => 'required|in:active,pending,suspended',
            'max_clients' => 'required|integer|min:1',
            'password' => 'nullable|string|min:6',
            'products' => 'nullable|array',
            'products.*' => 'exists:products,id',
            'gemini_api_key' => 'nullable|string|max:255',
            'openai_api_key' => 'nullable|string|max:255',
            'is_gemini_active' => 'nullable|boolean',
            'is_openai_active' => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        if ($validated['type'] === 'master') {
            $validated['parent_id'] = null;
        }

        if (isset($validated['custom_domain'])) {
            $validated['custom_domain'] = $validated['custom_domain'] ? rtrim(preg_replace('#^https?://#', '', trim($validated['custom_domain'])), '/') : null;
        }

        $agency->update($validated);

        // Update corresponding User account
        $agencyUser = User::where('agency_id', $agency->id)->first() ?? User::where('email', $agency->email)->first();
        if ($agencyUser) {
            $userUpdate = [
                'name' => $validated['owner_name'],
                'role' => $validated['type'] === 'master' ? 'master_agency' : 'white_label_agency',
                'status' => $validated['status'],
            ];
            if (!empty($validated['password'])) {
                $userUpdate['password'] = Hash::make($validated['password']);
            }
            $agencyUser->update($userUpdate);
        }

        if (isset($validated['products'])) {
            $syncData = [];
            foreach ($validated['products'] as $productId) {
                $syncData[$productId] = ['status' => 'enabled'];
            }
            $agency->products()->sync($syncData);
        }

        AuditLog::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'action' => "Updated Agency Settings: {$agency->name}",
            'ip_address' => $request->ip(),
            'details' => $validated,
        ]);

        return redirect()->route('admin.agencies.index')->with('success', "Agency '{$agency->name}' updated successfully!");
    }

    public function toggleProductAccess(Request $request, Agency $agency, Product $product)
    {
        $pivot = $agency->products()->where('product_id', $product->id)->first();

        if ($pivot) {
            $newStatus = $pivot->pivot->status === 'enabled' ? 'disabled' : 'enabled';
            $agency->products()->updateExistingPivot($product->id, ['status' => $newStatus]);
        } else {
            $agency->products()->attach($product->id, ['status' => 'enabled']);
            $newStatus = 'enabled';
        }

        AuditLog::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'action' => "Toggled Product Access '{$product->name}' to '{$newStatus}' for Agency {$agency->name}",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', "Access to {$product->name} updated for agency {$agency->name}.");
    }

    public function destroy(Request $request, Agency $agency)
    {
        $name = $agency->name;
        $agency->delete();

        AuditLog::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'action' => "Deleted Agency: {$name}",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.agencies.index')->with('success', "Agency '{$name}' removed.");
    }

    public function loginAsAgency(Request $request, Agency $agency)
    {
        // Find the agency user
        $agencyUser = User::where('agency_id', $agency->id)->first() 
            ?? User::where('email', $agency->email)->first();

        AuditLog::create([
            'user_id'   => auth()->id(),
            'user_name' => auth()->user()?->name ?? 'Super Admin',
            'action'    => "Credential-Free Admin Access launched for Agency: {$agency->name} ({$agency->type})",
            'ip_address'=> $request->ip(),
            'details'   => ['agency_id' => $agency->id, 'owner' => $agency->owner_name],
        ]);

        $targetPath = $agency->type === 'master' ? '/master/dashboard' : '/whitelabel/dashboard';
        $cleanDomain = $agency->clean_domain;

        // If agency has a custom domain (e.g. youverse.in, funkiddoz.in, maturednature.com), use signed SSO link WITHOUT logging out Super Admin on nooryak.in!
        if ($cleanDomain && !str_contains($cleanDomain, 'nooryak') && !str_contains($cleanDomain, 'localhost') && !str_contains($cleanDomain, '127.0.0.1')) {
            $email   = $agencyUser ? $agencyUser->email : $agency->email;
            $expires = time() + 300;
            $secret  = env('SSO_SECRET_KEY', 'LaunchshopSaaS_SSO_SecretKey_2026_SecureKey');
            $signature = hash_hmac('sha256', "{$email}|{$expires}", $secret);

            $ssoUrl = "https://{$cleanDomain}/agency-sso-login?" . http_build_query([
                'email'     => $email,
                'expires'   => $expires,
                'signature' => $signature,
                'redirect'  => $targetPath,
            ]);

            return redirect()->away($ssoUrl);
        }

        // For same-domain / local impersonation: store impersonator ID so Super Admin can return with 1 click
        if ($agencyUser) {
            session(['impersonator_user_id' => auth()->id()]);
            Auth::login($agencyUser);
        }

        if ($agency->type === 'master') {
            return redirect()->route('master.dashboard');
        }

        return redirect()->route('whitelabel.dashboard');
    }

    public function reprovisionDatabase(Request $request, Agency $agency)
    {
        $dbService = new \App\Services\DatabaseProvisioningService();
        $products = $agency->products->isNotEmpty() ? $agency->products : Product::where('is_active', true)->get();

        $errors = [];
        $successes = [];

        foreach ($products as $product) {
            try {
                $dbName = $dbService->provisionDatabaseForAgencyProduct($agency, $product);
                $successes[] = "Tables successfully imported into database '{$dbName}' for {$product->name}!";
            } catch (\Throwable $e) {
                $errors[] = "Failed for {$product->name}: " . $e->getMessage();
            }
        }

        if ($errors) {
            return redirect()->route('admin.agencies.index')->with('error', implode(' | ', $errors));
        }

        return redirect()->route('admin.agencies.index')->with('success', implode(' | ', $successes));
    }
}
