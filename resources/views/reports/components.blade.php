<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Component Parts Inventory Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        h2, p { margin: 2px 0; padding: 0; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
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
        .red { background: #e74c3c; }
        .yellow { background: #f1c40f; color: #000; }
        .green { background: #2ecc71; }
    </style>
</head>
<body>

<h1 style="text-align:center;">Component Parts Inventory Report</h1>
<p style="text-align:center;">Date: {{ \Carbon\Carbon::now()->format('m/d/Y') }}</p>
<hr>

@foreach ($grouped as $roomName => $items)
    <h2>{{ $roomName }}</h2>

    <table>
        <thead>
            <tr>
                <th>Part</th>
                <th>Description</th>
                <th>Total</th>
                <th>Available</th>
                <th>In Use</th>
                <th>Defective</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td>{{ $item['part'] ?? 'N/A' }}</td>
                    <td style="text-align:left;">{{ $item['description'] }}</td>
                    <td>{{ $item['total'] }}</td>
                    <td>
                        {{ $item['available'] }}
                        @if($item['available'] == 0)
                            <span class="badge red">0</span>
                        @endif
                    </td>
                    <td>{{ $item['in_use'] }}</td>
                    <td>
                        {{ $item['defective'] }}
                        @if($item['defective'] > 0)
                            <span class="badge yellow">Check</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endforeach

</body>
</html>
