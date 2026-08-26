<?php

namespace App\Http\Controllers\WhiteLabel;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\AuditLog;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class WhiteLabelClientController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $agency = $user->agency ?? Agency::where('type', 'white_label')->first();

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
        $productCounts = [];

        foreach ($agencyProducts as $ap) {
            $prodName = $ap->product_name ?? 'Launchshop';
            $prodSlug = $ap->product_slug ?? 'launchshop';

            $dbCandidates = array_unique(array_filter([
                $ap->db_name ?? null,
                "{$cpanelUser}_ps_{$agencySlug}_{$prodSlug}",
                "{$cpanelUser}_ps_{$agencySlug}_launchshop",
                "bazaarwa_ps_{$agencySlug}_{$prodSlug}",
                "bazaarwa_ps_{$agencySlug}_launchshop",
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
                    // Exclude theme preview template users (preview_template = 1) so only real end-clients are counted
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
                            $name = $tu->username ?? $tu->email ?? 'Client';
                        }
                        $status = ($tu->status ?? 1) == 1 ? 'Active' : 'Inactive';

                        // Fetch real plan title from memberships
                        $planTitle = 'Standard';
                        try {
                            $memb = DB::table("{$foundDb}.memberships")
                                ->where('user_id', $tu->id)
                                ->orderBy('id', 'desc')
                                ->first();
                            if ($memb) {
                                $pkg = DB::table("{$foundDb}.packages")->where('id', $memb->package_id)->first();
                                if ($pkg) {
                                    $planTitle = $pkg->title;
                                }
                            }
                        } catch (\Throwable $mEx) {}

                        $clients[] = [
                            'id'           => $tu->id,
                            'name'         => $name,
                            'contact'      => $tu->username ?? $name,
                            'email'        => $tu->email ?? 'N/A',
                            'product_name' => $prodName,
                            'product_id'   => $ap->product_id,
                            'plan'         => $planTitle,
                            'status'       => $status,
                            'joined'       => !empty($tu->created_at) ? \Carbon\Carbon::parse($tu->created_at)->format('M d, Y') : now()->format('M d, Y'),
                        ];

                        if (!isset($productCounts[$prodName])) {
                            $productCounts[$prodName] = 0;
                        }
                        $productCounts[$prodName]++;
                    }
                } catch (\Throwable $e) {}
            }
        }

        // Include local clients from Sass Admin DB
        $localUsers = User::where('agency_id', $agency->id ?? 0)->where('role', 'client')->with('product')->get();
        foreach ($localUsers as $lu) {
            $pName = $lu->product->name ?? 'Launchshop';
            $clients[] = [
                'id'           => 'local_' . $lu->id,
                'name'         => $lu->name,
                'contact'      => $lu->name,
                'email'        => $lu->email,
                'product_name' => $pName,
                'product_id'   => $lu->product_id,
                'plan'         => 'Growth',
                'status'       => ucfirst($lu->status ?? 'active'),
                'joined'       => $lu->created_at ? $lu->created_at->format('M d, Y') : now()->format('M d, Y'),
            ];

            if (!isset($productCounts[$pName])) {
                $productCounts[$pName] = 0;
            }
            $productCounts[$pName]++;
        }

        $products = Product::where('is_active', true)->get();

        return view('whitelabel.clients.index', compact('user', 'agency', 'clients', 'products', 'productCounts'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $agency = $user->agency ?? Agency::where('type', 'white_label')->first();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'plan' => 'required|string',
            'product_id' => 'nullable|exists:products,id',
        ]);

        $defaultProduct = Product::where('slug', 'launchshop')->first() ?? Product::first();

        $client = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make('password'),
            'role' => 'client',
            'agency_id' => $agency->id ?? 0,
            'product_id' => $validated['product_id'] ?? ($defaultProduct->id ?? null),
            'status' => 'active',
        ]);

        AuditLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'action' => "New client '{$client->name}' onboarded",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->back()->with('success', "Client '{$client->name}' onboarded successfully into White Label Network!");
    }
}
