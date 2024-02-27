<?= $this->extend('Layouts/v_template.php'); ?>
<?= $this->section('content'); ?>

<head>
    <style>
        main {
            gap: 1rem;
        }

        main .card-info {
            grid-template-columns: repeat(4, 2fr);
            margin: 0;
            gap: 2rem;
        }
    </style>
</head>
<!-- Main Content -->
<main>
    <div class="head-main">
        <h1>Data Ruangan</h1>
        <?php include(APPPATH . 'Views\Layouts\v_profileNav.php') ?>
    </div>

    <!-- card -->
    <div class="card-info">
        <?php foreach($data_ruangan as $v): ?>
        <div class="room">
            <div class="status">
                <div class="icon">
                    <i class="fa-solid fa-box fa-2xl"></i>
                </div>
                <div class="info">
                    <h1><?= $v->ruangan ?></h1>
                    <h3><?= $total_peminjam. ' Peminjam' ?></h3>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <!-- cardend -->
</main>
<?= $this->endSection(); ?>