<?php

namespace App\Livewire;

use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Support\PartsConfig;

class UnitExportPdf extends Component
{
    public $rooms = [];
    public $partsConfig = [];
    public $selectedComponents = [];
    public $showSelectComponents = false;
    public $showPreview = false;
    public $pdfBase64 = null;

    // public function mount($rooms)
    // {
    //     $this->rooms = $rooms;

    //     // Load parts config from central location
    //     // In mount():
    //     $this->partsConfig = collect(PartsConfig::get())
    //         ->map(function ($cfg) {
    //             unset($cfg['formatter']); // remove closure
    //             return $cfg;
    //         })
    //         ->toArray();


    //     // Select all components by default
    //     foreach (array_keys($this->partsConfig) as $key) {
    //         $this->selectedComponents[$key] = true;
    //     }
    // }
    public function mount($rooms)
    {
        $this->rooms = $rooms;

        // If PartsConfig::get() in your app requires args, pass them here.
        $full = PartsConfig::get();

        // Store a Livewire-safe copy (no closures)
        $this->partsConfig = collect($full)->map(function ($cfg) {
            unset($cfg['formatter']);
            return $cfg;
        })->toArray();

        // Select all by default
        foreach (array_keys($this->partsConfig) as $key) {
            $this->selectedComponents[$key] = true;
        }
    }



    public function openSelectComponentsModal()
    {
        $this->showSelectComponents = true;
    }

    public function confirmComponentSelection()
    {
        $this->generatePreview();
        $this->showSelectComponents = false;
        $this->showPreview = true;
    }



    public function generatePreview()
    {
        // Safe meta used for labels/subs in the view
        $partsMeta = $this->partsConfig;

        // Full config with closures for formatting only here
        $fullConfig = PartsConfig::get();

        // Which columns are selected (and keep their order)
        $selectedKeys = array_keys(array_filter($this->selectedComponents));

        $formattedRooms = collect($this->rooms)->map(function ($room) use ($selectedKeys, $fullConfig, $partsMeta) {
            return [
                'room_name' => $room->name,
                'units' => collect($room->systemUnits)->map(function ($unit) use ($selectedKeys, $fullConfig, $partsMeta) {
                    $unitParts = [];

                    foreach ($selectedKeys as $key) {
                        // Skip if key not in config
                        if (!isset($partsMeta[$key], $fullConfig[$key]))
                            continue;

                        $relation = $partsMeta[$key]['value'];          // relation on SystemUnit
                        $label = $partsMeta[$key]['label'];          // column header label
                        $formatter = $fullConfig[$key]['formatter'];     // closure
    
                        $data = $unit->{$relation} ?? null;

                        // Convert relation data to an array of "detail arrays"
                        if ($data instanceof \Illuminate\Support\Collection) {
                            $details = $data->map(fn($p) => $formatter($p))->values()->all();
                        } elseif ($data) {
                            $details = [$formatter($data)];
                        } else {
                            $details = []; // none attached
                        }

                        $unitParts[$key] = [
                            'label' => $label,
                            'details' => $details, // array of [Field => Value, ...] maps
                        ];
                    }

                    return [
                        'unit_id' => $unit->id,
                        'unit_name' => $unit->name,
                        'parts' => $unitParts,
                    ];

                })->toArray(),
            ];
        })->toArray();

        $pdf = Pdf::loadView('pdf.system-units', [
            'rooms' => $formattedRooms,         // hierarchical data used by the view
            'selectedKeys' => $selectedKeys,           // which columns to render
            'partsConfig' => $partsMeta,              // safe meta (for header labels/subs if needed)
            'selectedComponents' => $this->selectedComponents,
        ])->setPaper('legal', 'landscape');

        $this->pdfBase64 = base64_encode($pdf->output());
    }


    public function downloadPdf()
    {
        return response()->streamDownload(function () {
            echo base64_decode($this->pdfBase64);
        }, 'units.pdf');
    }

    public function render()
    {
        return view('livewire.unit-export-pdf');
    }
}
