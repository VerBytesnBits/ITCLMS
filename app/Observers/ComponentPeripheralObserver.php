<?php

namespace App\Observers;

use App\Models\ComponentParts;
use App\Models\Peripheral;

class ComponentPeripheralObserver
{
    public function saved($model)
    {
        $model->systemUnit?->checkOperationalStatus();
    }

    public function updated($model)
    {
        $model->systemUnit?->checkOperationalStatus();
    }
}
