<?php

// app/Models/Mouse.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'system_unit_id',
        'brand',
        'model',
        'serial_number',
        'dpi',
        'connection_type',
        'status',
        'date_purchased'
    ];

    public function systemUnit()
    {
        return $this->belongsTo(SystemUnit::class);
    }
}
