<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GraphicsCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'system_unit_id','brand','model','chipset','memory_size','memory_type',
        'serial_number','status','date_purchased'
    ];

    protected $table = 'graphics_cards';

    public function systemUnit()
    {
        return $this->belongsTo(SystemUnit::class);
    }
}
