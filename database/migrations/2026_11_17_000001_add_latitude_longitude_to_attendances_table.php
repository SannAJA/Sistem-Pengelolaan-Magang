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
        Schema::table('attendances', function (Blueprint $table): void {
            if (!Schema::hasColumn('attendances', 'latitude')) {
                $table->decimal('latitude', 11, 8)->nullable()->after('clock_out');
            }
            if (!Schema::hasColumn('attendances', 'longitude')) {
                $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table): void {
            if (Schema::hasColumn('attendances', 'longitude')) {
                $table->dropColumn('longitude');
            }
            if (Schema::hasColumn('attendances', 'latitude')) {
                $table->dropColumn('latitude');
            }
        });
    }
};
