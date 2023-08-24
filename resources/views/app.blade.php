@php
    $theme = session()->get('theme');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="{{ $theme }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ url('assets/css/bootstrap.css') }}">

    {{-- css package --}}
    <link rel="stylesheet" href="{{ url('assets/DataTables/css/dataTables.bootstrap5.min.css') }}">
    @if ($theme == 'dark')
        <link rel="stylesheet" href="{{ url('assets/sweatalert/dark.css') }}">
    @else
        <link rel="stylesheet" href="{{ url('assets/sweatalert/sweetalert2.min.css') }}">
    @endif

</head>

<body class="font-sans antialiased">
    {{-- @include('components.preloader') --}}
    @if (Auth::user())
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
        <div>
            <div class="container mt-5">

                <div class="card border-{{ $theme == 'dark' ? 'light' : 'dark shadow shadow-lg' }}">
                    <div class="card-header">
                        @if (request()->segment(1) === 'dashboard')
                            <div class="row">
                                <div class="col">
                                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                        <button type="button"
                                            class="btn btn-outline-{{ $theme == 'dark' ? 'light' : 'dark' }}"
                                            data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                                            aria-controls="offcanvasRight">
                                            <i class="bi bi-gear"></i>
                                        </button>
                                        <button type="button"
                                            class="btn btn-outline-{{ $theme == 'dark' ? 'light' : 'dark' }}">Middle</button>
                                        <button type="button"
                                            class="btn btn-outline-{{ $theme == 'dark' ? 'light' : 'dark' }}"
                                            id="setTheme">
                                            @csrf
                                            <i class="bi bi-brightness-high"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col text-end">
                                    <form action="{{ route('logout') }}" method="POST" class="text-right">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-outline-{{ $theme == 'dark' ? 'light' : 'dark' }}"
                                            id="btnEscLogout">
                                            <i class="bi bi-power text-danger"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasRight"
                                aria-labelledby="offcanvasRightLabel">
                                <div class="offcanvas-header">
                                    <h5 class="offcanvas-title" id="offcanvasRightLabel">Setting</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                        aria-label="Close"></button>
                                </div>
                                <div class="offcanvas-body">
                                    ...
                                </div>
                            </div>
                        @else
                            @yield('contentHead')
                        @endif
                    </div>
                    <div class="card-body">

                        <div>
                            @yield('home')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div>
            @yield('content')
        </div>
    @endif

    <script src="{{ url('/assets/js/bootstrap.bundle.js') }}"></script>
    {{-- js package --}}
    <script src="{{ url('/assets/jQuery/jquery-3.7.0.min.js') }}"></script>
    <script src="{{ url('/assets/DataTables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('/assets/DataTables/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ url('/assets/sweatalert/sweetalert2.all.min.js') }}"></script>
    <script src="{{ url('/assets/jQuery.printPage.plugin.js') }}"></script>
    <script src="{{ url('/assets/chart.min.js') }}"></script>

    <script>
        $('#setTheme').click((e) => {
            var theme = "{{ session()->get('theme') }}"
            var _token = $("input[name='_token']").val()
            $.ajax({
                url: "{{ route('setTheme') }}",
                method: "POST",
                data: {
                    theme,
                    _token
                },
                success: (res) => {
                    window.location.reload()
                }
            })
        })
        // document.addEventListener('DOMContentLoaded', function() {
        //     // Hapus preloader setelah halaman selesai dimuat
        //     var preloader = document.getElementById('preloader');
        //     setInterval(() => {
        //         preloader.style.display = 'none';
        //     }, 1000);
        // });
    </script>

    @stack('js')
</body>

</html>
