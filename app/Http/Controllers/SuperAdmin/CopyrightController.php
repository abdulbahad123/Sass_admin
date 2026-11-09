<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CopyrightController extends Controller
{
    /**
     * Display the Whitelabel Copyright & Attribution Management Dashboard.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user && method_exists($user, 'canAccessMenu') && !$user->canAccessMenu(['view-settings', 'manage-settings', 'view-agencies']) && method_exists($user, 'isSuperAdmin') && !$user->isSuperAdmin()) {
            abort(403, 'Access Denied: You do not have permission to view copyright settings.');
        }

        Agency::ensureAttributionColumn();

        $query = Agency::where('type', '!=', 'master')
            ->with(['subscription.plan']);

        $search = trim((string)$request->input('search', ''));
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('custom_domain', 'like', "%{$search}%");
            });
        }

        $filter = $request->input('filter', 'all');
        if ($filter === 'hidden') {
            $query->where('hide_developer_attribution', 1);
        } elseif ($filter === 'visible') {
            $query->where(function ($q) {
                $q->whereNull('hide_developer_attribution')->orWhere('hide_developer_attribution', 0);
            });
        }

        $totalAgencies = Agency::where('type', '!=', 'master')->count();
        $hiddenCount = Agency::where('type', '!=', 'master')->where('hide_developer_attribution', 1)->count();
        $visibleCount = $totalAgencies - $hiddenCount;

        $agencies = $query->orderBy('name', 'asc')->paginate(20)->withQueryString();

        return view('admin.copyright.index', compact('agencies', 'totalAgencies', 'hiddenCount', 'visibleCount', 'search', 'filter'));
    }

    /**
     * Toggle developer attribution for a specific whitelabel agency.
     */
    public function toggleAttribution(Request $request, Agency $agency)
    {
        Agency::ensureAttributionColumn();

        if ($request->has('hide')) {
            $newVal = $request->boolean('hide') ? 1 : 0;
        } else {
            $newVal = $agency->hide_developer_attribution ? 0 : 1;
        }

        $agency->hide_developer_attribution = $newVal;
        $agency->save();

        $message = $newVal 
            ? "Developer attribution (Nooryak Technologies) has been HIDDEN for '{$agency->name}'."
            : "Developer attribution (Nooryak Technologies) is now VISIBLE for '{$agency->name}'.";

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'hidden'  => (bool)$newVal,
                'message' => $message,
                'agency_id' => $agency->id,
            ]);
        }

        return redirect()->back()->with('success', $message);
    }
}

