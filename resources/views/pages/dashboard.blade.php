@extends('app')
@section('home')
@php
    $theme = session()->get('theme');
@endphp
    <div class="row row-cols-1 row-cols-md-1 g-4">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title font-bold">Data Master</h5>
                    <div class="row">
                        <div class="col">

                            <a href="#" id="btnAlt1" class="btn btn-outline-{{ $theme == 'dark' ? 'light' : 'dark' }} btn-lg d-block">
                                <i class="bi bi-plus-square"></i>
                                <p>Alt+1 - Tambah Barang</p>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{ route('items.index') }}" class="btn btn-outline-{{ $theme == 'dark' ? 'light' : 'dark' }} btn-lg d-block">
                                <i class="bi bi-pencil"></i>
                                <p>Alt+2 - Stok Barang</p>
                            </a>
                        </div>
                        <div class="col">
                            <a href="#" id="btnAlt3" class="btn btn-outline-{{ $theme == 'dark' ? 'light' : 'dark' }} btn-lg d-block">
                                <i class="bi bi-person-add"></i>
                                <p>Alt+3 - Tambah Member</p>
                            </a>
                        </div>
                        <div class="col">

                            <a href="{{ route('members.index') }}" class="btn btn-outline-{{ $theme == 'dark' ? 'light' : 'dark' }} btn-lg d-block">
                                <i class="bi bi-person-vcard"></i>
                                <p>Alt+4 - Data Member</p>
                            </a>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">

                            <a href="#" id="btnAlt5" class="btn btn-outline-{{ $theme == 'dark' ? 'light' : 'dark' }} btn-lg d-block">
                                <i class="bi bi-truck"></i>
                                <p>Alt+5 - Tambah Suplier</p>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{ route('supliers.index') }}" class="btn btn-outline-{{ $theme == 'dark' ? 'light' : 'dark' }} btn-lg d-block">
                                <i class="bi bi-truck-front"></i>
                                <p>Alt+6 - Data Suplier</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-md-1 g-4 mt-3">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title font-bold">Transaksi</h5>
                    <div class="row">
                        <div class="col">

                            <a href="{{ route('penjualan.index') }}" class="btn btn-outline-{{ $theme == 'dark' ? 'light' : 'dark' }} btn-lg d-block">
                                <i class="bi bi-cash-coin"></i>
                                <p>Alt+7 - Penjualan</p>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{ route('retur_penjualan.index') }}" class="btn btn-outline-{{ $theme == 'dark' ? 'light' : 'dark' }} btn-lg d-block">
                                <i class="bi bi-arrow-down-square"></i>
                                <p>Alt+8 - Retur Penjualan</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-md-1 g-4 mt-3">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title font-bold">Laporan</h5>
                    <div class="row">
                        <div class="col">

                            <a href="{{ route('laporan.penjualan') }}" class="btn btn-outline-{{ $theme == 'dark' ? 'light' : 'dark' }} btn-lg d-block">
                                <i class="bi bi-file-earmark-bar-graph"></i>
                                <p>Alt+W - Penjualan</p>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{ route('laporan.retur_penjualan') }}" class="btn btn-outline-{{ $theme == 'dark' ? 'light' : 'dark' }} btn-lg d-block">
                                <i class="bi bi-file-earmark-excel"></i>
                                <p>Alt+L - Retur Penjualan</p>
                            </a>
                        </div>
                    </div>
                    <div class="col">
                        <a href="{{ route('laporan.laba_rugi') }}" class="btn btn-outline-{{ $theme == 'dark' ? 'light' : 'dark' }} btn-lg d-block mt-3">
                            <i class="bi bi-currency-exchange"></i>
                            <p>Alt+Y - Laba Rugi</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('pages.item.create')
    @include('pages.member.create')
    @include('pages.suplier.create')
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            var openModal = false

            $(document).keydown(function(event) {
                if (openModal == false) {
                    if (!event.altKey && event.key !== 'Escape') {
                        event.preventDefault();
                    }
                }

                var allowedKeys = [
                    '1', '2', '3', '4', '5', '6', '7', '8', '9', 'q', 'w', 'l', 'r', 'p', 'y'
                ];
                if (event.key === 'Escape') {
                    if (openModal) {
                        $('.modal').modal('hide')
                    } else {
                        var _token = $("input[name='_token']").val()

                        Swal.fire({
                            title: 'Anda yakin untuk keluar?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#0d6efd',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Ya',
                            cancelButtonText: 'Tidak',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: "{{ route('logout') }}",
                                    method: "POST",
                                    data: {
                                        _token
                                    },
                                    success: function() {
                                        window.location.reload()
                                    }
                                })
                            }
                        })
                    }
                } else if (event.altKey && allowedKeys.includes(event.key.toLowerCase())) {
                    // Aksi yang ingin Anda lakukan ketika kombinasi tombol Alt+Angka/AngkaHuruf ditekan
                    switch (event.key.toLowerCase()) {
                        case '1':
                            $('#createItem').modal('show')
                            break;
                        case '2':
                            event.preventDefault()
                            window.location.href = "{{ route('items.index') }}"
                            break;
                        case '3':
                            $('#createMember').modal('show')
                            break;
                        case '4':
                            event.preventDefault()
                            window.location.href = "{{ route('members.index') }}"
                            break;
                        case '5':
                            $('#createSuplier').modal('show')
                            break;
                        case '6':
                            event.preventDefault()
                            window.location.href = "{{ route('supliers.index') }}"
                            break;
                        case '7':
                            event.preventDefault()
                            window.location.href = "{{ route('penjualan.index') }}"
                            break;
                        case '8':
                            event.preventDefault()
                            window.location.href = "{{ route('retur_penjualan.index') }}"
                            break;
                        case 'w':
                            event.preventDefault()
                            window.location.href = "{{ route('laporan.penjualan') }}"
                            break;
                        case 'l':
                            event.preventDefault()
                            window.location.href = "{{ route('laporan.retur_penjualan') }}"
                            break;
                        case 'y':
                            event.preventDefault()
                            window.location.href = "{{ route('laporan.laba_rugi') }}"
                            break;
                    }
                }
            })

            $('#btnAlt1').click(function(e) {
                e.preventDefault()
                $('#createItem').modal('show')
            })
            $('#btnAlt3').click(function(e) {
                e.preventDefault()
                $('#createMember').modal('show')
            })
            $('#btnAlt5').click(function(e) {
                e.preventDefault()
                $('#createSuplier').modal('show')
            })
            



            // untuk mencegah triger tombol escape berfungsi logout
            $('.modal').on('show.bs.modal', function() {
                openModal = true
            })
            $('.modal').on('hidden.bs.modal', function() {
                openModal = false
                $(this).find('form').trigger('reset')
            })
        })
    </script>
@endpush
