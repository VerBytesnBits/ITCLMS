<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Milon\Barcode\DNS1D;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GenerateAssetCode implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $modelClass;
    public int $id;
    public string $type; // 'qr' or 'barcode'

    /**
     * Create a new job instance.
     */
    public function __construct(string $modelClass, int $id, string $type)
    {
        $this->modelClass = $modelClass;
        $this->id = $id;
        $this->type = $type;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Retrieve the model instance
        $record = $this->modelClass::find($this->id);

        // If missing record or missing serial, skip
        if (!$record || empty($record->serial_number)) {
            return;
        }

        // Determine config based on type
        $isBarcode = $this->type === 'barcode';

        $dir      = $isBarcode ? 'barcodes/' : 'qrcodes/';
        $dbField  = $isBarcode ? 'barcode_path' : 'qr_code_path';

        // Generate image
        $binary = $isBarcode
            ? $this->generateBarcode($record->serial_number)
            : $this->generateQr($record->serial_number);

        if (!$binary) {
            return; // Safety check
        }

        // Store file
        $fileName = $dir . Str::slug($record->serial_number) . '-' . Str::random(6) . '.png';
        Storage::disk('public')->put($fileName, $binary);

        // Save path quietly
        $record->updateQuietly([
            $dbField => 'storage/' . $fileName
        ]);
    }

    /**
     * Generate barcode PNG binary.
     */
    private function generateBarcode(string $serial): string|null
    {
        try {
            $barcode = new DNS1D();
            $imageData = $barcode->getBarcodePNG($serial, 'C128', 2, 60, [0, 0, 0], true);
            return base64_decode($imageData);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Generate QR code PNG binary.
     */
    private function generateQr(string $serial): string|null
    {
        try {
            return QrCode::format('png')
                ->size(250)
                ->margin(2)
                ->generate(url('/units/' . urlencode($serial)));
        } catch (\Exception $e) {
            return null;
        }
    }
}
