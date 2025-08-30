<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SystemUnit extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'serial_number', 'status', 'room_id'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
