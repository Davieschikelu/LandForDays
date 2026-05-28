<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceRequest;
use App\Models\Property;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of the tenant's maintenance requests and filing form.
     */
    public function tenantIndex()
    {
        $user = Auth::user();
        $lease = $user->activeLease()->with('unit.property')->first();
        
        if (!$lease || !$lease->is_confirmed) {
            abort(403, 'Your tenancy has not been confirmed yet. Please upload and verify your tenancy agreement.');
        }

        $requests = MaintenanceRequest::where('tenant_id', $user->id)
            ->with('unit.property')
            ->latest()
            ->get();

        return view('tenant.maintenance.index', compact('lease', 'requests'));
    }

    /**
     * Store a newly created maintenance request from a tenant.
     */
    public function tenantStore(Request $request)
    {
        $user = Auth::user();
        $lease = $user->activeLease()->first();

        if (!$lease || !$lease->is_confirmed) {
            abort(403, 'Your tenancy has not been confirmed yet. Please upload and verify your tenancy agreement.');
        }

        $request->validate([
            'category' => 'required|string|in:plumbing,electrical,appliance,hvac,structural,other',
            'priority' => 'required|string|in:low,medium,high,emergency',
            'description' => 'required|string|min:10',
            'photo' => 'nullable|image|max:5120', // Max 5MB
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('maintenance', 'public');
        }

        MaintenanceRequest::create([
            'unit_id' => $lease->unit_id,
            'tenant_id' => $user->id,
            'category' => $request->category,
            'priority' => $request->priority,
            'description' => $request->description,
            'photo_path' => $photoPath,
            'status' => 'open',
        ]);

        return redirect()->route('tenant.maintenance.index')->with('success', 'Your maintenance request has been submitted successfully.');
    }

    /**
     * Display the landlord's maintenance tickets dashboard (Kanban board).
     */
    public function landlordIndex()
    {
        $user = Auth::user();
        
        // Get all units owned by the logged-in landlord
        $propertyIds = Property::where('landlord_id', $user->id)->pluck('id');
        $unitIds = Unit::whereIn('property_id', $propertyIds)->pluck('id');

        // Get all maintenance requests for these units
        $requests = MaintenanceRequest::whereIn('unit_id', $unitIds)
            ->with(['unit.property', 'tenant'])
            ->latest()
            ->get();

        // Group by status
        $open = $requests->where('status', 'open');
        $inProgress = $requests->where('status', 'in_progress');
        $resolved = $requests->where('status', 'resolved');

        return view('landlord.maintenance.index', compact('open', 'inProgress', 'resolved'));
    }

    /**
     * Update status and add notes to a maintenance ticket by landlord.
     */
    public function landlordUpdate(Request $request, $id)
    {
        $user = Auth::user();
        $ticket = MaintenanceRequest::findOrFail($id);

        // Security check: ensure ticket belongs to landlord
        $propertyIds = Property::where('landlord_id', $user->id)->pluck('id');
        $unitIds = Unit::whereIn('property_id', $propertyIds)->pluck('id');

        if (!$unitIds->contains($ticket->unit_id)) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|string|in:open,in_progress,resolved',
            'notes' => 'nullable|string',
        ]);

        $ticket->update([
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()->route('landlord.maintenance.index')->with('success', 'Maintenance ticket updated successfully.');
    }
}
