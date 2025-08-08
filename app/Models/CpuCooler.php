<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CpuCooler extends Model
{
    use HasFactory;

    protected $fillable = [
        'system_unit_id','brand','model','type','fan_size',
        'serial_number','status','date_purchased'
    ];

    protected $table = 'cpu_coolers';

    public function systemUnit()
    {
        return $this->belongsTo(SystemUnit::class);
    }
}
