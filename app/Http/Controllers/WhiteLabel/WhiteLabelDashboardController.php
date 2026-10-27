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

class WhiteLabelDashboardController extends Controller
{
    /**
     * Helper to fetch end-clients dynamically from both Sass Admin DB and product tenant DBs (e.g. bazaarwa_ps_ysquare_launchshop).
     */
protected function getAgencyClientsData($agency)
    {
        if (!$agency) {
            return ['clients' => [], 'productCounts' => [], 'productCards' => []];
        }

        $cpanelUser = env('CPANEL_USER', 'nooryak');
        $agencySlug = str_replace('-', '_', strtolower($agency->slug ?? 'ysquare'));

        $agencyProducts = DB::table('agency_products')
            ->join('products', 'products.id', '=', 'agency_products.product_id')
            ->where('agency_products.agency_id', $agency->id)
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

        $allClients = [];
        $productCounts = [];
        $productCards = [];

        foreach ($agencyProducts as $idx => $ap) {
            $prodName = $ap->product_name ?? 'Product';
            $prodSlug = $ap->product_slug ?? 'launchshop';
            if ($prodSlug === 'launchshop') {
                $prodName = 'Ecombuilder';
            }
            $cleanSlug = str_replace('-', '_', $prodSlug);

            $dbCandidates = array_unique(array_filter([
                $ap->db_name ?? null,
                "{$cpanelUser}_ps_{$agencySlug}_{$prodSlug}",
                "{$cpanelUser}_ps_{$agencySlug}_{$cleanSlug}",
                "{$cpanelUser}_ps_{$agencySlug}_websitebuilder",
                "{$cpanelUser}_ps_{$agencySlug}_website_builder",
                "{$cpanelUser}_ps_{$agencySlug}_whatsappbulk",
                "{$cpanelUser}_ps_{$agencySlug}_whatsapp_bulk",
                "{$cpanelUser}_ps_{$agencySlug}_launchshop",
                "bazaarwa_ps_{$agencySlug}_{$prodSlug}",
                "bazaarwa_ps_{$agencySlug}_{$cleanSlug}",
                "bazaarwa_ps_{$agencySlug}_websitebuilder",
                "bazaarwa_ps_{$agencySlug}_whatsappbulk",
                "bazaarwa_ps_{$agencySlug}_launchshop",
                "nooryak_ps_{$agencySlug}_{$prodSlug}",
                "nooryak_ps_{$agencySlug}_{$cleanSlug}",
                "nooryak_ps_{$agencySlug}_websitebuilder",
                "nooryak_ps_{$agencySlug}_website_builder",
                "nooryak_ps_{$agencySlug}_whatsappbulk",
                "nooryak_ps_{$agencySlug}_whatsapp_bulk",
                "nooryak_ps_{$agencySlug}_launchshop",
                "{$cpanelUser}_whatautomation",
                "nooryak_whatautomation",
                "bazaarwa_launchshop",
                "nooryak_launchshop",
                env('DB_DATABASE'),
                env('LAUNCHSHOP_MAIN_DB'),
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

            $countInThisDb = 0;
            $dbOnline = false;
            $totalDbMrr = 0;

            if ($foundDb) {
                $dbOnline = true;
                try {
                    // Check if 'users' table exists in tenant database
                    $hasUsersTable = !empty(DB::select("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'users'", [$foundDb]));

                    if ($hasUsersTable) {
                        // Check if preview_template column exists (Launchshop)
                        $hasPreviewCol = !empty(DB::select("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'users' AND COLUMN_NAME = 'preview_template'", [$foundDb]));

                        $query = DB::table("{$foundDb}.users");
                        if ($hasPreviewCol) {
                            $query->where(function ($q) {
                                $q->whereNull('preview_template')
                                  ->orWhere('preview_template', 0)
                                  ->orWhere('preview_template', '0')
                                  ->orWhere('preview_template', '');
                            });
                        } else {
                            $userCount = DB::table("{$foundDb}.users")->count();
                            if ($userCount > 1) {
                                $hasRoleCol = !empty(DB::select("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'users' AND COLUMN_NAME = 'role'", [$foundDb]));
                                if ($hasRoleCol) {
                                    $query->where('role', '!=', 1);
                                } else {
                                    $query->where('id', '>', 1);
                                }
                            }
                        }

                        $tenantUsers = $query->get();

                        foreach ($tenantUsers as $tu) {
                            $name = trim(($tu->first_name ?? '') . ' ' . ($tu->last_name ?? ''));
                            if (empty($name)) {
                                $name = $tu->fullname ?? $tu->name ?? $tu->username ?? $tu->email ?? 'Client';
                            }
                            $status = ($tu->status ?? 1) == 1 ? 'active' : 'inactive';

                            $planTitle = 'Standard';
                            $planPrice = 2999;

                            // 1. Check memberships + packages (Launchshop)
                            try {
                                $hasMemberships = !empty(DB::select("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'memberships'", [$foundDb]));
                                if ($hasMemberships) {
                                    $memb = DB::table("{$foundDb}.memberships")->where('user_id', $tu->id)->orderBy('id', 'desc')->first();
                                    if ($memb) {
                                        $planPrice = floatval($memb->price ?? 0);
                                        $pkg = DB::table("{$foundDb}.packages")->where('id', $memb->package_id)->first();
                                        if ($pkg) {
                                            $planTitle = $pkg->title ?? $pkg->name ?? 'Standard';
                                            if ($planPrice <= 0) {
                                                $planPrice = floatval($pkg->price ?? 2999);
                                            }
                                        }
                                    }
                                }
                            } catch (\Throwable $mEx) {}

                            // 2. Check plans table (WhatsApp Bulk)
                            try {
                                if (isset($tu->plan_id) && $tu->plan_id > 0) {
                                    $hasPlans = !empty(DB::select("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'plans'", [$foundDb]));
                                    if ($hasPlans) {
                                        $waPlan = DB::table("{$foundDb}.plans")->where('id', $tu->plan_id)->first();
                                        if ($waPlan) {
                                            $planTitle = $waPlan->name ?? 'Pro Plan';
                                            $planPrice = floatval($waPlan->price ?? 1999);
                                        }
                                    }
                                }
                            } catch (\Throwable $pEx) {}

                            $createdDate = now()->format('M d, Y');
                            if (!empty($tu->created_at)) {
                                $createdDate = \Carbon\Carbon::parse($tu->created_at)->format('M d, Y');
                            } elseif (!empty($tu->created)) {
                                $createdDate = \Carbon\Carbon::createFromTimestamp($tu->created)->format('M d, Y');
                            }

                            $allClients[] = [
                                'id'           => $tu->id,
                                'name'         => $name,
                                'username'     => $tu->username ?? '',
                                'email'        => $tu->email ?? 'N/A',
                                'product_id'   => $ap->product_id,
                                'product_name' => $prodName,
                                'db_name'      => $foundDb,
                                'plan'         => $planTitle,
                                'price'        => $planPrice,
                                'status'       => $status,
                                'status_color' => $status === 'active' ? 'emerald' : 'amber',
                                'country'      => !empty($tu->country) ? ucfirst(strtolower($tu->country)) : (!empty($tu->country_code) ? strtoupper($tu->country_code) : 'India'),
                                'joined'       => $createdDate,
                                'products'     => [$prodSlug === 'launchshop' ? 'shopping-bag' : ($prodSlug === 'whatsappbulk' ? 'message-square' : 'layout')],
                            ];

                            $countInThisDb++;
                            $totalDbMrr += $planPrice;

                            if (!isset($productCounts[$prodName])) {
                                $productCounts[$prodName] = 0;
                            }
                            $productCounts[$prodName]++;
                        }
                    }

                    // Check wb_customers table for Website Builder
                    $hasWbCustomers = !empty(DB::select("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'wb_customers'", [$foundDb]));
                    if ($hasWbCustomers) {
                        $wbCustomers = DB::table("{$foundDb}.wb_customers")->get();
                        foreach ($wbCustomers as $wc) {
                            $cName = $wc->username ?? $wc->name ?? $wc->email ?? 'Client';
                            $cStatus = ($wc->status ?? 1) == 1 ? 'active' : 'inactive';
                            $cPrice = 2999;
                            $cCreated = !empty($wc->created_at) ? \Carbon\Carbon::parse($wc->created_at)->format('M d, Y') : now()->format('M d, Y');

                            $allClients[] = [
                                'id'           => 'wb_' . $wc->id,
                                'name'         => $cName,
                                'username'     => $wc->username ?? '',
                                'email'        => $wc->email ?? 'N/A',
                                'product_id'   => $ap->product_id,
                                'product_name' => $prodName,
                                'db_name'      => $foundDb,
                                'plan'         => 'Agency Pro',
                                'price'        => $cPrice,
                                'status'       => $cStatus,
                                'status_color' => $cStatus === 'active' ? 'emerald' : 'amber',
                                'country'      => 'India',
                                'joined'       => $cCreated,
                                'products'     => ['layout'],
                            ];
                            $countInThisDb++;
                            $totalDbMrr += $cPrice;

                            if (!isset($productCounts[$prodName])) {
                                $productCounts[$prodName] = 0;
                            }
                            $productCounts[$prodName]++;
                        }
                    }
                } catch (\Throwable $e) {}
            }

            $cardColor = ($prodSlug === 'launchshop') ? '#6366F1' : (($prodSlug === 'whatsappbulk' || $prodSlug === 'whatsapp-bulk') ? '#10B981' : '#0EA5E9');
            $cardIcon  = ($prodSlug === 'launchshop') ? 'shopping-bag' : (($prodSlug === 'whatsappbulk' || $prodSlug === 'whatsapp-bulk') ? 'message-square' : 'layout');

            $productCards[] = [
                'name'      => $prodName,
                'slug'      => $prodSlug,
                'count'     => $countInThisDb,
                'db_name'   => $foundDb ?? ($ap->db_name ?? 'Not Created'),
                'is_online' => $dbOnline,
                'color'     => $cardColor,
                'icon'      => $cardIcon,
                'mrr'       => $totalDbMrr,
            ];
        }

        // Sort product cards: 1: Launchshop (Ecombuilder), 2: Website Builder, 3: WhatsApp Bulk
        $cardOrder = [
            'launchshop'       => 1,
            'websitebuilder'   => 2,
            'website-builder'  => 2,
            'whatsappbulk'     => 3,
            'whatsapp-bulk'    => 3,
        ];
        usort($productCards, function ($a, $b) use ($cardOrder) {
            $getOrd = function ($item) use ($cardOrder) {
                $id = strtolower(trim(($item['slug'] ?? '') . ' ' . ($item['name'] ?? '')));
                if (str_contains($id, 'ecom') || str_contains($id, 'launchshop') || str_contains($id, 'store')) return 1;
                if (str_contains($id, 'website') || str_contains($id, 'site') || str_contains($id, 'builder')) return 2;
                if (str_contains($id, 'whatsapp') || str_contains($id, 'bulk')) return 3;
                return $cardOrder[$item['slug'] ?? ''] ?? 99;
            };
            return $getOrd($a) <=> $getOrd($b);
        });

        // Include any clients in Sass Admin DB
        $localUsers = User::where('agency_id', $agency->id)->where('role', 'client')->with('product')->get();
        foreach ($localUsers as $lu) {
            $pName = $lu->product->name ?? 'Ecombuilder';
            if ($lu->product && $lu->product->slug === 'launchshop') {
                $pName = 'Ecombuilder';
            }
            $status = $lu->status ?? 'active';
            $allClients[] = [
                'id'           => 'local_' . $lu->id,
                'name'         => $lu->name,
                'username'     => $lu->name,
                'email'        => $lu->email,
                'product_id'   => $lu->product_id,
                'product_name' => $pName,
                'db_name'      => 'sass_admin',
                'plan'         => 'Growth',
                'status'       => ucfirst($status),
                'status_color' => $status === 'active' ? 'emerald' : 'amber',
                                'country'      => !empty($tu->country) ? ucfirst(strtolower($tu->country)) : (!empty($tu->country_code) ? strtoupper($tu->country_code) : 'India'),
                'joined'       => $lu->created_at ? $lu->created_at->format('M d, Y') : now()->format('M d, Y'),
                'products'     => ['shopping-bag', 'users', 'layout'],
            ];

            if (!isset($productCounts[$pName])) {
                $productCounts[$pName] = 0;
            }
            $productCounts[$pName]++;
        }

        return [
            'clients'       => $allClients,
            'productCounts' => $productCounts,
            'productCards'  => $productCards,
        ];
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        $agency = $user->agency ?? Agency::where('type', 'white_label')->first();
        if (!$agency) {
            $agency = Agency::first();
        }

        // Revenue Overview Filter ONLY (Does not filter other widgets)
        $revenuePeriod = $request->input('revenue_period', $request->input('period', 'all'));
        $customStart   = $request->input('start_date');
        $customEnd     = $request->input('end_date');

        $revStartDate = null;
        $revEndDate   = null;

        if ($revenuePeriod === 'today') {
            $revStartDate = \Carbon\Carbon::today()->startOfDay();
            $revEndDate   = \Carbon\Carbon::now();
        } elseif ($revenuePeriod === 'yesterday') {
            $revStartDate = \Carbon\Carbon::yesterday()->startOfDay();
            $revEndDate   = \Carbon\Carbon::yesterday()->endOfDay();
        } elseif ($revenuePeriod === 'week') {
            $revStartDate = \Carbon\Carbon::now()->startOfWeek();
            $revEndDate   = \Carbon\Carbon::now()->endOfWeek();
        } elseif ($revenuePeriod === 'month') {
            $revStartDate = \Carbon\Carbon::now()->startOfMonth();
            $revEndDate   = \Carbon\Carbon::now()->endOfMonth();
        } elseif ($revenuePeriod === 'custom' && !empty($customStart)) {
            $revStartDate = \Carbon\Carbon::parse($customStart)->startOfDay();
            $revEndDate   = !empty($customEnd) ? \Carbon\Carbon::parse($customEnd)->endOfDay() : \Carbon\Carbon::now();
        }

        $clientsData = $this->getAgencyClientsData($agency);
        $rawClients   = $clientsData['clients'];
        $productCards = $clientsData['productCards'];

        // All agency clients are preserved for overall metrics, usage, tables, and distribution
        $allClients = $rawClients;

        $totalClients   = count($allClients);
        $activeClients  = 0;
        $pendingClients = 0;

        foreach ($allClients as $c) {
            if (strtolower($c['status']) === 'active') {
                $activeClients++;
            } else {
                $pendingClients++;
            }
        }

        // Total Agency MRR from all active client subscriptions
        $mrr = array_sum(array_column($allClients, 'price'));

        // Calculate Revenue specifically for Revenue Overview based on selected Revenue Period
        if ($revStartDate && $revEndDate) {
            $filteredRevClients = [];
            foreach ($rawClients as $c) {
                try {
                    $joinedCarbon = \Carbon\Carbon::parse($c['joined']);
                    if ($joinedCarbon->between($revStartDate, $revEndDate)) {
                        $filteredRevClients[] = $c;
                    }
                } catch (\Throwable $dEx) {}
            }
            $revenueOverviewAmount = array_sum(array_column($filteredRevClients, 'price'));
        } else {
            // 'all' time
            $revenueOverviewAmount = $mrr;
        }

        // Generate Dynamic Revenue Chart based on selected Revenue Period
        if ($revenuePeriod === 'today' || $revenuePeriod === 'yesterday') {
            $revenueChartLabels = ['09:00', '12:00', '15:00', '18:00', '21:00', '23:59'];
        } elseif ($revenuePeriod === 'week') {
            $revenueChartLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        } else {
            $monthName = now()->format('M');
            $revenueChartLabels = ["{$monthName} 1", "{$monthName} 6", "{$monthName} 11", "{$monthName} 16", "{$monthName} 21", "{$monthName} 26", "{$monthName} 31"];
        }

        $numPoints = count($revenueChartLabels);
        if ($revenueOverviewAmount > 0) {
            $revenueChartData = [];
            for ($i = 1; $i <= $numPoints; $i++) {
                $pct = $i / $numPoints;
                $revenueChartData[] = round($revenueOverviewAmount * max(0.15, $pct));
            }
        } else {
            $revenueChartData = array_fill(0, $numPoints, 0);
        }

        // Calculate Dynamic % vs last month
        $thisMonthStart = \Carbon\Carbon::now()->startOfMonth();
        $thisMonthEnd   = \Carbon\Carbon::now()->endOfMonth();
        $lastMonthStart = \Carbon\Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd   = \Carbon\Carbon::now()->subMonth()->endOfMonth();

        $thisMonthRev = 0;
        $lastMonthRev = 0;
        foreach ($rawClients as $c) {
            $p = floatval($c['price'] ?? 0);
            try {
                $joinedCarbon = \Carbon\Carbon::parse($c['joined']);
                if ($joinedCarbon->between($thisMonthStart, $thisMonthEnd)) {
                    $thisMonthRev += $p;
                } elseif ($joinedCarbon->between($lastMonthStart, $lastMonthEnd)) {
                    $lastMonthRev += $p;
                }
            } catch (\Throwable $e) {}
        }

        if ($thisMonthRev == 0 && $mrr > 0) {
            $thisMonthRev = $mrr;
        }

        if ($lastMonthRev > 0) {
            $diff = $thisMonthRev - $lastMonthRev;
            $revChangePct = round(($diff / $lastMonthRev) * 100, 1);
            $revChangeDirection = ($revChangePct >= 0) ? 'up' : 'down';
            $revChangePct = abs($revChangePct);
        } elseif ($thisMonthRev > 0) {
            $revChangePct = 100.0;
            $revChangeDirection = 'up';
        } else {
            $revChangePct = 0.0;
            $revChangeDirection = 'up';
        }

        $activeSubscriptions = $activeClients;

        // Recalculate productCards and productUsage based on all agency clients
        $clientCounts = [];
        $clientMrrs   = [];
        foreach ($allClients as $c) {
            $pName = $c['product_name'] ?? 'Ecombuilder';
            $clientCounts[$pName] = ($clientCounts[$pName] ?? 0) + 1;
            $clientMrrs[$pName]   = ($clientMrrs[$pName] ?? 0) + ($c['price'] ?? 0);
        }

        foreach ($productCards as &$pc) {
            $pc['count'] = $clientCounts[$pc['name']] ?? 0;
            $pc['mrr']   = $clientMrrs[$pc['name']] ?? 0;
        }
        unset($pc);

        $productsInUseCount = count(array_filter($productCards, function ($pc) { return $pc['count'] > 0; }));
        $totalProductsCount = count($productCards) > 0 ? count($productCards) : (Product::count() > 0 ? Product::count() : 1);

        $productUsage = [];
        foreach ($productCards as $pc) {
            $count = $pc['count'];
            $pct   = ($totalClients > 0 && $count > 0) ? min(100, round(($count / $totalClients) * 100)) : 0;
            $productUsage[] = [
                'name'       => $pc['name'],
                'clients'    => $count,
                'percentage' => $pct,
                'color'      => $pc['color'],
                'progress'   => $pct,
                'icon'       => $pc['icon'],
                'db_name'    => $pc['db_name'],
                'is_online'  => $pc['is_online'],
                'mrr'        => $pc['mrr'],
            ];
        }

        // Recent Clients (unfiltered, top 5)
        $recentClients = array_slice($allClients, 0, 5);

        // Recent Activity Stream from database AuditLogs
        $recentActivities = [];
        $logs = AuditLog::latest()->take(5)->get();
        foreach ($logs as $log) {
            $recentActivities[] = [
                'title'      => $log->action,
                'time'       => $log->created_at ? $log->created_at->diffForHumans() : 'Just now',
                'icon'       => 'activity',
                'bg_color'   => 'bg-indigo-100',
                'text_color' => 'text-indigo-600',
                'amount'     => null,
            ];
        }

        // Subscription status live breakdown
        $subscriptionStatus = [
            'active'    => $activeClients,
            'expiring'  => $pendingClients,
            'cancelled' => 0,
            'trial'     => max(0, $totalClients - $activeClients - $pendingClients),
        ];

        // Dynamic Client distribution by location
        $countryCounts = [
            'India'     => 0,
            'USA'       => 0,
            'UK'        => 0,
            'Australia' => 0,
            'Others'    => 0,
        ];

        foreach ($allClients as $c) {
            $cCountry = $c['country'] ?? 'India';
            if (isset($countryCounts[$cCountry])) {
                $countryCounts[$cCountry]++;
            } else {
                $countryCounts['Others']++;
            }
        }

        $clientDistribution = [];
        $countryColors = [
            'India'     => '#6366F1',
            'USA'       => '#818CF8',
            'UK'        => '#A5B4FC',
            'Australia' => '#C7D2FE',
            'Others'    => '#E0E7FF',
        ];

        foreach ($countryCounts as $country => $cnt) {
            $pct = ($totalClients > 0 && $cnt > 0) ? round(($cnt / $totalClients) * 100) : 0;
            $clientDistribution[] = [
                'country'    => $country,
                'count'      => $cnt,
                'percentage' => $pct,
                'color'      => $countryColors[$country] ?? '#E0E7FF',
            ];
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'amount'             => '₹' . number_format($revenueOverviewAmount),
                'mrr'                => $revenueOverviewAmount,
                'revenueChartLabels' => $revenueChartLabels,
                'revenueChartData'   => $revenueChartData,
                'revChangePct'       => $revChangePct,
                'revChangeDirection' => $revChangeDirection,
            ]);
        }

        return view('whitelabel.dashboard', compact(
            'user',
            'agency',
            'revenuePeriod',
            'revenueOverviewAmount',
            'revChangePct',
            'revChangeDirection',
            'customStart',
            'customEnd',
            'totalClients',
            'activeClients',
            'mrr',
            'revenueChartLabels',
            'revenueChartData',
            'activeSubscriptions',
            'productsInUseCount',
            'totalProductsCount',
            'productUsage',
            'productCards',
            'recentClients',
            'recentActivities',
            'subscriptionStatus',
            'clientDistribution'
        ));
    }
}
