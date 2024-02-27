<div class="modal fade modal-lg" id="modalBarang" tabindex="-1" role="dialog" aria-labelledby="myModal"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <strong>Tambah Barang</strong>
            </div>
            <div class="modal-body">
                <form action="BarangMasukController/store" method="post">
                    <div class="mb-3">
                        <label for="namaBarang" class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" id="namaBarang" name="nama_barang" required>
                        <div class="invalid-feedback errorNamaBarang">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="merkBarang" class="form-label">Merk Barang</label>
                        <input type="text" class="form-control" id="merkBarang" name="merk_barang" required>
                        <div class="invalid-feedback errorMerkBarang">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="jumlahBarang" class="form-label">Jumlah</label>
                        <input type="number" class="form-control" id="jumlahBarang" name="jumlah" required>
                        <div class="invalid-feedback errorJumlah">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="kondisiBarang" class="form-label">Kondisi</label>
                        <select name="kondisi" id="kondisiBarang" class="form-select" required>
                            <option value="" hidden></option>
                            <option value="Baik">Baik</option>
                            <option value="Rusak">Rusak</option>
                        </select>
                        <div class="invalid-feedback errorKondisi">
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button class="btn btn-primary" type="submit" id="kirim">Kirim</button>
            </div>
            </form>
        </div>
    </div>
</div>