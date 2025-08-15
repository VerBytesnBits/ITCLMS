<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>System Units Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #000; }
        .header { text-align: center; margin-bottom: 12px; }
        .header h1 { font-size: 16px; margin: 0; }
        .header h2 { font-size: 14px; margin: 0; font-weight: normal; }
        .room-title { margin-top: 18px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; page-break-inside: avoid; }
        table, th, td { border: 1px solid #000; }
        th { background-color: #f0f0f0; text-align: center; padding: 5px; font-size: 11px; }
        td { vertical-align: top; padding: 5px; font-size: 11px; }
        .part-block { margin-bottom: 6px; }
        .kv { line-height: 1.2; }
        .muted { color: #555; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LABORATORY INVENTORY SYSTEM</h1>
        <h2>System Units Report</h2>
        <small>{{ now()->format('F d, Y') }}</small>
    </div>

    @foreach ($rooms as $room)
        <div class="room-title">Room: {{ $room['room_name'] }}</div>

        <table>
            <thead>
                <tr>
                    <th style="width: 70px;">Unit ID</th>
                     <th style="width: 70px;">Unit Name</th>
                    @foreach ($selectedKeys as $key)
                        @php
                            // Prefer labels from config; fallback to the first unit's label if present
                            $label = $partsConfig[$key]['label'] ?? ucfirst($key);
                            $sub   = $partsConfig[$key]['sub']   ?? '';
                        @endphp
                        <th>
                            {{ $label }}
                            @if (!empty($sub))
                                <br><small class="muted">{{ $sub }}</small>
                            @endif
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse ($room['units'] as $unit)
                    <tr>
                        <td style="text-align:center;">{{ $unit['unit_id'] }}</td>
                        <td style="text-align:center;">{{ $unit['unit_name'] }}</td>

                        @foreach ($selectedKeys as $key)
                            @php
                                $part = $unit['parts'][$key] ?? null;
                                $detailsList = $part['details'] ?? [];
                            @endphp

                            <td>
                                @if (empty($detailsList))
                                    <span class="muted">—</span>
                                @else
                                    @foreach ($detailsList as $detail)
                                        <div class="part-block">
                                            @foreach ($detail as $k => $v)
                                                @if (!is_null($v) && $v !== '' && $v !== 'N/A')
                                                    <div class="kv"><strong>{{ $k }}:</strong> {{ $v }}</div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endforeach
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ 1 + count($selectedKeys) }}" style="text-align:center;">
                            <em class="muted">No units in this room.</em>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endforeach
</body>
</html>
