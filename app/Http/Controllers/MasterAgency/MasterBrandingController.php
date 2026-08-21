<?php

namespace App\Http\Controllers\MasterAgency;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MasterBrandingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $agency = $user->agency ?? Agency::where('type', 'master')->first();

        return view('master.branding.index', compact('agency'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'custom_domain' => 'nullable|string|max:255',
            'primary_color' => 'required|string|max:7',
        ]);

        $user = Auth::user();
        $agency = $user->agency ?? Agency::where('type', 'master')->first();

        if ($agency) {
            $agency->update([
                'name' => $validated['name'],
                'slug' => \Illuminate\Support\Str::slug($validated['name']),
                'custom_domain' => $validated['custom_domain'],
                'primary_color' => $validated['primary_color'],
            ]);
        }

        return redirect()->route('master.branding.index')->with('success', 'Custom white-label branding settings updated successfully!');
    }
}
