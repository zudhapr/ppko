<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('device_commands', function (Blueprint $table) {

            $table->id();

            $table->foreignId('device_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('valve_id')
                ->constrained()
                ->cascadeOnDelete();

            /*
             * TEST_OPEN
             * CLOSE
             */
            $table->string('command');

            $table->unsignedInteger('duration_seconds')
                ->nullable();

            /*
             * pending
             * running
             * completed
             * failed
             * expired
             */
            $table->string('status')->default('pending');

            $table->timestamp('expires_at')->nullable();

            $table->timestamp('started_at')->nullable();

            $table->timestamp('completed_at')->nullable();

            $table->text('message')->nullable();

            $table->timestamps();

            $table->index([
                'device_id',
                'status',
                'expires_at'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('device_commands');
    }
};