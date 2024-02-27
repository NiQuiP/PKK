<?= $this->extend('Layouts/v_template.php'); ?>
<?= $this->section('content'); ?>
<?php include(APPPATH . 'Views\Modal\m_BarangKeluar.php') ?>
<!-- Main Content -->
<main>
    <div class="head-main">
        <h1>Barang Keluar</h1>
        <?php include(APPPATH . 'Views\Layouts\v_profileNav.php') ?>
    </div>

    <div class="card-info p-0 d-inline">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalBarangKeluar"
            id="tambahBarangKeluar">Tambah
            Barang</button>
    </div>

    <!-- Recent Orders Table -->
    <div class="recent-orders">
        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0"
                        style="background-color: red !important">
                        <thead class="border">
                            <tr>
                                <th class="col-1">Kode Barang</th>
                                <th class="col-2" style="">Nama Barang</th>
                                <th class="col-2" style="">Merk Barang</th>
                                <th class="col-1" style="">Jumlah</th>
                                <th class="col-2" style="">Kondisi Barang</th>
                                <th class="col-1" style="">Foto Barang</th>
                                <th class="col-2" style="">Tanggal</th>
                                <th class="col-1" style="min-width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="border">
                            <?php foreach ($barang_keluar as $v): ?>
                            <tr style="vertical-align: middle;">
                                <td>
                                    <?= $v['kode_barang']; ?>
                                </td>
                                <td>
                                    <?= $v['nama_barang']; ?>
                                </td>
                                <td>
                                    <?= $v['merk_barang']; ?>
                                </td>
                                <td>
                                    <?= $v['jumlah_barang']; ?>
                                </td>
                                <td>
                                    <?= $v['kondisi_barang']; ?>
                                </td>
                                <td>

                                </td>
                                <td>
                                    <?= $v['tanggal_keluar']; ?>
                                </td>
                                <td>
                                    <div class="p-1">
                                        <a href="#" class="btn btn-danger d-inline mx-1"
                                            onclick="hapus_barangKeluar(<?= $v['id_barang']; ?>)">
                                            <i class="fas fa-trash text-white"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?= $pager->links('barang', 'barangtable'); ?>
                </div>
            </div>
        </div>
    </div>
</main>
<?= $this->endSection(); ?>