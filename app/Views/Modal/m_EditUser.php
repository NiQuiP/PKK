<div class="modal fade modal-lg" id="EditUser" tabindex="-1" role="dialog" aria-labelledby="myModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <strong>Edit User</strong>
            </div>
            <div class="modal-body">
                <input type="hidden" name="edit_input_id" id="Edit_id">
                <div class="mb-3">
                    <label for="EditNamaLengkap" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="EditNamaLengkap" name="nama_lengkap" required>
                    <div class="invalid-feedback errorNamaLengkap"></div>
                </div>
                <div class="mb-3">
                    <label for="Editusername" class="form-label">Username</label>
                    <input type="text" class="form-control" id="Editusername" name="username" required>
                    <div class="invalid-feedback errorUsername"></div>
                </div>
                <div class="mb-3">
                    <label for="Editemail" class="form-label">Email</label>
                    <input type="text" class="form-control" id="Editemail" name="email" required>
                    <div class="invalid-feedback errorEmail"></div>
                </div>
                <div class="mb-3">
                    <label for="Editpassword" class="form-label">Password</label>
                    <input type="text" class="form-control" id="Editpassword" name="password" value="12345678" readonly>
                    <div class="invalid-feedback errorPassword"></div>
                </div>
                <div class="mb-3">
                    <label for="Editrole" class="form-label">Role</label>
                    <select class="form-select" id="Editrole" name="role">
                        <option value="User">User</option>
                        <option value="Admin">Admin</option>
                    </select>
                    <div class="invalid-feedback errorRole"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button class="btn btn-primary" type="button" id="TambahUser" onclick="EditUser()">Kirim</button>
            </div>
        </div>
    </div>
</div>