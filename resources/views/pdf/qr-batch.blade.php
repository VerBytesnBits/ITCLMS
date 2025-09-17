<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>QR Codes</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .grid { display: flex; flex-wrap: wrap; }
        .qr-box {
            width: 25%;
            text-align: center;
            padding: 10px;
            box-sizing: border-box;
        }
        img { width: 100px; height: 100px; }
    </style>
</head>
<body>
    <h2>QR Codes Batch</h2>
    <div class="grid">
        @foreach ($qrs as $id => $qr)
            <div class="qr-box">
                <img src="data:image/png;base64,{{ $qr }}">
                <p>{{ $records[$id] ?? 'Unknown' }}</p>
            </div>
        @endforeach
    </div>
</body>
</html>
