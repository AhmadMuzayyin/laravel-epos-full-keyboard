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
                        <p>Pembelian</p>
                        <div class="d-flex">
                            <h1>Rp. </h1>
                            <h1 class="font-bold" id="totalPembayaran">
                                {{ count($pembelian) > 0 ? number_format($total_pembelian) : 0 }}</h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body text-center">
                        <p>Jumlah</p>
                        <h1 class="font-bold" id="banyak">
                            {{ count($pembelian) > 0 ? $jumlah : 0 }}</h1>
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
                                <input type="search" name="kode" id="kode" class="form-control form-control-sm"
                                    placeholder="Kode barang">
                            </div>
                            <div class="col text-end">
                                <input type="text" class="form-control form-control-sm" placeholder="Faktur" aria-label="Faktur"
                                    name="faktur" id="faktur"
                                    value="{{ count($pembelian) > 0 ? $faktur_kasir[0]['faktur'] : '' }}">
                            </div>
                        </div>
                        <div class="table-responsive">
                            @csrf
                            <table class="table table-hover" id="table">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Item</th>
                                        <th>Harga Beli</th>
                                        <th>Diskon Beli</th>
                                        <th>Harga Jual</th>
                                        <th>Diskon Jual</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                                {{-- <tbody>
                                    @if (count($pembelian) > 0)
                                        @foreach ($pembelian[0]['data'] as $item)
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
            </div>
            <div class="col">
                {{-- card kanan --}}
                <div class="card" style="height: 30rem">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col">
                                <select class="form-select" aria-label="Default select example" name="member"
                                    id="member">
                                    @foreach ($supliers as $suplier)
                                        <option value="{{ $suplier['id'] }}">{{ $suplier['nama'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <button role="button"
                                    class="btn btn-outline-{{ $theme == 'dark' ? 'light' : 'dark' }} mx-2"
                                    id="simpanPembelian"><i class="bi bi-clipboard2-check"></i>
                                    <p>Ctrl+Enter - Simpan</p>
                                </button>
                                <button role="button" class="btn btn-outline-{{ $theme == 'dark' ? 'light' : 'dark' }}"
                                    id="deletePembelian"><i class="bi bi-trash text-danger"></i>
                                    <p>Delete - Hapus</p>
                                </button>
                                <button class="btn btn-outline-{{ $theme == 'dark' ? 'light' : 'dark' }} mt-3 mx-2"
                                    role="button" id="deleteAllPembelian">
                                    <i class="bi bi-trash text-danger"></i>
                                    <p>Ctrl+Del - Hapus semua</p>
                                </button>
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
                ajax: "{{ route('pembelian.index') }}",
                columns: [{
                        data: 'item.kode',
                        name: 'kode'
                    },
                    {
                        data: 'item.nama',
                        name: 'nama',
                    },
                    {
                        data: 'harga_beli',
                        name: 'harga_beli',
                        className: 'harga_beli'
                    },
                    {
                        data: 'diskon_beli',
                        name: 'diskon_beli',
                        className: 'diskon_beli'
                    },
                    {
                        data: 'harga_jual',
                        name: 'harga_jual',
                        className: 'harga_jual'
                    },
                    {
                        data: 'diskon_jual',
                        name: 'diskon_jual',
                        className: 'diskon_jual'
                    },
                    {
                        data: 'qty',
                        name: 'qty',
                        className: 'qty'
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
                if (event.keyCode === 13 && !event.ctrlKey) {
                    var currentInput = $(':focus');
                    if (currentInput.attr('name') === 'kode') {
                        var kodeValue = currentInput.val();
                        // untuk input diskon
                        if (kodeValue === '') {
                            $('#kode').focus()
                        } else {
                            $.ajax({
                                url: "{{ route('pembelian.store') }}",
                                method: 'POST',
                                data: {
                                    _token: $("input[name='_token']").val()
                                },
                                success: (res) => {
                                    if (res.status == true) {
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
                if (event.ctrlKey && event.keyCode === 13) {
                    $.ajax({
                        url: "{{ route('pembelian.save') }}",
                        method: "POST",
                        data: {
                            _token: $("input[name='_token']").val(),
                        },
                        success: (res) => {
                            if (res.status === true) {
                                console.log('oke');
                            } else {
                                console.log(res.msg);
                            }
                        }
                    })
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
            cellClick(table, 'td.harga_beli', update)
            cellClick(table, 'td.diskon_beli', update)
            cellClick(table, 'td.harga_jual', update)
            cellClick(table, 'td.diskon_jual', update)
            cellClick(table, 'td.qty', update)

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

            function cellClick(table, className, funcUpdate) {
                var attName = removeElementFromSelector(className)
                table.on('click', className, function() {
                    var cell = $(this);
                    var currentData = cell.text();
                    var input = $('<input type="text" class="form-control">').val(currentData);
                    input.attr('name', attName)
                    input.attr('id', attName)
                    cell.html(input);
                    input.focus();

                    var row = cell.closest('tr')
                    var id = row.attr('id')
                    input.on('keydown', function(e) {
                        var value = input.val()
                        var data = {
                            id,
                            field: attName,
                            value: value
                        }
                        if (e.key === 'Enter') {
                            update(data, (res) => {
                                if (res.status === true) {
                                    table.draw()
                                    table.on('draw.dt', function() {
                                        let total_beli = 0
                                        $('#table').DataTable().column(2).data()
                                            .each(function(val) {
                                                    total_beli += parseInt(val)
                                                })
                                        let diskon_beli = 0
                                        $('#table').DataTable().column(3).data()
                                            .each(function(val) {
                                                    diskon_beli += parseInt(val)
                                                })
                                        let total_qty = 0
                                        $('#table').DataTable().column(6).data()
                                            .each(function(val) {
                                                    total_qty += parseInt(val)
                                                })
                                        $('#totalPembayaran').text(hitungPembayaran())
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
                var blurClassName = addElementToSelector(className)
                $('#table').on('blur', blurClassName, function() {
                    var input = $(this);
                    var newAddress = input.val();
                    var cell = input.closest('.' + attName);
                    cell.html(newAddress);
                });

                function removeElementFromSelector(selector) {
                    const parts = selector.split('.');
                    return parts.filter(part => part !== 'td').join('.');
                }

                function addElementToSelector(selector) {
                    return selector.replace(selector, selector + ' input');
                }
            }
            // update function
            function update(params, callback) {
                var _token = $("input[name='_token']").val()
                $.ajax({
                    url: "{{ route('pembelian.update') }}",
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
        });
    </script>
@endpush
