<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HardDiskDrive extends Model
{
    use HasFactory;

    protected $fillable = [
        'system_unit_id','brand','model','capacity','rpm','interface',
        'serial_number','status','date_purchased'
    ];

    protected $table = 'hard_disk_drives';

    public function systemUnit()
    {
        return $this->belongsTo(SystemUnit::class);
    }
}
