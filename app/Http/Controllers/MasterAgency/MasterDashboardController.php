<?php

namespace App\Http\Controllers\MasterAgency;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\AuditLog;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MasterDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Fetch or default to logged-in user's Master Agency
        $agency = $user->agency;
        if (!$agency && $user->isSuperAdmin()) {
            $agency = Agency::where('type', 'master')->first();
        }
        if (!$agency) {
            $agency = Agency::first();
        }

        $agencyId = $agency->id ?? 0;

        // Sub-agencies managed specifically under this Master Agency
        $subAgencies = Agency::where('parent_id', $agencyId)
            ->with(['subscription.plan', 'products'])
            ->latest()
            ->get();

        $totalSubAgencies = $subAgencies->count();
        $totalClients = $subAgencies->sum('max_clients');
        
        $mrr = 0;
        $activeSubscriptions = 0;
        foreach ($subAgencies as $sub) {
            if ($sub->subscription && $sub->subscription->status === 'active') {
                $mrr += $sub->subscription->amount;
                $activeSubscriptions++;
            }
        }

        $productsInUseCount = $subAgencies->pluck('products')->flatten()->unique('id')->count();
        $totalProductsCount = Product::where('is_active', true)->count();

        // Build dynamic recent sub-agencies list
        $subAgenciesList = [];
        foreach ($subAgencies->take(5) as $sub) {
            $subAgenciesList[] = [
                'name' => $sub->name,
                'owner' => $sub->owner_name,
                'plan' => $sub->subscription->plan->name ?? 'Standard',
                'plan_color' => 'violet',
                'clients' => $sub->max_clients,
                'mrr' => $sub->subscription->amount ?? 0,
                'status' => ucfirst($sub->status),
                'status_color' => $sub->status === 'active' ? 'emerald' : 'rose',
            ];
        }

        // Fetch real audit logs / activities
        $activities = [];
        $logs = AuditLog::where('user_id', $user->id)->latest()->take(5)->get();
        if ($logs->isEmpty()) {
            $logs = AuditLog::latest()->take(5)->get();
        }

        foreach ($logs as $log) {
            $activities[] = [
                'title' => $log->action,
                'time' => $log->created_at ? $log->created_at->diffForHumans() : 'Recently',
                'icon' => 'activity',
                'color' => 'indigo',
            ];
        }

        // Plan distribution breakdown
        $planCounts = [
            'Enterprise' => 0,
            'Growth' => 0,
            'Starter' => 0,
            'Trial' => 0,
        ];
        
        $subscriptionStats = [
            'active' => 0,
            'expiring' => 0,
            'cancelled' => 0,
            'trial' => 0,
        ];

        foreach ($subAgencies as $sub) {
            $planName = $sub->subscription->plan->name ?? 'Starter';
            if (str_contains(strtolower($planName), 'enterprise')) {
                $planCounts['Enterprise']++;
            } elseif (str_contains(strtolower($planName), 'growth')) {
                $planCounts['Growth']++;
            } else {
                $planCounts['Starter']++;
            }

            if ($sub->subscription) {
                $status = strtolower($sub->subscription->status);
                if (isset($subscriptionStats[$status])) {
                    $subscriptionStats[$status]++;
                } else {
                    $subscriptionStats['active']++;
                }
            }
        }

        // Product usage breakdown across sub-agencies
        $allProducts = Product::where('is_active', true)->get();
        $productUsage = [];
        foreach ($allProducts as $prod) {
            $count = 0;
            foreach ($subAgencies as $sub) {
                if ($sub->products->contains('id', $prod->id)) {
                    $count += $sub->max_clients;
                }
            }
            $productUsage[] = [
                'name' => $prod->name,
                'slug' => $prod->slug,
                'clients' => $count,
                'percentage' => $totalClients > 0 ? min(100, round(($count / $totalClients) * 100)) : 0,
            ];
        }

        return view('master.dashboard', compact(
            'agency',
            'user',
            'totalSubAgencies',
            'totalClients',
            'mrr',
            'activeSubscriptions',
            'productsInUseCount',
            'totalProductsCount',
            'subAgenciesList',
            'activities',
            'planCounts',
            'subscriptionStats',
            'productUsage'
        ));
    }
}
