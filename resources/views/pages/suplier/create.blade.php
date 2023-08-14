<div class="modal fade" id="createSuplier" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Suplier</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="formcreateSuplier">
                    @csrf
                    <div class="container">
                        <div class="row mb-3">
                            <label for="nama_suplier" class="col-sm-2 col-form-label">Nama Suplier</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="nama_suplier" name="nama_suplier">
                                <div class="invalid-feedback" id="nama_suplier_error"></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="alamat" name="alamat">
                                <div class="invalid-feedback" id="alamat_error"></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="telepon" class="col-sm-2 col-form-label">Telepon</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="telepon" name="telepon">
                                <div class="invalid-feedback " id="telepon_error"></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="kontak" class="col-sm-2 col-form-label">Kontak</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="kontak" name="kontak">
                                <div class="invalid-feedback " id="kontak_error"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-light" id="btncreateSuplier">
                    <i class="bi bi-clipboard2-check"></i> Enter - Simpan
                </button>
                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">
                    <i class="bi bi-clipboard2-x text-danger"></i> Esc - Batal
                </button>
            </div>
        </div>
    </div>
</div>
@push('js')
    <script>
        $('#createSuplier').on('show.bs.modal', function() {
            setTimeout(() => {
                $('#formcreateSuplier').find('input[name="nama_suplier"]').focus()
            }, 1000);
            var myForm = $('#formcreateSuplier').find('input').attr('autocomplete', 'off')

            $('#btncreateSuplier').click(function() {
                var form = {}
                $('#createSuplier .modal-body input').each(function() {
                    var input = $(this).attr('name')
                    var value = $(this).val()

                    form[input] = value
                })
                $.ajax({
                    url: "{{ route('supliers.store') }}",
                    method: "POST",
                    data: {
                        _token: form['_token'],
                        nama_suplier: form['nama_suplier'],
                        alamat: form['alamat'],
                        telepon: form['telepon'],
                        kontak: form['kontak'],
                    },
                    success: function(res) {
                        if (res.status === true) {
                            $('#createSuplier').modal('hide')
                        }
                        for (const key in res) {
                            if (res.hasOwnProperty(key)) {
                                const element = res[key];

                                $('#' + key + '_error').text(element[0])
                            }
                        }
                    }
                })
            })

            $(document).keydown((e) => {
                if (e.keyCode == 13) {
                    var form = {}
                    $('#createSuplier .modal-body input').each(function() {
                        var input = $(this).attr('name')
                        var value = $(this).val()

                        form[input] = value
                    })
                    $.ajax({
                        url: "{{ route('supliers.store') }}",
                        method: "POST",
                        data: {
                            _token: form['_token'],
                            nama_suplier: form['nama_suplier'],
                            alamat: form['alamat'],
                            telepon: form['telepon'],
                            kontak: form['kontak'],
                            diskon: form['diskon']
                        },
                        success: function(res) {
                            if (res.status === true) {
                                $('#createSuplier').modal('hide')
                            }
                            for (const key in res) {
                                if (res.hasOwnProperty(key)) {
                                    const element = res[key];

                                    $('#' + key + '_error').text(element[0])
                                }
                            }
                        }
                    })
                }
            })
        })
    </script>
@endpush
