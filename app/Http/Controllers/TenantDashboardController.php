<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $lease = $user->activeLease()->with('unit.property.landlord')->first();
        
        $payments = collect();
        if ($lease) {
            $payments = $lease->payments()->orderBy('payment_date', 'desc')->get();
        }

        return view('tenant.dashboard', compact('lease', 'payments'));
    }
}
