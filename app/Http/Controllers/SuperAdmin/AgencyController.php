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
        if (!$request->user()->hasPermission('view-agencies')) {
            abort(403, 'Access Denied: You do not have permission to view agencies.');
        }

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
        if (!$request->user()->hasPermission('create-agencies')) {
            abort(403, 'Access Denied: You do not have permission to create agencies.');
        }

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
            'price_monthly' => 'nullable|numeric|min:0',
            'billing_cycle' => 'nullable|in:monthly,yearly',
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

        // Assign subscription with custom price and billing cycle (monthly vs yearly)
        $priceAmount = $validated['price_monthly'] ?? 2999;
        $billingCycle = $validated['billing_cycle'] ?? 'monthly';
        $firstPlan = Plan::first();
        Subscription::create([
            'agency_id' => $agency->id,
            'plan_id' => $firstPlan?->id ?? 1,
            'status' => 'active',
            'billing_cycle' => $billingCycle,
            'amount' => $priceAmount,
            'starts_at' => now(),
        ]);

        // Assign products & provision dynamic database + Launchshop tables automatically
        $dbService = new \App\Services\DatabaseProvisioningService();
        $provisionErrors = [];
        $productIdsToProvision = !empty($validated['products'])
            ? $validated['products']
            : Product::where('is_active', true)->pluck('id')->toArray();

        if (!empty($productIdsToProvision)) {
            $syncData = [];
            $hasDbCol = \Illuminate\Support\Facades\Schema::hasColumn('agency_products', 'db_name');

            foreach ($productIdsToProvision as $productId) {
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
        if (!$request->user()->hasPermission('edit-agencies')) {
            abort(403, 'Access Denied: You do not have permission to edit agencies.');
        }

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
            'price_monthly' => 'nullable|numeric|min:0',
            'billing_cycle' => 'nullable|in:monthly,yearly',
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

        if (isset($validated['price_monthly']) || isset($validated['billing_cycle'])) {
            $sub = $agency->subscription;
            $billingCycle = $validated['billing_cycle'] ?? ($sub->billing_cycle ?? 'monthly');
            $priceAmount = $validated['price_monthly'] ?? ($sub->amount ?? 2999);

            if ($sub) {
                $sub->update([
                    'amount' => $priceAmount,
                    'billing_cycle' => $billingCycle,
                ]);
            } else {
                $firstPlan = Plan::first();
                Subscription::create([
                    'agency_id' => $agency->id,
                    'plan_id' => $firstPlan?->id ?? 1,
                    'status' => 'active',
                    'billing_cycle' => $billingCycle,
                    'amount' => $priceAmount,
                    'starts_at' => now(),
                ]);
            }
        }

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
            $dbService = new \App\Services\DatabaseProvisioningService();
            $syncData = [];
            $hasDbCol = \Illuminate\Support\Facades\Schema::hasColumn('agency_products', 'db_name');

            foreach ($validated['products'] as $productId) {
                $product = Product::find($productId);
                $existingPivot = $agency->products()->where('product_id', $productId)->first()?->pivot;
                $dbName = $existingPivot->db_name ?? null;
                $dbStatus = $existingPivot->db_status ?? 'active';

                if (!$dbName && $product) {
                    try {
                        $dbName = $dbService->provisionDatabaseForAgencyProduct($agency, $product);
                        $dbStatus = 'active';
                    } catch (\Throwable $e) {
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
        if (!$request->user()->hasPermission(['manage-product-access', 'edit-agencies'])) {
            abort(403, 'Access Denied: You do not have permission to manage product access.');
        }

        $dbService = new \App\Services\DatabaseProvisioningService();
        $pivot = $agency->products()->where('product_id', $product->id)->first();
        $hasDbCol = \Illuminate\Support\Facades\Schema::hasColumn('agency_products', 'db_name');

        if ($pivot) {
            $newStatus = $pivot->pivot->status === 'enabled' ? 'disabled' : 'enabled';
            $dbName = $pivot->pivot->db_name ?? null;
            if (!$dbName && $newStatus === 'enabled') {
                try {
                    $dbName = $dbService->provisionDatabaseForAgencyProduct($agency, $product);
                } catch (\Throwable $e) {}
            }
            $updateData = ['status' => $newStatus];
            if ($hasDbCol && $dbName) {
                $updateData['db_name'] = $dbName;
                $updateData['db_status'] = 'active';
            }
            $agency->products()->updateExistingPivot($product->id, $updateData);
        } else {
            $dbName = null;
            try {
                $dbName = $dbService->provisionDatabaseForAgencyProduct($agency, $product);
            } catch (\Throwable $e) {}

            $attachData = ['status' => 'enabled'];
            if ($hasDbCol) {
                $attachData['db_name'] = $dbName;
                $attachData['db_status'] = $dbName ? 'active' : 'failed';
            }
            $agency->products()->attach($product->id, $attachData);
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
        if (!$request->user()->hasPermission('delete-agencies')) {
            abort(403, 'Access Denied: You do not have permission to remove agencies.');
        }

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
        // Find or create the agency user
        $agencyUser = User::where('agency_id', $agency->id)->first() 
            ?? User::where('email', $agency->email)->first();

        if (!$agencyUser) {
            $agencyUser = User::create([
                'name' => $agency->owner_name,
                'email' => $agency->email,
                'password' => Hash::make(Str::random(16)),
                'role' => $agency->type === 'master' ? 'master_agency' : 'white_label_agency',
                'agency_id' => $agency->id,
                'status' => 'active',
            ]);
        }

        AuditLog::create([
            'user_id'   => auth()->id(),
            'user_name' => auth()->user()?->name ?? 'Super Admin',
            'action'    => "Credential-Free Admin Access launched for Agency: {$agency->name} ({$agency->type})",
            'ip_address'=> $request->ip(),
            'details'   => ['agency_id' => $agency->id, 'owner' => $agency->owner_name],
        ]);

        // Store impersonator ID so Super Admin can return with 1 click
        session(['impersonator_user_id' => auth()->id()]);
        Auth::login($agencyUser);

        if ($agency->type === 'master') {
            return redirect()->route('master.dashboard')->with('success', "Logged in as Master Agency Admin: {$agency->name}");
        }

        return redirect()->route('whitelabel.dashboard')->with('success', "Logged in as White Label Agency Admin: {$agency->name}");
    }

    public function reprovisionDatabase(Request $request, Agency $agency)
    {
        if (!$request->user()->hasPermission(['edit-agencies', 'create-agencies'])) {
            abort(403, 'Access Denied: You do not have permission to import database tables.');
        }

        $dbService = new \App\Services\DatabaseProvisioningService();
        $products = $agency->products->isNotEmpty() ? $agency->products : Product::where('is_active', true)->get();

        $errors = [];
        $successes = [];

        foreach ($products as $product) {
            try {
                $dbName = $dbService->provisionDatabaseForAgencyProduct($agency, $product);
                if ($dbName) {
                    $agency->products()->updateExistingPivot($product->id, [
                        'db_name' => $dbName,
                        'db_status' => 'active',
                        'status' => 'enabled'
                    ]);
                }
                $isWa = in_array(strtolower($product->slug ?? ''), ['whatsappbulk', 'whatsapp', 'whatautomation']);
                if ($isWa) {
                    try {
                        $mainWaDb = $dbService->provisionMainWhatsappDatabase();
                        $successes[] = "Main platform database '{$mainWaDb}' verified and seeded!";
                    } catch (\Throwable $waEx) {}
                }
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

    public function broadcast(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,warning,critical,success',
            'target_agency_id' => 'nullable|string',
            'send_email' => 'nullable|boolean',
            'send_mobile' => 'nullable|boolean',
        ]);

        $targetAgencyId = !empty($validated['target_agency_id']) && $validated['target_agency_id'] !== 'all'
            ? (int) $validated['target_agency_id']
            : null;

        if (!\Illuminate\Support\Facades\Schema::hasTable('agency_broadcasts')) {
            try {
                \Illuminate\Support\Facades\Schema::create('agency_broadcasts', function ($table) {
                    $table->id();
                    $table->string('title');
                    $table->text('message');
                    $table->string('type')->default('info');
                    $table->unsignedBigInteger('target_agency_id')->nullable();
                    $table->boolean('send_email')->default(true);
                    $table->boolean('send_mobile')->default(true);
                    $table->boolean('is_active')->default(true);
                    $table->unsignedBigInteger('created_by')->nullable();
                    $table->timestamps();
                });
            } catch (\Throwable $e) {}
        }

        $broadcast = \App\Models\AgencyBroadcast::create([
            'title' => $validated['title'],
            'message' => $validated['message'],
            'type' => $validated['type'],
            'target_agency_id' => $targetAgencyId,
            'send_email' => !empty($request->send_email),
            'send_mobile' => !empty($request->send_mobile),
            'is_active' => true,
            'created_by' => auth()->id(),
        ]);

        $query = Agency::where('status', 'active');
        if ($targetAgencyId) {
            $query->where('id', $targetAgencyId);
        }
        $agencies = $query->get();

        $emailCount = 0;
        $mobileCount = 0;

        foreach ($agencies as $agency) {
            // Send Email Notification
            if (!empty($request->send_email) && !empty($agency->email)) {
                try {
                    $toEmail = $agency->email;
                    $subject = "Notice: " . $validated['title'];
                    $noticeType = strtoupper($validated['type']);
                    $htmlBody = "
                        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 12px; background: #ffffff;'>
                            <div style='padding-bottom: 12px; border-bottom: 2px solid #4f46e5;'>
                                <span style='display: inline-block; padding: 4px 10px; font-size: 11px; font-weight: bold; border-radius: 6px; background: #4f46e5; color: #ffffff;'>{$noticeType}</span>
                                <h2 style='color: #1e293b; margin: 10px 0 0 0;'>{$validated['title']}</h2>
                            </div>
                            <div style='padding: 20px 0; color: #334155; line-height: 1.6; font-size: 14px;'>
                                " . nl2br(e($validated['message'])) . "
                            </div>
                            <div style='padding-top: 15px; border-top: 1px solid #e2e8f0; font-size: 12px; color: #64748b;'>
                                <p>This is an official announcement from Super Admin platform to your White-Label Agency (<strong>{$agency->name}</strong>).</p>
                            </div>
                        </div>
                    ";
                    \Illuminate\Support\Facades\Mail::html($htmlBody, function ($msg) use ($toEmail, $subject) {
                        $msg->to($toEmail)->subject($subject);
                    });
                    $emailCount++;
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::warning("Broadcast email failed for {$agency->email}: " . $e->getMessage());
                }
            }

            // Send Mobile Notification (WhatsApp / SMS)
            if (!empty($request->send_mobile) && !empty($agency->phone)) {
                try {
                    $waSettings = \App\Http\Controllers\SuperAdmin\WhatsAppGatewayController::getDefaultSettings();
                    $endpoint = rtrim($waSettings['whatsapp_gateway_url'] ?? 'http://localhost:8000', '/');
                    $token = $waSettings['whatsapp_access_token'] ?? '';
                    $instanceId = $waSettings['whatsapp_instance_id'] ?? '';
                    $dbName = $waSettings['whatsapp_database'] ?? 'nooryak_whatautomation';

                    $waMessage = "ANNOUNCEMENT: " . $validated['title'] . "\n\n" . $validated['message'] . "\n\n– Super Admin Platform";

                    $sendPath = $endpoint . '/send_message';
                    $queryUrl = $sendPath . '?' . http_build_query([
                        'access_token' => $token,
                        'instance_id' => $instanceId,
                        'db' => $dbName,
                    ]);

                    \Illuminate\Support\Facades\Http::asForm()->timeout(10)->withoutVerifying()->post($queryUrl, [
                        'access_token' => $token,
                        'instance_id' => $instanceId,
                        'number' => $agency->phone,
                        'message' => $waMessage,
                        'caption' => $waMessage,
                        'db' => $dbName,
                    ]);
                    $mobileCount++;
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::warning("Broadcast mobile notification failed for {$agency->phone}: " . $e->getMessage());
                }
            }
        }

        AuditLog::create([
            'user_id' => auth()->id() ?? 1,
            'user_name' => auth()->user()->name ?? 'Super Admin',
            'action' => "Broadcast Notice Sent: {$validated['title']}",
            'ip_address' => $request->ip(),
            'details' => [
                'broadcast_id' => $broadcast->id,
                'target_agency' => $targetAgencyId ?: 'All',
                'emails_sent' => $emailCount,
                'mobiles_sent' => $mobileCount
            ],
        ]);

        return redirect()->route('admin.agencies.index')->with(
            'success',
            "Broadcast notice published successfully! (Header banner active on Whitelabel Dashboard, {$emailCount} emails sent, {$mobileCount} mobile alerts sent)."
        );
    }
}
