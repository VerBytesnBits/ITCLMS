<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Room extends Model
{
    protected $fillable = ['name', 'status','description'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'room_user')
                    ->withPivot('role_in_room')
                    ->withTimestamps();
    }

    public function labIncharges()
    {
        return $this->users()->wherePivot('role_in_room', 'lab_incharge');
    }

    public function technicians()
    {
        return $this->users()->wherePivot('role_in_room', 'lab_technician');
    }

    public function systemUnits()
    {
        return $this->hasMany(SystemUnit::class);
    }
}

