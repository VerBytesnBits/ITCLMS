<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IssueReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'system_unit_id',
        'component_part_id',
        'peripheral_id',
        'issue_type',
        'remarks',
        'reported_by',
        'resolved_by',
        'status',
        'resolution_notes',
    ];

    public function systemUnit()
    {
        return $this->belongsTo(SystemUnit::class);
    }

    public function componentPart()
    {
        return $this->belongsTo(ComponentParts::class);
    }

    public function peripheral()
    {
        return $this->belongsTo(Peripheral::class);
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}
