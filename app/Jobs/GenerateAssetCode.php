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
    private function generateBarcode(string $serial): ?string
    {
        try {
            $barcode = new DNS1D();
            $imageData = $barcode->getBarcodePNG($serial, 'C128', 3, 60); // writeText optional
            return base64_decode($imageData);
        } catch (\Exception $e) {
            logger()->error("Barcode generation failed: {$e->getMessage()}");
            return null;
        }
    }

    private function generateQr(string $serial): ?string
    {
        try {
            // Force raw PNG binary output
            return QrCode::format('png')
                ->size(250)
                ->margin(2)
                ->generate(url('/units/' . urlencode($serial)));
        } catch (\Exception $e) {
            logger()->error("QR generation failed: {$e->getMessage()}");
            return null;
        }
    }

    public function handle(): void
    {
        $record = $this->modelClass::find($this->id);

        if (!$record || empty($record->serial_number))
            return;

        $isBarcode = $this->type === 'barcode';
        $dir = $isBarcode ? 'barcodes/' : 'qrcodes/';
        $dbField = $isBarcode ? 'barcode_path' : 'qr_code_path';

        $binary = $isBarcode
            ? $this->generateBarcode($record->serial_number)
            : $this->generateQr($record->serial_number);

        if (!$binary) {
            logger()->error("Asset generation returned empty binary for {$record->serial_number}");
            return;
        }

        $fileName = $dir . Str::slug($record->serial_number) . '-' . Str::random(6) . '.png';

        // Store file in storage/app/public/... and make path for web access
        Storage::disk('public')->put($fileName, $binary);

        $record->updateQuietly([
            $dbField => $fileName // will map to /storage/... if you use asset()
        ]);
    }

}
