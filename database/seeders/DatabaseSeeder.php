<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $landlord = User::factory()->create([
            'name' => 'Lara Landlord',
            'email' => 'landlord@example.com',
            'password' => bcrypt('password'),
            'role' => 'landlord',
        ]);

        User::factory()->create([
            'name' => 'Tom Tenant',
            'email' => 'tenant@example.com',
            'password' => bcrypt('password'),
            'role' => 'tenant',
        ]);

        // Seed property
        $property = \App\Models\Property::create([
            'landlord_id' => $landlord->id,
            'name' => 'Oakwood Heights Apartment',
            'type' => 'apartment',
            'address' => '422 Oakwood Blvd',
            'city' => 'Portland',
            'description' => 'Beautiful complex with amenities.',
            'is_archived' => false,
        ]);

        // Seed vacant unit
        \App\Models\Unit::create([
            'property_id' => $property->id,
            'unit_number' => '4B',
            'status' => 'vacant',
        ]);

        // Seed occupied unit
        \App\Models\Unit::create([
            'property_id' => $property->id,
            'unit_number' => '2A',
            'status' => 'vacant',
        ]);
    }
}
