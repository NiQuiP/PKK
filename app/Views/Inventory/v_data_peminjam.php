<?= $this->extend('Layouts/v_template.php'); ?>
<?= $this->section('content'); ?>
<!-- Main Content -->
<main>
    <div class="head-main">
        <h1>Data Peminjam</h1>
        <?php include(APPPATH . 'Views\Layouts\v_profileNav.php') ?>
    </div>
    <!-- Recent Orders Table -->
    <div class="recent-orders" style="margin-top:1.5rem;">
        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0"
                        style="background-color: red !important">
                        <thead class="border">
                            <tr>
                                <th class="col-2">Nama Peminjam</th>
                                <th class="col-2">Tanggal</th>
                                <th class="col-1">Kode Barang</th>
                                <th class="col-2">Nama Barang</th>
                                <th class="col-1">Kondisi Barang</th>
                                <th class="col-1">Jumlah Barang</th>
                                <th class="col-1">Ruangan</th>
                            </tr>
                            </tr>
                        </thead>
                        <tbody class="border">
                            <?php foreach ($dataPinjaman as $v): ?>
                            <tr>
                                <td>
                                    <?= $v['nama_lengkap']; ?>
                                </td>
                                <td>
                                    <?= tanggal_indo($v['tanggal_pinjaman']) ?>
                                </td>
                                <td>
                                    <?= $v['kode_barang']; ?>
                                </td>
                                <td>
                                    <?= $v['nama_barang']; ?>
                                </td>
                                <td>
                                    <?= $v['kondisi_barang']; ?>
                                </td>
                                <td>
                                    <?= $v['jumlah_barang']; ?>
                                </td>
                                <td>
                                    <?= $v['ruangan']; ?>
                                </td>
                                <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?= $pager->links('pinjaman', 'barangtable'); ?>
                </div>
            </div>
        </div>
    </div>
</main>
<!-- End of Main Content -->
<?= $this->endSection(); ?>