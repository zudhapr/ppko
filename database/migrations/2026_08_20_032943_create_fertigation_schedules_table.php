<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fertigation_schedules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('fertigation_profile_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('valve_id')
                ->constrained()
                ->cascadeOnDelete();

            // Rentang usia tanaman
            $table->unsignedInteger('hst_start');
            $table->unsignedInteger('hst_end');

            $table->time('start_time');

            $table->unsignedInteger('duration_seconds');

            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();

            $table->index([
                'fertigation_profile_id',
                'hst_start',
                'hst_end'
            ],'fs_profile_hst_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fertigation_schedules');
    }
};