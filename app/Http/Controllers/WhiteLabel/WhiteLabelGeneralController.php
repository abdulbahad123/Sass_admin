<?php

namespace App\Http\Controllers\WhiteLabel;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WhiteLabelGeneralController extends Controller
{
    public function team()
    {
        $user = Auth::user();
        $agency = $user->agency ?? Agency::where('type', 'white_label')->first();

        $team = User::where('agency_id', $agency->id ?? 0)
            ->whereIn('role', ['team_member', 'white_label_agency'])
            ->get();

        return view('whitelabel.team.index', compact('user', 'agency', 'team'));
    }

    public function activityLogs()
    {
        $user = Auth::user();
        $agency = $user->agency ?? Agency::where('type', 'white_label')->first();

        $activities = AuditLog::latest()->take(30)->get();

        return view('whitelabel.activity.index', compact('user', 'agency', 'activities'));
    }

    public function billing()
    {
        $user = Auth::user();
        $agency = $user->agency ?? Agency::where('type', 'white_label')->first();

        $subClients = User::where('agency_id', $agency->id ?? 0)->where('role', 'client')->get();

        $invoices = [];
        foreach ($subClients as $client) {
            $invoices[] = [
                'id' => 'INV-WL-' . str_pad($client->id, 3, '0', STR_PAD_LEFT),
                'client' => $client->name,
                'amount' => '₹' . number_format(24900, 0),
                'date' => $client->created_at ? $client->created_at->format('M d, Y') : now()->format('M d, Y'),
                'status' => ucfirst($client->status === 'active' ? 'Paid' : 'Pending'),
            ];
        }

        return view('whitelabel.billing.index', compact('user', 'agency', 'invoices'));
    }

    public function branding()
    {
        $user = Auth::user();
        $agency = $user->agency ?? Agency::where('type', 'white_label')->first();

        return view('whitelabel.branding.index', compact('user', 'agency'));
    }

    public function updateBranding(Request $request)
    {
        $user = Auth::user();
        $agency = $user->agency ?? Agency::where('type', 'white_label')->first();

        if (!$agency) {
            return back()->with('error', 'Agency profile not found in database.');
        }

        $validated = $request->validate([
            'agency_name' => 'required|string|max:255',
            'custom_domain' => 'nullable|string|max:255',
            'primary_color' => 'nullable|string|max:50',
            'gemini_api_key' => 'nullable|string|max:255',
            'openai_api_key' => 'nullable|string|max:255',
            'is_gemini_active' => 'nullable|boolean',
            'is_openai_active' => 'nullable|boolean',
        ]);

        $updateData = [
            'name' => $validated['agency_name'],
            'custom_domain' => $validated['custom_domain'] ?? $agency->custom_domain,
            'primary_color' => $validated['primary_color'] ?? $agency->primary_color,
        ];
        if (\Illuminate\Support\Facades\Schema::hasColumn('agencies', 'gemini_api_key')) {
            $updateData['gemini_api_key'] = $request->input('gemini_api_key');
        }
        if (\Illuminate\Support\Facades\Schema::hasColumn('agencies', 'openai_api_key')) {
            $updateData['openai_api_key'] = $request->input('openai_api_key');
        }
        if (\Illuminate\Support\Facades\Schema::hasColumn('agencies', 'is_gemini_active')) {
            $updateData['is_gemini_active'] = $request->has('is_gemini_active') ? (int)$request->input('is_gemini_active') : 1;
        }
        if (\Illuminate\Support\Facades\Schema::hasColumn('agencies', 'is_openai_active')) {
            $updateData['is_openai_active'] = $request->has('is_openai_active') ? (int)$request->input('is_openai_active') : 1;
        }

        $agency->update($updateData);

        AuditLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'action' => "Updated custom agency branding: {$agency->name}",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', "Custom branding & agency settings for '{$agency->name}' saved successfully!");
    }

    private function getRealAgencyClients($agency)
    {
        $cpanelUser = env('CPANEL_USER', 'bazaarwa');
        $agencySlug = str_replace('-', '_', strtolower($agency->slug ?? 'ysquare'));

        $agencyProducts = DB::table('agency_products')
            ->join('products', 'products.id', '=', 'agency_products.product_id')
            ->where('agency_products.agency_id', $agency->id ?? 0)
            ->select('products.id as product_id', 'products.name as product_name', 'products.slug as product_slug', 'agency_products.db_name')
            ->get();

        if ($agencyProducts->isEmpty()) {
            $allProds = DB::table('products')->get();
            $agencyProducts = collect();
            foreach ($allProds as $p) {
                $agencyProducts->push((object)[
                    'product_id'   => $p->id,
                    'product_name' => $p->name,
                    'product_slug' => $p->slug,
                    'db_name'      => "{$cpanelUser}_ps_{$agencySlug}_{$p->slug}",
                ]);
            }
        }

        $clients = [];

        foreach ($agencyProducts as $ap) {
            $prodSlug = $ap->product_slug ?? 'launchshop';
            $dbCandidates = array_unique(array_filter([
                $ap->db_name ?? null,
                "{$cpanelUser}_ps_{$agencySlug}_{$prodSlug}",
                "{$cpanelUser}_ps_{$agencySlug}_launchshop",
                "bazaarwa_ps_{$agencySlug}_{$prodSlug}",
                "bazaarwa_ps_{$agencySlug}_launchshop",
                env('DB_DATABASE'),
            ]));

            $foundDb = null;
            foreach ($dbCandidates as $cand) {
                try {
                    $rows = DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?", [$cand]);
                    if (!empty($rows)) {
                        $foundDb = $cand;
                        break;
                    }
                } catch (\Throwable $e) {}
            }

            if ($foundDb) {
                try {
                    // EXCLUDE preview template users (preview_template = 1)
                    $tenantUsers = DB::table("{$foundDb}.users")
                        ->where(function ($q) {
                            $q->whereNull('preview_template')
                              ->orWhere('preview_template', 0)
                              ->orWhere('preview_template', '0')
                              ->orWhere('preview_template', '');
                        })
                        ->get();

                    foreach ($tenantUsers as $tu) {
                        $name = trim(($tu->first_name ?? '') . ' ' . ($tu->last_name ?? ''));
                        if (empty($name)) {
                            $name = $tu->username ?? $tu->email ?? ('Client #' . $tu->id);
                        }
                        $clients[] = [
                            'id'       => $tu->id,
                            'db_name'  => $foundDb,
                            'name'     => $name,
                            'username' => $tu->username ?? '',
                            'email'    => $tu->email ?? '',
                        ];
                    }
                } catch (\Throwable $e) {}
            }
        }

        return $clients;
    }

    public function aiSettings(Request $request)
    {
        $user = Auth::user();
        $agency = $user->agency ?? Agency::where('type', 'white_label')->first();
        $clients = $this->getRealAgencyClients($agency);

        $selectedClientId = $request->query('client_id', 'global');
        $selectedClient = null;
        $isGeminiActive = 1;
        $geminiApiKey = '';
        $isOpenaiActive = 1;
        $openaiApiKey = '';

        if (!empty($selectedClientId) && $selectedClientId !== 'global') {
            foreach ($clients as $c) {
                if ((string)$c['id'] === (string)$selectedClientId) {
                    $selectedClient = $c;
                    break;
                }
            }
            if ($selectedClient) {
                try {
                    $bs = DB::table("{$selectedClient['db_name']}.user_basic_settings")
                        ->where('user_id', $selectedClient['id'])
                        ->first();
                    if ($bs) {
                        $isGeminiActive = $bs->is_gemini ?? 1;
                        $geminiApiKey = $bs->gemini_api_key ?? '';
                        $isOpenaiActive = $bs->is_openai ?? 1;
                        $openaiApiKey = $bs->openai_api_key ?? '';
                    }
                } catch (\Throwable $e) {}
            }
        } else {
            $isGeminiActive = $agency->is_gemini_active ?? 1;
            $geminiApiKey = $agency->gemini_api_key ?? '';
            $isOpenaiActive = $agency->is_openai_active ?? 1;
            $openaiApiKey = $agency->openai_api_key ?? '';
        }

        return view('whitelabel.ai.index', compact(
            'user',
            'agency',
            'clients',
            'selectedClientId',
            'selectedClient',
            'isGeminiActive',
            'geminiApiKey',
            'isOpenaiActive',
            'openaiApiKey'
        ));
    }

    public function updateAiSettings(Request $request)
    {
        $user = Auth::user();
        $agency = $user->agency ?? Agency::where('type', 'white_label')->first();

        if (!$agency) {
            return back()->with('error', 'Agency profile not found in database.');
        }

        $validated = $request->validate([
            'client_id' => 'nullable|string',
            'gemini_api_key' => 'nullable|string|max:255',
            'openai_api_key' => 'nullable|string|max:255',
            'is_gemini_active' => 'nullable|boolean',
            'is_openai_active' => 'nullable|boolean',
        ]);

        $clientId = $request->input('client_id', 'global');

        if (!empty($clientId) && $clientId !== 'global') {
            $clients = $this->getRealAgencyClients($agency);
            $targetClient = null;
            foreach ($clients as $c) {
                if ((string)$c['id'] === (string)$clientId) {
                    $targetClient = $c;
                    break;
                }
            }

            if ($targetClient) {
                try {
                    $db = $targetClient['db_name'];
                    $uId = $targetClient['id'];
                    $bs = DB::table("{$db}.user_basic_settings")->where('user_id', $uId)->first();

                    $updateArr = [
                        'is_gemini' => $request->has('is_gemini_active') ? (int)$request->input('is_gemini_active') : 1,
                        'is_openai' => $request->has('is_openai_active') ? (int)$request->input('is_openai_active') : 1,
                        'gemini_api_key' => $request->input('gemini_api_key'),
                        'openai_api_key' => $request->input('openai_api_key'),
                    ];

                    if ($bs) {
                        DB::table("{$db}.user_basic_settings")->where('user_id', $uId)->update($updateArr);
                    } else {
                        $updateArr['user_id'] = $uId;
                        DB::table("{$db}.user_basic_settings")->insert($updateArr);
                    }

                    AuditLog::create([
                        'user_id' => $user->id,
                        'user_name' => $user->name,
                        'action' => "Updated AI Settings for Client: {$targetClient['name']} (ID: {$uId})",
                        'ip_address' => $request->ip(),
                    ]);

                    return redirect()->route('whitelabel.ai-settings.index', ['client_id' => $clientId])
                        ->with('success', "AI Engine API Keys & Access updated successfully for Client '{$targetClient['name']}'!");
                } catch (\Throwable $e) {
                    return back()->with('error', "Failed to update client AI settings: " . $e->getMessage());
                }
            }
        }

        // Global Agency Update
        $updateData = [];
        if (\Illuminate\Support\Facades\Schema::hasColumn('agencies', 'gemini_api_key')) {
            $updateData['gemini_api_key'] = $request->input('gemini_api_key');
        }
        if (\Illuminate\Support\Facades\Schema::hasColumn('agencies', 'openai_api_key')) {
            $updateData['openai_api_key'] = $request->input('openai_api_key');
        }
        if (\Illuminate\Support\Facades\Schema::hasColumn('agencies', 'is_gemini_active')) {
            $updateData['is_gemini_active'] = $request->has('is_gemini_active') ? (int)$request->input('is_gemini_active') : 1;
        }
        if (\Illuminate\Support\Facades\Schema::hasColumn('agencies', 'is_openai_active')) {
            $updateData['is_openai_active'] = $request->has('is_openai_active') ? (int)$request->input('is_openai_active') : 1;
        }

        $agency->update($updateData);

        AuditLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'action' => "Updated Global White-Label Agency AI Settings: {$agency->name}",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('whitelabel.ai-settings.index', ['client_id' => 'global'])
            ->with('success', "Global Agency Default AI Settings saved successfully!");
    }
}
