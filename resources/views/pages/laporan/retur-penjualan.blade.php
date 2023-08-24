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
        <div class="row">
            <div class="col">
                <h3 class="mb-2 mt-2">Laporan Retur Penjualan</h5>
            </div>
            <div class="col text-end">
                <button class="btn btn-outline-{{ $theme == 'dark' ? 'light' : 'dark' }} btn-sm" type="button"
                    id="export-penjualan"><i class="bi bi-file-earmark-spreadsheet-fill"></i> Excel</button>
            </div>
        </div>
        <div class="table-responsive">
            @csrf
            <table class="table table-hover" id="table">
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
            </table>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            var table = $('#table').DataTable({
                serverSide: true,
                select: 'single',
                ajax: @json($tabel),
                columns: [{
                        data: 'kode',
                        name: 'kode'
                    },
                    {
                        data: 'barang',
                        name: 'nama',
                    },
                    {
                        data: 'suplier',
                        name: 'suplier',
                    },
                    {
                        data: 'member',
                        name: 'member',
                    },
                    {
                        data: 'qty',
                        name: 'qty',
                    },
                    {
                        data: 'harga',
                        name: 'harga',
                    },
                    {
                        data: 'total',
                        name: 'total',
                    }
                ]
            });

            var btn = $('#export-penjualan')
            btn.click(function() {
                window.open("{{ route('laporan.export_retur_penjualan') }}", '_blank')
            })
        });

        $(document).keydown(function(event) {
            if (event.key === 'Escape') {
                event.preventDefault()
                window.location.href = "{{ route('dashboard') }}"
            }
        })
    </script>
@endpush
