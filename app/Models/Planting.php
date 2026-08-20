<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Planting extends Model
{
    protected $fillable = [
        'name',
        'planting_date',
        'fertigation_profile_id',
        'is_active',
    ];

    protected $casts = [
        'planting_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function fertigationProfile()
    {
        return $this->belongsTo(FertigationProfile::class);
    }
}