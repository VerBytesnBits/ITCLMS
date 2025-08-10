<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>System Units Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header h1 {
            font-size: 16px;
            margin: 0;
        }

        .header h2 {
            font-size: 14px;
            margin: 0;
            font-weight: normal;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table, th, td {
            border: 1px solid #000;
        }

        th {
            background-color: #f0f0f0;
            text-align: center;
            padding: 5px;
            font-size: 11px;
        }

        td {
            padding: 5px;
            text-align: center;
            font-size: 11px;
        }

        .status-working {
            background-color: #d4edda;
        }
        .status-maintenance {
            background-color: #fff3cd;
        }
        .status-decommissioned {
            background-color: #e2e3e5;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>LABORATORY INVENTORY SYSTEM</h1>
    <h2>System Units Report</h2>
    <small>{{ now()->format('F d, Y') }}</small>
</div>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>CPU<br><small>(model)</small></th>
            <th>MBOARD<br><small>(model)</small></th>
            <th>RAM<br><small>(type & capacity)</small></th>
            <th>DRIVE<br><small>(type & capacity)</small></th>
            <th>CASING<br><small>(model)</small></th>
            <th>STATUS</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($units as $unit)
            <tr>
                <td>{{ $unit->id }}</td>
                <td>
                    {{ $unit->processor
                        ? "{$unit->processor->brand} {$unit->processor->model} {$unit->processor->base_clock}GHz" .
                            ($unit->processor->boost_clock ? " / {$unit->processor->boost_clock}GHz" : '')
                        : 'N/A' }}
                </td>
                <td>
                    {{ $unit->motherboard ? $unit->motherboard->brand . ' ' . $unit->motherboard->model : 'N/A' }}
                </td>
                <td>
                    {{ $unit->memory ? $unit->memory->type . ' ' . $unit->memory->capacity . 'GB' : 'N/A' }}
                </td>
                <td>
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
                <td>
                    {{ $unit->computerCase ? $unit->computerCase->brand . ' ' . $unit->computerCase->model : 'N/A' }}
                </td>
                <td
                    class="
                        {{ $unit->status === 'Working' ? 'status-working' : '' }}
                        {{ $unit->status === 'Under Maintenance' ? 'status-maintenance' : '' }}
                        {{ $unit->status === 'Decommissioned' ? 'status-decommissioned' : '' }}
                    ">
                    {{ $unit->status }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
