<div class="modal fade" id="createItem" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Barang</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-danger" id="error"></p>
                <form action="" method="POST" id="formCreateItem">
                    @csrf
                    <div class="container">
                        <div class="row mb-3">
                            <label for="nama_barang" class="col-sm-2 col-form-label">Nama Barang</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="nama_barang" name="nama_barang">
                                <div class="invalid-feedback" id="nama_barang_error"></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="kode_barang" class="col-sm-2 col-form-label">Kode Barang</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="kode_barang" name="kode_barang">
                                <div class="invalid-feedback" id="kode_barang_error"></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="ukuran" class="col-sm-2 col-form-label">Ukuran (Kg/Grm)</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="ukuran" name="ukuran">
                                <div class="invalid-feedback " id="ukuran_error"></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="deskripsi" class="col-sm-2 col-form-label">Deskripsi</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="deskripsi" name="deskripsi">
                                <div class="invalid-feedback " id="deskripsi_error"></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="harga_beli" class="col-sm-2 col-form-label">Harga Beli</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="harga_beli" name="harga_beli">
                                <div class="invalid-feedback " id="harga_beli_error"></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="diskon_beli" class="col-sm-2 col-form-label">Diskon Beli</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="diskon_beli" name="diskon_beli">
                                <div class="invalid-feedback " id="diskon_beli_error"></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="harga_jual" class="col-sm-2 col-form-label">Harga Jual</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="harga_jual" name="harga_jual">
                                <div class="invalid-feedback" id="harga_jual_error"></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="diskon_jual" class="col-sm-2 col-form-label">Diskon Jual</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="diskon_jual" name="diskon_jual">
                                <div class="invalid-feedback" id="diskon_jual_error"></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="harga_jual" class="col-sm-2 col-form-label">Suplier</label>
                            <div class="col-sm-10">
                                <select class="form-select" aria-label="Default select example" id="suplier"
                                    name="suplier">
                                    <option value="" disabled selected>Pilih Suplier</option>
                                    @foreach ($supliers as $suplier)
                                        <option value="{{ $suplier->id }}">
                                            {{ $suplier->nama . ' - ' . $suplier->kontak }}</option>
                                    @endforeach
                                </select>

                                <div class="invalid-feedback" id="suplier_error"></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="diskon_jual" class="col-sm-2 col-form-label">Stok</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="stok" name="stok">
                                <div class="invalid-feedback" id="stok_error"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-{{ $theme == 'dark' ? 'light' : 'dark' }}" id="btnCreateItem">
                    <i class="bi bi-clipboard2-check"></i> Enter - Simpan
                </button>
                <button type="button" class="btn btn-outline-{{ $theme == 'dark' ? 'light' : 'dark' }}" data-bs-dismiss="modal">
                    <i class="bi bi-clipboard2-x text-danger"></i> Esc - Batal
                </button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        $('#createItem').on('show.bs.modal', function() {
            setTimeout(() => {
                $('#formCreateItem').find('input[name="nama_barang"]').focus()
            }, 1000);
            var myForm = $('#formCreateItem').find('input').attr('autocomplete', 'off')

            $('#btnCreateItem').click(function() {
                var form = {}
                $('#createItem .modal-body input').each(function() {
                    var input = $(this).attr('name')
                    var value = $(this).val()

                    form[input] = value
                })
                var suplier = $('#suplier').val()
                $.ajax({
                    url: "{{ route('items.store') }}",
                    method: "POST",
                    data: {
                        _token: form['_token'],
                        nama_barang: form['nama_barang'],
                        kode_barang: form['kode_barang'],
                        ukuran: form['ukuran'],
                        deskripsi: form['deskripsi'],
                        harga_beli: form['harga_beli'],
                        diskon_beli: form['diskon_beli'],
                        harga_jual: form['harga_jual'],
                        diskon_jual: form['diskon_jual'],
                        suplier: suplier,
                        stok: form['stok'],
                    },
                    success: function(res) {
                        if (res.status === true) {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Berhasil',
                                showConfirmButton: false,
                                timer: 1500
                            })
                            $('#createItem').modal('hide')
                        }
                        $('.invalid-feedback').html('');
                        for (const key in res) {
                            if (key) {
                                showErrorById(key + '_error', res[key][0])
                            }
                        }
                    }
                })
            })

            $(document).keydown((e) => {
                if (e.keyCode == 13) {
                    var form = {}
                    $('#createItem .modal-body input').each(function() {
                        var input = $(this).attr('name')
                        var value = $(this).val()

                        form[input] = value
                    })
                    var suplier = $('#suplier').val()
                    $.ajax({
                        url: "{{ route('items.store') }}",
                        method: "POST",
                        data: {
                            _token: form['_token'],
                            nama_barang: form['nama_barang'],
                            kode_barang: form['kode_barang'],
                            ukuran: form['ukuran'],
                            deskripsi: form['deskripsi'],
                            harga_beli: form['harga_beli'],
                            diskon_beli: form['diskon_beli'],
                            harga_jual: form['harga_jual'],
                            diskon_jual: form['diskon_jual'],
                            suplier: suplier,
                            stok: form['stok'],
                        },
                        success: function(res) {
                            if (res.status === true) {
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Berhasil',
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                                $('#createItem').modal('hide')
                            }
                            $('.invalid-feedback').html('');
                            for (const key in res) {
                                if (key) {
                                    showErrorById(key + '_error', res[key][0])
                                }
                            }
                        }
                    })
                }
            })
        })

        function showErrorById(id, message) {
            const errorDiv = $(`#${id}`);
            errorDiv.removeClass('d-none');
            errorDiv.addClass('d-block');
            errorDiv.html(message);
        }
    </script>
@endpush
