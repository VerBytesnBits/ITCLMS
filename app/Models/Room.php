<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Room extends Model
{
    /** @use HasFactory<\Database\Factories\RoomFactory> */
    use HasFactory;
    protected $fillable = ['name', 'lab_in_charge_id', 'status'];

    public function labInCharge()
    {
        return $this->belongsTo(User::class, 'lab_in_charge_id');
    }
}
