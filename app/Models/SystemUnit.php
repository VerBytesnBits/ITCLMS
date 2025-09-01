<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SystemUnit extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'serial_number', 'status','condition', 'room_id'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
    public function peripherals()
    {
        return $this->hasMany(Peripheral::class);
    }
    public function components()
    {
        return $this->hasMany(ComponentParts::class);
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }

}
