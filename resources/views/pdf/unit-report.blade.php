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

        header p {
            margin: 2px 0 0;
            font-size: 12px;
            color: #333;
        }

        h4.room-title {
            background: #eaeaea;
            padding: 6px 10px;
            margin-top: 25px;
            margin-bottom: 5px;
            font-size: 13px;
            font-weight: bold;
            border: 1px solid #ccc;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
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

       
        @if (isset($selectedRoom) && $selectedRoom)
            <p>
                Room:
                <strong>
                    {{ \App\Models\Room::find($selectedRoom)?->name ?? 'Unknown Room' }}
                </strong>
            </p>
        @else
            <p><em>All Rooms</em></p>
        @endif
    </header>

   
    @php
        $groupedUnits = $units->groupBy(fn($u) => $u->room?->name ?? 'Unassigned');
    @endphp

    @foreach ($groupedUnits as $roomName => $roomUnits)
        <h4 class="room-title">Room: {{ $roomName }}</h4>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>System Unit</th>

                   
                    @if ($includeComponents)
                        @php
                            // Fallback: if no parts selected, show these defaults
                            $componentPartsToShow = !empty($selectedComponentParts)
                                ? $selectedComponentParts
                                : ['CPU', 'Motherboard', 'RAM', 'Storage', 'Casing'];
                        @endphp

                        @foreach ($componentPartsToShow as $part)
                            <th>{{ strtoupper($part) }}</th>
                        @endforeach
                    @endif

                   
                    @if ($includePeripherals)
                        @php
                            // Fallback: if no types selected, show defaults
                            $peripheralTypesToShow = !empty($selectedPeripheralTypes)
                                ? $selectedPeripheralTypes
                                : ['Monitor', 'Mouse', 'Keyboard', 'Printer', 'Speaker'];
                        @endphp

                        @foreach ($peripheralTypesToShow as $type)
                            <th>{{ ucfirst($type) }}</th>
                        @endforeach
                    @endif

                   
                    @if ($includeComponents)
                        <th>Status</th>
                    @endif
                </tr>
            </thead>

            <tbody>
                @foreach ($roomUnits as $i => $unit)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $unit->name ?? '—' }}</td>

                        
                        @if ($includeComponents)
                            @foreach ($componentPartsToShow as $part)
                                @php
                                    $component = $unit->components->firstWhere('part', $part);
                                @endphp
                                <td>
                                    @if ($component)
                                        @if (in_array($part, ['RAM', 'Storage']))
                                            {{ $component->type ?? '' }}
                                            {{ $component->capacity ? ' ' . $component->capacity : '' }}
                                        @else
                                            {{ $component->brand ?? '' }} {{ $component->model ?? '' }}
                                        @endif
                                    @endif
                                </td>
                            @endforeach
                        @endif

                       
                        @if ($includePeripherals)
                            @foreach ($peripheralTypesToShow as $type)
                                @php
                                    $periph = $unit->peripherals->firstWhere('type', $type);
                                @endphp
                                <td>
                                    @if ($periph)
                                        {{ $periph->brand ?? '' }} {{ $periph->model ?? '' }}
                                        @if ($periph->serial_number)
                                            <br><span class="small-note">SN: {{ $periph->serial_number }}</span>
                                        @endif
                                    @endif
                                </td>
                            @endforeach
                        @endif

                       
                        @if ($includeComponents)
                            <td>{{ $unit->status ?? '' }}</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

</body>

</html>
