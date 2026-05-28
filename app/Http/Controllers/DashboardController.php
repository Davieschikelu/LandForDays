<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'landlord') {
            return redirect()->route('landlord.dashboard');
        }

        return redirect()->route('tenant.dashboard');
    }
}
