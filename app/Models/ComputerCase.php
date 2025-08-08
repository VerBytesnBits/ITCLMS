<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComputerCase extends Model
{
    use HasFactory;

    protected $table = 'computer_cases';


    protected $fillable = [
        'system_unit_id','brand','model','form_factor','color',
        'serial_number','status','date_purchased'
    ];

    public function systemUnit()
    {
        return $this->belongsTo(SystemUnit::class);
    }
}
