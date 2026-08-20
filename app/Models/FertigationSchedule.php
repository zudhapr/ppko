<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FertigationSchedule extends Model
{
    protected $fillable = [
        'fertigation_profile_id',
        'valve_id',
        'hst',
        'start_time',
        'duration_seconds',
        'is_active',
    ];

    protected $casts = [
        'hst' => 'integer',
        'duration_seconds' => 'integer',
        'is_active' => 'boolean',
    ];

    public function profile()
    {
        return $this->belongsTo(FertigationProfile::class);
    }

    public function valve()
    {
        return $this->belongsTo(Valve::class);
    }
}