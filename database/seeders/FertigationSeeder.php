<?php

namespace Database\Seeders;

use App\Models\Valve;
use App\Models\Planting;
use App\Models\FertigationProfile;
use App\Models\FertigationSchedule;
use Illuminate\Database\Seeder;

class FertigationSeeder extends Seeder
{
    public function run(): void
    {
        $valve1 = Valve::create([
            'name' => 'Valve 1',
            'gpio' => '25',
            'description' => 'Solenoid irigasi zona 1',
            'is_active' => true,
        ]);

        $valve2 = Valve::create([
            'name' => 'Valve 2',
            'gpio' => '26',
            'description' => 'Solenoid irigasi zona 2',
            'is_active' => true,
        ]);

        $profile = FertigationProfile::create([
            'name' => 'Melon Standar',
            'description' => 'Profil fertigasi tanaman melon',
            'is_active' => true,
        ]);

        FertigationSchedule::create([
            'fertigation_profile_id' => $profile->id,
            'valve_id' => $valve1->id,
            'hst' => 0,
            'start_time' => '06:00:00',
            'duration_seconds' => 120,
            'is_active' => true,
        ]);

        FertigationSchedule::create([
            'fertigation_profile_id' => $profile->id,
            'valve_id' => $valve1->id,
            'hst' => 0,
            'start_time' => '16:00:00',
            'duration_seconds' => 120,
            'is_active' => true,
        ]);

        FertigationSchedule::create([
            'fertigation_profile_id' => $profile->id,
            'valve_id' => $valve1->id,
            'hst' => 7,
            'start_time' => '06:00:00',
            'duration_seconds' => 180,
            'is_active' => true,
        ]);

        FertigationSchedule::create([
            'fertigation_profile_id' => $profile->id,
            'valve_id' => $valve1->id,
            'hst' => 14,
            'start_time' => '06:00:00',
            'duration_seconds' => 300,
            'is_active' => true,
        ]);

        Planting::create([
            'name' => 'Melon Greenhouse A',
            'planting_date' => now()->toDateString(),
            'fertigation_profile_id' => $profile->id,
            'is_active' => true,
        ]);
    }
}