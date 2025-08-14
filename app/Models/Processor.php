<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Processor extends Model
{
    use HasFactory;

    protected $fillable = [
        'system_unit_id','brand','model','generation','cores','threads',
        'base_clock','boost_clock','serial_number','status','date_purchased'
    ];

    public function systemUnit()
    {
        return $this->belongsTo(SystemUnit::class);
    }

    // protected static function booted()
    // {
    //     static::updated(function ($model) {
    //         if ($model->isDirty('status') && $model->status === 'Needs Repair') {
    //             $unit = $model->systemUnit;
    //             if ($unit && $unit->status !== 'Non-Operational') {
    //                 $unit->update(['status' => 'Non-Operational']);
    //                 $unit->updatePartsStatus('Under Maintenance');
    //             }
    //         }
    //     });
    // }
}


