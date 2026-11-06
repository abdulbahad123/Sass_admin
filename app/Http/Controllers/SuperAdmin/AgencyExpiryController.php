<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Services\AgencyExpiryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgencyExpiryController extends Controller
{
    protected AgencyExpiryService $expiryService;

    public function __construct(AgencyExpiryService $expiryService)
    {
        $this->expiryService = $expiryService;
    }

    /**
     * Display the White-Label Expiry & Storage Management Dashboard.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user->canAccessMenu(['view-agencies', 'view-subscriptions']) && !$user->isSuperAdmin()) {
            abort(403, 'Access Denied: You do not have permission to view white-label expiry and storage analytics.');
        }

        // Fetch all white-label agencies (exclude master/super-admin agency)
        $query = Agency::where('type', '!=', 'master')
            ->with(['subscription.plan', 'products']);

        // Optional search filter
        $search = trim((string)$request->input('search', ''));
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('owner_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('custom_domain', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $allAgencies = $query->orderBy('id', 'desc')->get();

        // Calculate real-time expiry and storage metrics for each agency
        $enrichedAgencies = [];
        $totalWhitelabels = 0;
        $expiringSoonCount = 0;
        $expiredCount = 0;
        $alertsDueCount = 0;
        $totalStorageBytes = 0;

        foreach ($allAgencies as $agency) {
            $expiry = $this->expiryService->calculateAgencyExpiry($agency);
            $storage = $this->expiryService->calculateStorageConsumption($agency);

            $totalWhitelabels++;
            if ($expiry['is_expired']) {
                $expiredCount++;
            } elseif ($expiry['is_expiring_soon']) {
                $expiringSoonCount++;
            }

            if ($expiry['is_alert_due']) {
                $alertsDueCount++;
            }

            $totalStorageBytes += ($storage['total_size_mb'] * 1024 * 1024);

            $enrichedAgencies[] = (object) [
                'agency'  => $agency,
                'expiry'  => $expiry,
                'storage' => $storage,
            ];
        }

        // Apply Tab / Status Filter
        $filter = $request->input('filter', 'all');
        if ($filter === 'expiring_soon') {
            $enrichedAgencies = array_filter($enrichedAgencies, fn($item) => $item->expiry['is_expiring_soon']);
        } elseif ($filter === 'expired') {
            $enrichedAgencies = array_filter($enrichedAgencies, fn($item) => $item->expiry['is_expired']);
        } elseif ($filter === 'alert_due') {
            $enrichedAgencies = array_filter($enrichedAgencies, fn($item) => $item->expiry['is_alert_due']);
        } elseif ($filter === 'high_storage') {
            $enrichedAgencies = array_filter($enrichedAgencies, fn($item) => $item->storage['percent_used'] >= 75);
        }

        // Sort: alert due and expiring soon first
        usort($enrichedAgencies, function ($a, $b) {
            if ($a->expiry['is_alert_due'] !== $b->expiry['is_alert_due']) {
                return $a->expiry['is_alert_due'] ? -1 : 1;
            }
            return $a->expiry['days_left'] <=> $b->expiry['days_left'];
        });

        $totalStorageFormatted = $totalStorageBytes >= 1073741824 
            ? number_format($totalStorageBytes / 1073741824, 2) . ' GB'
            : number_format($totalStorageBytes / 1048576, 1) . ' MB';

        $metrics = [
            'total_whitelabels'       => $totalWhitelabels,
            'expiring_soon_count'     => $expiringSoonCount,
            'expired_count'           => $expiredCount,
            'alerts_due_count'        => $alertsDueCount,
            'total_storage_formatted' => $totalStorageFormatted,
        ];

        return view('admin.agencies.expiring', compact('enrichedAgencies', 'metrics', 'filter', 'search'));
    }

    /**
     * Dispatch Instant Multi-Channel Expiry Alert (WhatsApp + Email) for a specific Agency.
     */
    public function sendAlert(Request $request, Agency $agency): JsonResponse
    {
        $user = Auth::user();
        if (!$user->canAccessMenu(['view-agencies', 'manage-agencies', 'view-subscriptions']) && !$user->isSuperAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        try {
            $result = $this->expiryService->sendExpiryAlert($agency);

            return response()->json([
                'success'           => $result['success'],
                'whatsapp_sent'     => $result['whatsapp_sent'],
                'email_sent'        => $result['email_sent'],
                'phone'             => $result['phone'],
                'email'             => $result['email'],
                'sent_at'           => $result['sent_at'],
                'message'           => $result['success'] 
                    ? "Alert successfully dispatched to {$agency->name} via " . ($result['whatsapp_sent'] ? 'WhatsApp ' : '') . ($result['email_sent'] ? 'and Email' : '') . "!"
                    : "Could not deliver alert. Please verify the agency's phone/email and gateway settings.",
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
                'message' => 'Failed to send alert: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Trigger Batch Daily Expiry Alerts for All Eligible White-Label Agencies.
     */
    public function triggerDailyAlerts(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user->canAccessMenu(['view-agencies', 'manage-agencies']) && !$user->isSuperAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        try {
            $batchResult = $this->expiryService->triggerBatchAlerts();

            return response()->json([
                'success'        => true,
                'alerts_sent'    => $batchResult['alerts_sent'],
                'skipped'        => $batchResult['skipped'],
                'total_agencies' => $batchResult['total_agencies'],
                'message'        => "Daily recurring check complete: {$batchResult['alerts_sent']} alert(s) dispatched, {$batchResult['skipped']} skipped (not due or already sent today).",
                'details'        => $batchResult['details'],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
                'message' => 'Batch alert trigger failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update storage limit for an agency.
     */
    public function updateStorageLimit(Request $request, Agency $agency): JsonResponse
    {
        $user = Auth::user();
        if (!$user->canAccessMenu(['manage-agencies']) && !$user->isSuperAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $validated = $request->validate([
            'storage_limit_mb' => 'required|numeric|min:500|max:1048576',
        ]);

        $agency->update([
            'storage_limit_mb' => $validated['storage_limit_mb'],
        ]);

        return response()->json([
            'success' => true,
            'message' => "Storage quota updated to {$validated['storage_limit_mb']} MB for {$agency->name}.",
        ]);
    }
}

