<?= $this->extend('Layouts/v_template.php'); ?>
<?= $this->section('content'); ?>
<!-- Main Content -->
<main>
    <div class="head-main">
        <h1>Permintaan Barang</h1>
        <?php include(APPPATH . 'Views\Layouts\v_profileNav.php'); ?>
    </div>

    <!-- Recent Orders Table -->
    <div class="recent-orders" style="margin-top: 1.5rem;">
        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0"
                        style="background-color: red !important">
                        <thead class="border">
                            <tr>
                                <th class="col-2">Nama Lengkap</th>
                                <th class="col-2">Email</th>
                                <th class="col-2">Barang Yang Dipinjam</th>
                                <th class="col-1">Kode Barang</th>
                                <th class="col-1">Jumlah Barang</th>
                                <th class="col-1">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="border">
                            <?php foreach ($permintaan as $v): ?>
                                <tr>
                                    <td>
                                        <?= $v['nama_lengkap']; ?>
                                    </td>
                                    <td>
                                        <?= $v['email']; ?>
                                    </td>
                                    <td>
                                        <?= $v['nama_barang']; ?>
                                    </td>
                                    <td>
                                        <?= $v['kode_barang']; ?>
                                    </td>
                                    <td>
                                        <?= $v['jumlah_barang'] ?>
                                    </td>
                                    <td>
                                        <button onclick="terima(<?= $v['id']; ?>)" type="button"
                                            class="btn btn-success btn-circle">
                                            <i class="fa-solid fa-check"></i>
                                        </button>
                                        <button onclick="tolak(<?= $v['id']; ?>)" type="button"
                                            class="btn btn-danger btn-circle">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
<?= $this->endSection(); ?>