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
    @csrf
    <div>
        <div class="row mb-2">
            <div class="col-10">
                <div class="card">
                    <div class="card-body">
                        <p>Pengembalian</p>
                        <div class="d-flex">
                            <h1>Rp. </h1>
                            <h1 class="font-bold" id="totalPembayaran">
                                {{ count($penjualan) > 0 ? number_format($pengembalian) : 0 }}</h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body text-center">
                        <p>Jumlah</p>
                        <h1 class="font-bold" id="banyak">
                            {{ count($penjualan) > 0 ? $banyak : 0 }}</h1>
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
                                <input type="search" name="nomor_faktur" id="nomor_faktur"
                                    class="form-control form-control-sm" placeholder="No Faktur" autofocus>
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
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card mt-2">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-sm-7">
                                <label for="faktur" class="mb-2">No Faktur</label>
                                <input type="text" class="form-control" placeholder="No Faktur" aria-label="No Faktur"
                                    name="faktur" id="faktur" readonly
                                    value="{{ count($penjualan) > 0 ? $penjualan[0]['nomor_faktur'] : '' }}">
                            </div>
                            <div class="col-sm">
                                <label for="faktur" class="mb-2">Kasir</label>
                                <input type="text" class="form-control" placeholder="Kasir" aria-label="Kasir"
                                    id="kasir" readonly
                                    value="{{ count($penjualan) > 0 ? $penjualan[0]['kasir'] : '' }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                {{-- card kanan --}}
                <div class="card" style="height: 35rem">
                    <div class="card-body">
                        {{-- <div class="row mb-5">
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
                        </div> --}}
                        <div class="row mb-3">
                            <div class="col">
                                <button role="button" class="btn btn-outline-{{ $theme == 'dark' ? 'light' : 'dark' }}"
                                    id="simpan"><i class="bi bi bi-clipboard2-check"></i>
                                    <p>Ctrl+Enter - Simpan data</p>
                                </button>
                                <button role="button" class="btn btn-outline-{{ $theme == 'dark' ? 'light' : 'dark' }}"
                                    id="penjualanDelete"><i class="bi bi-trash text-danger"></i>
                                    <p>Delete - Hapus</p>
                                </button>
                                <button class="btn btn-outline-{{ $theme == 'dark' ? 'light' : 'dark' }} my-2"
                                    role="button"><i class="bi bi-trash text-danger"></i>
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
                    emptyTable: "Tidak ada retur penjualan",
                },
                dom: 'rt',
                responsive: false,
                serverSide: true,
                select: 'single',
                ajax: "{{ route('retur_penjualan.index') }}",
                columns: [{
                        data: 'kode',
                        name: 'kode'
                    },
                    {
                        data: 'item',
                        name: 'item',
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
            $(document).keydown(function(event) {
                if (event.key === 'Escape') {
                    event.preventDefault()
                    window.location.href = "{{ route('dashboard') }}"
                }
                // delete semua data
                if (event.ctrlKey && event.keyCode === 46) {
                    if (table.data().length > 0) {
                        $.ajax({
                            url: "{{ route('retur.penjualan.destroyAll') }}",
                            method: "POST",
                            data: {
                                _token: $("input[name='_token']").val()
                            },
                            success: (res) => {
                                if (res.status == true) {
                                    table.draw()
                                    table.on('draw.dt', function() {
                                        if (table.data().length == 0) {
                                            $('#totalPembayaran').text(0)
                                            $('#banyak').text(0)
                                            $('#nomor_faktur').focus()
                                            $('#faktur').val('')
                                            $('#kasir').val('')
                                        }
                                    })
                                } else {
                                    alert(res.msg)
                                }
                            }
                        })
                    }
                }
                // tampilkan barang retur
                if (event.keyCode === 13 && !event.ctrlKey) {
                    var currentInput = $(':focus');
                    if (currentInput.attr('name') === 'nomor_faktur') {
                        var kodeValue = currentInput.val();
                        // untuk input diskon
                        if (kodeValue === '') {
                            $('#nomor_faktur').focus()
                        } else {
                            $.ajax({
                                url: "{{ route('retur_penjualan.store') }}",
                                method: 'POST',
                                data: {
                                    kode: kodeValue,
                                    _token: $("input[name='_token']").val()
                                },
                                success: (res) => {
                                    if (res.status == true) {
                                        table.draw()
                                        table.on('draw.dt', function() {
                                            let total_qty = 0
                                            $('#table').DataTable().column(3).data()
                                                .each(function(val) {
                                                    total_qty += parseInt(val)
                                                })
                                            var total_bayar_item = res.data
                                                .penjualan_detail.total_tagihan - (res
                                                    .data.penjualan_detail
                                                    .total_tagihan * res.data
                                                    .penjualan_detail.diskon / 100)
                                            $('#totalPembayaran').text(formatRupiah(
                                                total_bayar_item))
                                            $('#banyak').text(parseInt(total_qty))
                                            $('#nomor_faktur').val('')
                                            if (table.data().length > 0) {
                                                $('#faktur').val(res.data
                                                    .penjualan_detail.nomor_faktur)
                                                $('#kasir').val(res.data
                                                    .penjualan_detail.user.name)
                                            } else {
                                                $('#faktur').val('')
                                                $('#kasir').val('')
                                            }
                                        })
                                    } else {
                                        alert(res.msg)
                                        $('#nomor_faktur').val('')
                                        $('#nomor_faktur').focus()
                                    }
                                }
                            })
                        }
                    }
                }
                // simpan barang retur
                if (event.ctrlKey && event.keyCode === 13) {
                    $.ajax({
                        url: "{{ route('retur.penjualan.update') }}",
                        method: "POST",
                        data: {
                            _token: $("input[name='_token']").val()
                        },
                        success: (res) => {
                            if (res.status === true) {
                                table.draw()
                                $('#nomor_faktur').val('')
                                table.on('draw.dt', function() {
                                    if (table.data().length == 0) {
                                        $('#faktur').val('')
                                        $('#kasir').val('')
                                        $('#nomor_faktur').focus()
                                        $('#totalPembayaran').text(0)
                                        $('#banyak').text(0)
                                    }
                                })
                            } else {
                                alert(res.msg)
                            }
                        }
                    })
                }
            });
            // delete barang
            var data = []
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
                                    url: "{{ route('retur.penjualan.destroy') }}",
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
                        url: "{{ route('retur.penjualan.destroy') }}",
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
            setInterval(() => {
                $('#kode').focus()
            }, 1000);
        });
    </script>
@endpush
