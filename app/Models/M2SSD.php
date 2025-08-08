<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M2SSD extends Model
{
    use HasFactory;

    protected $fillable = [
        'system_unit_id','brand','model','capacity','interface',
        'serial_number','status','date_purchased'
    ];

    protected $table = 'm2_ssds';

    public function systemUnit()
    {
        return $this->belongsTo(SystemUnit::class);
    }
}
