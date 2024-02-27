<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <script src="https://kit.fontawesome.com/fb6ebd8b45.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="<?= base_url('css/style.css'); ?>" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <title>web OrPus | Dashboard</title>
    <style>
        tr {
            vertical-align: middle;
        }

        #popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            justify-content: center;
            align-items: center;
            cursor: pointer;
        }

        #popup img {
            width: 40%;
            height: 80%;
            cursor: default;
            filter: none;
            position: fixed;
        }

        td img {
            width: 35px;
            height: 35px;
            margin-right: 0.5rem;
            border-radius: 50%;
            vertical-align: middle;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="kontainer">
        <!-- Sidebar Section -->
        <aside>
            <div class="toggle">
                <div class="logo" style="cursor: pointer;">
                    <img src="<?= base_url('img/logo.png'); ?>"
                        onclick="tampilkanPopup('<?= base_url('img/logo.png') ?>')" />
                    <h2>Or<span class="danger">Pus</span></h2>
                </div>
                <div class="close" id="close-btn">
                    <span class="material-icons-sharp"> close </span>
                </div>
            </div>

            <div class="sidebar">
                <?php if (session('redirected') == 'admin'): ?>
                    <a href="<?= site_url('inventory/dashboard'); ?>"
                        class="navbar-item <?= isset($aktif_dashboard) ? 'active' : ''; ?>">
                        <span class="material-icons-sharp"> dashboard </span>
                        <h3>Dashboard</h3>
                    </a>
                <?php endif; ?>

                <a href="<?= site_url('inventory/data-barang'); ?>"
                    class="navbar-item <?= isset($aktif_data_barang) ? 'active' : ''; ?>">
                    <span class="material-icons-sharp"> inventory_2 </span>
                    <h3>Data Barang</h3>
                </a>

                <?php if (session('redirected') == 'admin'): ?>
                    <a href="#" class="navbar-item sub-btn <?= isset($aktif_transaksi) ? 'active' : ''; ?>">
                        <span class="material-icons-sharp"> assignment </span>
                        <h3>Transaksi</h3>
                        <i class="fa-solid fa-angle-right"></i>
                    </a>

                    <div class="collaps">
                        <a href="<?= site_url('inventory/barang-masuk'); ?>"
                            class="sub-menu <?= isset($aktif_barang_masuk) ? 'active' : ''; ?>">
                            <span class="material-icons-sharp"> inventory_2 </span>
                            <h3>Barang Masuk</h3>
                        </a>
                        <a href="<?= site_url('inventory/barang-keluar'); ?>"
                            class="sub-menu <?= isset($aktif_barang_keluar) ? 'active' : ''; ?>">
                            <span class="material-icons-sharp"> inventory_2 </span>
                            <h3>Barang Keluar</h3>
                        </a>
                    </div>
                    <a href="<?= site_url('inventory/data-peminjam'); ?>"
                        class="navbar-item <?= isset($aktif_pinjaman) ? 'active' : ''; ?>">
                        <span class="material-symbols-outlined"> partner_exchange </span>
                        <h3>Data Peminjam</h3>
                    </a>
                    <a href="<?= site_url('inventory/permintaan'); ?>"
                        class="navbar-item <?= isset($aktif_permintaan) ? 'active' : ''; ?>">
                        <span class="material-icons-sharp"> mail_outline </span>
                        <h3>Permintaan</h3>
                        <span class="message-count text-white">
                            <?= isset($jumlah_permintaan) ? $jumlah_permintaan : '0' ?>
                        </span>
                    </a>
                    <a href="<?= site_url('inventory/data-user'); ?>"
                        class="navbar-item <?= isset($aktif_data_user) ? 'active' : ''; ?>">
                        <span class="material-symbols-outlined"> group </span>
                        <h3>Data User</h3>
                    </a>
                    <a href="<?= site_url('inventory/data-ruangan'); ?>"
                        class="navbar-item <?= isset($aktif_data_ruangan) ? 'active' : ''; ?>">
                        <span class="material-symbols-outlined"> nest_multi_room </span>
                        <h3>Data Ruangan</h3>
                    </a>
                <?php endif; ?>

                <?php if (session('redirected') == 'user'): ?>
                    <a href="<?= site_url('inventory/data-pinjaman'); ?>"
                        class="navbar-item <?= isset($aktif_pinjaman) ? 'active' : ''; ?>">
                        <span class="material-symbols-outlined"> partner_exchange </span>
                        <h3>Data Pinjaman</h3>
                    </a>
                <?php endif; ?>

                <a href="<?= site_url('inventory/setting'); ?>"
                    class="navbar-item setting <?= isset($aktif_setting) ? 'active' : ''; ?>">
                    <span class="material-icons-sharp"> settings </span>
                    <h3>Settings</h3>
                </a>
                <a class="navbar-item" onclick="logout()" style="cursor: pointer;">
                    <span class="material-icons-sharp"> logout </span>
                    <h3>Logout</h3>
                </a>
            </div>
        </aside>
        <!-- End of Sidebar Section -->

        <?= $this->renderSection('content'); ?>

    </div>
    <div id="popup" style="display: none;">
        <img id="gambar" src="" alt="Preview">
    </div>
</body>
<script src="<?= base_url('js/index.js'); ?>"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php include(APPPATH . 'Views/Pesan/pesan_sukses.php') ?>

<script>
    function hapus_validasi_barang() {
        $("#namaBarang").removeClass("is-invalid");
        $("#merkBarang").removeClass("is-invalid");
        $("#kondisiBarang").removeClass("is-invalid");
        $("#jumlahBarang").removeClass("is-invalid");
    }

    function hapus_validasi_user() {
        $("#NamaLengkap").removeClass("is-invalid");
        $("#email").removeClass("is-invalid");
        $("#username").removeClass("is-invalid");
    }

    function hapus_validasi_user_edit() {
        $("#EditNamaLengkap").removeClass("is-invalid");
        $("#Editemail").removeClass("is-invalid");
        $("#Editusername").removeClass("is-invalid");
        $("#Editpasswords").removeClass("is-invalid");
    }

    function reload() {
        window.location.reload(true);
    }

    // Preview image
    function tampilkanPopup(sumberGambar) {
        var popup = document.getElementById('popup');
        var gambarPopup = popup.querySelector('img');
        var wrapper = document.getElementById('wrapper');

        gambarPopup.src = sumberGambar;

        popup.style.display = "flex";
        wrapper.style.filter = "blur(8px)";
    }

    function sembunyikanPopup() {
        var popup = document.getElementById('popup');
        var wrapper = document.getElementById('wrapper');

        popup.style.display = "none";
        wrapper.style.filter = "none";
    }

    $('#popup').on("click", function () {
        sembunyikanPopup();
    });
    // End of Preview Image

    $(document).ready(function () {
        $(".sub-btn").click(function () {
            $(this).next(".collaps").slideToggle();
            $(this).find(".fa-angle-right").toggleClass("rotate");


        });

        $('#modalBarangKeluar').on('shown.bs.modal', function () {
            var $dropDown = $('#barangKeluar');
            $dropDown.select2({
                dropdownParent: $('#modalBarangKeluar')
            });
        });
        $('#modalPinjamBarang').on('shown.bs.modal', function () {
            var $dropDown = $('#pinjamBarang');
            $dropDown.select2({
                dropdownParent: $('#modalPinjamBarang')
            });
        });
        $('#modalKembalikanBarang').on('shown.bs.modal', function () {
            var $dropDown = $('#kembalikanBarang');
            $dropDown.select2({
                dropdownParent: $('#modalKembalikanBarang')
            });
        });
    });

    // data barang
    function hapus_dataBarang($id) {
        Swal.fire({
            title: "Yakin ingin hapus?",
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, hapus!"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?= site_url('Inventory/DataBarangController/destroy') ?>/" + $id;
            }
        });
    }

    function show_dataBarang($id) {
        $.ajax({
            url: "DataBarangController/show/" + $id,
            type: 'get',
            dataType: 'json',
            success: function (response) {
                $('#idBarang').val(response.id_barang);
                $('#kodeBarang').val(response.kode_barang);
                $('#namaBarang').val(response.nama_barang);
                $('#merkBarang').val(response.merk_barang);
                $('#kondisiBarang').val(response.kondisi_barang);
                $('#jumlahBarang').val(response.jumlah_barang);
            }
        });
    }

    function edit_dataBarang() {
        window.location.href = "<?= site_url('Inventory/DataBarangController/edit') ?>/" + $id;
    }
    // end of data barangg

    // barang masuk
    function hapus_barangMasuk($id) {
        Swal.fire({
            title: "Yakin ingin hapus?",
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, hapus!"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?= site_url('Inventory/BarangMasukController/destroy') ?>/" + $id;
            }
        });
    }
    // end of barang masuk

    // barang keluar
    function hapus_barangKeluar($id) {
        Swal.fire({
            title: "Yakin ingin hapus?",
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, hapus!"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?= site_url('Inventory/BarangKeluarController/destroy') ?>/" + $id;
            }
        });
    }
    // end of barnag keluar

    // terima permintaan pinjaman
    function terima($id) {
        window.location.href = '<?= site_url('Inventory/PermintaanController/terima/') ?> ' + $id;
    }
    // end of terima permintaan pinjaman

    // tolak permintaan pinjaman
    function tolak($id) {
        window.location.href = '<?= site_url('Inventory/PermintaanController/tolak/') ?> ' + $id;
    }
    // end of tolak permintaan pinjaman
    // tambah user 
    function TambahUser() {
        var nama_lengkap = $('#NamaLengkap').val();
        var username = $('#username').val();
        var email = $('#email').val();
        var password = $('#password').val();

        $.ajax({
            url: '<?= site_url("Inventory/UserController/store"); ?>',
            type: 'post',
            data: {
                nama_lengkap: nama_lengkap,
                username: username,
                email: email,
                password: password
            },
            dataType: 'json',
            success: function (response) {
                if (response.error) {
                    if (response.error.nama_lengkap) {
                        $('#NamaLengkap').addClass('is-invalid');
                        $('.errorNamaLengkap').html(response.error.nama_lengkap);
                    }
                    if (response.error.email) {
                        $('#email').addClass('is-invalid');
                        $('.errorEmail').html(response.error.email);
                    }
                    if (response.error.username) {
                        $('#username').addClass('is-invalid');
                        $('.errorUsername').html(response.error.username);
                    }
                } else if (response.success == true) {
                    reload();
                }
            },
        });
        hapus_validasi_user();
    }
    // end of tambah user

    // edit user 
    function show_user($id) {
        $.ajax({
            url: '<?= site_url('Inventory/UserController/show/') ?>' + $id,
            type: 'get',
            dataType: 'json',
            success: function (response) {
                $('#Edit_id').val(response.id);
                $('#EditNamaLengkap').val(response.nama_lengkap);
                $('#Editemail').val(response.email);
                $('#Editusername').val(response.username);
                $('#Editpassword').val(response.password);
                $('#Editrole').val(response.role);
            }
        });
    }

    function EditUser() {
        var nama_lengkap = $('#EditNamaLengkap').val();
        var username = $('#Editusername').val();
        var email = $('#Editemail').val();
        var password = $('#Editpassword').val();
        var role = $('#Editrole').val();
        var edit_input_id = $('#Edit_id').val();

        $.ajax({
            url: '<?= site_url('Inventory/UserController/edit') ?> ',
            type: 'post',
            data: {
                edit_input_id: edit_input_id,
                edit_nama_lengkap: nama_lengkap,
                edit_username: username,
                edit_email: email,
                edit_password: password,
                edit_role: role,
            },
            dataType: 'json',
            success: function (response) {
                if (response.error) {
                    if (response.error.edit_nama_lengkap) {
                        $('#EditNamaLengkap').addClass('is-invalid');
                        $('.errorNamaLengkap').html(response.error.edit_nama_lengkap);
                    }
                    if (response.error.edit_email) {
                        $('#Editemail').addClass('is-invalid');
                        $('.errorEmail').html(response.error.edit_email);
                    }
                    if (response.error.edit_username) {
                        $('#Editusername').addClass('is-invalid');
                        $('.errorUsername').html(response.error.edit_username);
                    }
                    if (response.error.edit_password) {
                        $('#Editpassword').addClass('is-invalid');
                        $('.errorPassword').html(response.error.edit_password);
                    }
                    if (response.error.edit_role) {
                        $('#Editpassword').addClass('is-invalid');
                        $('.errorRole').html(response.error.edit_role);
                    }
                } else if (response.success == true) {
                    reload();
                }
            }
        });
        hapus_validasi_user_edit();
    }
    // end of edit user
    // hapus user
    function hapus_user($id) {
        Swal.fire({
            title: "Yakin ingin hapus?",
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, hapus!"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= site_url('Inventory/UserController/destroy/') ?>' + $id;
            }
        });
    }
    // end of hapus user

    //  kembalikan barang
    function kembalikan() {
        $.ajax({
            url: '<?= site_url('Inventory/DataPinjamanController/kembalikan') ?>',
            type: 'get',
            dataType: 'json',
            success: function (response) {

            }
        });
    }
    // end of kembalikan barang

    // notif logout
    function logout() {
        Swal.fire({
            title: "Yakin ingin logout?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, logout!"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?= site_url('logout') ?>/";
            }
        });
    }
    // end of notif 
</script>

</html>