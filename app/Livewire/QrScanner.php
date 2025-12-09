<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app', ['title' => 'Issues'])]
class QrScanner extends Component
{
    public $scannedCode = null;

    protected $listeners = ['qrScanned' => 'handleScannedCode'];

    public function handleScannedCode($code)
    {
        $this->scannedCode = $code;
    }

    public function render()
    {
        return view('livewire.qr-scanner');
    }
}
