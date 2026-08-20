<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FertigationProfile extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function schedules()
    {
        return $this->hasMany(FertigationSchedule::class);
    }

    public function plantings()
    {
        return $this->hasMany(Planting::class);
    }
}