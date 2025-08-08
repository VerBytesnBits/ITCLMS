<?php

// app/Models/Display.php   or monitor
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Display extends Model
{
    use HasFactory;

    protected $fillable = [
        'system_unit_id',
        'brand',
        'model',
        'serial_number',
        'resolution',
        'size_inches',
        'refresh_rate',
        'panel_type',
        'status',
        'date_purchased'
    ];

    public function systemUnit()
    {
        return $this->belongsTo(SystemUnit::class);
    }
}
