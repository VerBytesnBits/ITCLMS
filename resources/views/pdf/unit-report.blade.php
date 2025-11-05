<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 25mm 20mm 25mm 20mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            margin: 0;
            color: #222;
        }

        header {
            text-align: center;
            margin-bottom: 15px;
        }

        header h2 {
            margin: 0;
            font-size: 16px;
        }

        header h3 {
            margin: 0;
            font-size: 13px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
            vertical-align: middle;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        td {
            font-size: 11px;
        }

        .small-note {
            font-size: 10px;
            color: #555;
        }

        .peripheral-row {
            background: #fafafa;
            font-size: 10px;
            text-align: left;
        }

        .peripheral-row ul {
            margin: 5px 0 0 15px;
            padding: 0;
        }
    </style>
</head>

<body>

    <header>
        <h2>Palompon Institute of Technology</h2>
        <h3>System Unit Specifications</h3>
    </header>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>ID</th>
                @if ($includeComponents)
                    <th>CPU<br><span class="small-note">(model)</span></th>
                    <th>MBOARD<br><span class="small-note">(model)</span></th>
                    <th>RAM<br><span class="small-note">(type & capacity)</span></th>
                    <th>DRIVE<br><span class="small-note">(type & capacity)</span></th>
                    <th>CASING<br><span class="small-note">(model)</span></th>
                @endif
                @if ($includePeripherals)
                    <th>Monitor</th>
                    <th>Mouse</th>
                    <th>Keyboard</th>
                    <th>Other Peripherals</th>
                @endif
                @if ($includeComponents)
                    <th>STATUS<br><span class="small-note">(Operational, Needs Repair, etc.)</span></th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($units as $i => $unit)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $unit->name ?? '—' }}</td>

                    {{-- Components --}}
                    @if ($includeComponents)
                        <td>{{ optional($unit->components->firstWhere('part', 'CPU'))->model ?? '' }}</td>
                        <td>{{ optional($unit->components->firstWhere('part', 'Motherboard'))->model ?? '' }}</td>
                        @php $ram = $unit->components->firstWhere('part', 'RAM'); @endphp
                        <td>{{ $ram ? $ram->type . ' ' . $ram->capacity : '' }}</td>
                        @php $storage = $unit->components->firstWhere('part', 'Storage'); @endphp
                        <td>{{ $storage ? $storage->type . ' ' . $storage->capacity : '' }}</td>
                        <td>{{ optional($unit->components->firstWhere('part', 'Casing'))->model ?? '' }}</td>
                    @endif

                    {{-- Peripherals --}}
                    @if ($includePeripherals)
                        @php
                            $monitor = $unit->peripherals->firstWhere('type', 'Monitor');
                            $mouse = $unit->peripherals->firstWhere('type', 'Mouse');
                            $keyboard = $unit->peripherals->firstWhere('type', 'Keyboard');
                            $otherPeripherals = $unit->peripherals->reject(function ($p) {
                                return in_array($p->type, ['Monitor', 'Mouse', 'Keyboard']);
                            });
                        @endphp
                        <td>{{ $monitor ? $monitor->brand . ' ' . $monitor->model : '' }}</td>
                        <td>{{ $mouse ? $mouse->brand . ' ' . $mouse->model : '' }}</td>
                        <td>{{ $keyboard ? $keyboard->brand . ' ' . $keyboard->model : '' }}</td>
                        <td>
                            @if ($otherPeripherals->isNotEmpty())
                                <ul style="margin:0;padding-left:15px;">
                                    @foreach ($otherPeripherals as $peripheral)
                                        <li>
                                            {{ $peripheral->type ?? '—' }}
                                            {{ $peripheral->brand ? ' - ' . $peripheral->brand : '' }}
                                            {{ $peripheral->model ? ' (' . $peripheral->model . ')' : '' }}
                                            {{ $peripheral->serial_number ? ' | SN: ' . $peripheral->serial_number : '' }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </td>
                    @endif

                    {{-- Status --}}
                    @if ($includeComponents)
                        <td>{{ $unit->status ?? '' }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>


</body>

</html>
