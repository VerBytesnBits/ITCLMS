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

        .header-table {
            width: 100%;
            text-align: center;
            margin-bottom: 5px;
        }

        .header-table td {
            vertical-align: middle;
        }

        .header-logo {
            width: 70px;
            height: 70px;
            object-fit: contain;
        }

        .info-table {
            width: 60%;
            margin: 0 auto 20px auto;
            border-collapse: collapse;
        }

        .info-table td {
            border: 1px solid #000;
            padding: 6px 10px;
            font-size: 11px;
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
    </style>
</head>

<body>

    <!-- === LOGO HEADER === -->
    <table class="header-table">
        <tr>
            <td style="width: 80px;">
                <img src="{{ public_path('storage/images/PIT.png') }}" class="header-logo">


            </td>

            <td>
                <h2 style="margin:0; font-size:16px;">Palompon Institute of Technology</h2>
                <h3 style="margin:0; font-size:13px; color:#555;">System Unit Specifications</h3>

                @if (isset($selectedRoom) && $selectedRoom)
                    <p style="margin:2px 0 0; font-size:12px;">
                        Room:
                        <strong>
                            {{ \App\Models\Room::find($selectedRoom)?->name ?? 'Unknown Room' }}
                        </strong>
                    </p>
                @else
                    <p style="margin:2px 0 0; font-size:12px;"><em>All Rooms</em></p>
                @endif
            </td>

            <td style="width: 80px;">
                <img src="{{ public_path('storage/images/PIT-right.png') }}" class="header-logo">
            </td>
        </tr>
    </table>

    <!-- === DATE & CONDUCTED BY TABLE === -->
    <table class="info-table">
        <tr>
            <td><strong>Date:</strong></td>
            <td>{{ $date ?? now()->format('F d, Y') }}</td>
        </tr>
        <tr>
            <td><strong>Conducted By:</strong></td>
            <td>{{ $conductedBy ?? '—' }}</td>
        </tr>
    </table>


    <!-- === GROUPED SYSTEM UNITS === -->
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
