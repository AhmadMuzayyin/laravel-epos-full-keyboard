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
                <h3 class="mb-2 mt-2">Stok Barang</h5>
            </div>
            <div class="col text-end">
                <button type="button" class="btn btn-outline-{{ $theme == 'dark' ? 'light' : 'dark' }}" id="import"><i
                        class="bi bi-file-earmark-bar-graph"></i> Import</button>
                <a href="#" role="button" class="btn btn-outline-{{ $theme == 'dark' ? 'light' : 'dark' }}"><i
                        class="bi bi-pencil"></i>
                    Enter -
                    Simpan</a>
                <a href="#" role="button" class="btn btn-outline-{{ $theme == 'dark' ? 'light' : 'dark' }}"
                    id="itemDelete"><i class="bi bi-trash text-danger"></i>
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
                        <th>Stok</th>
                        <th>Suplier</th>
                        <th>Harga Beli</th>
                        <th>Diskon Beli</th>
                        <th>Harga Jual</th>
                        <th>Diskon Jual</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    @include('pages.item.import')
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            var table = $('#table').DataTable({
                serverSide: true,
                select: 'single',
                ajax: "{{ route('items.index') }}",
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
                        data: 'stok',
                        name: 'stok',
                        className: 'stok'
                    },
                    {
                        data: 'nama_suplier',
                        name: 'nama_suplier',
                        className: 'suplier'
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
                    // Add more columns as needed
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
                                        url: "{{ route('items.destroy') }}",
                                        method: "POST",
                                        data: {
                                            id: data[0].id,
                                            _token
                                        },
                                        success: (res) => {
                                            if (res.status == true) {
                                                Swal.fire({
                                                    position: 'top-end',
                                                    icon: 'success',
                                                    title: 'Berhasil',
                                                    showConfirmButton: false,
                                                    timer: 1500
                                                })
                                                // window.location.reload()
                                                table.draw()
                                            }
                                        }
                                    })
                                } else if (result.isDenied) {
                                    Swal.fire('Changes are not saved', '', 'info')
                                }
                            })
                        }
                    })
                }
                data = table.rows('.selected').data().toArray();
            });

            $('#itemDelete').on('click', function() {
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
                            url: "{{ route('items.destroy') }}",
                            method: "POST",
                            data: {
                                id: data[0].id,
                                _token
                            },
                            success: (res) => {
                                if (res.status == true) {
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'success',
                                        title: 'Berhasil',
                                        showConfirmButton: false,
                                        timer: 1500
                                    })
                                    // window.location.reload()
                                    table.draw()
                                }
                            }
                        })
                    } else if (result.isDenied) {
                        Swal.fire('Changes are not saved', '', 'info')
                    }
                })
                // table.row('.selected').remove().draw(false);
            });


            table.on('click', 'td.nama', function() {
                var cell = $(this);
                var currentData = cell.text();
                var input = $('<input type="text" class="form-control">').val(currentData);
                input.attr('name', 'nama_barang')
                input.attr('id', 'nama_barang')
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
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Berhasil',
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                                // window.location.reload()
                                table.draw()
                            } else {
                                alert(res.msg);
                            }
                        })
                    }
                })
            });
            table.on('click', 'td.suplier', function() {
                var cell = $(this);
                var currentData = cell.text();

                cell.popover({
                    title: 'Pilih Suplier',
                    content: function() {
                        var select = $('<select>').addClass('form-control form-select-sm');
                        var closeButton = $('<button class="btn btn-sm">').addClass('close')
                            .attr('aria-label', 'Close').html(
                                '<i aria-hidden="true" class="bi bi-x text-danger"></i>');
                        $.ajax({
                            url: "{{ route('getsuplier') }}",
                            method: 'GET',
                            success: function(response) {
                                response.supliers.forEach(function(suplier) {
                                    var option = $('<option>').val(suplier
                                        .id).text(suplier.nama);

                                    if (suplier.nama == currentData) {
                                        option.attr('selected', 'selected')
                                    }
                                    select.append(option);
                                });
                                closeButton.on('click', function() {
                                    cell.popover('hide');
                                });

                                var row = cell.closest('tr')
                                var kode_barang = row.find('td:eq(0)').text()
                                select.on('keydown', function(e) {
                                    var newData = select.val()
                                    var data = {
                                        id: kode_barang,
                                        suplier: newData
                                    }
                                    if (e.key === 'Enter') {
                                        update(data, (res) => {
                                            if (res == '') {
                                                cell.popover(
                                                    'hide');
                                                Swal.fire({
                                                    position: 'top-end',
                                                    icon: 'success',
                                                    title: 'Berhasil',
                                                    showConfirmButton: false,
                                                    timer: 1500
                                                })
                                                // window.location.reload()
                                                table.draw()
                                            } else {
                                                alert(res.msg);
                                            }
                                        })
                                    }
                                })
                            }
                        });

                        // return select;
                        return $('<div>').addClass('popover-content input-group').append(select)
                            .append(closeButton);
                    },
                    html: true,
                    placement: 'right',
                    trigger: 'manual'
                });
                cell.popover('show');
            });
            table.on('click', 'td.harga_beli', function() {
                var cell = $(this);
                var currentData = cell.text();
                var input = $('<input type="text" class="form-control">').val(currentData);
                input.attr('name', 'harga_beli')
                input.attr('id', 'harga_beli')
                cell.html(input);
                input.focus();

                var row = cell.closest('tr')
                var kode_barang = row.find('td:eq(0)').text()
                input.on('keydown', function(e) {
                    var newData = input.val()
                    var data = {
                        id: kode_barang,
                        harga_beli: newData
                    }
                    if (e.key === 'Enter') {
                        update(data, (res) => {
                            if (res == '') {
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Berhasil',
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                                // window.location.reload()
                                table.draw()
                            } else {
                                alert(res.msg);
                            }
                        })
                    }
                })
            });
            table.on('click', 'td.diskon_beli', function() {
                var cell = $(this);
                var currentData = cell.text();
                var input = $('<input type="text" class="form-control">').val(currentData);
                input.attr('name', 'diskon_beli')
                input.attr('id', 'diskon_beli')
                cell.html(input);
                input.focus();

                var row = cell.closest('tr')
                var kode_barang = row.find('td:eq(0)').text()
                input.on('keydown', function(e) {
                    var newData = input.val()
                    var data = {
                        id: kode_barang,
                        diskon_beli: newData
                    }
                    if (e.key === 'Enter') {
                        update(data, (res) => {
                            if (res == '') {
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Berhasil',
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                                // window.location.reload()
                                table.draw()
                            } else {
                                alert(res.msg);
                            }
                        })
                    }
                })
            });
            table.on('click', 'td.harga_jual', function() {
                var cell = $(this);
                var currentData = cell.text();
                var input = $('<input type="text" class="form-control">').val(currentData);
                input.attr('name', 'harga_jual')
                input.attr('id', 'harga_jual')
                cell.html(input);
                input.focus();

                var row = cell.closest('tr')
                var kode_barang = row.find('td:eq(0)').text()
                input.on('keydown', function(e) {
                    var newData = input.val()
                    var data = {
                        id: kode_barang,
                        harga_jual: newData
                    }
                    if (e.key === 'Enter') {
                        update(data, (res) => {
                            if (res == '') {
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Berhasil',
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                                // window.location.reload()
                                table.draw()
                            } else {
                                alert(res.msg);
                            }
                        })
                    }
                })
            });
            table.on('click', 'td.diskon_jual', function() {
                var cell = $(this);
                var currentData = cell.text();
                var input = $('<input type="text" class="form-control">').val(currentData);
                input.attr('name', 'diskon_jual')
                input.attr('id', 'diskon_jual')
                cell.html(input);
                input.focus();

                var row = cell.closest('tr')
                var kode_barang = row.find('td:eq(0)').text()
                input.on('keydown', function(e) {
                    var newData = input.val()
                    var data = {
                        id: kode_barang,
                        diskon_jual: newData
                    }
                    if (e.key === 'Enter') {
                        update(data, (res) => {
                            if (res == '') {
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Berhasil',
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                                // window.location.reload()
                                table.draw()
                            } else {
                                alert(res.msg);
                            }
                        })
                    }
                })
            });
            table.on('click', 'td.stok', function() {
                var cell = $(this);
                var currentData = cell.text();
                var input = $('<input type="text" class="form-control">').val(currentData);
                input.attr('name', 'stok')
                input.attr('id', 'stok')
                cell.html(input);
                input.focus();

                var row = cell.closest('tr')
                var kode_barang = row.find('td:eq(0)').text()
                input.on('keydown', function(e) {
                    var newData = input.val()
                    var data = {
                        id: kode_barang,
                        stok: newData
                    }
                    if (e.key === 'Enter') {
                        update(data, (res) => {
                            if (res == '') {
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Berhasil',
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                                // window.location.reload()
                                table.draw()
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
            $('#table').on('blur', '.suplier', function(event) {
                var input = $(this);
                var newAddress = input.val();
                // var cell = input.closest('.suplier');
                // cell.html(newAddress);
                input.popover('hide')
            });
            $('#table').on('blur', '.harga_beli input', function() {
                var input = $(this);
                var newAddress = input.val();
                var cell = input.closest('.harga_beli');
                cell.html(newAddress);
            });
            $('#table').on('blur', '.diskon_beli input', function() {
                var input = $(this);
                var newAddress = input.val();
                var cell = input.closest('.diskon_beli');
                cell.html(newAddress);
            });
            $('#table').on('blur', '.harga_jual input', function() {
                var input = $(this);
                var newAddress = input.val();
                var cell = input.closest('.harga_jual');
                cell.html(newAddress);
            });
            $('#table').on('blur', '.diskon_jual input', function() {
                var input = $(this);
                var newAddress = input.val();
                var cell = input.closest('.diskon_jual');
                cell.html(newAddress);
            });
            $('#table').on('blur', '.stok input', function() {
                var input = $(this);
                var newAddress = input.val();
                var cell = input.closest('.stok');
                cell.html(newAddress);
            });

            // update function

            function update(params, callback) {
                var _token = $("input[name='_token']").val()
                $.ajax({
                    url: "{{ route('items.update') }}",
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
        $(document).ready(function() {
            let openModal = false
            $('#import').click(function() {
                $('#importItem').modal('show')
            })
            $('#importItem').on('show.bs.modal', function() {
                openModal = true
            })
            $('#importItem').on('hide.bs.modal', function() {
                setTimeout(() => {
                    openModal = false
                }, 3000);
            })
            $(document).keydown(function(event) {
                event.preventDefault()
                if (event.key === 'Escape') {
                    if (openModal == true) {
                        $('.modal').modal('hide')
                    }
                    if (openModal == false) {
                        window.location.href = "{{ route('dashboard') }}"
                    }
                }
            })
        })
    </script>
@endpush
