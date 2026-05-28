<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenantInvite extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'email',
        'token',
        'monthly_rent',
        'start_date',
        'end_date',
        'status',
        'agreement_path',
        'expires_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the unit associated with the invitation.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}
