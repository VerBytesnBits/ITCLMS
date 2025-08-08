<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Memory extends Model
{
    use HasFactory;

    protected $fillable = [
        'system_unit_id','brand','model','type','capacity','speed',
        'serial_number','status','date_purchased'
    ];

    protected $table = 'memories';

    public function systemUnit()
    {
        return $this->belongsTo(SystemUnit::class);
    }
}
