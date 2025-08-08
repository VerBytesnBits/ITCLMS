<?php

// app/Models/Keyboard.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Keyboard extends Model
{
    use HasFactory;

    protected $fillable = [
        'system_unit_id',
        'brand',
        'model',
        'serial_number',
        'type', // mechanical, membrane, etc.
        'connection_type', // wired, wireless
        'status',
        'date_purchased'
    ];

    public function systemUnit()
    {
        return $this->belongsTo(SystemUnit::class);
    }
}
