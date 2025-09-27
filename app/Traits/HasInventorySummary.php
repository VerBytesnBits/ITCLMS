<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait HasInventorySummary
{
    /**
     * Generate a summary grouped by type + description.
     *
     * @param string $modelClass Eloquent model (e.g. ComponentParts::class)
     * @param string $groupColumn Column to group by (e.g. 'part' or 'category')
     * @param array $descriptionColumns Columns to concatenate for description
     * @param string $sortColumn Default sort column
     * @param string $sortDirection asc|desc
     */
    public function getInventorySummary(
        string $modelClass,
        string $groupColumn,
        array $descriptionColumns,
        string $sortColumn = 'available',
        string $sortDirection = 'asc'
    ) {
        // Build CONCAT expression for description
        $concatExpr = implode(", ' ', ", array_map(fn($col) => "COALESCE($col,'')", $descriptionColumns));

        $summary = $modelClass::select(
            $groupColumn,
            DB::raw("CONCAT($concatExpr) as description"),
            DB::raw('COUNT(*) as total'),
            DB::raw("SUM(CASE WHEN status = 'Available' THEN 1 ELSE 0 END) as available"),
            DB::raw("SUM(CASE WHEN status = 'In Use' THEN 1 ELSE 0 END) as in_use"),
            DB::raw("SUM(CASE WHEN status = 'Defective' THEN 1 ELSE 0 END) as defective"),
            DB::raw("SUM(CASE WHEN status = 'Under Maintenance' THEN 1 ELSE 0 END) as maintenance"),
            DB::raw("SUM(CASE WHEN status = 'Junk' THEN 1 ELSE 0 END) as junk"),
            // DB::raw("SUM(CASE WHEN status = 'Salvaged' THEN 1 ELSE 0 END) as salvage")
        )
            ->groupBy($groupColumn, 'description')
            ->orderBy($groupColumn)
            ->get();

        // Sorting
        if ($sortColumn && $sortDirection) {
            $summary = $summary->sortBy(function ($item) use ($sortColumn) {
                return $item->{$sortColumn};
            });

            if ($sortDirection === 'desc') {
                $summary = $summary->reverse();
            }
        }

        return $summary->groupBy($groupColumn)->toArray();
    }


    public function getInventoryDetails(
        string $modelClass,
        string $groupColumn,
        array $descriptionColumns
    ) {
        // Build CONCAT expression for description
        $concatExpr = implode(", ' ', ", array_map(fn($col) => "COALESCE($col,'')", $descriptionColumns));

        return $modelClass::select(
            $groupColumn,
            DB::raw("CONCAT($concatExpr) as description"),
            'status'
        )
            ->orderBy($groupColumn)
            ->get()
            ->groupBy($groupColumn);
    }

}
