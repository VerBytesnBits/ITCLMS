<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    protected $fillable = [
        'maintainable_id',
        'maintainable_type',
        'user_id',
        'type',
        'description',
        'status',
        'started_at',
        'completed_at',
        'created_by',
        'started_by',
        'completed_by',
    ];

    public function maintainable()
    {
        return $this->morphTo();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function starter()
    {
        return $this->belongsTo(User::class, 'started_by');
    }

    public function completer()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }
}

