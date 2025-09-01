<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    protected $fillable = ['system_unit_id', 'user_id', 'type', 'description', 'status'];

    public function unit()
    {
        return $this->belongsTo(SystemUnit::class, 'system_unit_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
