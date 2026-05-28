<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $fillable = [
        'landlord_id',
        'name',
        'type',
        'address',
        'city',
        'description',
        'is_archived',
    ];

    public function landlord()
    {
        return $this->belongsTo(User::class, 'landlord_id');
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }
}
