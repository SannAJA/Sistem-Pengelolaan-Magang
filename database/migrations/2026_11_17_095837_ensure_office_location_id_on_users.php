<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration pengaman: memastikan kolom office_location_id ada di tabel users.
 * Dibuat sebagai fallback jika migration 2026_11_17_000002 pernah gagal atau
 * tercatat selesai tanpa benar-benar membuat kolom di Railway.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'office_location_id')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->foreignId('office_location_id')
                    ->nullable()
                    ->after('role')
                    ->constrained('office_locations')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'office_location_id')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->dropForeign(['office_location_id']);
                $table->dropColumn('office_location_id');
            });
        }
    }
};
