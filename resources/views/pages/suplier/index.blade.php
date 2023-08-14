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
                <h3 class="mb-2 mt-2">Suplier</h5>
            </div>
            <div class="col text-end">
                <a href="#" role="button" class="btn btn-outline-{{ $theme == 'dark' ? 'light' : 'dark' }}"
                    id="memberEdit"><i class="bi bi-pencil"></i>
                    Enter -
                    Simpan Perubahan</a>
                <a href="#" role="button" class="btn btn-outline-{{ $theme == 'dark' ? 'light' : 'dark' }}"
                    id="suplierDelete"><i class="bi bi-trash text-danger"></i>
                    Delete -
                    Hapus</a>
            </div>
        </div>

        <div class="table-responsive">
            @csrf
            <table class="table table-hover" id="table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Kontak</th>
                        <th>Telepon</th>
                        <th>Alamat</th>
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
                ajax: "{{ route('supliers.index') }}",
                columns: [{
                        data: 'kode',
                        name: 'kode'
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                        className: 'nama'
                    },
                    {
                        data: 'kontak',
                        name: 'kontak',
                        className: 'kontak'
                    },
                    {
                        data: 'telepon',
                        name: 'telepon',
                        className: 'telepon'
                    },
                    {
                        data: 'alamat',
                        name: 'alamat',
                        className: 'alamat'
                    }
                ]
            });
            var data = []
            table.on('click', 'tbody tr', (e) => {
                let classList = e.currentTarget.classList;

                if (classList.contains('selected')) {
                    classList.remove('selected');
                } else {
                    table.rows('.selected').nodes().each((row) => row.classList.remove('selected'));
                    classList.add('selected');

                    $(document).keydown((e) => {
                        if (e.keyCode === 46) {
                            Swal.fire({
                                title: 'Yakin untuk menghapus data ini?',
                                showCancelButton: true,
                                confirmButtonColor: '#0d6efd',
                                cancelButtonColor: '#6c757d',
                                confirmButtonText: 'Ya',
                                cancelButtonText: 'Tidak',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    var _token = $("input[name='_token']").val()
                                    $.ajax({
                                        url: "{{ route('supliers.destroy') }}",
                                        method: "POST",
                                        data: {
                                            id: data[0].id,
                                            _token
                                        },
                                        success: (res) => {
                                            if (res.status == true) {
                                                window.location.reload()
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
                            })
                        }
                    })
                }
                data = table.rows('.selected').data().toArray();
            });

            $('#suplierDelete').on('click', function() {
                Swal.fire({
                    title: 'Yakin untuk menghapus data ini?',
                    showCancelButton: true,
                    confirmButtonColor: '#0d6efd',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak',
                }).then((result) => {
                    if (result.isConfirmed) {
                        var _token = $("input[name='_token']").val()
                        $.ajax({
                            url: "{{ route('supliers.destroy') }}",
                            method: "POST",
                            data: {
                                id: data[0].id,
                                _token
                            },
                            success: (res) => {
                                if (res.status == true) {
                                    window.location.reload()
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
                })
                // table.row('.selected').remove().draw(false);
            });


            table.on('click', 'td.nama', function() {
                var cell = $(this);
                var currentData = cell.text();
                var input = $('<input type="text" class="form-control">').val(currentData);
                input.attr('name', 'nama')
                input.attr('id', 'nama')
                cell.html(input);
                input.focus();

                var row = cell.closest('tr')
                var kode_barang = row.find('td:eq(0)').text()
                input.on('keydown', function(e) {
                    var newData = input.val()
                    var data = {
                        id: kode_barang,
                        nama: newData
                    }
                    if (e.key === 'Enter') {
                        update(data, (res) => {
                            if (res == '') {
                                window.location.reload()
                            } else {
                                alert(res.msg);
                            }
                        })
                    }
                })
            });
            table.on('click', 'td.kontak', function() {
                var cell = $(this);
                var currentData = cell.text();
                var input = $('<input type="text" class="form-control">').val(currentData);
                input.attr('name', 'kontak')
                input.attr('id', 'kontak')
                cell.html(input);
                input.focus();

                var row = cell.closest('tr')
                var kode_barang = row.find('td:eq(0)').text()
                input.on('keydown', function(e) {
                    var newData = input.val()
                    var data = {
                        id: kode_barang,
                        kontak: newData
                    }
                    if (e.key === 'Enter') {
                        update(data, (res) => {
                            if (res == '') {
                                window.location.reload()
                            } else {
                                alert(res.msg);
                            }
                        })
                    }
                })
            });
            table.on('click', 'td.telepon', function() {
                var cell = $(this);
                var currentData = cell.text();
                var input = $('<input type="text" class="form-control">').val(currentData);
                input.attr('name', 'telepon')
                input.attr('id', 'telepon')
                cell.html(input);
                input.focus();

                var row = cell.closest('tr')
                var kode_barang = row.find('td:eq(0)').text()
                input.on('keydown', function(e) {
                    var newData = input.val()
                    var data = {
                        id: kode_barang,
                        telepon: newData
                    }
                    if (e.key === 'Enter') {
                        update(data, (res) => {
                            if (res == '') {
                                window.location.reload()
                            } else {
                                alert(res.msg);
                            }
                        })
                    }
                })
            });
            table.on('click', 'td.alamat', function() {
                var cell = $(this);
                var currentData = cell.text();
                var input = $('<input type="text" class="form-control">').val(currentData);
                input.attr('name', 'alamat')
                input.attr('id', 'alamat')
                cell.html(input);
                input.focus();

                var row = cell.closest('tr')
                var kode_barang = row.find('td:eq(0)').text()
                input.on('keydown', function(e) {
                    var newData = input.val()
                    var data = {
                        id: kode_barang,
                        alamat: newData
                    }
                    if (e.key === 'Enter') {
                        update(data, (res) => {
                            if (res == '') {
                                window.location.reload()
                            } else {
                                alert(res.msg);
                            }
                        })
                    }
                })
            });
            table.on('click', 'td.diskon', function() {
                var cell = $(this);
                var currentData = cell.text();
                var input = $('<input type="text" class="form-control">').val(currentData);
                input.attr('name', 'diskon')
                input.attr('id', 'diskon')
                cell.html(input);
                input.focus();

                var row = cell.closest('tr')
                var kode_barang = row.find('td:eq(0)').text()
                input.on('keydown', function(e) {
                    var newData = input.val()
                    var data = {
                        id: kode_barang,
                        diskon: newData
                    }
                    if (e.key === 'Enter') {
                        update(data, (res) => {
                            if (res == '') {
                                window.location.reload()
                            } else {
                                alert(res.msg);
                            }
                        })
                    }
                })
            });


            $('#table').on('blur', '.nama input', function() {
                var input = $(this);
                var newAddress = input.val();
                var cell = input.closest('.nama');
                cell.html(newAddress);
            });
            $('#table').on('blur', '.kontak input', function() {
                var input = $(this);
                var newAddress = input.val();
                var cell = input.closest('.kontak');
                cell.html(newAddress);
            });
            $('#table').on('blur', '.telepon input', function() {
                var input = $(this);
                var newAddress = input.val();
                var cell = input.closest('.telepon');
                cell.html(newAddress);
            });
            $('#table').on('blur', '.alamat input', function() {
                var input = $(this);
                var newAddress = input.val();
                var cell = input.closest('.alamat');
                cell.html(newAddress);
            });
            $('#table').on('blur', '.diskon input', function() {
                var input = $(this);
                var newAddress = input.val();
                var cell = input.closest('.diskon');
                cell.html(newAddress);
            });

            // update function

            function update(params, callback) {
                var _token = $("input[name='_token']").val()
                $.ajax({
                    url: "{{ route('supliers.update') }}",
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

        $(document).keydown(function(event) {
            if (event.key === 'Escape') {
                event.preventDefault()
                window.location.href = "{{ route('dashboard') }}"
            }
        })
    </script>
@endpush
