<?= $this->extend('Layouts/v_template.php'); ?>
<?= $this->section('content'); ?>
<?php include(APPPATH . 'Views\Modal\m_TambahUser.php'); ?>
<?php include(APPPATH . 'Views\Modal\m_EditUser.php'); ?>
<!-- Main Content -->
<main>
    <div class="head-main">
        <h1>Data User</h1>
        <?php include(APPPATH . 'Views\Layouts\v_profileNav.php'); ?>
    </div>

    <div class="card-info p-0 d-inline">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahUser">Tambah User</button>
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
                                <th style="min-width: 50px;">No</th>
                                <th class="col-2">Nama Lengkap</th>
                                <th class="col-2">Email</th>
                                <th class="col-2">Username</th>
                                <th class="col-2">Password</th>
                                <th class="col-1">Foto</th>
                                <th class="col-1">Role</th>
                                <th class="col-2" style="width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="border">
                            <?php foreach ($data_user as $v): ?>
                                <tr>
                                    <td>
                                        <?= $nomor ?>
                                    </td>
                                    <td>
                                        <?= $v['nama_lengkap']; ?>
                                    </td>
                                    <td>
                                        <?= $v['email']; ?>
                                    </td>
                                    <td>
                                        <?= $v['username']; ?>
                                    </td>
                                    <td>
                                        <?= $v['password']; ?>
                                    </td>
                                    <td>
                                        <div class="logo d-flex justify-content-center">
                                            <img src="<?= base_url('img/' . $v['foto']) ?>" alt=""
                                                onclick="tampilkanPopup('<?= base_url('img/' . $v['foto']) ?>')">
                                        </div>
                                    </td>
                                    <td>
                                        <?= $v['role']; ?>
                                    </td>
                                    <td>
                                        <button onclick="hapus_user(<?= $v['id']; ?>)" type="button"
                                            class="btn btn-danger btn-circle">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                        <button onclick="show_user(<?= $v['id']; ?>)" type="button"
                                            class="btn btn-warning btn-circle" data-bs-target="#EditUser"
                                            data-bs-toggle="modal">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php $nomor++; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?= $pager->links('user', 'barangtable'); ?>
                </div>
            </div>
        </div>
    </div>
</main>
<script>

</script>
<?= $this->endSection(); ?>