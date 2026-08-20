<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceCommand extends Model
{
    protected $fillable = [
        'device_id',
        'valve_id',
        'command',
        'duration_seconds',
        'status',
        'expires_at',
        'started_at',
        'completed_at',
        'message',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function valve()
    {
        return $this->belongsTo(Valve::class);
    }
}