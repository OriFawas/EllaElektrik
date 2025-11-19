<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Profile fields
            $table->string('nik', 32)->nullable()->after('password');
            $table->string('phone', 32)->nullable()->after('nik');
            $table->string('province', 100)->nullable()->after('phone');
            $table->string('city', 100)->nullable()->after('province');
            $table->text('address')->nullable()->after('city');
            $table->string('ktp_path')->nullable()->after('address');

            // Verification fields
            $table->string('verification_status', 20)->default('unverified')->index()->after('ktp_path');
            $table->text('verification_note')->nullable()->after('verification_status');
            $table->timestamp('verified_at')->nullable()->after('verification_note');
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete()->after('verified_at');

            // Helpful indexes
            $table->index('nik');
            $table->index('phone');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop FK before column
            if (Schema::hasColumn('users', 'verified_by')) {
                $table->dropConstrainedForeignId('verified_by');
            }

            // Drop columns (indexes on columns are dropped automatically by most drivers)
            $drops = [
                'nik', 'phone', 'province', 'city', 'address', 'ktp_path',
                'verification_status', 'verification_note', 'verified_at',
            ];

            foreach ($drops as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
