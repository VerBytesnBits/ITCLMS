<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Str;

class Checkbox extends Component
{
    public $id;
    public $value;
    public $wireModel;

    /**
     * Create a new component instance.
     */
    public function __construct($id = null, $value = null, $wireModel = null)
    {
        $this->id = $id ?? 'checkbox-' . Str::uuid();
        $this->value = $value;
        $this->wireModel = $wireModel;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.checkbox');
    }
}
