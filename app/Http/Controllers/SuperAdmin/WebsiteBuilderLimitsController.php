<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\AuditLog;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WebsiteBuilderLimitsController extends Controller
{
    public function index(Request $request)
    {
        $agencies = Agency::orderBy('name')->get();
        $selectedAgencyId = $request->query('agency_id', $agencies->first()?->id);

        $defaultServicesLimit  = (int) Setting::get('wb_default_services_limit', 100);
        $defaultPortfolioLimit = (int) Setting::get('wb_default_portfolio_limit', 100);
        $defaultBlogLimit      = (int) Setting::get('wb_default_blog_limit', 300);

        // Calculate limits map for all agencies
        $agencyLimits = [];
        foreach ($agencies as $agency) {
            $extraServices  = (int) Setting::get("wb_agency_extra_{$agency->id}_services", 0);
            $extraPortfolio = (int) Setting::get("wb_agency_extra_{$agency->id}_portfolio", 0);
            $extraBlog      = (int) Setting::get("wb_agency_extra_{$agency->id}_blog", 0);

            $agencyLimits[$agency->id] = [
                'agency'          => $agency,
                'extra_services'  => $extraServices,
                'extra_portfolio' => $extraPortfolio,
                'extra_blog'      => $extraBlog,
                'total_services'  => $defaultServicesLimit + $extraServices,
                'total_portfolio' => $defaultPortfolioLimit + $extraPortfolio,
                'total_blog'      => $defaultBlogLimit + $extraBlog,
            ];
        }

        $selectedAgency = $agencies->firstWhere('id', $selectedAgencyId);

        // Generate SSO URL to launch Website Builder admin limits tool directly
        $currentHost = $request->getHost();
        $rootDomain = preg_replace('/^(app|www)\./i', '', $currentHost);
        $timestamp = time() + 300;
        $nonce = Str::random(16);
        $targetUser = 'Admin1@Launchshop';
        $secret = env('SSO_SECRET_KEY', 'LaunchshopSaaS_SSO_SecretKey_2026_SecureKey');
        $signature = hash_hmac('sha256', "{$targetUser}|{$timestamp}|{$nonce}", $secret);

        $wbAdminLaunchUrl = "https://websitebuilder.{$rootDomain}/website-builder/admin/limits?" . http_build_query([
            'user' => $targetUser,
            'expires' => $timestamp,
            'nonce' => $nonce,
            'signature' => $signature,
        ]);

        return view('admin.limits.index', compact(
            'agencies',
            'agencyLimits',
            'selectedAgency',
            'selectedAgencyId',
            'defaultServicesLimit',
            'defaultPortfolioLimit',
            'defaultBlogLimit',
            'wbAdminLaunchUrl'
        ));
    }

    public function updateDefaults(Request $request)
    {
        $validated = $request->validate([
            'default_services_limit'  => 'required|integer|min:1',
            'default_portfolio_limit' => 'required|integer|min:1',
            'default_blog_limit'      => 'required|integer|min:1',
        ]);

        Setting::set('wb_default_services_limit', (int) $validated['default_services_limit']);
        Setting::set('wb_default_portfolio_limit', (int) $validated['default_portfolio_limit']);
        Setting::set('wb_default_blog_limit', (int) $validated['default_blog_limit']);

        $this->syncDefaultsToLaunchshopDb(
            (int) $validated['default_services_limit'],
            (int) $validated['default_portfolio_limit'],
            (int) $validated['default_blog_limit']
        );

        AuditLog::create([
            'user_id' => auth()->id() ?? 1,
            'user_name' => auth()->user()?->name ?? 'Super Admin',
            'action' => 'Updated Website Builder Platform Global Limits',
            'ip_address' => $request->ip(),
            'details' => $validated,
        ]);

        return back()->with('success', 'Website Builder platform global default limits updated successfully!');
    }

    public function updateAgency(Request $request)
    {
        $validated = $request->validate([
            'agency_id'            => 'required|exists:agencies,id',
            'extra_services_limit' => 'required|integer|min:0',
            'extra_portfolio_limit'=> 'required|integer|min:0',
            'extra_blog_limit'     => 'required|integer|min:0',
        ]);

        $agencyId = $validated['agency_id'];
        $agency = Agency::findOrFail($agencyId);

        Setting::set("wb_agency_extra_{$agencyId}_services", (int) $validated['extra_services_limit']);
        Setting::set("wb_agency_extra_{$agencyId}_portfolio", (int) $validated['extra_portfolio_limit']);
        Setting::set("wb_agency_extra_{$agencyId}_blog", (int) $validated['extra_blog_limit']);

        $this->syncAgencyLimitsToLaunchshopDb($agency, [
            'extra_services_limit'  => (int) $validated['extra_services_limit'],
            'extra_portfolio_limit' => (int) $validated['extra_portfolio_limit'],
            'extra_blog_limit'      => (int) $validated['extra_blog_limit'],
        ]);

        AuditLog::create([
            'user_id' => auth()->id() ?? 1,
            'user_name' => auth()->user()?->name ?? 'Super Admin',
            'action' => "Updated Website Builder Limits for White-Label Agency: {$agency->name}",
            'ip_address' => $request->ip(),
            'details' => $validated,
        ]);

        return redirect()->route('admin.website-builder-limits.index', ['agency_id' => $agencyId])
            ->with('success', "Custom limits saved for agency '{$agency->name}'!");
    }

    private function syncDefaultsToLaunchshopDb(int $services, int $portfolio, int $blogs)
    {
        try {
            $launchshopDb = env('LAUNCHSHOP_DB', 'nooryak_launchshopp');
            DB::statement("UPDATE `{$launchshopDb}`.`wb_landing_settings` SET 
                `default_services_limit` = ?,
                `default_portfolio_limit` = ?,
                `default_blog_limit` = ?
                LIMIT 1", [$services, $portfolio, $blogs]);
        } catch (\Throwable $e) {
            // Continue if cross-database query is not permitted
        }
    }

    private function syncAgencyLimitsToLaunchshopDb(Agency $agency, array $limits)
    {
        try {
            $launchshopDb = env('LAUNCHSHOP_DB', 'nooryak_launchshopp');
            DB::statement("UPDATE `{$launchshopDb}`.`wb_customers` SET 
                `extra_services_limit` = ?,
                `extra_portfolio_limit` = ?,
                `extra_blog_limit` = ?
                WHERE `agency_id` = ? OR `email` = ? OR `username` = ?", [
                $limits['extra_services_limit'],
                $limits['extra_portfolio_limit'],
                $limits['extra_blog_limit'],
                $agency->id,
                $agency->owner_email ?? '',
                $agency->slug ?? '',
            ]);
        } catch (\Throwable $e) {
            // Continue if cross-database query is not permitted
        }
    }
}