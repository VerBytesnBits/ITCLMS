<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'name',
        'brand',
        'model',
        'serial_number',
        'inventory_code',
        'status',
        'date_purchased'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    // Components
    public function processors()
    {
        return $this->hasMany(Processor::class);
    }
    public function cpuCoolers()
    {
        return $this->hasMany(CpuCooler::class);
    }
    public function motherboards()
    {
        return $this->hasMany(Motherboard::class);
    }
    public function memories()
    {
        return $this->hasMany(Memory::class);
    }
    public function graphicsCards()
    {
        return $this->hasMany(GraphicsCard::class);
    }
    public function m2Ssds()
    {
        return $this->hasMany(M2SSD::class);
    }
    public function sataSsds()
    {
        return $this->hasMany(SataSSD::class);
    }
    public function hardDiskDrives()
    {
        return $this->hasMany(HardDiskDrive::class);
    }
    public function powerSupplies()
    {
        return $this->hasMany(PowerSupply::class);
    }
    public function computerCase()
    {
        return $this->hasMany(ComputerCase::class);
    }

    // Peripherals
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


