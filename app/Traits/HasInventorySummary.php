<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait HasInventorySummary
{
    /**
     * Apply dynamic filters including age filters.
     */
    protected function applyFilters($query, array $filters)
    {
        foreach ($filters as $column => $value) {
            if ($column === '__age') {
                if ($value === 'new') {
                    // Still in warranty OR purchased within 12 months
                    $query->where(function ($q) {
                        $q->where('warranty_expires_at', '>=', now())
                            ->orWhere('purchase_date', '>=', now()->subYear());
                    });
                } elseif (preg_match('/^older_(\d+)(month|months|year|years)$/', $value, $matches)) {
                    $amount = (int)$matches[1];
                    $unit = $matches[2];

                    // Normalize plural → singular (years, months → year, month)
                    $unit = rtrim($unit, 's');

                    $query->where('purchase_date', '<', now()->sub($unit, $amount));
                }
            } else {
                // Normal column filters (e.g. room_id, unit_id, category)
                $query->where($column, $value);
            }
        }

        return $query;
    }

    /**
     * Generate a summary grouped by type + description.
     */
    public function getInventorySummary(
        string $modelClass,
        string $groupColumn,
        array $descriptionColumns,
        string $sortColumn = 'available',
        string $sortDirection = 'asc',
        array $filters = []
    ) {
        $concatExpr = implode(", ' ', ", array_map(fn($col) => "COALESCE($col,'')", $descriptionColumns));

        $query = $modelClass::select(
            $groupColumn,
            DB::raw("CONCAT($concatExpr) as description"),
            DB::raw('COUNT(*) as total'),
            DB::raw("SUM(CASE WHEN status = 'Available' THEN 1 ELSE 0 END) as available"),
            DB::raw("SUM(CASE WHEN status = 'In Use' THEN 1 ELSE 0 END) as in_use"),
            DB::raw("SUM(CASE WHEN status = 'Defective' THEN 1 ELSE 0 END) as defective"),
            DB::raw("SUM(CASE WHEN status = 'Under Maintenance' THEN 1 ELSE 0 END) as maintenance"),
            DB::raw("SUM(CASE WHEN status = 'Decommission' THEN 1 ELSE 0 END) as decommission")
        );

        // ✅ Apply filters (normal + age-based)
        $this->applyFilters($query, $filters);

        $summary = $query
            ->groupBy($groupColumn, 'description')
            ->orderBy($groupColumn)
            ->get();

        // Sorting
        if ($sortColumn && $sortDirection) {
            $summary = $summary->sortBy(fn($item) => $item->{$sortColumn});
            if ($sortDirection === 'desc') {
                $summary = $summary->reverse();
            }
        }

        return $summary->groupBy($groupColumn)->toArray();
    }

    /**
     * Get inventory details with optional filters.
     */
    public function getInventoryDetails(
        string $modelClass,
        string $groupColumn,
        array $descriptionColumns,
        array $filters = []
    ) {
        $concatExpr = implode(", ' ', ", array_map(fn($col) => "COALESCE($col,'')", $descriptionColumns));

        $query = $modelClass::select(
            $groupColumn,
            DB::raw("CONCAT($concatExpr) as description"),
            'status'
        );

 
        $this->applyFilters($query, $filters);

        return $query
            ->orderBy($groupColumn)
            ->get()
            ->groupBy($groupColumn);
    }

    
}
