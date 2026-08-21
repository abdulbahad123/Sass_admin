<?php

namespace App\Http\Controllers\MasterAgency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MasterIntegrationController extends Controller
{
    public function index()
    {
        return view('master.integrations.index');
    }
}
