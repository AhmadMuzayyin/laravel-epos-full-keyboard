<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ url('assets/css/bootstrap.css') }}">
    <style>
        @page {
            size: 58mm 210mm;
            margin: 0;
        }

        body {
            max-width: 58mm;
            max-height: 210mm;
            margin: 1;
            font-size: 10pt;
        }
    </style>

</head>

<body class="font-monospace">
    @csrf
    <div class="container" id="printable">
        <p class="text-center" style="margin-bottom: -4px">"{{ Str::upper($setting['nama_toko']) }}"</p>
        <p class="text-center" style="font-size: 80%; margin-bottom: -5px">{{ Str::upper($setting['alamat_toko']) }}</p>
        ---------------------------
        <p id="kode_transaksi" style="margin-bottom: -5px; font-size: 80%; text-align: center">
            {{ $status == true ? $data_print->nomor_faktur : '' }}
        </p>
        <p class="text-uppercase" style="margin-bottom: -5px; margin-top: 3px; font-size: 80%; text-align: center">
            Kasir:
            {{ Auth::user()->name }}</p>
        ---------------------------
        @foreach ($data_print->penjualan as $value)
            <div class="row" style="font-size: 80%; ">
                <div class="col">
                    <p style="margin-bottom: -4px">
                        {{ $value->qty . 'x' . $value->item->nama }}
                    </p>
                </div>
                <div class="col text-end">
                    <p style="margin-bottom: -4px">{{ number_format($value->harga) }}</p>
                </div>
            </div>
        @endforeach
        ---------------------------
        <div class="row" style="font-size: 80%;">
            <div class="col">
                <p style="margin-bottom: -4px">Diskon</p>
                <p style="margin-bottom: -4px">Tagihan</p>
                <p style="margin-bottom: -4px">Tunai</p>
                <p style="margin-bottom: -4px">Kembalian</p>
            </div>
            <div class="col">
                <p style="margin-bottom: -4px">:</p>
                <p style="margin-bottom: -4px">:</p>
                <p style="margin-bottom: -4px">:</p>
                <p style="margin-bottom: -4px">:</p>
            </div>
            <div class="col text-end">
                <p style="margin-bottom: -4px">{{ $status == true ? $data_print->diskon : '' }}%</p>
                <p style="margin-bottom: -4px">{{ $status == true ? number_format($data_print->total_tagihan) : '' }}
                </p>
                <p style="margin-bottom: -4px">{{ $status == true ? number_format($data_print->bayar) : '' }}</p>
                <p style="margin-bottom: -4px">{{ $status == true ? number_format($data_print->kembalian) : '' }}</p>
            </div>
        </div>
        ---------------------------
        <p style="margin-bottom: -5px;text-align: center; font-size: 80%;">
            KRITIK DAN SARAN HUB: <br>
            {{ $setting['kontak'] }}
        </p>
    </div>


    <script src="{{ url('/assets/jQuery/jquery-3.7.0.min.js') }}"></script>
    <script src="{{ url('/assets/print.js') }}"></script>
    @if ($status == true)
        <script>
            // $(document).ready(function() {
            //     $.print('#printable')
            // })
            window.print()
            var kode_transaksi = $('#kode_transaksi').text()
            var _token = $("input[name='_token']").val()
            $.ajax({
                url: "{{ route('updatePrint') }}",
                method: "POST",
                data: {
                    kode_transaksi: kode_transaksi,
                    _token: _token
                },
                success: (res) => {
                    if (res.status === true) {
                        setInterval(() => {
                            window.location.href = "{{ route('penjualan.index') }}"
                        }, 1000);
                    }
                }
            })
        </script>
    @else
        <script>
            setInterval(() => {
                window.location.href = "{{ route('penjualan.index') }}"
            }, 5000);
        </script>
    @endif
</body>

</html>
