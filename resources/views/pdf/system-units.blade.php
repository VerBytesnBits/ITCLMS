<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>System Units Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #000; }
        .header { text-align: center; margin-bottom: 10px; }
        .header h1 { font-size: 16px; margin: 0; }
        .header h2 { font-size: 14px; margin: 0; font-weight: normal; }

        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table, th, td { border: 1px solid #000; }
        th { background-color: #f0f0f0; text-align: center; padding: 5px; font-size: 11px; }
        td { padding: 5px; text-align: center; font-size: 11px; }

        .status-working { background-color: #d4edda; }
        .status-maintenance { background-color: #fff3cd; }
        .status-decommissioned { background-color: #e2e3e5; }

        /* Hide columns dynamically */
        .hidden-col { display: none; }
    </style>
</head>
<body>

<div class="header">
    <h1>LABORATORY INVENTORY SYSTEM</h1>
    <h2>System Units Report</h2>
    <small>{{ now()->format('F d, Y') }}</small>
</div>


<table id="units-table">
    <thead>
        <tr>
            <th>ID</th>
            <th class="col-cpu">CPU<br><small>(model)</small></th>
            <th class="col-mboard">MBOARD<br><small>(model)</small></th>
            <th class="col-ram">RAM<br><small>(type & capacity)</small></th>
            <th class="col-drive">DRIVE<br><small>(type & capacity)</small></th>
            <th class="col-gpu">GPU<br><small>(model)</small></th>
            <th class="col-monitor">Monitor<br><small>(model)</small></th>
            <th class="col-keyboard">Keyboard<br><small>(model)</small></th>
            <th class="col-mouse">Mouse<br><small>(model)</small></th>
            <th class="col-status">STATUS</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($units as $unit)
            <tr>
                <td>{{ $unit->id }}</td>
                <td class="col-cpu">
                    {{ $unit->processor
                        ? "{$unit->processor->brand} {$unit->processor->model} {$unit->processor->base_clock}GHz" .
                            ($unit->processor->boost_clock ? " / {$unit->processor->boost_clock}GHz" : '')
                        : 'N/A' }}
                </td>
                <td class="col-mboard">
                    {{ $unit->motherboard ? $unit->motherboard->brand . ' ' . $unit->motherboard->model : 'N/A' }}
                </td>
                <td class="col-ram">
                    {{ $unit->memory ? $unit->memory->type . ' ' . $unit->memory->capacity . 'GB' : 'N/A' }}
                </td>
                <td class="col-drive">
                    @if ($unit->drive_type === 'm2' && $unit->m2Ssd)
                        M.2 - ({{ $unit->m2Ssd->capacity }} GB)
                    @elseif ($unit->drive_type === 'sata' && $unit->sataSsd)
                        SATA - ({{ $unit->sataSsd->capacity }} GB)
                    @elseif ($unit->drive_type === 'hdd' && $unit->hardDiskDrive)
                        HDD - ({{ $unit->hardDiskDrive->capacity }} GB)
                    @else
                        N/A
                    @endif
                </td>
                <td class="col-gpu">{{ $unit->gpu ? $unit->gpu->brand . ' ' . $unit->gpu->model : 'N/A' }}</td>
                <td class="col-monitor">{{ $unit->monitor ? $unit->monitor->brand . ' ' . $unit->monitor->model : 'N/A' }}</td>
                <td class="col-keyboard">{{ $unit->keyboard ? $unit->keyboard->brand . ' ' . $unit->keyboard->model : 'N/A' }}</td>
                <td class="col-mouse">{{ $unit->mouse ? $unit->mouse->brand . ' ' . $unit->mouse->model : 'N/A' }}</td>
                <td class="col-status
                    {{ $unit->status === 'Working' ? 'status-working' : '' }}
                    {{ $unit->status === 'Under Maintenance' ? 'status-maintenance' : '' }}
                    {{ $unit->status === 'Decommissioned' ? 'status-decommissioned' : '' }}">
                    {{ $unit->status }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<script>
    document.querySelectorAll('.col-toggle').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            let colClass = 'col-' + this.dataset.col;
            document.querySelectorAll('.' + colClass).forEach(cell => {
                cell.classList.toggle('hidden-col', !this.checked);
            });
        });
    });
</script>

</body>
</html>
