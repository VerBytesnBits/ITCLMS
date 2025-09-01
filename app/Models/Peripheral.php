<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peripheral extends Model
{
    use HasFactory;

    protected $fillable = [
        'system_unit_id',
        'room_id',
        'name',
        'serial_number',
        'brand',
        'model',
        'color',
        'type',
        'condition',
        'status',
        'warranty',
    ];

    public function systemUnit()
    {
        return $this->belongsTo(SystemUnit::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
