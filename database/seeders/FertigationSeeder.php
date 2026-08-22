<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Valve;
use App\Models\Planting;
use App\Models\GrowthPhase;
use App\Models\FertigationProfile;
use App\Models\FertigationSchedule;

class FertigationSeeder extends Seeder
{
    public function run(): void
    {
        // =====================================================
        // MASTER VALVE
        // =====================================================

        $valve1 = Valve::updateOrCreate(
            [
                'name' => 'Valve 1'
            ],
            [
                'gpio' => '25',
                'description' => 'Solenoid fertigasi zona 1',
                'is_active' => true,
            ]
        );


        $valve2 = Valve::updateOrCreate(
            [
                'name' => 'Valve 2'
            ],
            [
                'gpio' => '26',
                'description' => 'Solenoid fertigasi zona 2',
                'is_active' => true,
            ]
        );


        // =====================================================
        // PROFIL FERTIGASI
        // =====================================================

        $profile = FertigationProfile::updateOrCreate(
            [
                'name' => 'Melon Standar'
            ],
            [
                'description' =>
                    'Profil fertigasi standar tanaman melon berdasarkan fase pertumbuhan dan HST.',

                'is_active' => true,
            ]
        );


        // =====================================================
        // AMBIL MASTER FASE
        // Fase sudah dibuat oleh GrowthPhaseSeeder
        // =====================================================

        $faseAwal = GrowthPhase::where(
            'name',
            'Masa Awal'
        )->firstOrFail();


        $faseVegetatif = GrowthPhase::where(
            'name',
            'Vegetatif'
        )->firstOrFail();


        $fasePembungaan = GrowthPhase::where(
            'name',
            'Pembungaan'
        )->firstOrFail();


        $fasePenyerbukan = GrowthPhase::where(
            'name',
            'Penyerbukan'
        )->firstOrFail();


        $fasePembentukanBuah = GrowthPhase::where(
            'name',
            'Pembentukan Buah'
        )->firstOrFail();


        $fasePembesaranBuah = GrowthPhase::where(
            'name',
            'Pembesaran Buah'
        )->firstOrFail();


        $fasePematangan = GrowthPhase::where(
            'name',
            'Pematangan'
        )->firstOrFail();


        // =====================================================
        // HST 0 - 7
        // MASA AWAL
        // =====================================================

        $this->schedule(
            $profile->id,
            $valve1->id,
            $faseAwal->id,
            0,
            7,
            '06:00:00',
            120
        );

        $this->schedule(
            $profile->id,
            $valve1->id,
            $faseAwal->id,
            0,
            7,
            '12:00:00',
            120
        );

        $this->schedule(
            $profile->id,
            $valve1->id,
            $faseAwal->id,
            0,
            7,
            '16:00:00',
            120
        );


        // =====================================================
        // HST 8 - 20
        // VEGETATIF
        // =====================================================

        $this->schedule(
            $profile->id,
            $valve1->id,
            $faseVegetatif->id,
            8,
            20,
            '06:00:00',
            180
        );

        $this->schedule(
            $profile->id,
            $valve1->id,
            $faseVegetatif->id,
            8,
            20,
            '12:00:00',
            180
        );

        $this->schedule(
            $profile->id,
            $valve1->id,
            $faseVegetatif->id,
            8,
            20,
            '16:00:00',
            180
        );


        // =====================================================
        // HST 21 - 24
        // PEMBUNGAAN
        // =====================================================

        $this->schedule(
            $profile->id,
            $valve1->id,
            $fasePembungaan->id,
            21,
            24,
            '06:00:00',
            240
        );

        $this->schedule(
            $profile->id,
            $valve1->id,
            $fasePembungaan->id,
            21,
            24,
            '12:00:00',
            180
        );

        $this->schedule(
            $profile->id,
            $valve1->id,
            $fasePembungaan->id,
            21,
            24,
            '16:00:00',
            240
        );


        // =====================================================
        // HST 25 - 28
        // PENYERBUKAN
        // =====================================================

        $this->schedule(
            $profile->id,
            $valve1->id,
            $fasePenyerbukan->id,
            25,
            28,
            '06:00:00',
            240
        );

        $this->schedule(
            $profile->id,
            $valve1->id,
            $fasePenyerbukan->id,
            25,
            28,
            '16:00:00',
            240
        );


        // =====================================================
        // HST 29 - 35
        // PEMBENTUKAN BUAH
        // =====================================================

        $this->schedule(
            $profile->id,
            $valve1->id,
            $fasePembentukanBuah->id,
            29,
            35,
            '06:00:00',
            300
        );

        $this->schedule(
            $profile->id,
            $valve1->id,
            $fasePembentukanBuah->id,
            29,
            35,
            '12:00:00',
            240
        );

        $this->schedule(
            $profile->id,
            $valve1->id,
            $fasePembentukanBuah->id,
            29,
            35,
            '16:00:00',
            300
        );


        // =====================================================
        // HST 36 - 55
        // PEMBESARAN BUAH
        // =====================================================

        $this->schedule(
            $profile->id,
            $valve1->id,
            $fasePembesaranBuah->id,
            36,
            55,
            '06:00:00',
            360
        );

        $this->schedule(
            $profile->id,
            $valve1->id,
            $fasePembesaranBuah->id,
            36,
            55,
            '12:00:00',
            300
        );

        $this->schedule(
            $profile->id,
            $valve1->id,
            $fasePembesaranBuah->id,
            36,
            55,
            '16:00:00',
            360
        );


        // =====================================================
        // HST 56 - 70
        // PEMATANGAN
        // =====================================================

        $this->schedule(
            $profile->id,
            $valve1->id,
            $fasePematangan->id,
            56,
            70,
            '06:00:00',
            240
        );

        $this->schedule(
            $profile->id,
            $valve1->id,
            $fasePematangan->id,
            56,
            70,
            '16:00:00',
            240
        );


        // =====================================================
        // PENANAMAN AKTIF
        // =====================================================

        Planting::updateOrCreate(
            [
                'name' => 'Melon Greenhouse A'
            ],
            [
                'planting_date' =>
                    now()->toDateString(),

                'fertigation_profile_id' =>
                    $profile->id,

                'is_active' => true,
            ]
        );
    }


    // =========================================================
    // HELPER PEMBUATAN JADWAL
    // =========================================================

    private function schedule(
        int $profileId,
        int $valveId,
        int $phaseId,
        int $hstStart,
        int $hstEnd,
        string $startTime,
        int $durationSeconds
    ): void {

        FertigationSchedule::updateOrCreate(
            [
                'fertigation_profile_id' =>
                    $profileId,

                'valve_id' =>
                    $valveId,

                'growth_phase_id' =>
                    $phaseId,

                'hst_start' =>
                    $hstStart,

                'hst_end' =>
                    $hstEnd,

                'start_time' =>
                    $startTime,
            ],
            [
                'duration_seconds' =>
                    $durationSeconds,

                'is_active' => true,
            ]
        );
    }
}