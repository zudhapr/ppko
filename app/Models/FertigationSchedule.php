<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FertigationSchedule extends Model
{
    protected $fillable = [
        'fertigation_profile_id',
        'valve_id',
        'growth_phase_id',
        'hst_start',
        'hst_end',
        'start_time',
        'duration_seconds',
        'is_active',
    ];

    protected $casts = [
        'hst_start' => 'integer',
        'hst_end' => 'integer',
        'duration_seconds' => 'integer',
        'is_active' => 'boolean',
    ];


    public function profile()
    {
        return $this->belongsTo(
            FertigationProfile::class,
            'fertigation_profile_id'
        );
    }


    public function valve()
    {
        return $this->belongsTo(
            Valve::class
        );
    }


    public function growthPhase()
    {
        return $this->belongsTo(
            GrowthPhase::class
        );
    }
}