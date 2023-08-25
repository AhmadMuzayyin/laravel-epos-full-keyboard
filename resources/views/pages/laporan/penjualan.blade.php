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
                <h3 class="mb-2 mt-2">Laporan Penjualan</h5>
            </div>
            <div class="col text-end">
                @if (request()->get('tipe') == 'tabel')
                    <button class="btn btn-outline-{{ $theme == 'dark' ? 'light' : 'dark' }} btn-sm" type="button"
                        id="export-penjualan"><i class="bi bi-file-earmark-spreadsheet-fill"></i> Excel</button>
                @endif
                <div class="btn-group dropstart">
                    <button class="btn btn-outline-{{ $theme == 'dark' ? 'light' : 'dark' }} dropdown-toggle btn-sm"
                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Jenis Laporan
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('laporan.penjualan') . '?tipe=grafik' }}">Chart</a>
                        </li>
                        <li><a class="dropdown-item" href="{{ route('laporan.penjualan') . '?tipe=tabel' }}">Tabel</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        @if (request()->get('tipe') == 'tabel')
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
        @elseif (request()->get('tipe') == null || request()->get('tipe') == 'grafik')
            <div class="row">
                <div class="col-md-6">
                    <canvas id="line"></canvas>
                </div>
                <div class="col-md-6">
                    <canvas id="bar"></canvas>
                </div>
            </div>
        @endif
    </div>
@endsection
@if (request()->get('tipe') == null || request()->get('tipe') == 'grafik')
    @push('js')
        <script>
            // chart by months
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober',
                'November', 'Desember'
            ];
            const lineLabel = []
            const linedata = []
            const values = @json($line);
            values.forEach(element => {
                lineLabel.push(element.month)
                linedata.push(element.total_value)
            });
            const line = document.getElementById('line').getContext('2d');
            const config = {
                type: 'line',
                data: {
                    labels: lineLabel,
                    datasets: [{
                        label: 'Bulanan',
                        data: linedata,
                        border: 'blue',
                        backgroundColor: '#36A2EB',
                        fill: true,
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            }
            const lineChart = new Chart(line, config);

            // chart by members
            const bar = document.getElementById('bar').getContext('2d');
            const barLabel = []
            const barData = [];
            const barValue = @json($barData);
            barValue.forEach(val => {
                barLabel.push(val.nama_member)
                barData.push(val.total_value)
            });
            const configBar = {
                type: 'bar',
                data: {
                    labels: barLabel,
                    datasets: [{
                        label: 'Member',
                        data: barData,
                        backgroundColor: ['#FF5E80', '#FF9F40', '#4BC0C0', '#FFCD56', '#36A2EB', ],
                        fill: true,
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            }
            const barChart = new Chart(bar, configBar);
        </script>
    @endpush
@else
    @push('js')
        <script>
            $(document).ready(function() {
                var table = $('#table').DataTable({
                    serverSide: true,
                    select: 'single',
                    ajax: @json($tabel),
                    columns: [{
                            data: 'item.kode',
                            name: 'kode'
                        },
                        {
                            data: 'item.nama',
                            name: 'nama',
                        },
                        {
                            data: 'suplier.nama',
                            name: 'suplier',
                        },
                        {
                            data: 'member.nama',
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
                    window.open("{{ route('laporan.export_penjualan') }}", '_blank')
                })
            });
        </script>
    @endpush
@endif
@push('js')
    <script>
        $(document).keydown(function(event) {
            if (event.key === 'Escape') {
                event.preventDefault()
                window.location.href = "{{ route('dashboard') }}"
            }
        })
    </script>
@endpush
