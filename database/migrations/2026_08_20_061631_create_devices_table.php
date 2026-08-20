<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            // Misalnya ESP-FERTIGASI-01
            $table->string('device_code')->unique();

            $table->string('mode')->default('AUTO');

            $table->unsignedInteger('current_hst')->nullable();

            $table->timestamp('last_seen')->nullable();

            $table->timestamp('schedule_updated_at')->nullable();

            $table->string('ip_address')->nullable();

            $table->string('firmware_version')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};