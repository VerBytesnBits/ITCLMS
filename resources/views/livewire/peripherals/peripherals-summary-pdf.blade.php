<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Peripheral Inventory Report</title>
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
    <h2 style="text-align: center;">Peripheral Inventory</h2>
    <p><strong>Date:</strong> {{ \Carbon\Carbon::now()->format('m/d/Y') }}</p>

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
                    <th>Description</th>
                    <th>Total</th>
                    <th>Available</th>
                    <th>In Use</th>
                    <th>Defective</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $row)
                    <tr>
                        <td style="text-align:left;">
                            {{ $row['description'] }}
                            @if ($row['available'] == 0)
                                <span class="badge red">Out of stock</span>
                            @elseif ($row['available'] < 3)
                                <span class="badge yellow">Low stock</span>
                            @else
                                <span class="badge green">In stock</span>
                            @endif
                        </td>
                        <td>{{ $row['total'] }}</td>
                        <td>{{ $row['available'] }}</td>
                        <td>{{ $row['in_use'] }}</td>
                        <td>{{ $row['defective'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

</body>

</html>
