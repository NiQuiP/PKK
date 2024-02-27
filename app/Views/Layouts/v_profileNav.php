<div class="nav">
    <button id="menu-btn">
        <span class="material-icons-sharp"> menu </span>
    </button>
    <div class="dark-mode">
        <span class="material-icons-sharp active"> light_mode </span>
        <span class="material-icons-sharp"> dark_mode </span>
    </div>

    <div class="profile">
        <div class="info">
            <?php 
                if (session('redirected') == 'user') {
                    $role = 'User';
                } else if(session('redirected') == 'admin') {
                    $role = 'Admin';
                }
                $nama = session()->get('member_nama_lengkap');
            ?>
            <p>Hey, <b><?= $nama ?></b></p>
            <small class="profile__status"><?= $role ?></small>
        </div>
        <div class="profile-photo" style="cursor: pointer;">
            <img src="<?= base_url('img/' . session()->get('member_foto')) ?>"
                onclick="tampilkanPopup('<?= base_url('img/' . session()->get('member_foto')) ?>')" />
        </div>
    </div>
</div>