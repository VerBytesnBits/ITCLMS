<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Components Inventory Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th,
        td {
            border: 1px solid #000;
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
