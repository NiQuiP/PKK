<?= $this->extend('Layouts/v_template.php'); ?>
<?= $this->section('content'); ?>
<?php include(APPPATH . 'Views\Modal\m_DataBarangEdit.php') ?>
<!-- Main Content -->
<main>
    <div class="head-main">
        <h1>Data Barang</h1>
        <?php include(APPPATH . 'Views\Layouts\v_profileNav.php') ?>
    </div>

    <!-- Recent Orders Table -->
    <div class="recent-orders" style="margin-top: 1.5rem;">
        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered mt-3" id="dataTable" width="100%" cellspacing="0"
                        style="background-color: red !important">
                        <thead class="border">
                            <tr>
                                <th class="col-1">Kode Barang</th>
                                <th class="col-3" style="">Nama Barang</th>
                                <th class="col-2" style="">Merk Barang</th>
                                <th class="col-1" style="">Jumlah</th>
                                <th class="col-2" style="">Kondisi Barang</th>
                                <th class="col-1" style="">Foto Barang</th>
                                <?php if(session('redirected') == 'admin'): ?>
                                <th class="col-2" style="width: 150px;">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody class="border">
                            <?php foreach ($dataBarang as $v): ?>
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
                                        <div class="logo d-flex justify-content-center">
                                            <img src="<?= base_url('img/' . $v['foto_barang']) ?>" alt=""
                                                onclick="tampilkanPopup('<?= base_url('img/' . $v['foto_barang']) ?>')">
                                        </div>
                                    </td>
                                    <?php if(session('redirected') == 'admin'): ?>
                                    <td>
                                        <!-- <div class="p-2"> -->
                                            <a href="#" class="btn btn-danger d-inline mx-1"
                                                onclick="hapus_dataBarang(<?= $v['id_barang']; ?>)">
                                                <i class="fas fa-trash text-white"></i>
                                            </a>
                                            <a href="#" onclick="show_dataBarang(<?= $v['id_barang']; ?>)"
                                                class="btn btn-warning d-inline" data-bs-target="#DataBarangEdit"
                                                data-bs-toggle="modal">
                                                <i class="fa-solid fa-pen-to-square text-white"></i>
                                            </a>
                                        <!-- </div> -->
                                    </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?= $pager->links('barang', 'barangtable') ?>
                </div>
            </div>
        </div>
    </div>
</main>
<script>

</script>
<!-- End of Main Content -->
<?= $this->endSection(); ?>