<script>
    <?php if (session()->getFlashdata('swal_icon')): ?>
        Swal.fire({
            icon: "<?= session()->getFlashdata('swal_icon'); ?>",
            title: "<?= session()->getFlashdata('swal_title'); ?>",
        });
    <?php endif; ?>
</script>