<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandlordDashboardController;
use App\Http\Controllers\TenantDashboardController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\PaymentController;

Route::get('/', function () {
    return view('welcome');
});

// Main dashboard routing entry point
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Landlord specific routes
Route::middleware(['auth', 'verified', 'role:landlord'])->prefix('landlord')->group(function () {
    Route::get('/dashboard', [LandlordDashboardController::class, 'index'])->name('landlord.dashboard');
    
    // Properties
    Route::get('/properties', [PropertyController::class, 'index'])->name('landlord.properties.index');
    Route::post('/properties', [PropertyController::class, 'store'])->name('landlord.properties.store');
    Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('landlord.properties.show');
    Route::put('/properties/{property}', [PropertyController::class, 'update'])->name('landlord.properties.update');
    Route::delete('/properties/{property}', [PropertyController::class, 'destroy'])->name('landlord.properties.destroy');
    
    // Units
    Route::post('/units', [UnitController::class, 'store'])->name('landlord.units.store');
    Route::put('/units/{unit}', [UnitController::class, 'update'])->name('landlord.units.update');
    Route::delete('/units/{unit}', [UnitController::class, 'destroy'])->name('landlord.units.destroy');

    // Tenants & Invitations
    Route::get('/tenants', [\App\Http\Controllers\TenantController::class, 'index'])->name('landlord.tenants.index');
    Route::post('/tenants/invite', [\App\Http\Controllers\TenantController::class, 'storeInvite'])->name('landlord.tenants.invite');
    Route::delete('/tenants/invite/{id}', [\App\Http\Controllers\TenantController::class, 'destroyInvite'])->name('landlord.tenants.invite.destroy');

    // Centralized Ledger Payments
    Route::get('/payments', [PaymentController::class, 'index'])->name('landlord.payments.index');
    Route::post('/payments/manual', [PaymentController::class, 'storeManual'])->name('landlord.payments.manual');
    Route::post('/payments/bank-details', [PaymentController::class, 'updateBankDetails'])->name('landlord.payments.bank-details');
    Route::post('/payments/{payment}/verify', [PaymentController::class, 'verifyPayment'])->name('landlord.payments.verify');

    // Maintenance Portal
    Route::get('/maintenance', [\App\Http\Controllers\MaintenanceController::class, 'landlordIndex'])->name('landlord.maintenance.index');
    Route::put('/maintenance/{id}', [\App\Http\Controllers\MaintenanceController::class, 'landlordUpdate'])->name('landlord.maintenance.update');

    // Tenancy Agreement Confirmation
    Route::post('/leases/{lease}/confirm-tenancy', [\App\Http\Controllers\AgreementController::class, 'landlordConfirm'])->name('landlord.leases.confirm-tenancy');
});

// Tenant specific routes
Route::middleware(['auth', 'verified', 'role:tenant'])->prefix('tenant')->group(function () {
    Route::get('/dashboard', [TenantDashboardController::class, 'index'])->name('tenant.dashboard');
    
    // Simulated Checkout
    Route::post('/payments/checkout', [PaymentController::class, 'checkout'])->name('tenant.payments.checkout');

    // Maintenance Portal
    Route::get('/maintenance', [\App\Http\Controllers\MaintenanceController::class, 'tenantIndex'])->name('tenant.maintenance.index');
    Route::post('/maintenance', [\App\Http\Controllers\MaintenanceController::class, 'tenantStore'])->name('tenant.maintenance.store');

    // Tenancy Agreement Upload
    Route::post('/leases/{lease}/upload-agreement', [\App\Http\Controllers\AgreementController::class, 'tenantUpload'])->name('tenant.leases.upload-agreement');
});

// Printable Invoices Receipts & Agreement Downloads
Route::middleware(['auth'])->group(function () {
    Route::get('/payments/receipt/{referenceCode}', [PaymentController::class, 'receipt'])->name('tenant.payments.receipt');
    Route::get('/agreements/download/{type}/{id}', [\App\Http\Controllers\AgreementController::class, 'download'])->name('agreements.download');
});

// Public Tenant Onboarding Registry Route Paths
Route::get('/onboard/{token}', [\App\Http\Controllers\TenantOnboardController::class, 'showOnboard'])->name('tenant.onboard.show');
Route::post('/onboard/{token}', [\App\Http\Controllers\TenantOnboardController::class, 'processOnboard'])->name('tenant.onboard.process');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout.get');

require __DIR__.'/auth.php';
