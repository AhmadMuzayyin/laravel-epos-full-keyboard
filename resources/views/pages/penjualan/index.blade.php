@extends('app')
@php
    $theme = session()->get('theme');
@endphp
@section('contentHead')
    <a href="{{ route('dashboard') }}" role="button" class="btn btn-outline-{{ $theme == 'dark' ? 'light' : 'dark' }}"><i
            class="bi bi-box-arrow-left"></i> Esc -
        Kembali</a>
@endsection
@section('home')
    <div>
        <div class="row mb-2">
            <div class="col-10">
                <div class="card">
                    <div class="card-body">
                        <p>Tagihan</p>
                        <div class="d-flex">
                            <h1>Rp. </h1>
                            <h1 class="font-bold" id="totalPembayaran">
                                {{ count($penjualan) > 0 ? number_format($penjualan[0]['total_pembayaran']) : 0 }}</h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body text-center">
                        <p>Jumlah</p>
                        <h1 class="font-bold" id="banyak">
                            {{ count($penjualan) > 0 ? $penjualan[0]['total_penjualan'] : 0 }}</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-8">
                <div class="card" style="height: 30rem">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <input type="search" name="kode" id="kode" class="form-control form-control-sm" placeholder="Kode barang">
                            </div>
                            <div class="col text-end">
                            </div>
                        </div>
                        <div class="table-responsive">
                            @csrf
                            <table class="table table-hover" id="table">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Item</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                {{-- <tbody>
                                    @if (count($penjualan) > 0)
                                        @foreach ($penjualan[0]['data'] as $item)
                                            <tr>
                                                <input type="hidden" name="id" value="{{ $item->id }}">
                                                <td>{{ $item->item->kode }}</td>
                                                <td>{{ $item->item->nama }}</td>
                                                <td>{{ $item->item->harga_jual }}</td>
                                                <td class="qty">{{ $item->qty }}</td>
                                                <td>{{ $item->total }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody> --}}
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card mt-2">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-sm-7">
                                <label for="faktur" class="mb-2">No Faktur</label>
                                <input type="text" class="form-control" placeholder="Faktur" aria-label="Faktur"
                                    name="faktur" readonly
                                    value="{{ count($penjualan) > 0 ? $penjualan[0]['no_faktur'] : '' }}">
                            </div>
                            <div class="col-sm">
                                <label for="faktur" class="mb-2">Kasir</label>
                                <input type="text" class="form-control" placeholder="Kasir" aria-label="Kasir" readonly
                                    value="{{ auth()->user()->name }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                {{-- card kanan --}}
                <div class="card" style="height: 35rem">
                    <div class="card-body">
                        <div class="row mb-5">
                            <div class="col">
                            </div>
                            <div class="col">
                                <select class="form-select" aria-label="Default select example" name="member"
                                    id="member">
                                    @foreach ($members as $member)
                                        <option value="{{ $member['diskon'] }}">{{ $member['nama'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label for="diskon" class="col-form-label">Diskon</label>
                                <input type="text" class="form-control  text-end" id="diskon" name="diskon" placeholder="Diskon">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="bayar" class="col-form-label">Bayar</label>
                                <input type="text" class="form-control  text-end" width="50px" id="bayar" placeholder="Bayar">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="kembali" class="col-form-label">Kembali</label>
                                <input type="text" class="form-control  text-end" id="kembali" readonly placeholder="Kembalian">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <div class="col text-end">
                                    {{-- <a href="{{ route('print_nota') }}"
                                        class="btn btn-outline-{{ $theme == 'dark' ? 'light' : 'dark' }}"
                                        id="printFaktur">Print</a> --}}
                                        <button role="button" class="btn btn-outline-{{ $theme == 'dark' ? 'light' : 'dark' }} mt-2"
                                        id="penjualanDelete"><i class="bi bi-trash text-danger"></i>
                                        Delete -
                                        Hapus</button>
                                        <button class="btn btn-outline-{{ $theme == 'dark' ? 'light' : 'dark' }} my-3" role="button"><i class="bi bi-trash text-danger"></i>Ctrl+Del - Hapus semua</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            var table = $('#table').DataTable({
                bFilter: false,
                searching: false,
                paging: false,
                info: false,
                ordering: false,
                lengthChange: false,
                language: {
                    emptyTable: "Tidak ada belanjaan",
                },
                dom: 'rt',
                responsive: false,
                serverSide: true,
                select: 'single',
                ajax: "{{ route('penjualan.index') }}",
                columns: [{
                        data: 'kode_transaksi',
                        name: 'kode'
                    },
                    {
                        data: 'item.nama',
                        name: 'nama',
                    },
                    {
                        data: 'harga',
                        name: 'harga',
                    },
                    {
                        data: 'qty',
                        name: 'qty',
                        className: 'qty'
                    },
                    {
                        data: 'total',
                        name: 'total',
                    },
                ]
            })
            // input barang belanjaan dan pembayaran
            $(document).keydown(function(event) {
                if (event.key === 'Escape') {
                    event.preventDefault()
                    window.location.href = "{{ route('dashboard') }}"
                }
                if (event.ctrlKey && event.keyCode === 46) {
                    if (table.data().length > 0) {
                        $.ajax({
                            url: "{{ route('penjualan.destroyAll') }}",
                            method: "POST",
                            data: {
                                _token: $("input[name='_token']").val()
                            },
                            success: (res) => {
                                if (res.status == true) {
                                    table.draw()
                                    $('#totalPembayaran').text('0')
                                    $('#banyak').text('0')
                                    $('#kode').focus()
                                } else {
                                    alert(res.msg)
                                }
                            }
                        })
                    }
                }
                if (event.keyCode === 13) {
                    var currentInput = $(':focus');
                    if (currentInput.attr('name') === 'kode') {
                        var kodeValue = currentInput.val();
                        // untuk input diskon
                        if (kodeValue === '') {
                            var diskonInput = $('#diskon');
                            diskonInput.focus();
                            $(document).keydown(function(e) {
                                if (e.keyCode === 13) {
                                    if (diskonInput.val() === '') {
                                        diskonInput.val(0)
                                    } else {
                                        // untuk input pembayaran
                                        var inputBayar = $('#bayar')
                                        inputBayar.focus()
                                        inputBayar.keydown(function(e) {
                                            if (e.keyCode === 13) {
                                                if (inputBayar.val() === '') {
                                                    console.log(inputBayar.val());
                                                } else {
                                                    var strPembayaran = $(
                                                        '#totalPembayaran').text()
                                                    var intPembayaran = parseInt(
                                                        strPembayaran.replace(
                                                            /,/g, '').replace(/\./g, '')
                                                    )
                                                    var strBanyak = $('#banyak').text()
                                                    var intBanyak = parseInt(strBanyak
                                                        .replace(/,/g, '')
                                                        .replace(/\./g, ''))

                                                    var result = hitungPembayaran(
                                                        intPembayaran,
                                                        diskonInput.val(), inputBayar
                                                        .val())
                                                    if (parseInt(inputBayar.val()) >=
                                                        parseInt(
                                                            intPembayaran)) {
                                                        $('#kembali').val(formatRupiah(
                                                            result
                                                            .kembalian))
                                                    }
                                                }
                                            }
                                        })
                                        $('#bayar').on('keyup', function() {
                                            var strPembayaran = $('#totalPembayaran')
                                                .text()
                                            var intPembayaran = parseInt(strPembayaran
                                                .replace(
                                                    /,/g, '').replace(/\./g, ''))
                                            var strBanyak = $('#banyak').text()
                                            var intBanyak = parseInt(strBanyak.replace(
                                                    /,/g, '')
                                                .replace(/\./g, ''))

                                            var uang_pelanggan = parseInt(inputBayar.val())
                                            var diskon_member = parseInt(diskonInput.val())
                                            var result = hitungPembayaran(intPembayaran,
                                                diskon_member,
                                                uang_pelanggan)

                                            if (parseInt(inputBayar.val()) >= parseInt(
                                                    intPembayaran)) {
                                                $('#kembali').val('Rp.' + formatRupiah(
                                                    result
                                                    .kembalian))

                                                Swal.fire({
                                                    title: 'Print Faktur?',
                                                    text: "Kembaliannya " +
                                                        formatRupiah(result
                                                            .kembalian),
                                                    imageUrl: "{{ url('assets/img/print.svg') }}",
                                                    imageWidth: 400,
                                                    imageHeight: 200,
                                                    showCancelButton: true,
                                                    confirmButtonColor: '#0d6efd',
                                                    cancelButtonColor: '#d33',
                                                    confirmButtonText: 'Ya',
                                                    cancelButtonText: 'Tidak'
                                                }).then((resp) => {
                                                    if (resp.isConfirmed) {
                                                        var _token = $(
                                                                "input[name='_token']"
                                                            )
                                                            .val()
                                                        var faktur = $(
                                                                "input[name='faktur']"
                                                            )
                                                            .val()
                                                        var url =
                                                            "{{ route('penjualan.store') }}"
                                                        $.ajax({
                                                            url: url,
                                                            method: "POST",
                                                            data: {
                                                                diskon: parseInt(
                                                                    diskon_member
                                                                ),
                                                                bayar: parseInt(
                                                                    uang_pelanggan
                                                                ),
                                                                kembali: parseInt(
                                                                    result
                                                                    .kembalian
                                                                ),
                                                                _token: _token,
                                                                faktur: faktur,
                                                                total_pembayaran: intPembayaran,
                                                                qty: intBanyak,
                                                                status: true
                                                            },
                                                            success: (
                                                                res
                                                            ) => {
                                                                window
                                                                    .location
                                                                    .href =
                                                                    "{{ route('print_nota') }}"
                                                            }
                                                        })
                                                    } else {
                                                        var _token = $(
                                                                "input[name='_token']"
                                                            )
                                                            .val()
                                                        var faktur = $(
                                                                "input[name='faktur']"
                                                            )
                                                            .val()
                                                        var url =
                                                            "{{ route('penjualan.store') }}"
                                                        $.ajax({
                                                            url: url,
                                                            method: "POST",
                                                            data: {
                                                                diskon: parseInt(
                                                                    diskon_member
                                                                ),
                                                                bayar: parseInt(
                                                                    uang_pelanggan
                                                                ),
                                                                kembali: parseInt(
                                                                    result
                                                                    .kembalian
                                                                ),
                                                                _token: _token,
                                                                faktur: faktur,
                                                                total_pembayaran: intPembayaran,
                                                                qty: intBanyak,
                                                                status: false
                                                            },
                                                            success: (
                                                                res
                                                            ) => {
                                                                window
                                                                    .location
                                                                    .href =
                                                                    "{{ route('penjualan.index') }}"
                                                            }
                                                        })
                                                    }
                                                })
                                            } else {
                                                $('#kembali').val('')
                                            }
                                        })
                                        // end input pembayaran
                                    }
                                }
                            })
                        } else {
                            let member = $('#member').val()
                            var faktur = $("input[name='faktur']").val()
                            $.ajax({
                                url: "{{ route('getBarang') }}",
                                method: 'GET',
                                data: {
                                    kode: kodeValue,
                                    member_id: member,
                                    faktur: faktur
                                },
                                success: (res) => {
                                    if (res.status == true) {
                                        // let data = [
                                        //     [
                                        //         res.data.item.kode,
                                        //         res.data.item.nama,
                                        //         res.data.item.harga_jual,
                                        //         res.data.qty,
                                        //         res.data.total
                                        //     ]
                                        // ]
                                        // $.each(data, function(i, row) {
                                        //     let node = $('#table').DataTable().row.add(row).draw().node()
                                        //     // $(node).find('td:eq(3)').addClass('qty')
                                        //     // $(node).append(
                                        //     //     '<input type="hidden" name="id" value="' +
                                        //     //     res.data.id + '">');
                                        // })
                                        table.draw()
                                        table.on('draw.dt', function() {
                                            let total_qty = 0
                                            $('#table').DataTable().column(3).data()
                                                .each(function(
                                                    val) {
                                                    total_qty += parseInt(val)
                                                })
                                            let total_bayar_item = 0
                                            $('#table').DataTable().column(4).data()
                                                .each(function(
                                                    val) {
                                                    total_bayar_item += parseInt(
                                                        val)
                                                })

                                            $('#totalPembayaran').text(formatRupiah(
                                                total_bayar_item))
                                            $('#banyak').text(parseInt(total_qty))
                                            $('#kode').val('')
                                        })
                                    } else {
                                        alert(res.msg)
                                    }
                                }
                            })
                        }
                    }
                }
            });
            var data = []
            // delete barang
            table.on('click', 'tbody tr', (e) => {
                let classList = e.currentTarget.classList;
                let el = e.currentTarget
                if (classList.contains('selected')) {
                    classList.remove('selected');
                } else {
                    table.rows('.selected').nodes().each((row) => row.classList.remove('selected'));
                    classList.add('selected');

                    $(document).keydown((e) => {
                        if (e.keyCode == 46) {
                            var _token = $("input[name='_token']").val()
                            if (data != '') {
                                $.ajax({
                                    url: "{{ route('penjualan.destroy') }}",
                                    method: "POST",
                                    data: {
                                        id: data,
                                        _token
                                    },
                                    success: (res) => {
                                        if (res.status == true) {
                                            $('#table').DataTable().rows('.selected')
                                                .remove().draw()
                                            let total_qty = 0
                                            $('#table').DataTable().column(3).data()
                                                .each(
                                                    function(val) {
                                                        total_qty += parseInt(val)
                                                    })
                                            let total_bayar_item = 0
                                            $('#table').DataTable().column(4).data()
                                                .each(
                                                    function(val) {
                                                        total_bayar_item += parseInt(
                                                            val)
                                                    })
                                            $('#totalPembayaran').text(formatRupiah(
                                                total_bayar_item))
                                            $('#banyak').text(parseInt(total_qty))
                                        } else {
                                            Swal.fire({
                                                title: res.msg,
                                                icon: 'info',
                                                showCancelButton: false,
                                                confirmButtonColor: '#0d6efd',
                                                confirmButtonText: 'Ya',
                                            })
                                        }
                                    }
                                })
                            }
                        }
                    })
                }
                data = el.getAttribute('id')
            });
            $('#penjualanDelete').on('click', function() {
                var _token = $("input[name='_token']").val()
                if (data != '') {
                    $.ajax({
                        url: "{{ route('penjualan.destroy') }}",
                        method: "POST",
                        data: {
                            id: data,
                            _token
                        },
                        success: (res) => {
                            if (res.status == true) {
                                $('#table').DataTable().rows('.selected').remove().draw()
                                let total_qty = 0
                                $('#table').DataTable().column(3).data().each(function(val) {
                                    total_qty += parseInt(val)
                                })
                                let total_bayar_item = 0
                                $('#table').DataTable().column(4).data().each(function(val) {
                                    total_bayar_item += parseInt(val)
                                })

                                $('#totalPembayaran').text(formatRupiah(total_bayar_item))
                                $('#banyak').text(parseInt(total_qty))
                            } else {
                                Swal.fire({
                                    title: res.msg,
                                    icon: 'info',
                                    showCancelButton: false,
                                    confirmButtonColor: '#0d6efd',
                                    confirmButtonText: 'Ya',
                                })
                            }

                        }
                    })
                }
                // table.row('.selected').remove().draw(false);
            });
            // edit qty barang
            table.on('click', 'td.qty', function() {
                var cell = $(this);
                var currentData = cell.text();
                var input = $('<input type="text" class="form-control">').val(currentData);
                input.attr('name', 'qty')
                input.attr('id', 'qty')
                cell.html(input);
                input.focus();

                var row = cell.closest('tr')
                var id = row.attr('id')
                input.on('keydown', function(e) {
                    var newData = input.val()
                    var data = {
                        id: id,
                        qty: newData
                    }
                    if (e.key === 'Enter') {
                        update(data, (res) => {
                            if (res.status === true) {
                                // window.location.reload()
                                table.draw()
                                table.on('draw.dt', function() {
                                    let total_qty = 0
                                    $('#table').DataTable().column(3).data().each(
                                        function(
                                            val) {
                                            total_qty += parseInt(val)
                                        })
                                    let total_bayar_item = 0
                                    $('#table').DataTable().column(4).data().each(
                                        function(
                                            val) {
                                            total_bayar_item += parseInt(val)
                                        })
                                    $('#totalPembayaran').text(formatRupiah(
                                        total_bayar_item))
                                    $('#banyak').text(parseInt(total_qty))
                                    $('#kode').val('')
                                    $('#kode').focus()
                                })
                            } else {
                                alert(res.msg);
                            }
                        })
                    }
                })
            });
            $('#table').on('blur', '.qty input', function() {
                var input = $(this);
                var newAddress = input.val();
                var cell = input.closest('.qty');
                cell.html(newAddress);
            });
            // update function
            function update(params, callback) {
                var _token = $("input[name='_token']").val()
                $.ajax({
                    url: "{{ route('penjualan.update') }}",
                    data: {
                        params,
                        _token
                    },
                    type: "POST",
                    success: (res) => {
                        callback(res)
                    },
                    error: (err) => {
                        callback(err)
                    }
                })
            }
            $(document).on('input', 'input[type="text"]', function() {
                var value = $(this).val();
                $(this).val(value.replace(/\D/g, ''));
            });
            $('#member').change(function() {
                var dsc_member = $(this).val()
                $('#diskon').val(parseInt(dsc_member))
                $('#kode').focus()
            })

            function hitungPembayaran(total, diskon, uangDiberikan) {
                var subtotal = total - (total * diskon / 100);
                var kembalian = uangDiberikan - subtotal;

                var result = {
                    subtotal: subtotal,
                    kembalian: kembalian
                };

                return result;
            }

            function formatRupiah(angka) {
                var numberString = angka.toString();
                var splitNumber = numberString.split('.');
                var sisa = splitNumber[0].length % 3;
                var rupiah = splitNumber[0].substr(0, sisa);
                var ribuan = splitNumber[0].substr(sisa).match(/\d{1,3}/gi);

                if (ribuan) {
                    separator = sisa ? ',' : '';
                    rupiah += separator + ribuan.join(',');
                }

                rupiah = splitNumber[1] !== undefined ? rupiah + ',' + splitNumber[1] : rupiah;
                // return 'Rp ' + rupiah;
                return rupiah;
            }

            setTimeout(() => {
                $('#kode').focus()
            }, 1000);
        });
    </script>
@endpush
