<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemUnit extends Model
{
    protected $fillable = ['name', 'room_id', 'status'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function processor()
    {
        return $this->hasOne(Processor::class); // One processor per unit
    }

    public function cpuCooler()
    {
        return $this->hasOne(CpuCooler::class);
    }

    public function motherboard()
    {
        return $this->hasOne(Motherboard::class);
    }

    public function memories()
    {
        return $this->hasMany(Memory::class); // multiple RAM sticks
    }

    public function graphicsCards()
    {
        return $this->hasMany(GraphicsCard::class);
    }

    public function powerSupply()
    {
        return $this->hasOne(PowerSupply::class);
    }

    public function computerCase()
    {
        return $this->hasOne(ComputerCase::class);
    }

    public function m2Ssds()
    {
        return $this->hasMany(M2Ssd::class);
    }

    public function sataSsds()
    {
        return $this->hasMany(SataSsd::class);
    }

    public function hardDiskDrives()
    {
        return $this->hasMany(HardDiskDrive::class);
    }

    public function monitor()
    {
        return $this->hasOne(Display::class);
    }

    public function keyboard()
    {
        return $this->hasOne(Keyboard::class);
    }

    public function mouse()
    {
        return $this->hasOne(Mouse::class);
    }

    public function headset()
    {
        return $this->hasOne(Headset::class);
    }

    public function speaker()
    {
        return $this->hasOne(Speaker::class);
    }

    public function webCamera()
    {
        return $this->hasOne(WebDigitalCamera::class);
    }
}
