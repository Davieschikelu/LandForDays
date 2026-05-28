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
        Schema::table('tenant_invites', function (Blueprint $table) {
            $table->string('agreement_path')->nullable()->after('status');
        });

        Schema::table('leases', function (Blueprint $table) {
            $table->string('agreement_path')->nullable()->after('status');
            $table->string('signed_agreement_path')->nullable()->after('agreement_path');
            $table->boolean('is_confirmed')->default(false)->after('signed_agreement_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenant_invites', function (Blueprint $table) {
            $table->dropColumn('agreement_path');
        });

        Schema::table('leases', function (Blueprint $table) {
            $table->dropColumn(['agreement_path', 'signed_agreement_path', 'is_confirmed']);
        });
    }
};
