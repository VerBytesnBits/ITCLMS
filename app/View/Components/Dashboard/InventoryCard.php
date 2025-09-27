<?php

namespace App\View\Components\Dashboard;

use Illuminate\View\Component;

class InventoryCard extends Component
{
    public string $title;
    public string $fromColor;
    public string $toColor;
    public int $percentage;
    public array $stats;
    public int $belowThreshold;
    public int $outOfStock;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $title,
        string $fromColor,
        string $toColor,
        int $percentage,
        array $stats,
        int $belowThreshold = 0,
        int $outOfStock = 0
    ) {
        $this->title = $title;
        $this->fromColor = $fromColor;
        $this->toColor = $toColor;
        $this->percentage = $percentage;
        $this->stats = $stats;
        $this->belowThreshold = $belowThreshold;
        $this->outOfStock = $outOfStock;
    }

    public function render()
    {
        return view('components.dashboard.inventory-card');
    }
}
