<!DOCTYPE html>
<html>

<head>
    <style>
        @page {
            margin: 10mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        td {
            width: 33.33%;
            border: 1px solid #ccc;
            padding: 5px;
            text-align: center;
            vertical-align: middle;
            min-height: 90px;
            page-break-inside: avoid;
        }

        .description {
            font-weight: bold;
            font-size: 9px;
            margin-bottom: 3px;
        }

        .barcode img {
            max-width: 100%;
            max-height: 40px;
            object-fit: contain;
            display: block;
            margin: 0 auto;
        }

        .room {
            font-size: 8px;
            color: #555;
            margin-top: 2px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>

    <h2>Components Barcode</h2>

    <table>
        <tbody>
            @foreach ($items as $index => $item)
                @if ($index % 3 === 0)
                    <tr>
                @endif

                <td>
                    <div class="description">{{ $item['description'] }}</div>
                    <div class="barcode">
                        @if ($item['barcode'])
                            <img src="data:image/png;base64,{{ $item['barcode'] }}">
                        @else
                            <em>No barcode</em>
                        @endif
                    </div>
                    <div class="room">{{ $item['room'] }}</div>
                </td>

                @if ($index % 3 === 2)
                    </tr>
                @endif
            @endforeach

            {{-- Handle remaining cells if items are not multiple of 3 --}}
            @if ($items->count() % 3 !== 0)
                @for ($i = 0; $i < 3 - ($items->count() % 3); $i++)
                    <td></td>
                @endfor
                </tr>
            @endif
        </tbody>
    </table>

</body>

</html>
