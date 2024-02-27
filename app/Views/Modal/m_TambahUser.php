<div class="modal fade modal-lg" id="tambahUser" tabindex="-1" role="dialog" aria-labelledby="myModal"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <strong>Tambah User</strong>
            </div>
            <div class="modal-body">

                <div class="mb-3">
                    <label for="NamaLengkap" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="NamaLengkap" name="nama_lengkap" required>
                    <div class="invalid-feedback errorNamaLengkap"></div>
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                    <div class="invalid-feedback errorUsername"></div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" class="form-control" id="email" name="email" required>
                    <div class="invalid-feedback errorEmail"></div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="text" class="form-control" id="password" name="password" value="12345678" readonly>
                    <div class="invalid-feedback errorPassword"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button class="btn btn-primary" type="button" id="TambahUser" onclick="TambahUser()">Kirim</button>
            </div>
        </div>
    </div>
</div>