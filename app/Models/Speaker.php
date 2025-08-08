<?php

// app/Models/Speaker.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Speaker extends Model
{
    use HasFactory;

    protected $fillable = [
        'system_unit_id',
        'brand',
        'model',
        'serial_number',
        'connection_type',
        'channels', // 2.0, 2.1, 5.1 etc.
        'status',
        'date_purchased'
    ];

    public function systemUnit()
    {
        return $this->belongsTo(SystemUnit::class);
    }
}
