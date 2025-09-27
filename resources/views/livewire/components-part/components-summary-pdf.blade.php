<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Component Summary Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .header-table {
            width: 100%;
            margin-bottom: 10px;
            border: none;
        }

        .header-table td {
            text-align: center;
            vertical-align: middle;
            border: none;
        }

        .header-table .left,
        .header-table .right {
            width: 80px;
        }

        .header-table img {
            width: 80px;
            height: auto;
        }

        h2,
        h3,
        p {
            margin: 2px 0;
            padding: 0;
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

        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: flex-end;
            font-size: 12px;
        }
    </style>
</head>

<body>
    {{-- HEADER --}}
    <table class="header-table">
        <tr>
            <td class="left" style="text-align: left;">
                <img src="{{ storage_path('app/public/images/PIT.png') }}" alt="Logo" width="100">

            </td>
            <td class="center">
                <h2>Palompon Institute of Technology</h2>
                <p>College of Technology and Engineering</p>
                <h3>INFORMATION TECHNOLOGY DEPARTMENT</h3>
                <h2><strong>DETAILED COMPONENT INVENTORY</strong></h2>
            </td>
            <td class="right" style="text-align: right;">
                <img src="{{ storage_path('app/public/images/PIT-RIGHT.png') }}" alt="Logo" width="100">
            </td>
        </tr>
    </table>
    <table style="width: 100%; margin-bottom: 20px; border: none; border-collapse: collapse;">
        <tr>
            <!-- Left Side -->
            <td style="text-align: left; vertical-align: top; width: 50%; border: none;">
                <p>
                    <strong>DATE:</strong>
                    <span style="text-decoration: underline;">
                        {{ \Carbon\Carbon::now()->format('m/d/Y') }}
                    </span>
                </p>

                <p><strong>LABORATORY ROOM:</strong> LAB 3 Room 237</p>
            </td>

            <!-- Right Side -->
            <td style="text-align: right; vertical-align: top; width: 50%; border: none;">
                <p><strong>CONDUCTED BY:</strong> <span style="text-decoration: underline;">BENCHITO M. SURALTA</span>
                </p>
                <p>Lab. Technician</p>
                <p>Signature over printed name</p>
            </td>
        </tr>
    </table>
    {{-- SUMMARY TABLE --}}
    @foreach ($summary as $part => $items)
        <h3>{{ $part }}</h3>
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Available</th>
                    <th>In Use</th>
                    <th>Defective</th>
                    <th>Maintenance</th>
                    <th>Junk</th>
                    {{-- <th>Salvaged</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $row)
                    <tr>
                        <td>{{ $row['description'] }}</td>
                        <td>{{ $row['total'] }}</td>
                        <td>{{ $row['available'] }}</td>
                        <td>{{ $row['in_use'] }}</td>
                        <td>{{ $row['defective'] }}</td>
                        <td>{{ $row['maintenance'] }}</td>
                        <td>{{ $row['junk'] }}</td>
                        {{-- <td>{{ $row['salvage'] }}</td> --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

    <!-- Footer -->
    <table style="width: 100%; margin-top: 60px; border: none; border-collapse: collapse;">
        <tr>
            <!-- Confirmed By -->
            <td style="text-align: left; vertical-align: top; width: 20%; border: none;">
                <p><strong>CONFIRMED BY:</strong></p>
                <p>_____________________________</p>
                <span style="text-align: center;">
                    <p>Laboratory In-charge</p>
                    <p>IT Department</p>
                </span>
            </td>
            <td style="text-align: center; vertical-align: top; width: 50%; border: none;">
            </td>

            <!-- Noted By -->
            <td style="text-align: left; vertical-align: top; width: 20%; border: none;">
                <p><strong>NOTED BY:</strong></p>
                <p>_____________________________</p>
                <span style="text-align: center;">
                    <p>Chair</p>
                    <p>IT Department</p>
                </span>

            </td>
        </tr>
    </table>

</body>

</html>
