<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
        'name',
        'device_code',
        'mode',
        'current_hst',
        'last_seen',
        'schedule_updated_at',
        'ip_address',
        'firmware_version',
        'is_active',
    ];

    protected $casts = [
        'last_seen' => 'datetime',
        'schedule_updated_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function commands()
    {
        return $this->hasMany(DeviceCommand::class);
    }

    public function getIsOnlineAttribute()
    {
        if (!$this->last_seen) {
            return false;
        }

        return $this->last_seen->gt(
            now()->subSeconds(30)
        );
    }
}