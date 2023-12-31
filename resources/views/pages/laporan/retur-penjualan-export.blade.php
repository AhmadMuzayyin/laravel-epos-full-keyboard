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
                <td>{{ $item->penjualan->kode }}</td>
                <td>{{ $item->penjualan->barang }}</td>
                <td>{{ $item->penjualan->suplier }}</td>
                <td>{{ $item->penjualan->member }}</td>
                <td>{{ $item->penjualan->qty }}</td>
                <td>{{ number_format($item->penjualan->harga) }}</td>
                <td>{{ number_format($item->penjualan->total) }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="6"><strong>Jumlah Total</strong></td>
            <td>{{ number_format($total) }}</td>
        </tr>
    </tbody>
</table>