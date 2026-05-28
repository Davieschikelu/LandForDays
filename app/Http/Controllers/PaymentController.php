<?php

namespace App\Http\Controllers;

use App\Models\Lease;
use App\Models\Payment;
use App\Models\Property;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Centralized Ledger Dashboard for Landlords.
     */
    public function index()
    {
        $landlordId = Auth::id();

        // 1. Get landlord's active properties and units
        $properties = Property::where('landlord_id', $landlordId)
            ->where('is_archived', false)
            ->with(['units.activeLease.tenant'])
            ->get();

        $propertyIds = $properties->pluck('id');
        $unitIds = Unit::whereIn('property_id', $propertyIds)->pluck('id');
        $activeLeases = Lease::whereIn('unit_id', $unitIds)
            ->where('status', 'active')
            ->with(['unit.property', 'tenant'])
            ->get();

        $leaseIds = $activeLeases->pluck('id');

        // 2. Load all payments records linked to landlord's leases
        $payments = Payment::whereIn('lease_id', $leaseIds)
            ->with(['lease.unit.property', 'lease.tenant'])
            ->orderBy('payment_date', 'desc')
            ->get();

        // 3. Central Ledger Metric Calculations
        $totalCollected = $payments->where('status', 'completed')->sum('amount');
        $upcomingRent = $activeLeases->sum('monthly_rent');
        
        // Mocking some late rent metric based on unpaid states for demonstration
        $overdueBalance = $activeLeases->count() * 100.00; // standard overdue mock or base metric

        return view('landlord.payments.index', compact(
            'properties',
            'activeLeases',
            'payments',
            'totalCollected',
            'upcomingRent',
            'overdueBalance'
        ));
    }

    /**
     * Record a manual off-platform payment (Cash, Check, Bank Transfer).
     */
    public function storeManual(Request $request)
    {
        $request->validate([
            'lease_id' => 'required|exists:leases,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,check,bank_transfer',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ]);

        $lease = Lease::findOrFail($request->lease_id);

        // Verify landlord ownership through unit
        if ($lease->unit->property->landlord_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $referenceCode = 'REF-MAN-' . strtoupper(Str::random(10));

        Payment::create([
            'lease_id' => $request->lease_id,
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'payment_method' => $request->payment_method,
            'status' => 'completed',
            'reference_code' => $referenceCode,
            'notes' => $request->notes,
        ]);

        return redirect()->route('landlord.payments.index')
            ->with('success', "Manual payment recorded successfully! Reference: {$referenceCode}");
    }

    /**
     * Process a simulated rent payment submission for tenants (all default to pending for landlord verification).
     */
    public function checkout(Request $request)
    {
        $rules = [
            'lease_id' => 'required|exists:leases,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:card,bank_transfer,cash,check',
        ];

        if ($request->payment_method === 'card') {
            $rules['card_name'] = 'required|string|max:255';
            $rules['card_number'] = 'required|string|min:12|max:19';
            $rules['card_expiry'] = 'required|string|max:7'; // MM/YYYY
            $rules['card_cvv'] = 'required|string|min:3|max:4';
        }

        $request->validate($rules);

        $lease = Lease::findOrFail($request->lease_id);

        // Verify tenant owns the lease
        if ($lease->tenant_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        // Verify tenancy is active and confirmed
        if (!$lease->is_confirmed) {
            abort(403, 'Your tenancy has not been confirmed yet. Please upload and verify your tenancy agreement.');
        }

        $prefixes = [
            'card' => 'CARD',
            'bank_transfer' => 'BANK',
            'cash' => 'CASH',
            'check' => 'CHEQ',
        ];
        $prefix = $prefixes[$request->payment_method] ?? 'PAY';
        $referenceCode = 'REF-' . $prefix . '-' . strtoupper(Str::random(10));

        $notesMap = [
            'card' => 'Simulated online checkout via credit card.',
            'bank_transfer' => 'Pending bank transfer settlement.',
            'cash' => 'Pending cash collection verification.',
            'check' => 'Pending check/cheque clearance.',
        ];
        $notes = $notesMap[$request->payment_method] ?? 'Pending payment verification.';

        Payment::create([
            'lease_id' => $request->lease_id,
            'amount' => $request->amount,
            'payment_date' => now(),
            'payment_method' => $request->payment_method,
            'status' => 'pending', // Awaiting verification
            'reference_code' => $referenceCode,
            'notes' => $notes,
        ]);

        $labels = [
            'card' => 'Online Card Payment',
            'bank_transfer' => 'Bank Transfer Payment',
            'cash' => 'Cash Payment',
            'check' => 'Cheque Payment',
        ];
        $label = $labels[$request->payment_method] ?? 'Payment';

        return redirect()->route('tenant.dashboard')
            ->with('success', "{$label} of ₦" . number_format($request->amount, 2) . " submitted! Awaiting Landlord verification.");
    }

    /**
     * Update Landlord's Bank details.
     */
    public function updateBankDetails(Request $request)
    {
        $request->validate([
            'bank_details' => 'nullable|string|max:1000',
        ]);

        Auth::user()->update([
            'bank_details' => $request->bank_details,
        ]);

        return redirect()->route('landlord.payments.index')
            ->with('success', 'Your bank account details have been updated successfully!');
    }

    /**
     * Landlord verifies a pending payment.
     */
    public function verifyPayment(Payment $payment)
    {
        // Security check: Only the linked landlord can verify the payment
        if ($payment->lease->unit->property->landlord_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $payment->update([
            'status' => 'completed',
        ]);

        return redirect()->route('landlord.payments.index')
            ->with('success', "Payment {$payment->reference_code} verified successfully! The ledger and metrics have been updated.");
    }

    /**
     * Render a gorgeous, printable digital invoice receipt.
     */
    public function receipt($referenceCode)
    {
        $payment = Payment::where('reference_code', $referenceCode)
            ->with(['lease.unit.property', 'lease.tenant'])
            ->firstOrFail();

        // Security check: Only the linked tenant or the property landlord can view the receipt
        $userId = Auth::id();
        $isTenant = $payment->lease->tenant_id === $userId;
        $isLandlord = $payment->lease->unit->property->landlord_id === $userId;

        if (!$isTenant && !$isLandlord) {
            abort(403, 'Unauthorized access.');
        }

        return view('tenant.payments.receipt', compact('payment'));
    }
}
