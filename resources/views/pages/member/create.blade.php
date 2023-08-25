<div class="modal fade" id="createMember" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Member</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="formCreateMember">
                    @csrf
                    <div class="container">
                        <div class="row mb-3">
                            <label for="nama_member" class="col-sm-2 col-form-label">Nama Member</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="nama_member" name="nama_member">
                                <div class="invalid-feedback" id="nama_member_error"></div>
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
                        <div class="row mb-3">
                            <label for="diskon" class="col-sm-2 col-form-label">Diskon</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="diskon" name="diskon">
                                <div class="invalid-feedback " id="diskon_error"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-{{ $theme == 'dark' ? 'light' : 'dark' }}" id="btncreateMember">
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
        $('#createMember').on('show.bs.modal', function() {
            setTimeout(() => {
                $('#formCreateMember').find('input[name="nama_member"]').focus()
            }, 1000);
            var myForm = $('#formCreateMember').find('input').attr('autocomplete', 'off')

            $('#btncreateMember').click(function() {
                var form = {}
                $('#createMember .modal-body input').each(function() {
                    var input = $(this).attr('name')
                    var value = $(this).val()

                    form[input] = value
                })
                $.ajax({
                    url: "{{ route('members.store') }}",
                    method: "POST",
                    data: {
                        _token: form['_token'],
                        nama_member: form['nama_member'],
                        alamat: form['alamat'],
                        telepon: form['telepon'],
                        kontak: form['kontak'],
                        diskon: form['diskon']
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
                            $('#createMember').modal('hide')
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
                    $('#createMember .modal-body input').each(function() {
                        var input = $(this).attr('name')
                        var value = $(this).val()

                        form[input] = value
                    })
                    $.ajax({
                        url: "{{ route('members.store') }}",
                        method: "POST",
                        data: {
                            _token: form['_token'],
                            nama_member: form['nama_member'],
                            alamat: form['alamat'],
                            telepon: form['telepon'],
                            kontak: form['kontak'],
                            diskon: form['diskon']
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
                                $('#createMember').modal('hide')
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
