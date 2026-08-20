<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DeviceCommand;

class Valve extends Model
{
    protected $fillable = [
        'name',
        'gpio',
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

    public function commands()
    {
        return $this->hasMany(DeviceCommand::class);
    }
}