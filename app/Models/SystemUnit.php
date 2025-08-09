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
        'date_purchased',
        'drive_id',
        'processor_id',
        'cpu_cooler_id',
        'motherboard_id',
        'memory_id',
        'graphics_card_id',
        'power_supply_id',
        'computer_case_id'
    ];

    
   
    public function m2Ssd()
    {
        return $this->belongsTo(M2Ssd::class, 'drive_id');
    }

    public function sataSsd()
    {
        return $this->belongsTo(SataSsd::class, 'drive_id');
    }

    public function hardDiskDrive()
    {
        return $this->belongsTo(HardDiskDrive::class, 'drive_id');
    }

    /**
     * Standard component relationships
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function processor()
    {
        return $this->belongsTo(Processor::class);
    }

    public function cpuCooler()
    {
        return $this->belongsTo(CpuCooler::class);
    }

    public function motherboard()
    {
        return $this->belongsTo(Motherboard::class);
    }

    public function memory()
    {
        return $this->belongsTo(Memory::class);
    }

    public function graphicsCard()
    {
        return $this->belongsTo(GraphicsCard::class);
    }

    public function powerSupply()
    {
        return $this->belongsTo(PowerSupply::class);
    }

    public function computerCase()
    {
        return $this->belongsTo(ComputerCase::class);
    }

    /**
     * Peripheral relationships
     */
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
