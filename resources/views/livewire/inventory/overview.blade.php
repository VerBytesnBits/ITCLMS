<div class="space-y-8">
    <!-- Components Table -->
    <div class="overflow-x-auto">
        <h2 class="text-lg font-semibold mb-4">Component Inventory</h2>
        <table class="min-w-full border border-gray-300 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 text-left">Description</th>
                    <th class="p-2">Total</th>
                    <th class="p-2">Available</th>
                    <th class="p-2">In Use</th>
                    <th class="p-2">Defective</th>
                    <th class="p-2">Maintenance</th>
                    <th class="p-2">Junk</th>
                    <th class="p-2">Salvage</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($componentSummary as $item)
                    <tr class="border-t">
                        <td class="p-2">{{ $item->description }}</td>
                        <td class="p-2 text-center">{{ $item->total }}</td>
                        <td class="p-2 text-center">{{ $item->available }}</td>
                        <td class="p-2 text-center">{{ $item->in_use }}</td>
                        <td class="p-2 text-center">{{ $item->defective }}</td>
                        <td class="p-2 text-center">{{ $item->maintenance }}</td>
                        <td class="p-2 text-center">{{ $item->junk }}</td>
                        <td class="p-2 text-center">{{ $item->salvage }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Peripherals Table -->
    <div class="overflow-x-auto">
        <h2 class="text-lg font-semibold mb-4">Peripheral Inventory</h2>
        <table class="min-w-full border border-gray-300 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 text-left">Description</th>
                    <th class="p-2">Total</th>
                    <th class="p-2">Available</th>
                    <th class="p-2">In Use</th>
                    <th class="p-2">Defective</th>
                    <th class="p-2">Maintenance</th>
                    <th class="p-2">Junk</th>
                    <th class="p-2">Salvage</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($peripheralSummary as $item)
                    <tr class="border-t">
                        <td class="p-2">{{ $item->description }}</td>
                        <td class="p-2 text-center">{{ $item->total }}</td>
                        <td class="p-2 text-center">{{ $item->available }}</td>
                        <td class="p-2 text-center">{{ $item->in_use }}</td>
                        <td class="p-2 text-center">{{ $item->defective }}</td>
                        <td class="p-2 text-center">{{ $item->maintenance }}</td>
                        <td class="p-2 text-center">{{ $item->junk }}</td>
                        <td class="p-2 text-center">{{ $item->salvage }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
