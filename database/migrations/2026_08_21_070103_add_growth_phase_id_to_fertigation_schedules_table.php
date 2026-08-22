<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fertigation_schedules', function (Blueprint $table) {

            $table->foreignId('growth_phase_id')
                ->nullable()
                ->after('valve_id')
                ->constrained('growth_phases')
                ->nullOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('fertigation_schedules', function (Blueprint $table) {

            $table->dropForeign([
                'growth_phase_id'
            ]);

            $table->dropColumn(
                'growth_phase_id'
            );

        });
    }
};