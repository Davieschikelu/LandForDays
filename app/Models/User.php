<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'bank_details',
        'age',
        'state_of_origin',
        'marital_status',
        'current_address',
        'permanent_address',
        'occupation',
        'workplace_details',
        'phone_numbers',
        'spouse_names',
        'dependants_details',
        'next_of_kin_name',
        'next_of_kin_relationship',
        'next_of_kin_address',
        'next_of_kin_workplace',
        'next_of_kin_occupation',
        'next_of_kin_phone',
        'expected_duration',
        'rent_offer',
        'id_proof_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get all leases of the user (tenant).
     */
    public function leases()
    {
        return $this->hasMany(Lease::class, 'tenant_id');
    }

    /**
     * Get the active lease of the user (tenant).
     */
    public function activeLease()
    {
        return $this->hasOne(Lease::class, 'tenant_id')->where('status', 'active');
    }
}
