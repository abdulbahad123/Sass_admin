<?php

namespace App\Http\Controllers\MasterAgency;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MasterReportController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $masterAgency = $user->agency ?? Agency::where('type', 'master')->first();
        $masterAgencyId = $masterAgency->id ?? 0;

        $subAgencies = Agency::where('parent_id', $masterAgencyId)->with('subscription')->get();
        $totalSubAgencies = $subAgencies->count();

        $activeCount = 0;
        $totalMrr = 0;
        foreach ($subAgencies as $sub) {
            if ($sub->subscription && $sub->subscription->status === 'active') {
                $activeCount++;
                $totalMrr += $sub->subscription->amount;
            }
        }

        $avgMrr = $totalSubAgencies > 0 ? round($totalMrr / $totalSubAgencies, 2) : 0;
        $adoptionRate = $totalSubAgencies > 0 ? round(($activeCount / $totalSubAgencies) * 100, 1) : 0;
        $retentionRate = $totalSubAgencies > 0 ? 100.0 : 0;

        return view('master.reports.index', compact('totalSubAgencies', 'activeCount', 'avgMrr', 'adoptionRate', 'retentionRate'));
    }
}
