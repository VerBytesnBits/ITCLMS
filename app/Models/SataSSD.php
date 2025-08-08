<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SataSSD extends Model
{


    protected $fillable = [
        'system_unit_id','brand','model','capacity','interface',
        'serial_number','status','date_purchased'
    ];

    protected $table = 'sata_ssds';

    public function systemUnit()
    {
        return $this->belongsTo(SystemUnit::class);
    }
}

