<?php

namespace Database\Seeders;

use App\Models\Device;
use Illuminate\Database\Seeder;

class DeviceSeeder extends Seeder
{
    public function run(): void
    {
        Device::updateOrCreate(
            [
                'device_code' => 'ESP-FERTIGASI-01'
            ],
            [
                'name' => 'ESP Fertigasi Greenhouse',
                'mode' => 'AUTO',
                'is_active' => true,
            ]
        );
    }
}