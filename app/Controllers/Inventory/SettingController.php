<?php

namespace App\Controllers\Inventory;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;
use App\Validations\CustomRules;

class SettingController extends BaseController
{
    protected $helpers = (['url', 'form', 'text', 'global_helper']);
    public function index()
    {
        $sesi_id = session()->get('member_id');
        $user  = new UserModel();

        $data = [
            'aktif_setting' => 'aktif',
            'dataUser' => $user->find($sesi_id),
        ];
        return view('Inventory/v_setting', $data);
    }

    public function store(){
        $sesi_id = session()->get('member_id');
        $sesi_email = session()->get('member_email');
        $sesi_username = session()->get('member_username');
        $email = $this->request->getVar('email');
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');
        $password_new = $this->request->getVar('password_new');
        $file = $this->request->getFile('foto_profile');

        $user = new UserModel();

        if ($username == $sesi_username and $email == $sesi_email and $password_new == '') {
            if ($file != '' && $file->isValid()) {
                $member = new UserModel();
                $dataUser = $member->find($sesi_id);
                $foto_lama = (FCPATH . 'img/' . $dataUser['foto']);
                if (file_exists($foto_lama)) {
                    if ($dataUser['foto'] != 'profile.png' && $dataUser['foto'] != '') {
                        unlink($foto_lama);
                    }
                }

                $namaFile = date("Y.m.d") . " - " . date("H.i.s") . '.jpeg';
                $file->move(FCPATH . 'img', $namaFile);

                $update = [
                    'foto' => $namaFile,
                ];
                $member->where('id', $sesi_id)->set($update)->update();
                // update sesi foto
                session()->set('member_foto', $namaFile);
                notif_swal('success', 'Berhasil Update Data');
            }
            return redirect()->back();
        }

        if ($email != $sesi_email) {
            $aturan = $this->validate([
                'email' => [
                    'rules' => 'required|is_unique[users.email, id, ' . $sesi_id . ']|valid_email',
                    'errors' => [
                        'required' => 'Email harus diisi',
                        'is_unique' => 'Email yang dimasukkan sudah terdaftar',
                        'valid_email' => 'Email tidak valid'
                    ]
                ],
                'password_old' => [
                    'rules' => 'required|check_old_password[password_old]',
                    'errors' => [
                        'required' => 'Current Password harus diisi',
                        'check_old_password' => 'Current Password tidak sesuai'
                    ]
                ],
            ]);
        }

        if ($username != $sesi_username) {
            $aturan = $this->validate([
                'username' => [
                    'rules' => 'required|is_unique[users.username, id,' . $sesi_id . ']|regex_match[/^\S+$/]',
                    'errors' => [
                        'required' => 'Username harus diisi',
                        'is_unique' => 'Username yang dimasukkan sudah terdaftar',
                        'regex_match' => 'Username tidak boleh menggunakan spasi'
                    ]
                ]
            ]);
        }

         /**Validasi password */
         if ($password_new != '') {
            $aturan = $this->validate([
                'password_old' => [
                    'rules' => 'required|check_old_password[password_old]',
                    'errors' => [
                        'required' => 'Current Password harus diisi',
                        'check_old_password' => 'Current Password tidak sesuai'
                    ]
                ],
                'password_new' => [
                    'rules' => 'min_length[5]|regex_match[/^\S+$/]',
                    'errors' => [
                        'min_length' => 'Minimun panjang password adalah 5 karakter',
                        'regex_match' => 'Password tidak boleh menggunakan spasi'
                    ]
                ],
                'konfirmasi_password_new' => [
                    'rules' => 'matches[password_new]',
                    'errors' => [
                        'matches' => 'Konfirmasi Password tidak sesuai'
                    ]
                ]
            ]);
        }
        if (!$aturan){
            return view('Inventory/v_setting', [
                'validation' => $this->validator->getErrors(),
                'aktif_setting' => 'aktif'
            ]);
        } else {
            /**JIka proses validasi tidak ditemukan error maka akan mengupdate sesuai data yang diubah oleh user  */
            $member = new UserModel();

            /** Jika password diubah maka akan melakukan update */
            if ($password_new != '') {
                $dataUpdate = [
                    'password' => $password_new
                ];
                $member->where('id', $sesi_id)->set($dataUpdate)->update();
                notif_swal('success', 'Berhasil Update Data');
            }

            if ($file != '' && $file->isValid()) {
                $member = new UserModel();
                $dataUser = $member->find($sesi_id);
                $foto_lama = (FCPATH . 'img/' . $dataUser['foto']);
                if (file_exists($foto_lama)) {
                    if ($dataUser['foto'] != 'profile.png' && $dataUser['foto'] != '') {
                        unlink($foto_lama);
                    }
                }

                $namaFile = date("Y.m.d") . " - " . date("H.i.s") . '.jpeg';
                $file->move(FCPATH . 'img', $namaFile);
                $update = [
                    'foto' => $namaFile,
                ];
                $member->where('id', $sesi_id)->set($update)->update();
                // update sesi foto
                session()->set('member_foto', $namaFile);
                notif_swal('success', 'Berhasil Update Data');
            }

            $member->save([
                'id' => $sesi_id,
                'username' => $username,
                'email' => $email,
            ]);
            session()->set([
                'member_username' => $username,
                'member_email' => $email,
            ]);
            notif_swal('success', 'Berhasil Update Data');

            return redirect()->back();
        }
    }   
}