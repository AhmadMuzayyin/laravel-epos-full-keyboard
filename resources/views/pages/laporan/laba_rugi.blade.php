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
                <h3 class="mb-2 mt-2">Laporan Laba Rugi Penjualan</h5>
            </div>
            <div class="col text-end">
                <div class="row">
                    <div class="col">
                        <button class="btn btn-outline-{{ $theme == 'dark' ? 'light' : 'dark' }} btn-sm" type="button"
                            id="export-penjualan"><i class="bi bi-file-earmark-spreadsheet-fill"></i> Excel</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row my-4">
            <div class="col">
                <h2 class="fw-bold">Modal : Rp.{{ number_format($modal) }}</h2>
            </div>
            <div class="col">
                <form action="{{ url()->current() }}" method="GET">
                    <div class="d-flex mt-2">
                        <label for="from" class="mx-1">From</label>
                        <input type="date" name="from" id="from" class="form-control form-control-sm"
                            value="{{ request()->get('from') ?? date('Y-m-d') }}">
                        <label for="to" class="mx-1">To</label>
                        <input type="date" name="to" id="to" class="form-control form-control-sm mx-2"
                            value="{{ request()->get('to') ?? date('Y-m-d') }}">
                        <button type="submit"
                            class="btn btn-outline-{{ $theme == 'dark' ? 'light' : 'dark' }} btn-sm mx-2">Filter</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h3>Laba Penjualan</h3>
                    </div>
                    <div class="card-body">
                        <h1 class="fw-bold" style="font-family: Georgia, 'Times New Roman', Times, serif">
                            @if ($penjualan < $modal)
                                Rp. 0
                            @else
                                Rp. {{ number_format($hasil) }}
                            @endif
                        </h1>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h3>Rugi Penjualan</h3>
                    </div>
                    <div class="card-body">
                        <h1 class="fw-bold" style="font-family: Georgia, 'Times New Roman', Times, serif">
                            @if ($penjualan > $modal || $penjualan == 0)
                                Rp. 0
                            @else
                                Rp. {{ number_format($modal - $penjualan) }}
                            @endif
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
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
