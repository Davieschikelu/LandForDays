<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'property_id',
        'unit_number',
        'bedrooms',
        'status',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function activeLease()
    {
        return $this->hasOne(Lease::class)->where('status', 'active');
    }

    public function leases()
    {
        return $this->hasMany(Lease::class);
    }

    public function invites()
    {
        return $this->hasMany(TenantInvite::class);
    }

    public function maintenanceRequests()
    {
        return $this->hasMany(MaintenanceRequest::class);
    }
}
