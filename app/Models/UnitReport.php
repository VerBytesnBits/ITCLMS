<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UnitReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'system_unit_id',
        'reported_by',
        'part_type',
        'part_id',
        'issue',
        'description',
        'status',
        'approval_status',
        'approved_by',
    ];




    public function systemUnit()
    {
        return $this->belongsTo(SystemUnit::class);
    }

    public function reportedBy()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Dynamic relation to the actual part (Processor, Memory, etc.)
     */
    public function part()
    {
        return $this->morphTo(null, 'part_type', 'part_id');
    }

}
