<div class="modal fade modal-lg" id="modalKembalikanBarang" tabindex="-1" role="dialog" aria-labelledby="myModal"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <strong>Retur Barang</strong>
            </div>
            <div class="modal-body">
                <form action="DataPinjamanController/kembalikan" method="post" id="myForm2">
                    <div class="mb-3">
                        <label for="kembalikanBarang" class="form-label">Pilih Barang</label>
                        <select name="kembalikan_barang" id="kembalikanBarang" class="form-select" required>
                            <option value="" hidden></option>
                            <?php foreach ($data_barang as $v): ?>
                                <option value="<?= $v['id']; ?>">
                                    <?= $v['kode_barang'] . ' - ' . $v['nama_barang'] . ' ' . $v['merk_barang'] . ' Kondisi ' . $v['kondisi_barang'] . ' - ' . $v['jumlah_barang'] . ' Stock - ' . $v['status_pinjaman'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="jumlahBarang" class="form-label">Jumlah</label>
                        <input type="number" class="form-control" id="jumlahBarang" name="jumlah" required>
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