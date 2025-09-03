<div class="p-6">
    <h2 class="text-2xl font-semibold mb-4">Tracking Details</h2>

    <div class="border p-4 rounded space-y-2">
        <p><strong>Type:</strong> {{ ucfirst($type) }}</p>
        <p><strong>Serial Number:</strong> {{ $item->serial_number }}</p>

        @if($type === 'unit')
            <p><strong>Room:</strong> {{ $item->room->name ?? 'N/A' }}</p>
        @elseif($type === 'component')
            <p><strong>Part Name:</strong> {{ $item->brand }}</p>
        @elseif($type === 'peripheral')
            <p><strong>Peripheral Type:</strong> {{ $item->peripheral_type }}</p>
        @endif

        <p><strong>Created At:</strong> {{ $item->created_at->format('Y-m-d H:i') }}</p>
        <p><strong>Updated At:</strong> {{ $item->updated_at->format('Y-m-d H:i') }}</p>
    </div>
</div>
