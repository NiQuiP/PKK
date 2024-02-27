<div class="modal fade modal-lg" id="modalBarangKeluar" tabindex="-1" role="dialog" aria-labelledby="myModal"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <strong>Tambah Barang Keluar</strong>
            </div>
            <div class="modal-body">
                <form action="BarangKeluarController/store" method="post" id="myForm">
                    <div class="mb-3">
                        <label for="barangKeluar" class="form-label">Pilih Barang</label>
                        <select name="barang_keluar" id="barangKeluar" class="form-select" required>
                            <option value="" hideen></option>
                            <?php foreach ($inventori as $v): ?>
                                <option value="<?= $v['id_barang']; ?>">
                                    <?= $v['kode_barang'] . ' - ' . $v['nama_barang'] . ' ' . $v['merk_barang'] . ' Kondisi ' . $v['kondisi_barang'] . ' - ' . $v['jumlah_barang'] . ' Stock ' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="jumlahBarang" class="form-label">Jumlah</label>
                        <input type="text" class="form-control" id="jumlahBarang" name="jumlah" required>
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