<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class QrGeneration extends Model
{
    use LogsActivity;

    protected $fillable = ['item_id', 'item_type'];

    /**
     * Required by Spatie v4+ to configure logging.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['item_id', 'item_type'])
            ->logOnlyDirty()
            ->useLogName('qr_generation');
    }

    public function item()
    {
        return $this->morphTo();
    }
}
