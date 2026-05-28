<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'lease_id',
        'amount',
        'payment_date',
        'payment_method',
        'status',
        'reference_code',
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
    ];

    /**
     * Get the lease agreement associated with this payment.
     */
    public function lease()
    {
        return $this->belongsTo(Lease::class);
    }
}
