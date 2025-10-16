<?php

namespace App\Livewire;

use Livewire\Component;

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
