<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Models\SystemUnit;
use App\Support\PartsConfig; // for modelMap

class ImportComputerInventory extends Command
{
    protected $signature = 'import:computer-inventory {file=database/data/computer_inventory.json}';
    protected $description = 'Import computer units with components and peripherals from a JSON file';

    public function handle()
    {
        $path = base_path($this->argument('file'));

        if (!File::exists($path)) {
            $this->error("File not found: {$path}");
            return self::FAILURE;
        }

        $data = json_decode(File::get($path), true);

        if (!is_array($data)) {
            $this->error("Invalid JSON format in {$path}");
            return self::FAILURE;
        }

        foreach ($data as $item) {
            // Create system unit
            $unit = SystemUnit::create($item['system_unit']);

            // Loop through modelMap for components/peripherals
            foreach (PartsConfig::modelMap() as $key => $modelClass) {
                if (!empty($item[$key])) {
                    $modelClass::create(array_merge($item[$key], [
                        'system_unit_id' => $unit->id
                    ]));
                }
            }
        }

        $this->info("Computer inventory imported successfully from {$path}");
        return self::SUCCESS;
    }
}
