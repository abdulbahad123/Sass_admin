<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::withCount(['plans', 'agencies'])->latest()->get();
        return view('admin.products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'app_url' => 'nullable|url',
            'api_key' => 'nullable|string|max:255',
            'is_featured' => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = true;
        $validated['is_featured'] = $request->has('is_featured');
        $validated['api_key'] = $validated['api_key'] ?? 'pk_' . Str::random(24);

        $product = Product::create($validated);

        AuditLog::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'action' => "Added SaaS Product: {$product->name}",
            'ip_address' => $request->ip(),
            'details' => $validated,
        ]);

        return redirect()->route('admin.products.index')->with('success', "Product '{$product->name}' created successfully!");
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'app_url' => 'nullable|url',
            'api_key' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');

        $product->update($validated);

        AuditLog::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'action' => "Updated Product: {$product->name}",
            'ip_address' => $request->ip(),
            'details' => $validated,
        ]);

        return redirect()->route('admin.products.index')->with('success', "Product '{$product->name}' updated successfully!");
    }

    public function toggleStatus(Request $request, Product $product)
    {
        $product->is_active = !$product->is_active;
        $product->save();

        $statusStr = $product->is_active ? 'Enabled' : 'Disabled';

        AuditLog::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'action' => "{$statusStr} Product: {$product->name}",
            'ip_address' => $request->ip(),
            'details' => ['product_id' => $product->id, 'new_status' => $product->is_active],
        ]);

        return back()->with('success', "Product '{$product->name}' is now {$statusStr}.");
    }

    public function destroy(Request $request, Product $product)
    {
        $name = $product->name;
        $product->delete();

        AuditLog::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'action' => "Deleted Product: {$name}",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.products.index')->with('success', "Product '{$name}' deleted successfully!");
    }

    public function launchAdmin(Request $request, Product $product)
    {
        $baseUrl = !empty($product->app_url) ? rtrim($product->app_url, '/') : null;

        if (!$baseUrl) {
            $currentHost = $request->getHost();
            $rootDomain = preg_replace('/^(app|www)\./i', '', $currentHost);
            $slug = $product->slug ?? Str::slug($product->name);
            $baseUrl = "https://{$slug}.{$rootDomain}";
        }

        if (!preg_match("~^(?:f|ht)tps?://~i", $baseUrl)) {
            $baseUrl = "https://" . $baseUrl;
        }

        $timestamp = time() + 300;
        $nonce = Str::random(16);
        $targetUser = 'Admin1@Launchshop';
        $secret = env('SSO_SECRET_KEY', 'LaunchshopSaaS_SSO_SecretKey_2026_SecureKey');

        $dataToSign = "{$targetUser}|{$timestamp}|{$nonce}";
        $signature = hash_hmac('sha256', $dataToSign, $secret);

        $ssoUrl = "{$baseUrl}/X9_AdMiN-Portal_V7/sso-login?" . http_build_query([
            'user' => $targetUser,
            'expires' => $timestamp,
            'nonce' => $nonce,
            'signature' => $signature,
        ]);

        AuditLog::create([
            'user_id' => auth()->id() ?? 1,
            'user_name' => auth()->user()->name ?? 'Super Admin',
            'action' => "Credential-Free Direct Admin Launch for Product: {$product->name}",
            'ip_address' => $request->ip(),
            'details' => ['product_id' => $product->id, 'sso_url' => $ssoUrl],
        ]);

        return redirect()->away($ssoUrl);
    }
}
