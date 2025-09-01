<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CPU extends Model
{
    use HasFactory;

    // Explicit table name
    protected $table = 'cpus';

    protected $fillable = [
        'system_unit_id','serial_number','brand','model','type','capacity','speed',
        'condition','status','warranty'
    ];

    public function systemUnit()
    {
        return $this->belongsTo(SystemUnit::class);
    }
}
