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
     * Helper to resolve or normalize an Indian state dynamically from client records across all 3 products.
     */
    protected function resolveIndianState($state = null, $city = null, $address = null, $fallback = 'Maharashtra')
    {
        $indianStatesMap = [
            'maharashtra'       => 'Maharashtra',
            'mh'                => 'Maharashtra',
            'delhi'             => 'Delhi',
            'dl'                => 'Delhi',
            'new delhi'         => 'Delhi',
            'ncr'               => 'Delhi',
            'karnataka'         => 'Karnataka',
            'ka'                => 'Karnataka',
            'gujarat'           => 'Gujarat',
            'gj'                => 'Gujarat',
            'tamil nadu'        => 'Tamil Nadu',
            'tamilnadu'         => 'Tamil Nadu',
            'tn'                => 'Tamil Nadu',
            'uttar pradesh'     => 'Uttar Pradesh',
            'up'                => 'Uttar Pradesh',
            'west bengal'       => 'West Bengal',
            'wb'                => 'West Bengal',
            'telangana'         => 'Telangana',
            'ts'                => 'Telangana',
            'tg'                => 'Telangana',
            'rajasthan'         => 'Rajasthan',
            'rj'                => 'Rajasthan',
            'kerala'            => 'Kerala',
            'kl'                => 'Kerala',
            'andhra pradesh'    => 'Andhra Pradesh',
            'ap'                => 'Andhra Pradesh',
            'madhya pradesh'    => 'Madhya Pradesh',
            'mp'                => 'Madhya Pradesh',
            'punjab'            => 'Punjab',
            'pb'                => 'Punjab',
            'haryana'           => 'Haryana',
            'hr'                => 'Haryana',
            'bihar'             => 'Bihar',
            'br'                => 'Bihar',
            'odisha'            => 'Odisha',
            'orissa'            => 'Odisha',
            'od'                => 'Odisha',
            'or'                => 'Odisha',
            'jharkhand'         => 'Jharkhand',
            'jh'                => 'Jharkhand',
            'chhattisgarh'      => 'Chhattisgarh',
            'cg'                => 'Chhattisgarh',
            'uttarakhand'       => 'Uttarakhand',
            'uk'                => 'Uttarakhand',
            'ut'                => 'Uttarakhand',
            'himachal pradesh'  => 'Himachal Pradesh',
            'hp'                => 'Himachal Pradesh',
            'goa'               => 'Goa',
            'ga'                => 'Goa',
            'assam'             => 'Assam',
            'as'                => 'Assam',
            'jammu & kashmir'   => 'Jammu & Kashmir',
            'jammu and kashmir' => 'Jammu & Kashmir',
            'jk'                => 'Jammu & Kashmir',
            'chandigarh'        => 'Chandigarh',
            'ch'                => 'Chandigarh',
            'puducherry'        => 'Puducherry',
            'py'                => 'Puducherry',
        ];

        // 1. Direct State Check
        if (!empty($state) && is_string($state)) {
            $cleaned = strtolower(trim($state));
            if (isset($indianStatesMap[$cleaned])) {
                return $indianStatesMap[$cleaned];
            }
            foreach ($indianStatesMap as $key => $val) {
                if (str_contains($cleaned, $key)) {
                    return $val;
                }
            }
        }

        // 2. City Mapping
        $cityMap = [
            'mumbai' => 'Maharashtra', 'pune' => 'Maharashtra', 'nagpur' => 'Maharashtra', 'nashik' => 'Maharashtra', 'thane' => 'Maharashtra', 'aurangabad' => 'Maharashtra', 'navi mumbai' => 'Maharashtra', 'solapur' => 'Maharashtra', 'kolhapur' => 'Maharashtra',
            'delhi' => 'Delhi', 'new delhi' => 'Delhi',
            'bangalore' => 'Karnataka', 'bengaluru' => 'Karnataka', 'mysore' => 'Karnataka', 'mysuru' => 'Karnataka', 'mangalore' => 'Karnataka', 'mangaluru' => 'Karnataka', 'hubli' => 'Karnataka',
            'ahmedabad' => 'Gujarat', 'surat' => 'Gujarat', 'vadodara' => 'Gujarat', 'rajkot' => 'Gujarat', 'gandhinagar' => 'Gujarat', 'bhavnagar' => 'Gujarat', 'jamnagar' => 'Gujarat',
            'chennai' => 'Tamil Nadu', 'coimbatore' => 'Tamil Nadu', 'madurai' => 'Tamil Nadu', 'tiruchirappalli' => 'Tamil Nadu', 'salem' => 'Tamil Nadu',
            'lucknow' => 'Uttar Pradesh', 'kanpur' => 'Uttar Pradesh', 'noida' => 'Uttar Pradesh', 'greater noida' => 'Uttar Pradesh', 'ghaziabad' => 'Uttar Pradesh', 'agra' => 'Uttar Pradesh', 'varanasi' => 'Uttar Pradesh', 'prayagraj' => 'Uttar Pradesh', 'allahabad' => 'Uttar Pradesh', 'meerut' => 'Uttar Pradesh',
            'hyderabad' => 'Telangana', 'secunderabad' => 'Telangana', 'warangal' => 'Telangana',
            'kolkata' => 'West Bengal', 'howrah' => 'West Bengal', 'durgapur' => 'West Bengal', 'asansol' => 'West Bengal', 'siliguri' => 'West Bengal',
            'jaipur' => 'Rajasthan', 'jodhpur' => 'Rajasthan', 'udaipur' => 'Rajasthan', 'kota' => 'Rajasthan', 'bikaner' => 'Rajasthan', 'ajmer' => 'Rajasthan',
            'kochi' => 'Kerala', 'thiruvananthapuram' => 'Kerala', 'trivandrum' => 'Kerala', 'kozhikode' => 'Kerala', 'calicut' => 'Kerala', 'thrissur' => 'Kerala',
            'visakhapatnam' => 'Andhra Pradesh', 'vizag' => 'Andhra Pradesh', 'vijayawada' => 'Andhra Pradesh', 'guntur' => 'Andhra Pradesh', 'tirupati' => 'Andhra Pradesh',
            'indore' => 'Madhya Pradesh', 'bhopal' => 'Madhya Pradesh', 'gwalior' => 'Madhya Pradesh', 'jabalpur' => 'Madhya Pradesh', 'ujjain' => 'Madhya Pradesh',
            'ludhiana' => 'Punjab', 'amritsar' => 'Punjab', 'jalandhar' => 'Punjab', 'patiala' => 'Punjab',
            'gurgaon' => 'Haryana', 'gurugram' => 'Haryana', 'faridabad' => 'Haryana', 'panipat' => 'Haryana', 'ambala' => 'Haryana', 'karnal' => 'Haryana',
            'patna' => 'Bihar', 'gaya' => 'Bihar', 'bhagalpur' => 'Bihar', 'muzaffarpur' => 'Bihar',
            'bhubaneswar' => 'Odisha', 'cuttack' => 'Odisha', 'rourkela' => 'Odisha',
            'ranchi' => 'Jharkhand', 'jamshedpur' => 'Jharkhand', 'dhanbad' => 'Jharkhand',
            'raipur' => 'Chhattisgarh', 'bhilai' => 'Chhattisgarh', 'bilaspur' => 'Chhattisgarh',
            'dehradun' => 'Uttarakhand', 'haridwar' => 'Uttarakhand', 'rishikesh' => 'Uttarakhand', 'roorkee' => 'Uttarakhand',
            'shimla' => 'Himachal Pradesh', 'dharamshala' => 'Himachal Pradesh', 'manali' => 'Himachal Pradesh',
            'panaji' => 'Goa', 'margao' => 'Goa', 'vasco' => 'Goa',
            'guwahati' => 'Assam', 'silchar' => 'Assam', 'dibrugarh' => 'Assam',
            'srinagar' => 'Jammu & Kashmir', 'jammu' => 'Jammu & Kashmir',
            'chandigarh' => 'Chandigarh',
            'pondicherry' => 'Puducherry', 'puducherry' => 'Puducherry',
        ];

        if (!empty($city) && is_string($city)) {
            $cClean = strtolower(trim($city));
            if (isset($cityMap[$cClean])) {
                return $cityMap[$cClean];
            }
            foreach ($cityMap as $kCity => $vState) {
                if (str_contains($cClean, $kCity)) {
                    return $vState;
                }
            }
        }

        // 3. Address Scanning
        if (!empty($address) && is_string($address)) {
            $addrClean = strtolower($address);
            foreach ($cityMap as $kCity => $vState) {
                if (str_contains($addrClean, $kCity)) {
                    return $vState;
                }
            }
            foreach ($indianStatesMap as $kState => $vState) {
                if (strlen($kState) > 2 && str_contains($addrClean, $kState)) {
                    return $vState;
                }
            }
        }

        return $fallback;
    }

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

                            $tuData = null;
                            if (!empty($tu->data)) {
                                if (is_string($tu->data)) {
                                    $tuData = json_decode($tu->data, true) ?: @unserialize($tu->data);
                                } elseif (is_array($tu->data)) {
                                    $tuData = $tu->data;
                                }
                            }
                            $waState = $tu->state ?? ($tuData['state'] ?? null);
                            $waCity = $tu->city ?? ($tuData['city'] ?? null);
                            $waAddress = $tu->address ?? ($tuData['address'] ?? ($tuData['location'] ?? null));

                            $clientState = $this->resolveIndianState(
                                $waState,
                                $waCity,
                                $waAddress,
                                ($prodSlug === 'launchshop' ? 'Maharashtra' : ($prodSlug === 'whatsappbulk' ? 'Maharashtra' : 'Karnataka'))
                            );

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
                                'country'      => $clientState,
                                'state'        => $clientState,
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

                            $wbState = $this->resolveIndianState(
                                $wc->state ?? null,
                                $wc->city ?? null,
                                $wc->address ?? null,
                                'Karnataka'
                            );

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
                                'country'      => $wbState,
                                'state'        => $wbState,
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
            $localState = $this->resolveIndianState(
                $lu->state ?? null,
                $lu->city ?? null,
                null,
                'Maharashtra'
            );

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
                'country'      => $localState,
                'state'        => $localState,
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

    private function resolveDateRange($period, $customStart = null, $customEnd = null)
    {
        $startDate = null;
        $endDate = null;

        if ($period === 'today') {
            $startDate = \Carbon\Carbon::today()->startOfDay();
            $endDate = \Carbon\Carbon::now();
        } elseif ($period === 'yesterday') {
            $startDate = \Carbon\Carbon::yesterday()->startOfDay();
            $endDate = \Carbon\Carbon::yesterday()->endOfDay();
        } elseif ($period === 'week') {
            $startDate = \Carbon\Carbon::now()->startOfWeek();
            $endDate = \Carbon\Carbon::now()->endOfWeek();
        } elseif ($period === 'month') {
            $startDate = \Carbon\Carbon::now()->startOfMonth();
            $endDate = \Carbon\Carbon::now()->endOfMonth();
        } elseif ($period === 'custom' && !empty($customStart)) {
            $startDate = \Carbon\Carbon::parse($customStart)->startOfDay();
            $endDate = !empty($customEnd) ? \Carbon\Carbon::parse($customEnd)->endOfDay() : \Carbon\Carbon::now();
        }

        return [$startDate, $endDate];
    }

    private function filterClientsByDate($clients, $startDate, $endDate)
    {
        if (!$startDate || !$endDate) {
            return $clients;
        }

        $filtered = [];
        foreach ($clients as $c) {
            try {
                $joinedCarbon = \Carbon\Carbon::parse($c['joined']);
                if ($joinedCarbon->between($startDate, $endDate)) {
                    $filtered[] = $c;
                }
            } catch (\Throwable $e) {}
        }
        return $filtered;
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        $agency = Agency::resolveCurrent($request, $user);

        $clientsData = $this->getAgencyClientsData($agency);
        $rawClients   = $clientsData['clients'];
        $productCards = $clientsData['productCards'];

        // SECTION AJAX HANDLER: If request is asking for a specific section filter
        if ($request->ajax() || $request->wantsJson() || $request->has('section')) {
            $section = $request->input('section', ($request->has('revenue_period') ? 'revenue' : null));
            if ($section) {
                $secPeriod = $request->input('period', $request->input('revenue_period', 'all'));
                $customStart = $request->input('start_date');
                $customEnd = $request->input('end_date');

                list($sDate, $eDate) = $this->resolveDateRange($secPeriod, $customStart, $customEnd);
                $secClients = $this->filterClientsByDate($rawClients, $sDate, $eDate);
                $secTotal = count($secClients);

                if ($section === 'revenue') {
                    $revAmt = ($secPeriod === 'all') ? array_sum(array_column($rawClients, 'price')) : array_sum(array_column($secClients, 'price'));

                    if ($secPeriod === 'today' || $secPeriod === 'yesterday') {
                        $labels = ['09:00', '12:00', '15:00', '18:00', '21:00', '23:59'];
                    } elseif ($secPeriod === 'week') {
                        $labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                    } elseif ($secPeriod === 'custom' && $sDate && $eDate) {
                        $diffDays = $sDate->diffInDays($eDate);
                        if ($diffDays <= 7) {
                            $labels = [];
                            $cur = $sDate->copy();
                            while ($cur->lte($eDate)) {
                                $labels[] = $cur->format('d M');
                                $cur->addDay();
                            }
                        } else {
                            $labels = [$sDate->format('d M'), $sDate->copy()->addDays(round($diffDays * 0.25))->format('d M'), $sDate->copy()->addDays(round($diffDays * 0.5))->format('d M'), $sDate->copy()->addDays(round($diffDays * 0.75))->format('d M'), $eDate->format('d M')];
                        }
                    } else {
                        $monthName = now()->format('M');
                        $labels = ["{$monthName} 1", "{$monthName} 6", "{$monthName} 11", "{$monthName} 16", "{$monthName} 21", "{$monthName} 26", "{$monthName} 31"];
                    }

                    $numPts = count($labels);
                    $chartData = [];
                if ($revAmt > 0) {
                    for ($i = 1; $i <= $numPts; $i++) {
                        $pct = $i / $numPts;
                        $chartData[] = round($revAmt * max(0.15, $pct));
                    }
                } else {
                    $chartData = array_fill(0, $numPts, 0);
                }

                // Dynamic comparison vs last month
                $thisMonthStart = \Carbon\Carbon::now()->startOfMonth();
                $thisMonthEnd   = \Carbon\Carbon::now()->endOfMonth();
                $lastMonthStart = \Carbon\Carbon::now()->subMonth()->startOfMonth();
                $lastMonthEnd   = \Carbon\Carbon::now()->subMonth()->endOfMonth();

                $lastMonthRev = 0;
                foreach ($rawClients as $c) {
                    try {
                        $dt = \Carbon\Carbon::parse($c['joined']);
                        if ($dt->between($lastMonthStart, $lastMonthEnd)) {
                            $lastMonthRev += floatval($c['price'] ?? 0);
                        }
                    } catch (\Throwable $e) {}
                }

                if ($revAmt <= 0) {
                    $changePct = 0;
                    $direction = 'neutral';
                } elseif ($lastMonthRev > 0) {
                    $diff = $revAmt - $lastMonthRev;
                    $changePct = round(($diff / $lastMonthRev) * 100, 1);
                    $direction = ($changePct >= 0) ? 'up' : 'down';
                    $changePct = abs($changePct);
                } else {
                    $changePct = 100.0;
                    $direction = 'up';
                }

                return response()->json([
                    'section'            => 'revenue',
                    'amount'             => '₹' . number_format($revAmt),
                    'mrr'                => $revAmt,
                    'revenueChartLabels' => $labels,
                    'revenueChartData'   => $chartData,
                    'revChangePct'       => $changePct,
                    'revChangeDirection' => $direction,
                ]);
            }

            if ($section === 'product_cards') {
                $counts = [];
                $mrrs = [];
                foreach ($secClients as $c) {
                    $pName = $c['product_name'] ?? 'Ecombuilder';
                    $counts[$pName] = ($counts[$pName] ?? 0) + 1;
                    $mrrs[$pName] = ($mrrs[$pName] ?? 0) + ($c['price'] ?? 0);
                }
                $cardsOut = [];
                foreach ($productCards as $pc) {
                    $cCnt = $counts[$pc['name']] ?? 0;
                    $cMrr = $mrrs[$pc['name']] ?? 0;
                    $pct = ($secTotal > 0 && $cCnt > 0) ? ($secTotal <= 1 ? min(100, $cCnt) : min(100, round(($cCnt / $secTotal) * 100))) : 0;
                    $cardsOut[] = [
                        'name'       => $pc['name'],
                        'slug'       => $pc['slug'],
                        'count'      => $cCnt,
                        'mrr'        => $cMrr,
                        'percentage' => $pct,
                    ];
                }
                return response()->json([
                    'section' => 'product_cards',
                    'cards'   => $cardsOut,
                ]);
            }

            if ($section === 'product_usage' || $section === 'top_products') {
                $counts = [];
                foreach ($secClients as $c) {
                    $pName = $c['product_name'] ?? 'Ecombuilder';
                    $counts[$pName] = ($counts[$pName] ?? 0) + 1;
                }
                $usageOut = [];
                foreach ($productCards as $pc) {
                    $cCnt = $counts[$pc['name']] ?? 0;
                    $pct = ($secTotal > 0 && $cCnt > 0) ? ($secTotal <= 1 ? min(100, $cCnt) : min(100, round(($cCnt / $secTotal) * 100))) : 0;
                    $usageOut[] = [
                        'name'       => $pc['name'],
                        'clients'    => $cCnt,
                        'percentage' => $pct,
                        'color'      => $pc['color'],
                        'icon'       => $pc['icon'],
                    ];
                }
                return response()->json([
                    'section'      => $section,
                    'totalClients' => $secTotal,
                    'productUsage' => $usageOut,
                ]);
            }

            if ($section === 'recent_clients') {
                $recentOut = [];
                foreach (array_slice($secClients, 0, 5) as $rc) {
                    $recentOut[] = [
                        'name'         => $rc['name'],
                        'products'     => $rc['products'] ?? ['shopping-bag'],
                        'plan'         => $rc['plan'] ?? 'Standard',
                        'status'       => $rc['status'] ?? 'Active',
                        'status_color' => $rc['status_color'] ?? 'emerald',
                        'joined'       => $rc['joined'] ?? '',
                    ];
                }
                return response()->json([
                    'section' => 'recent_clients',
                    'clients' => $recentOut,
                ]);
            }

            if ($section === 'recent_activity') {
                $logsQuery = AuditLog::latest();
                if ($sDate && $eDate) {
                    $logsQuery->whereBetween('created_at', [$sDate, $eDate]);
                }
                $actOut = [];
                foreach ($logsQuery->take(5)->get() as $log) {
                    $actOut[] = [
                        'title' => $log->action,
                        'time'  => $log->created_at ? $log->created_at->diffForHumans() : 'Just now',
                    ];
                }
                return response()->json([
                    'section'    => 'recent_activity',
                    'activities' => $actOut,
                ]);
            }

            if ($section === 'subscription_status') {
                $actv = 0;
                $pnd = 0;
                foreach ($secClients as $c) {
                    if (strtolower($c['status']) === 'active') {
                        $actv++;
                    } else {
                        $pnd++;
                    }
                }
                return response()->json([
                    'section' => 'subscription_status',
                    'status'  => [
                        'active'    => $actv,
                        'expiring'  => $pnd,
                        'cancelled' => 0,
                        'trial'     => max(0, $secTotal - $actv - $pnd),
                    ],
                ]);
            }

            if ($section === 'client_distribution') {
                $stateColors = [
                    'Maharashtra'    => '#6366F1',
                    'Delhi'          => '#818CF8',
                    'Karnataka'      => '#0EA5E9',
                    'Gujarat'        => '#10B981',
                    'Tamil Nadu'     => '#F59E0B',
                    'Uttar Pradesh'  => '#EC4899',
                    'West Bengal'    => '#8B5CF6',
                    'Telangana'      => '#06B6D4',
                    'Rajasthan'      => '#F97316',
                    'Kerala'         => '#14B8A6',
                    'Others'         => '#94A3B8',
                ];

                $dynamicStateCounts = [];
                foreach ($secClients as $c) {
                    $st = $c['state'] ?? ($c['country'] ?? 'Maharashtra');
                    $dynamicStateCounts[$st] = ($dynamicStateCounts[$st] ?? 0) + 1;
                }

                arsort($dynamicStateCounts);
                $displayStates = [];
                foreach ($dynamicStateCounts as $st => $cnt) {
                    $displayStates[$st] = $cnt;
                }
                $baseStates = ['Maharashtra', 'Delhi', 'Karnataka', 'Gujarat', 'Tamil Nadu'];
                foreach ($baseStates as $bst) {
                    if (!isset($displayStates[$bst])) {
                        $displayStates[$bst] = 0;
                    }
                }
                if (!isset($displayStates['Others'])) {
                    $displayStates['Others'] = 0;
                }

                $topStates = [];
                $othersCount = 0;
                $i = 0;
                foreach ($displayStates as $st => $cnt) {
                    if ($st === 'Others') {
                        $othersCount += $cnt;
                        continue;
                    }
                    if ($i < 5) {
                        $topStates[$st] = $cnt;
                        $i++;
                    } else {
                        $othersCount += $cnt;
                    }
                }
                $topStates['Others'] = $othersCount;

                $distOut = [];
                foreach ($topStates as $stateName => $cnt) {
                    $pct = ($secTotal > 0 && $cnt > 0) ? ($secTotal <= 1 ? min(100, $cnt) : round(($cnt / $secTotal) * 100)) : 0;
                    $distOut[] = [
                        'state'      => $stateName,
                        'country'    => $stateName,
                        'count'      => $cnt,
                        'percentage' => $pct,
                        'color'      => $stateColors[$stateName] ?? '#6366F1',
                    ];
                }
                return response()->json([
                    'section'      => 'client_distribution',
                    'distribution' => $distOut,
                ]);
            }
        }
    }

        // Full Dashboard Default Rendering
        $revenuePeriod = $request->input('revenue_period', $request->input('period', 'all'));
        $customStart   = $request->input('start_date');
        $customEnd     = $request->input('end_date');

        list($revStartDate, $revEndDate) = $this->resolveDateRange($revenuePeriod, $customStart, $customEnd);

        $allClients     = $rawClients;
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

        $mrr = array_sum(array_column($allClients, 'price'));

        if ($revStartDate && $revEndDate) {
            $filteredRev = $this->filterClientsByDate($rawClients, $revStartDate, $revEndDate);
            $revenueOverviewAmount = array_sum(array_column($filteredRev, 'price'));
        } else {
            $revenueOverviewAmount = $mrr;
        }

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

        // Dynamic % vs last month
        $lastMonthStart = \Carbon\Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd   = \Carbon\Carbon::now()->subMonth()->endOfMonth();
        $lastMonthRev = 0;
        foreach ($rawClients as $c) {
            try {
                $dt = \Carbon\Carbon::parse($c['joined']);
                if ($dt->between($lastMonthStart, $lastMonthEnd)) {
                    $lastMonthRev += floatval($c['price'] ?? 0);
                }
            } catch (\Throwable $e) {}
        }

        if ($revenueOverviewAmount <= 0) {
            $revChangePct = 0;
            $revChangeDirection = 'neutral';
        } elseif ($lastMonthRev > 0) {
            $diff = $revenueOverviewAmount - $lastMonthRev;
            $revChangePct = round(($diff / $lastMonthRev) * 100, 1);
            $revChangeDirection = ($revChangePct >= 0) ? 'up' : 'down';
            $revChangePct = abs($revChangePct);
        } else {
            $revChangePct = 100.0;
            $revChangeDirection = 'up';
        }

        $activeSubscriptions = $activeClients;

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
            $pct   = ($totalClients > 0 && $count > 0) ? ($totalClients <= 1 ? min(100, $count) : min(100, round(($count / $totalClients) * 100))) : 0;
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

        $recentClients = array_slice($allClients, 0, 5);

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

        $subscriptionStatus = [
            'active'    => $activeClients,
            'expiring'  => $pendingClients,
            'cancelled' => 0,
            'trial'     => max(0, $totalClients - $activeClients - $pendingClients),
        ];

        $stateColors = [
            'Maharashtra'    => '#6366F1',
            'Delhi'          => '#818CF8',
            'Karnataka'      => '#0EA5E9',
            'Gujarat'        => '#10B981',
            'Tamil Nadu'     => '#F59E0B',
            'Uttar Pradesh'  => '#EC4899',
            'West Bengal'    => '#8B5CF6',
            'Telangana'      => '#06B6D4',
            'Rajasthan'      => '#F97316',
            'Kerala'         => '#14B8A6',
            'Others'         => '#94A3B8',
        ];

        $dynamicStateCounts = [];
        foreach ($allClients as $c) {
            $st = $c['state'] ?? ($c['country'] ?? 'Maharashtra');
            $dynamicStateCounts[$st] = ($dynamicStateCounts[$st] ?? 0) + 1;
        }

        arsort($dynamicStateCounts);
        $displayStates = [];
        foreach ($dynamicStateCounts as $st => $cnt) {
            $displayStates[$st] = $cnt;
        }

        $baseStates = ['Maharashtra', 'Delhi', 'Karnataka', 'Gujarat', 'Tamil Nadu'];
        foreach ($baseStates as $bst) {
            if (!isset($displayStates[$bst])) {
                $displayStates[$bst] = 0;
            }
        }
        if (!isset($displayStates['Others'])) {
            $displayStates['Others'] = 0;
        }

        $topStates = [];
        $othersCount = 0;
        $i = 0;
        foreach ($displayStates as $st => $cnt) {
            if ($st === 'Others') {
                $othersCount += $cnt;
                continue;
            }
            if ($i < 5) {
                $topStates[$st] = $cnt;
                $i++;
            } else {
                $othersCount += $cnt;
            }
        }
        $topStates['Others'] = $othersCount;

        $clientDistribution = [];
        foreach ($topStates as $stateName => $cnt) {
            // When totalClients <= 1 (e.g. only 1 client), display 1 = 1%
            $pct = ($totalClients > 0 && $cnt > 0) ? ($totalClients <= 1 ? min(100, $cnt) : round(($cnt / $totalClients) * 100)) : 0;
            $clientDistribution[] = [
                'state'      => $stateName,
                'country'    => $stateName,
                'count'      => $cnt,
                'percentage' => $pct,
                'color'      => $stateColors[$stateName] ?? '#6366F1',
            ];
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
