<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        body {
            /* Ensure font supports PDF rendering (DejaVu Sans is common for Laravel PDF packages) */
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            margin: 0;
            color: #000;
        }

        /* ================= HEADER ================= */
        .header {
            width: 100%;
            border-bottom: 2px solid #000;
            margin-bottom: 15px;
        }

        .header td {
            vertical-align: middle;
        }

        .logo {
            width: 75px;
            height: 75px;
        }

        .school-text {
            text-align: center;
        }

        .school-name {
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 0.3px;
        }

        .college {
            font-size: 12px;
        }

        .department {
            font-size: 11px;
            font-weight: bold;
            margin-top: 2px;
        }

        /* ================= TITLE ================= */
        .title {
            text-align: center;
            margin: 15px 0 20px 0;
        }

        .title h1 {
            font-size: 16px;
            margin: 0;
            font-weight: bold;
            letter-spacing: 1px;
        }

        /* --- Info Section Styling (DATE, ROOM, CONDUCTED BY) --- */
        .info-header {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-bottom: 15px;
        }

        .info-header td {
            padding: 2px 0;
            vertical-align: top;
        }

        /* Left Column (DATE & LAB ROOM) */
        .info-header .left-col {
            width: 50%;
            text-align: left;
        }

        .info-header .left-col strong {
            border-bottom: 1px solid #000;
            /* Line under the value */
            padding-bottom: 2px;
            font-weight: normal;
            /* To make the underline look like a blank space */
        }

        /* Right Column (CONDUCTED BY) */
        .info-header .right-col {
            width: 50%;
            text-align: right;
            line-height: 1.4;
        }

        .info-header .right-col strong {
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
            font-weight: normal;
        }


        /* --- Main Inventory Table Styling --- */
        .inventory-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
            table-layout: fixed;
            /* Ensures column widths are respected */
        }

        .inventory-table th,
        .inventory-table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
            vertical-align: top;
            line-height: 1.2;
            font-size: 9px;
            /* Smaller font for specifications table */
        }

        .inventory-table thead th {
            background-color: #f2f2f2;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }

        /* Custom style for the specifications header row */
        .specs-header-row th {
            background-color: #e0e0e0 !important;
            font-weight: normal;
        }

        .specs-header-row th:first-child,
        .specs-header-row th:last-child {
            background-color: #f2f2f2 !important;
            /* Keep # and Status normal background */
        }

        .small-note {
            font-size: 8px;
            color: #444;
            display: block;
        }

        .footer-note {
            position: fixed;
            bottom: 10mm;
            right: 15mm;
            font-size: 9px;
        }


        /* ================= INFO SECTION ================= */
        .info-table {
            width: 100%;
            font-size: 11px;
            margin-bottom: 15px;
        }

        .info-table td {
            padding: 4px 0;
            vertical-align: top;
        }

        .line {
            display: inline-block;
            border-bottom: 1px solid #000;
            min-width: 220px;

        }

        .center {
            display: inline-block;
            text-align: center;

        }
    </style>
</head>

<body>


    <table class="header">
        <tr>
            <td style="width:20%;">
                <img src="{{ public_path('storage/images/PIT.png') }}" class="logo">
            </td>

            <td style="width:60%;" class="school-text">
                <div class="school-name">PALOMPON INSTITUTE OF TECHNOLOGY</div>
                <div class="college">College of Technology and Engineering</div>
                <div class="department">INFORMATION TECHNOLOGY DEPARTMENT</div>
            </td>

            <td style="width:20%; text-align:right;">
                <img src="{{ public_path('storage/images/PIT-RIGHT.png') }}" class="logo">
            </td>
        </tr>
    </table>

    <!-- ================= TITLE ================= -->
    <div class="title">
        <h1>SYSTEM UNIT INVENTORY REPORT</h1>
    </div>

    <table class="info-table">
        <tr>
            <!-- ROW 1 -->
            <td style="width:45%; text-align:left;">
                <strong>DATE:</strong>
                <span class="line">{{ $reportDate ?? '' }}</span>
            </td>

            <td style="width:45%;"></td>

            <td style="width:10%; text-align:left;">
                <strong>CONDUCTED BY:</strong>
                <span class="line center">{{ $conductedByName ?? '' }}</span>
            </td>
        </tr>

        <tr>
            <!-- ROW 2 -->
            <td style="text-align:left;">
                <strong>LABORATORY ROOM:</strong>
                <span class="line">
                    @if ($selectedRoom)
                        {{ \App\Models\Room::find($selectedRoom)?->name }}
                    @else
                        All Rooms
                    @endif
                </span>
            </td>

            <td></td>

            <td style="text-align:center; font-size:10px; line-height:0%;">
                {{ ucfirst(Str::lower($conductedByRole ?? 'Laboratory In-Charge')) }}
            </td>

        </tr>

        <tr>
            <!-- ROW 3 -->
            <td></td>
            <td></td>
            <td style="text-align:center; font-size:10px; line-height: 0%;">
                Signature over printed name
            </td>
        </tr>
    </table>




    @php
        $groupedUnits = $units->groupBy(fn($u) => $u->room?->name ?? 'Unassigned');
        $showComponents = $includeComponents ?? true; // Default to true if not set
        $showPeripherals = $includePeripherals ?? false; // Default to false if not set

        $componentPartsToShow = !empty($selectedComponentParts)
            ? $selectedComponentParts
            : ['CPU', 'Motherboard', 'RAM', 'Storage', 'Casing'];

        $peripheralTypesToShow = !empty($selectedPeripheralTypes)
            ? $selectedPeripheralTypes
            : ['Monitor', 'Mouse', 'Keyboard'];
    @endphp

    @foreach ($groupedUnits as $roomName => $roomUnits)
        <h4 class="room-title">Lab. Room: {{ $roomName }}</h4>

        <table class="inventory-table">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 5%;">#</th>
                    <th rowspan="2" style="width: 10%;">ID</th>

                    @if ($showComponents)
                        <th colspan="{{ count($componentPartsToShow) }}" class="specs-header-row">SPECIFICATIONS</th>
                    @endif

                    @if ($showPeripherals)
                        <th colspan="{{ count($peripheralTypesToShow) }}" class="specs-header-row">PERIPHERALS</th>
                    @endif

                    @if ($showComponents)
                        <th rowspan="2" style="width: 10%;">STATUS (Operational, Needs Repair, Non-operational)</th>
                    @endif
                </tr>

                <tr class="specs-header-row">
                    @if ($showComponents)
                        @foreach ($componentPartsToShow as $part)
                            <th>{{ strtoupper($part) }}
                                @if ($part === 'RAM' || $part === 'Storage')
                                    <br><span style="font-weight: normal;">(type & capacity)</span>
                                @else
                                    <br><span style="font-weight: normal;">(model)</span>
                                @endif
                            </th>
                        @endforeach
                    @endif

                    @if ($showPeripherals)
                        @foreach ($peripheralTypesToShow as $type)
                            <th>{{ ucfirst($type) }}</th>
                        @endforeach
                    @endif
                </tr>
            </thead>

            <tbody>
                @foreach ($roomUnits as $i => $unit)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $unit->name ?? '—' }}</td>

                        @if ($showComponents)
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

                        @if ($showPeripherals)
                            @foreach ($peripheralTypesToShow as $type)
                                @php
                                    $periph = $unit->peripherals->firstWhere('type', $type);
                                @endphp
                                <td>
                                    @if ($periph)
                                        {{ $periph->brand ?? '' }} {{ $periph->model ?? '' }}
                                        @if ($periph->serial_number)
                                            <span class="small-note">SN: {{ $periph->serial_number }}</span>
                                        @endif
                                    @endif
                                </td>
                            @endforeach
                        @endif

                        @if ($showComponents)
                            @php
                                $status = $unit->status ?? '';
                                $bgColor = match ($status) {
                                    'Operational' => '#d4edda', // soft green
                                    'Needs Repair' => '#fff3cd', // soft yellow
                                    'Non-operational' => '#f8d7da', // soft red
                                    default => '#ffffff', // white / no color
                                };
                            @endphp
                            <td style="background-color: {{ $bgColor }}; text-align:center;">
                                {{ $status }}
                            </td>
                        @endif

                    </tr>
                @endforeach
            </tbody>
        </table>

        @if (!$loop->last)
            <div style="page-break-after: always;"></div>
        @endif
    @endforeach

    {{-- <div class="footer-note">

    </div> --}}
    <!-- ================= CONFIRMATION PAGE ================= -->
    <div style="page-break-before: always;"></div>

    <table class="signatory-table" style="width:100%; margin-top:30px;">
        <!-- ROW 1: CONFIRMED BY -->
        <tr>
            <td style="width:100%; text-align:left; padding-bottom:40px;">
                <strong>CONFIRMED BY:</strong><br><br>

                <span class="signature-line" style="display:inline-block; width:250px; border-bottom:1px solid #000;">
                    <strong>{{ $labInCharge?->name ?? ' ' }}</strong> </span><br>


                <span style="font-size:10px;">
                    Laboratory In-Charge
                </span><br>

                Information Technology Department
            </td>
        </tr>

        <!-- ROW 2: NOTED BY -->
        <tr>
            <td style="width:100%; text-align:left; padding-top:20px;">
                <strong>NOTED BY:</strong><br><br>

                <span class="signature-line" style="display:inline-block; width:250px; border-bottom:1px solid #000;">
                    <strong>{{ $chairman?->name ?? ' ' }}</strong> </span><br>
                <span style="font-size:10px;">
                    Chairman
                </span><br>

                Information Technology Department
            </td>
        </tr>
    </table>

    <script type="text/php">
        if (isset($pdf)) {
            $pdf->page_script('
                $text = __("System Unit Inventory :pageNum/:pageCount", ["pageNum" => $PAGE_NUM, "pageCount" => $PAGE_COUNT]);
                $font = null;
                $size = 7;
                $color = array(0,0,0);
                $word_space = 0.0;  //  default
                $char_space = 0.0;  //  default
                $angle = 0.0;   //  default
 
                // Compute text width to center correctly
                $textWidth = $fontMetrics->getTextWidth($text, $font, $size);
 
                $x = ($pdf->get_width() - $textWidth) / 1.1;
                $y = $pdf->get_height() - 35;
 
                $pdf->text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
            ');
        }
    </script>
</body>

</html>
