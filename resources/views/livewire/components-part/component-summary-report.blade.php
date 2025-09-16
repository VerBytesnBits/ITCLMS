<div id="printable-area">
    @foreach($summary as $part => $items)
        <h3 class="font-bold">{{ $part }}</h3>
        <table class="w-full border-collapse mb-4">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Total</th>
                    <th>Available</th>
                    <th>In Use</th>
                    <th>Defective</th>
                    <th>Maintenance</th>
                    <th>Junk</th>
                    <th>Salvage</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $item['description'] }}</td>
                        <td>{{ $item['total'] }}</td>
                        <td>{{ $item['available'] }}</td>
                        <td>{{ $item['in_use'] }}</td>
                        <td>{{ $item['defective'] }}</td>
                        <td>{{ $item['maintenance'] }}</td>
                        <td>{{ $item['junk'] }}</td>
                        <td>{{ $item['salvage'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
</div>
