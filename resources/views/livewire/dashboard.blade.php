<div class="p-6 space-y-8">
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Units -->
        <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl shadow">
            <h2 class="text-lg font-bold mb-4">All Units</h2>
            <p class="text-green-600 font-semibold">Operational: {{ $stats['units']['Operational'] }}</p>
            <p class="text-red-600 font-semibold">Defective: {{ $stats['units']['defective'] }}</p>
        </div>

        <!-- Parts -->
        <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl shadow">
            <h2 class="text-lg font-bold mb-4">Parts Inventory</h2>
            <p class="text-blue-600 font-semibold">Available: {{ $stats['parts']['available'] }}</p>
            <p class="text-orange-600 font-semibold">In Use: {{ $stats['parts']['In use'] }}</p>
            <p class="text-red-600 font-semibold">Defective: {{ $stats['parts']['defective'] }}</p>
        </div>

        <!-- Maintenance -->
        <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl shadow">
            <h2 class="text-lg font-bold mb-4">Maintenance</h2>
            <p class="text-yellow-600 font-semibold">Pending: {{ $stats['maintenance']['pending'] }}</p>
            <p class="text-blue-600 font-semibold">In Progress: {{ $stats['maintenance']['In Progress'] }}</p>
            <p class="text-green-600 font-semibold">Resolved: {{ $stats['maintenance']['completed'] }}</p>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Defective Units Trend -->
        <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl shadow">
            <h2 class="text-lg font-bold mb-4">Defective Units (This Year)</h2>
            <canvas id="unitTrendChart"></canvas>
        </div>

        <!-- Maintenance Trend -->
        <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl shadow">
            <h2 class="text-lg font-bold mb-4">Maintenance Reports (This Year)</h2>
            <canvas id="maintenanceTrendChart"></canvas>
        </div>
    </div>
{{-- 
    <!-- Recent Activity -->
    <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl shadow">
        <h2 class="text-lg font-bold mb-4">Recent Activity</h2>
        @if($recentLogs->count())
            <ul class="space-y-2">
                @foreach($recentLogs as $log)
                    <li class="text-gray-700 dark:text-gray-300">
                        <strong>{{ $log->user->name ?? 'System' }}</strong>
                        {{ $log->description }} 
                        <span class="text-sm text-gray-500">({{ $log->created_at->diffForHumans() }})</span>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-500">No recent activity.</p>
        @endif
    </div> --}}

    <!-- Quick Actions -->
    <div class="flex gap-4">
        <a href="units" class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow">Add Unit</a>
        <a href="components" class="px-4 py-2 bg-green-600 text-white rounded-lg shadow">Add Part</a>
        <a href="maintenance" class="px-4 py-2 bg-yellow-600 text-white rounded-lg shadow">New Report</a>
    </div>
</div>

@script
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Defective Units Chart
    const unitCtx = document.getElementById('unitTrendChart');
    new Chart(unitCtx, {
        type: 'line',
        data: {
            labels: ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
            datasets: [{
                label: 'Defective Units',
                data: @json($unitTrends),
                borderColor: 'rgb(239, 68, 68)',
                backgroundColor: 'rgba(239, 68, 68, 0.2)',
                tension: 0.3,
                fill: true,
            }]
        }
    });

    // Maintenance Chart
    const maintCtx = document.getElementById('maintenanceTrendChart');
    new Chart(maintCtx, {
        type: 'bar',
        data: {
            labels: ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
            datasets: [{
                label: 'Maintenance Reports',
                data: @json($maintenanceTrends),
                backgroundColor: 'rgb(59, 130, 246)',
            }]
        }
    });
</script>
@endscript

