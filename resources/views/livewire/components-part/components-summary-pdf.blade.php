<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Component Inventory Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th,
        td {

            padding: 6px;
            text-align: center;
        }

        th {
            background: #f2f2f2;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 10px;
            font-weight: bold;
            border-radius: 4px;
            color: #fff;
        }

        .red {
            background: #e74c3c;
        }

        .yellow {
            background: #f1c40f;
            color: #000;
        }

        .green {
            background: #2ecc71;
        }

        h2,
        h3,
        p {
            margin: 2px 0;
            padding: 0;
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

            <td style="width:20%;">
                <img src="{{ public_path('storage/images/PIT-RIGHT.png') }}" class="logo">
            </td>
        </tr>
    </table>
    <h2 style="text-align: center;">Component Inventory</h2>
    <table width="100%" style="margin-top:60px;">
        <tr>
            <td style="text-align:left;">
                <strong>Date:</strong> {{ \Carbon\Carbon::now()->format('m/d/Y') }}
            </td>

            <td style="text-align:right;">
                <p style="display:inline-block; width:300px; text-align:left;">Conducted by:</p>
                <div style="display:inline-block; width:300px; text-align:center;">

                    <div style="border-bottom:1px solid #000; padding-bottom:4px;">
                        {{ $conductedByName }}
                    </div>
                    <div style="font-size:12px;">Signature over printed name</div>
                </div>
            </td>
        </tr>
    </table>

    @php
    // Sort rooms: Unassigned first, then alphabetical
    $sortedGrouped = $grouped->sortBy(function ($items, $roomName) {
    return $roomName === 'Unassigned' ? '' : $roomName;
    });
    @endphp

    @foreach ($sortedGrouped as $roomName => $items)
    <h3>{{ $roomName }}</h3>
    <table>
        <thead>
            <tr>
                <th style="border: 1px solid #000;">Description</th>
                <th style="border: 1px solid #000;">Total</th>
                <th style="border: 1px solid #000;">Available</th>
                <th style="border: 1px solid #000;">In Use</th>
                <th style="border: 1px solid #000;">Defective</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $row)
            <tr>
                <td style="text-align:left; border: 1px solid #000;">
                    {{ $row['description'] }}
                    @if ($row['available'] == 0)
                    <span class="badge red">Out of stock</span>
                    @elseif ($row['available'] < 3) <span class="badge yellow">Low stock</span>
                        @else
                        <span class="badge green">In stock</span>
                        @endif
                </td>
                <td style="border: 1px solid #000;">{{ $row['total'] }}</td>
                <td style="border: 1px solid #000;">{{ $row['available'] }}</td>
                <td style="border: 1px solid #000;">{{ $row['in_use'] }}</td>
                <td style="border: 1px solid #000;">{{ $row['defective'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endforeach
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

</body>

</html>
