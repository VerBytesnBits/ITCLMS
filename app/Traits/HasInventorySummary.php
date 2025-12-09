<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait HasInventorySummary
{
    /**
     * Apply filters like age or other columns
     */
    protected function applyFilters($query, array $filters)
    {
        foreach ($filters as $column => $value) {

            if ($column === '__age') {
                if ($value === 'new') {
                    $query->where(function ($q) {
                        $q->where('warranty_expires_at', '>=', now())
                          ->orWhere('purchase_date', '>=', now()->subYear());
                    });
                } elseif (preg_match('/^older_(\d+)(month|months|year|years)$/', $value, $matches)) {
                    $amount = (int) $matches[1];
                    $unit = rtrim($matches[2], 's');
                    $query->where('purchase_date', '<', now()->sub($unit, $amount));
                }
            } elseif ($column !== 'room_id') {
                // Other direct filters
                $query->where($column, $value);
            }
        }

        return $query;
    }

    /**
     * Get inventory summary grouped by one or more columns.
     */
    public function getInventorySummary(
        string $modelClass,
        array|string $groupColumn, // <-- Now correctly handles array or string
        array $descriptionColumns,
        string $sortColumn = 'available',
        string $sortDirection = 'asc',
        array $filters = []
    ) {
        $model = new $modelClass;
        $table = $model->getTable();

        // 1. Normalize groupColumn to an array and determine the primary group column for final output
        $groupColumns = is_array($groupColumn) ? $groupColumn : [$groupColumn];
        $primaryGroupColumn = is_array($groupColumn) ? $groupColumn[0] : $groupColumn;
        
        // Prepare the columns for the SQL GROUP BY clause (e.g., ['assets.type', 'assets.model'])
        $qualifiedGroupColumns = array_map(fn($col) => "{$table}.{$col}", $groupColumns);


        $concatExpr = implode(", ' ', ", array_map(fn($col) => "COALESCE($col,'')", $descriptionColumns));

        $query = $modelClass::query();

        // Room filter (No changes needed here)
        if (!empty($filters['room_id'])) {
            $roomId = $filters['room_id'];
            unset($filters['room_id']);

            $query->leftJoin('system_units', "{$table}.system_unit_id", '=', 'system_units.id')
                ->where(function($q) use ($roomId, $table) {
                    $q->where('system_units.room_id', $roomId)      // linked system units
                      ->orWhere("{$table}.room_id", $roomId);       // direct room assignment
                });
        }

        // Apply other filters (No changes needed here)
        $query = $this->applyFilters($query, $filters);

        // 2. Build the SELECT statement
        $selects = [
            DB::raw("MAX(CONCAT($concatExpr)) as description"),
            DB::raw("COUNT(*) as total"),
            DB::raw("SUM(CASE WHEN LOWER({$table}.status) = 'available' THEN 1 ELSE 0 END) as available"),
            DB::raw("SUM(CASE WHEN LOWER({$table}.status) = 'in use' THEN 1 ELSE 0 END) as in_use"),
            DB::raw("SUM(CASE WHEN LOWER({$table}.status) = 'defective' THEN 1 ELSE 0 END) as defective")
        ];

        // Add the group columns to the SELECT list
        $selects = array_merge($qualifiedGroupColumns, $selects);

        // 3. Apply SELECT and GROUP BY using the qualified columns
        $query->select($selects)
            ->groupBy($qualifiedGroupColumns);

        // Execute query
        $summary = $query->get();

        // Optional sorting
        if ($sortColumn && $sortDirection) {
            $summary = $sortDirection === 'desc'
                ? $summary->sortByDesc($sortColumn)
                : $summary->sortBy($sortColumn);
        }

        // 4. Group the final collection by the primary column (e.g., 'type')
        return $summary->groupBy($primaryGroupColumn)->toArray();
    }
}