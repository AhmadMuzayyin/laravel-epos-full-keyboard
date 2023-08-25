<div class="modal fade" id="importItem" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Import Barang</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('items.import') }}" method="POST" id="formCreateItem" enctype="multipart/form-data">
            <div class="modal-body">
                <p class="text-danger" id="error"></p>
                    @csrf
                    <div class="container">
                        <div class="row mb-3">
                            <label for="file" class="col-sm-2 col-form-label">File</label>
                            <div class="col-sm-10">
                                <input type="file" class="form-control" id="file"
                                    name="file">
                                <div class="invalid-feedback" id="file_error"></div>
                                <a href="{{ route('items.download') }}" target="_blank">Download format</a>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-outline-{{ $theme == 'dark' ? 'light' : 'dark' }}" id="btnCreateItem">
                    <i class="bi bi-clipboard2-check"></i> Enter - Simpan
                </button>
                <button type="button" class="btn btn-outline-{{ $theme == 'dark' ? 'light' : 'dark' }}" data-bs-dismiss="modal">
                    <i class="bi bi-clipboard2-x text-danger"></i> Esc - Batal
                </button>
            </div>
        </form>
        </div>
    </div>
</div>
