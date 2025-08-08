<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PowerSupply extends Model
{
    use HasFactory;

    protected $fillable = [
        'system_unit_id','brand','model','wattage','efficiency_rating','modular',
        'serial_number','status','date_purchased'
    ];

    protected $table = 'power_supplies';

    public function systemUnit()
    {
        return $this->belongsTo(SystemUnit::class);
    }
}
