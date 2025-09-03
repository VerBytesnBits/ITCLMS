<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>QR Codes Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .container { display: flex; flex-wrap: wrap; }
        .qr-card {
            width: 25%; /* 4 per row */
            padding: 10px;
            box-sizing: border-box;
            text-align: center;
            page-break-inside: avoid;
        }
        .qr-card img { width: 100px; height: 100px; }
        .qr-title { margin: 5px 0 0; font-weight: bold; }
        .qr-subtitle { margin: 0; font-size: 10px; color: #555; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">QR Codes Report</h2>

    <div class="container">
        @foreach($qrs as $qr)
            @php
                $qrRaw = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(100)
                    ->generate(route('tracking.show', [
                        'type' => strtolower(class_basename($qr->item_type)),
                        'serial' => $qr->item->serial_number ?? '',
                    ]));

                $qrCode = base64_encode(mb_convert_encoding($qrRaw, 'UTF-8', 'UTF-8'));
            @endphp
            <div class="qr-card">
                <img src="data:image/png;base64,{!! $qrCode !!}">
                <p class="qr-title">{{ class_basename($qr->item_type) }}</p>
                <p class="qr-subtitle">{{ $qr->item->serial_number ?? '-' }}</p>
            </div>
        @endforeach
    </div>
</body>
</html>
