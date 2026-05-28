<?php

namespace App\Http\Controllers;

use App\Models\Lease;
use App\Models\TenantInvite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AgreementController extends Controller
{
    /**
     * Safely download tenancy agreement templates or signed documents.
     */
    public function download(Request $request, $type, $id)
    {
        $user = Auth::user();

        if ($type === 'invite') {
            $invite = TenantInvite::findOrFail($id);
            // Allow only the issuing landlord or invited tenant
            if ($user->role === 'landlord' && $invite->unit->property->landlord_id !== $user->id) {
                abort(403, 'Unauthorized access.');
            }
            if ($user->role === 'tenant' && strtolower($user->email) !== strtolower($invite->email)) {
                abort(403, 'Unauthorized access.');
            }
            $path = $invite->agreement_path;
        } else {
            $lease = Lease::findOrFail($id);
            // Allow only assigned landlord or assigned tenant
            if ($user->role === 'landlord' && $lease->unit->property->landlord_id !== $user->id) {
                abort(403, 'Unauthorized access.');
            }
            if ($user->role === 'tenant' && $lease->tenant_id !== $user->id) {
                abort(403, 'Unauthorized access.');
            }

            $path = $type === 'signed' ? $lease->signed_agreement_path : $lease->agreement_path;
        }

        if (!$path || !Storage::exists($path)) {
            abort(404, 'Agreement file not found.');
        }

        return Storage::download($path);
    }

    /**
     * Tenant uploads their signed copy of the tenancy agreement.
     */
    public function tenantUpload(Request $request, Lease $lease)
    {
        $user = Auth::user();

        if ($user->role !== 'tenant' || $lease->tenant_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'signed_agreement' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($request->hasFile('signed_agreement')) {
            // Delete old signed agreement file if it exists
            if ($lease->signed_agreement_path && Storage::exists($lease->signed_agreement_path)) {
                Storage::delete($lease->signed_agreement_path);
            }

            $path = $request->file('signed_agreement')->store('signed_agreements');
            $lease->update([
                'signed_agreement_path' => $path,
            ]);

            return redirect()->back()->with('success', 'Your signed tenancy agreement has been uploaded successfully! Awaiting landlord review.');
        }

        return redirect()->back()->with('error', 'Please select a valid file to upload.');
    }

    /**
     * Landlord confirms tenancy or rejects the uploaded agreement.
     */
    public function landlordConfirm(Request $request, Lease $lease)
    {
        $user = Auth::user();

        if ($user->role !== 'landlord' || $lease->unit->property->landlord_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'action' => 'required|in:confirm,reject',
        ]);

        if ($request->action === 'confirm') {
            $lease->update([
                'is_confirmed' => true,
            ]);

            return redirect()->back()->with('success', 'Tenancy confirmed! The tenant dashboard has been fully unlocked.');
        } else {
            // Delete rejected signed agreement file
            if ($lease->signed_agreement_path && Storage::exists($lease->signed_agreement_path)) {
                Storage::delete($lease->signed_agreement_path);
            }

            $lease->update([
                'signed_agreement_path' => null,
            ]);

            return redirect()->back()->with('success', 'Signed tenancy agreement rejected. The tenant has been requested to upload a new copy.');
        }
    }
}
