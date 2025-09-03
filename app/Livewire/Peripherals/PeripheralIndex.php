<?php

namespace App\Livewire\Peripherals;

use Livewire\Component;
use App\Models\Peripheral;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;

class PeripheralIndex extends Component
{
    #[Url(as: 'modal')]
    public ?string $modal = null;

    #[Url(as: 'id')]
    public ?int $id = null;

    public $peripherals;

    public function mount()
    {
        $this->refreshPeripherals();
    }

    public function openViewModal($id)
    {
        $this->id = $id;
        $this->modal = 'view';
    }


    public function openCreateModal()
    {
        $this->id = null;
        $this->modal = 'create';
    }

    public function openEditModal($id)
    {
        $this->id = $id;
        $this->modal = 'edit';
    }

    #[On('closeModal')]
    public function closeModal()
    {
        $this->modal = null;
        $this->id = null;
    }

    #[On('peripheralCreated')]
    #[On('peripheralUpdated')]
    #[On('peripheralDeleted')]
    public function refreshPeripherals()
    {
        $this->peripherals = Peripheral::with(['systemUnit', 'room'])->get();
    }

    public function deletePeripheral($id)
    {
        Peripheral::findOrFail($id)->delete();

        $this->dispatch('swal', toast: true, icon: 'success', title: 'Peripheral deleted successfully', timer: 3000);
        $this->dispatch('peripheralDeleted');
    }

    public function render()
    {
        return view('livewire.peripherals.peripheral-index');
    }
}
