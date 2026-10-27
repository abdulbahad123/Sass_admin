<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\DemoRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DemoRequestController extends Controller
{
    /**
     * Public submission handler for the live landing page demo modal
     */
    public function submit(Request $request)
    {
        DemoRequest::ensureTableExists();

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'whatsapp' => 'required|string|max:30',
            'email' => 'required|email|max:150',
            'profession' => 'nullable|string|max:100',
            'captcha_answer' => 'required|numeric',
            'captcha_expected' => 'required|numeric'
        ]);

        if ((int)$validated['captcha_answer'] !== (int)$validated['captcha_expected']) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect verification answer. Please try again.'
            ], 422);
        }

        $lead = DemoRequest::create([
            'name' => trim($validated['name']),
            'whatsapp' => trim($validated['whatsapp']),
            'email' => trim(strtolower($validated['email'])),
            'profession' => $validated['profession'] ?? 'General',
            'ip_address' => $request->ip(),
            'status' => 'new'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you! Your demo request has been received. Our team will contact you on WhatsApp shortly.',
            'lead_id' => $lead->id
        ]);
    }

    /**
     * Admin lead dashboard with filters (today, yesterday, this_week, last_7_days, this_month, custom) and pagination
     */
    public function index(Request $request)
    {
        DemoRequest::ensureTableExists();

        $query = DemoRequest::query()->latest();

        // 1. Date Filter
        $dateFilter = $request->input('date_filter', 'all');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        if ($dateFilter === 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($dateFilter === 'yesterday') {
            $query->whereDate('created_at', Carbon::yesterday());
        } elseif ($dateFilter === 'this_week') {
            $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($dateFilter === 'last_7_days') {
            $query->where('created_at', '>=', Carbon::now()->subDays(7));
        } elseif ($dateFilter === 'this_month') {
            $query->whereMonth('created_at', Carbon::now()->month)
                  ->whereYear('created_at', Carbon::now()->year);
        } elseif ($dateFilter === 'custom' && $fromDate && $toDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($fromDate)->startOfDay(),
                Carbon::parse($toDate)->endOfDay()
            ]);
        }

        // 2. Status Filter
        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        // 3. Search Filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('whatsapp', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('profession', 'like', "%{$search}%");
            });
        }

        $requests = $query->paginate(15)->withQueryString();

        // Summary Counts
        $totalLeads = DemoRequest::count();
        $todayLeads = DemoRequest::whereDate('created_at', Carbon::today())->count();
        $newLeads = DemoRequest::where('status', 'new')->count();
        $contactedLeads = DemoRequest::whereIn('status', ['contacted', 'scheduled', 'completed'])->count();

        return view('admin.demo_requests.index', compact(
            'requests',
            'totalLeads',
            'todayLeads',
            'newLeads',
            'contactedLeads',
            'dateFilter',
            'fromDate',
            'toDate'
        ));
    }

    /**
     * Update lead status
     */
    public function updateStatus(Request $request, DemoRequest $demoRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,contacted,scheduled,completed,cancelled'
        ]);

        $demoRequest->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', 'Demo lead status updated successfully.');
    }

    /**
     * Delete lead
     */
    public function destroy(DemoRequest $demoRequest)
    {
        $demoRequest->delete();

        return redirect()->back()->with('success', 'Demo lead deleted successfully.');
    }
}
