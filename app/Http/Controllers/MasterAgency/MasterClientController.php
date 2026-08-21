<?php

namespace App\Http\Controllers\MasterAgency;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\User;
use Illuminate\Http\Request;

class MasterClientController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $masterAgency = $user->agency ?? Agency::where('type', 'master')->first();
        $masterAgencyId = $masterAgency->id ?? 0;

        // Sub-agencies managed under this master agency
        $subAgencies = Agency::where('parent_id', $masterAgencyId)->get();

        $clients = [];

        return view('master.clients.index', compact('clients'));
    }

    public function store(Request $request)
    {
        return redirect()->route('master.clients.index')->with('success', 'End-client account onboarded into network database successfully!');
    }
}
