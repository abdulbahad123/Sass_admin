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

class DashboardController extends Controller
{
    public function index()
    {
        $totalAgencies = Agency::count();
        $masterAgenciesCount = Agency::where('type', 'master')->count();
        $whiteLabelAgenciesCount = Agency::where('type', 'white_label')->count();
        $totalClientsEstimate = Agency::sum('max_clients');

        $activeSubscriptions = Subscription::where('status', 'active')->count();
        $monthlyRevenue = Subscription::where('status', 'active')
            ->where('billing_cycle', 'monthly')
            ->sum('amount');
        $yearlyRevenue = Subscription::where('status', 'active')
            ->where('billing_cycle', 'yearly')
            ->sum('amount');
        $totalRevenueEstimate = $monthlyRevenue + ($yearlyRevenue / 12);

        $totalProducts = Product::count();
        $activeProductsCount = Product::where('is_active', true)->count();

        $recentAgencies = Agency::with(['parentAgency', 'subscription.plan'])
            ->latest()
            ->take(5)
            ->get();

        $recentLogs = AuditLog::latest()->take(6)->get();
        $products = Product::withCount('agencies')->get();
        $plans = Plan::withCount('subscriptions')->get();

        return view('admin.dashboard', compact(
            'totalAgencies',
            'masterAgenciesCount',
            'whiteLabelAgenciesCount',
            'totalClientsEstimate',
            'activeSubscriptions',
            'monthlyRevenue',
            'yearlyRevenue',
            'totalRevenueEstimate',
            'totalProducts',
            'activeProductsCount',
            'recentAgencies',
            'recentLogs',
            'products',
            'plans'
        ));
    }
}
