<table>
    <thead>
        <tr>
            <th>Kode</th>
            <th>Nama Barang</th>
            <th>Suplier</th>
            <th>Member</th>
            <th>Qty</th>
            <th>Harga</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td>{{ $item->item->kode }}</td>
                <td>{{ $item->item->nama }}</td>
                <td>{{ $item->suplier->nama }}</td>
                <td>{{ $item->member->nama }}</td>
                <td>{{ $item->qty }}</td>
                <td>{{ number_format($item->harga) }}</td>
                <td>{{ number_format($item->total) }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="6"><strong>Jumlah Total</strong></td>
            <td>{{ number_format($data->sum('total')) }}</td>
        </tr>
    </tbody>
</table>