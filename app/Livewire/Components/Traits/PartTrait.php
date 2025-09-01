<?php

namespace App\Livewire\Components\Traits;

use Livewire\Component;

trait PartTrait
{
    // Common properties for all components
    public $items;
    public $show = false;
    public $partId;
    public $system_unit_id;
    public $serial_number;
    public $brand;
    public $model;
    public $type;
    public $capacity;
    public $speed;
    public $condition = 'Good';
    public $status = 'Available';
    public $warranty;

    /**
     * Load items from the database
     *
     * @param string $modelClass
     * @return void
     */
    public function loadItems($modelClass)
    {
        $this->items = $modelClass::with('systemUnit')->orderBy('id')->get();

    }

    /**
     * Open modal form
     *
     * @return void
     */
    public function openForm($id = null)
    {
        if ($id) {
            $part = $this->getModelClass()::findOrFail($id);

            $this->partId = $part->id;
            $this->system_unit_id = $part->system_unit_id;
            $this->serial_number = $part->serial_number;
            $this->brand = $part->brand;
            $this->model = $part->model;
            $this->type = $part->type;
            $this->capacity = $part->capacity;
            $this->speed = $part->speed;
            $this->condition = $part->condition;
            $this->status = $part->status;
            $this->warranty = $part->warranty;
        } else {
            $this->reset(); // clear fields when adding
        }

        $this->show = true;
    }


    /**
     * Save or update a component
     *
     * @param string $modelClass
     * @return void
     */
    public function savePart($modelClass)
    {
        $table = (new $modelClass())->getTable(); // ✅

        $this->validate([
            'system_unit_id' => 'required|exists:system_units,id',
            'serial_number' => 'required|unique:' . $table . ',serial_number,' . $this->partId,
            'condition' => 'required',
            'status' => 'required',
        ]);

        $modelClass::updateOrCreate(
            ['id' => $this->partId],
            [
                'system_unit_id' => $this->system_unit_id,
                'serial_number' => $this->serial_number,
                'brand' => $this->brand,
                'model' => $this->model,
                'type' => $this->type,
                'capacity' => $this->capacity,
                'speed' => $this->speed,
                'condition' => $this->condition,
                'status' => $this->status,
                'warranty' => $this->warranty,
            ]
        );

        $this->dispatch("$table-saved");
        // optional: use table name for event

        $this->show = false;
    }

    public function deleteItem(string $modelClass, int $id)
    {
        $item = $modelClass::findOrFail($id);
        $item->delete();

        $this->dispatch('item-deleted'); // global event
        $this->loadItems($modelClass);
    }

}
