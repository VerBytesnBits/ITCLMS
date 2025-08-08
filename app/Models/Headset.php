<?php
// app/Models/Headset.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Headset extends Model
{
    use HasFactory;

    protected $fillable = [
        'system_unit_id',
        'brand',
        'model',
        'serial_number',
        'connection_type',
        'microphone', // yes/no
        'status',
        'date_purchased'
    ];

    public function systemUnit()
    {
        return $this->belongsTo(SystemUnit::class);
    }
}
