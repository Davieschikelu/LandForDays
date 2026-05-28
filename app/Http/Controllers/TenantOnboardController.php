<?php

namespace App\Http\Controllers;

use App\Models\Lease;
use App\Models\TenantInvite;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class TenantOnboardController extends Controller
{
    /**
     * Show the public tenant onboarding registration form.
     */
    public function showOnboard($token)
    {
        $invite = TenantInvite::where('token', $token)
            ->where('status', 'pending')
            ->first();

        // 1. Verify token exists and is active
        if (!$invite) {
            return redirect()->route('login')
                ->with('error', 'The invitation link is invalid or has already been accepted.');
        }

        // 2. Verify token expiration
        if ($invite->expires_at && $invite->expires_at->isPast()) {
            $invite->update(['status' => 'expired']);
            return redirect()->route('login')
                ->with('error', 'This invitation link has expired. Please request a new one from your landlord.');
        }

        return view('auth.onboard', compact('invite'));
    }

    /**
     * Process tenant registration and active lease generation.
     */
    public function processOnboard(Request $request, $token)
    {
        $invite = TenantInvite::where('token', $token)
            ->where('status', 'pending')
            ->first();

        if (!$invite || ($invite->expires_at && $invite->expires_at->isPast())) {
            return redirect()->route('login')
                ->with('error', 'The invitation token is invalid or expired.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'age' => ['required', 'integer', 'min:18', 'max:120'],
            'state_of_origin' => ['required', 'string', 'max:255'],
            'marital_status' => ['required', 'string', 'in:single,married,divorced,widowed'],
            'current_address' => ['required', 'string', 'max:1000'],
            'permanent_address' => ['required', 'string', 'max:1000'],
            'occupation' => ['required', 'string', 'max:255'],
            'workplace_details' => ['required', 'string', 'max:1000'],
            'phone_numbers' => ['required', 'string', 'max:255'],
            'spouse_names' => ['nullable', 'string', 'max:500'],
            'dependants_details' => ['nullable', 'string', 'max:1000'],
            
            // Next of Kin Group
            'next_of_kin_name' => ['required', 'string', 'max:255'],
            'next_of_kin_relationship' => ['required', 'string', 'max:255'],
            'next_of_kin_address' => ['required', 'string', 'max:1000'],
            'next_of_kin_workplace' => ['nullable', 'string', 'max:1000'],
            'next_of_kin_occupation' => ['required', 'string', 'max:255'],
            'next_of_kin_phone' => ['required', 'string', 'max:255'],
            
            // Tenancy & Verification
            'expected_duration' => ['required', 'string', 'max:255'],
            'rent_offer' => ['required', 'numeric', 'min:0'],
            'id_proof' => ['required', 'file', 'image', 'mimes:jpeg,png,jpg,pdf', 'max:10240'],
        ]);

        // Handle ID Proof file upload
        $idProofPath = null;
        if ($request->hasFile('id_proof')) {
            $idProofPath = $request->file('id_proof')->store('id_proofs');
        }

        // 1. Create the new Tenant User profile
        $user = User::create([
            'name' => $request->name,
            'email' => $invite->email,
            'password' => Hash::make($request->password),
            'role' => 'tenant',
            'age' => $request->age,
            'state_of_origin' => $request->state_of_origin,
            'marital_status' => $request->marital_status,
            'current_address' => $request->current_address,
            'permanent_address' => $request->permanent_address,
            'occupation' => $request->occupation,
            'workplace_details' => $request->workplace_details,
            'phone_numbers' => $request->phone_numbers,
            'spouse_names' => $request->spouse_names,
            'dependants_details' => $request->dependants_details,
            'next_of_kin_name' => $request->next_of_kin_name,
            'next_of_kin_relationship' => $request->next_of_kin_relationship,
            'next_of_kin_address' => $request->next_of_kin_address,
            'next_of_kin_workplace' => $request->next_of_kin_workplace,
            'next_of_kin_occupation' => $request->next_of_kin_occupation,
            'next_of_kin_phone' => $request->next_of_kin_phone,
            'expected_duration' => $request->expected_duration,
            'rent_offer' => $request->rent_offer,
            'id_proof_path' => $idProofPath,
        ]);

        // 2. Generate the active Lease agreement
        Lease::create([
            'unit_id' => $invite->unit_id,
            'tenant_id' => $user->id,
            'start_date' => $invite->start_date,
            'end_date' => $invite->end_date,
            'monthly_rent' => $invite->monthly_rent,
            'status' => 'active',
            'agreement_path' => $invite->agreement_path,
            'is_confirmed' => false,
        ]);

        // 3. Mark invitation as accepted
        $invite->update(['status' => 'accepted']);

        // 4. Set Unit status as occupied
        $unit = Unit::findOrFail($invite->unit_id);
        $unit->update(['status' => 'occupied']);

        // 5. Auto-authenticate new user
        Auth::login($user);

        return redirect()->route('tenant.dashboard')
            ->with('success', 'Your account has been created and your lease agreement is now active! Welcome home.');
    }
}
