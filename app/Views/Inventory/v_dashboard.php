<?= $this->extend('Layouts/v_template.php'); ?>
<?= $this->section('content'); ?>
<!-- Main Content -->
<main>
    <div class="head-main">
        <h1>Dashboard</h1>
        <?php include(APPPATH . 'Views\Layouts\v_profileNav.php') ?>
    </div>
    <!-- Analyses -->
    <div class="card-info">
        <div class="goods">
            <div class="status">
                <div class="icon">
                    <i class="fa-solid fa-box fa-2xl"></i>
                </div>
                <div class="info">
                    <h3>Data Barang</h3>
                    <h1>
                        <?= $jumlahBarang; ?>
                    </h1>
                </div>
            </div>
        </div>
        <div class="loan">
            <div class="status">
                <div class="icon">
                    <i class="fa-solid fa-arrow-right-arrow-left fa-2xl"></i>
                </div>
                <div class="info">
                    <h3>Jumlah Peminjam</h3>
                    <h1>
                        <?= $jumlahPinjaman; ?>
                    </h1>
                </div>
            </div>
        </div>
        <div class="user">
            <div class="status">
                <div class="icon">
                    <i class="fa-solid fa-user fa-2xl"></i>
                </div>
                <div class="info">
                    <h3>Jumlah User</h3>
                    <h1>
                        <?= $jumlahUser; ?>
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Analyses -->

    <!-- Recent Orders Table -->
    <div class="recent-orders">
        <div class="card shadow">
            <div class="card-header">
                <strong>Data Peminjam Bulan Ini</strong>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0" id="dataTable" width="100%" cellspacing="0"
                        style="background-color: red !important">
                        <thead class="border">
                            <tr>
                                <th class="" style="width: 350px">Nama Peminjam</th>
                                <th class="" style="width: 350px">Nama Barang</th>
                                <th class="" style="width: 300px">Merk Barang</th>
                                <th class="" style="width: 200px">Status Pengembalian</th>
                                <th class="" style="width: 300px">Tanggal Pinjaman</th>
                            </tr>
                        </thead>
                        <tbody class="border">
                            <?php foreach ($dataPinjaman as $v): ?>
                                <tr>
                                    <td>
                                        <?= $v['nama_lengkap']; ?>
                                    </td>
                                    <td>
                                        <?= $v['nama_barang']; ?>
                                    </td>
                                    <td>
                                        <?= $v['merk_barang']; ?>
                                    </td>
                                    <td>
                                        <?= $v['status_pengembalian']; ?>
                                    </td>
                                    <td>
                                        <?= tanggal_indo($v['tanggal_pinjaman']) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php if (count($dataPinjaman) > 0): ?>
                        <div class="d-flex">
                            <h3><a href="<?= site_url('inventory/data-peminjam'); ?>">Selengkapnya...</a></h3>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>
<!-- End of Main Content -->
<?= $this->endSection(); ?>