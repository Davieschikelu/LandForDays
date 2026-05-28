<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('age')->nullable()->after('role');
            $table->string('state_of_origin')->nullable()->after('age');
            $table->string('marital_status')->nullable()->after('state_of_origin');
            $table->text('current_address')->nullable()->after('marital_status');
            $table->text('permanent_address')->nullable()->after('current_address');
            $table->string('occupation')->nullable()->after('permanent_address');
            $table->text('workplace_details')->nullable()->after('occupation');
            $table->string('phone_numbers')->nullable()->after('workplace_details');
            $table->text('spouse_names')->nullable()->after('phone_numbers');
            $table->text('dependants_details')->nullable()->after('spouse_names');
            
            // Next of Kin Group
            $table->string('next_of_kin_name')->nullable()->after('dependants_details');
            $table->string('next_of_kin_relationship')->nullable()->after('next_of_kin_name');
            $table->text('next_of_kin_address')->nullable()->after('next_of_kin_relationship');
            $table->text('next_of_kin_workplace')->nullable()->after('next_of_kin_address');
            $table->string('next_of_kin_occupation')->nullable()->after('next_of_kin_workplace');
            $table->string('next_of_kin_phone')->nullable()->after('next_of_kin_occupation');
            
            // Tenancy & Verification
            $table->string('expected_duration')->nullable()->after('next_of_kin_phone');
            $table->decimal('rent_offer', 15, 2)->nullable()->after('expected_duration');
            $table->string('id_proof_path')->nullable()->after('rent_offer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'age', 'state_of_origin', 'marital_status', 'current_address', 'permanent_address',
                'occupation', 'workplace_details', 'phone_numbers', 'spouse_names', 'dependants_details',
                'next_of_kin_name', 'next_of_kin_relationship', 'next_of_kin_address', 'next_of_kin_workplace',
                'next_of_kin_occupation', 'next_of_kin_phone', 'expected_duration', 'rent_offer', 'id_proof_path'
            ]);
        });
    }
};
