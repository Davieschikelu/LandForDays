<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LandlordDashboardController extends Controller
{
    public function index()
    {
        $landlordId = Auth::id();

        $propertiesCount = Property::where('landlord_id', $landlordId)
            ->where('is_archived', false)
            ->count();

        $properties = Property::where('landlord_id', $landlordId)
            ->where('is_archived', false)
            ->get();

        $propertyIds = $properties->pluck('id');

        $unitsCount = Unit::whereIn('property_id', $propertyIds)->count();
        $vacantUnitsCount = Unit::whereIn('property_id', $propertyIds)->where('status', 'vacant')->count();
        $occupiedUnitsCount = Unit::whereIn('property_id', $propertyIds)->where('status', 'occupied')->count();
        $maintenanceUnitsCount = Unit::whereIn('property_id', $propertyIds)->where('status', 'maintenance')->count();

        $recentProperties = Property::where('landlord_id', $landlordId)
            ->where('is_archived', false)
            ->latest()
            ->take(5)
            ->get();

        return view('landlord.dashboard', compact(
            'propertiesCount',
            'unitsCount',
            'vacantUnitsCount',
            'occupiedUnitsCount',
            'maintenanceUnitsCount',
            'recentProperties'
        ));
    }
}
