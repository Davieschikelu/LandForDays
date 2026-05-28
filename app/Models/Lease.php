<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lease extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'tenant_id',
        'start_date',
        'end_date',
        'monthly_rent',
        'status',
        'agreement_path',
        'signed_agreement_path',
        'is_confirmed',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_confirmed' => 'boolean',
    ];

    /**
     * Get the unit associated with the lease.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Get the tenant associated with the lease.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    /**
     * Get all payments recorded under this lease.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
