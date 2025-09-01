<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class ComputerCase extends Model
{
     use HasFactory;

    // Explicit table name
    protected $table = 'cases';

    protected $fillable = [
        'system_unit_id','serial_number','brand','model','type','capacity','speed',
        'condition','status','warranty'
    ];

    public function systemUnit()
    {
        return $this->belongsTo(SystemUnit::class);
    }
}
