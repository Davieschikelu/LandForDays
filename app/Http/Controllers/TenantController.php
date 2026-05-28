<?php

namespace App\Http\Controllers;

use App\Models\Lease;
use App\Models\Property;
use App\Models\TenantInvite;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TenantController extends Controller
{
    /**
     * Display a registry of all active tenants and pending invitations.
     */
    public function index()
    {
        $landlordId = Auth::id();

        // 1. Get all active properties of the landlord
        $properties = Property::where('landlord_id', $landlordId)
            ->where('is_archived', false)
            ->with('units')
            ->get();

        $propertyIds = $properties->pluck('id');
        $unitIds = Unit::whereIn('property_id', $propertyIds)->pluck('id');

        // 2. Fetch all active leases linked to landlord's units
        $leases = Lease::whereIn('unit_id', $unitIds)
            ->where('status', 'active')
            ->with(['unit.property', 'tenant'])
            ->get();

        // 3. Fetch all active/pending invitations linked to landlord's units
        $invites = TenantInvite::whereIn('unit_id', $unitIds)
            ->where('status', 'pending')
            ->with('unit.property')
            ->get();

        // 4. Fetch vacant units that are ready for invitation
        $vacantUnits = Unit::whereIn('property_id', $propertyIds)
            ->where('status', 'vacant')
            ->get();

        return view('landlord.tenants.index', compact('properties', 'leases', 'invites', 'vacantUnits'));
    }

    /**
     * Generate a new tenant invite token.
     */
    public function storeInvite(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'email' => 'required|email',
            'monthly_rent' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'agreement_template' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        $unit = Unit::findOrFail($request->unit_id);

        // Verify property ownership
        if ($unit->property->landlord_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        // Handle File Upload
        $agreementPath = null;
        if ($request->hasFile('agreement_template')) {
            $agreementPath = $request->file('agreement_template')->store('agreements');
        }

        // Generate invitation token
        $token = Str::random(40);

        TenantInvite::create([
            'unit_id' => $request->unit_id,
            'email' => $request->email,
            'token' => $token,
            'monthly_rent' => $request->monthly_rent,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'pending',
            'agreement_path' => $agreementPath,
            'expires_at' => now()->addDays(7),
        ]);

        // Change unit status to pending invite if vacant
        if ($unit->status === 'vacant') {
            $unit->update(['status' => 'maintenance']); // Or we can keep it vacant until onboarded
        }

        $onboardUrl = route('tenant.onboard.show', $token);

        return redirect()->route('landlord.tenants.index')
            ->with('success', "Invitation created successfully! Invite Link: {$onboardUrl}");
    }

    /**
     * Revoke a pending invite.
     */
    public function destroyInvite($id)
    {
        $invite = TenantInvite::findOrFail($id);

        // Verify property ownership through unit
        if ($invite->unit->property->landlord_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        // Revert status of unit if it was set to maintenance
        if ($invite->unit->status === 'maintenance') {
            $invite->unit->update(['status' => 'vacant']);
        }

        $invite->update(['status' => 'expired']);

        return redirect()->route('landlord.tenants.index')
            ->with('success', 'Invitation revoked successfully.');
    }
}
