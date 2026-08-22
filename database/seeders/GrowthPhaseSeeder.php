<?php

namespace Database\Seeders;

use App\Models\GrowthPhase;
use Illuminate\Database\Seeder;

class GrowthPhaseSeeder extends Seeder
{
    public function run(): void
    {
        GrowthPhase::updateOrCreate(
            ['name' => 'Masa Awal'],
            [
                'description' => 'Fase adaptasi dan pertumbuhan awal tanaman',
                'is_active' => true,
            ]
        );

        GrowthPhase::updateOrCreate(
            ['name' => 'Vegetatif'],
            [
                'description' => 'Fase pertumbuhan batang, daun, dan akar',
                'is_active' => true,
            ]
        );

        GrowthPhase::updateOrCreate(
            ['name' => 'Pembungaan'],
            [
                'description' => 'Fase pembentukan dan perkembangan bunga',
                'is_active' => true,
            ]
        );

        GrowthPhase::updateOrCreate(
            ['name' => 'Penyerbukan'],
            [
                'description' => 'Fase pembungaan dan proses penyerbukan',
                'is_active' => true,
            ]
        );

        GrowthPhase::updateOrCreate(
            ['name' => 'Pembentukan Buah'],
            [
                'description' => 'Fase awal pembentukan buah setelah penyerbukan',
                'is_active' => true,
            ]
        );

        GrowthPhase::updateOrCreate(
            ['name' => 'Pembesaran Buah'],
            [
                'description' => 'Fase perkembangan ukuran dan bobot buah',
                'is_active' => true,
            ]
        );

        GrowthPhase::updateOrCreate(
            ['name' => 'Pematangan'],
            [
                'description' => 'Fase pematangan buah menjelang panen',
                'is_active' => true,
            ]
        );
    }
}