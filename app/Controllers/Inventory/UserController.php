<?php

namespace App\Controllers\Inventory;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;

class UserController extends BaseController
{
    protected $helpers = (['url', 'form', 'global_helper', 'text']);

    public function index()
    {
        // melarang user akses halaman ini
        if (session('redirected') != 'admin') {
            return redirect()->back();
        }

        $user = new UserModel();
        $jumlah_baris = 10;
        $current_page = $this->request->getVar('page_user');
        $sesi_id = session('member_id');
        $dataUser = $user->where('id !=', $sesi_id)->where('is_verifikasi', 'yes')->paginate($jumlah_baris, 'user');
        $data = [
            'aktif_data_user' => 'aktif',
            'data_user' => $dataUser,
            'pager' => $user->pager,
            'nomor' => nomor($current_page, $jumlah_baris)
        ];
        return view('Inventory/v_data_user', $data);
    }

    public function store()
    {
        $validasi = \Config\Services::validation();
        $aturan = [
            'nama_lengkap' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama Lengkap harus diisi',
                ]
            ],
            'username' => [
                'rules' => 'required|is_unique[users.username]|regex_match[/^\S+$/]',
                'errors' => [
                    'required' => 'Username harus diisi',
                    'is_unique' => 'Username sudah terdaftar',
                    'regex_match' => 'Username tidak boleh menggunakan spasi'
                ]
            ],
            'email' => [
                'rules' => 'required|is_unique[users.email]|valid_email',
                'errors' => [
                    'required' => 'Email harus diisi',
                    'is_unique' => 'Email sudah terdaftar',
                    'valid_email' => 'Email tidak valid'
                ]
            ],
        ];

        $validasi->setRules($aturan);
        if ($validasi->withRequest($this->request)->run()) {
            $nama_lengkap = ucwords($this->request->getPost('nama_lengkap'));
            $username = $this->request->getPost('username');
            $email = $this->request->getPost('email');

            $data = [
                'nama_lengkap' => $nama_lengkap,
                'username' => $username,
                'email' => $email,
                'foto' => 'profile.png',
                'password' => '12345678',
                'is_verifikasi' => 'yes',
                'tangga;_bergabung' => date('Y-m-d')
            ];
            $user = new UserModel();
            $user->insert($data);

            notif_swal('success', 'Berhasil Tambah User');
            $hasil['success'] = true;
        } else {
            $hasil = [
                'error' => [
                    'nama_lengkap' => $validasi->getError('nama_lengkap'),
                    'username' => $validasi->getError('username'),
                    'email' => $validasi->getError('email'),
                ],
            ];
        }
        return json_encode($hasil);
    }

    public function show($id)
    {
        $user = new UserModel();
        $data = $user->find($id);

        return json_encode($data);
    }

    public function edit()
    {
        $validasi = \Config\Services::validation();
        $id = $this->request->getVar('edit_input_id');
        $aturan = [
            'edit_nama_lengkap' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama Lengkap harus diisi',
                ]
            ],
            'edit_username' => [
                'rules' => 'required|is_unique[users.username, id, ' . $id . ']|regex_match[/^\S+$/]',
                'errors' => [
                    'required' => 'Username harus diisi',
                    'is_unique' => 'Username sudah terdaftar',
                    'regex_match' => 'Username tidak boleh menggunakan spasi'
                ]
            ],
            'edit_email' => [
                'rules' => 'required|is_unique[users.email, id,' . $id . ']|valid_email',
                'errors' => [
                    'required' => 'Email harus diisi',
                    'is_unique' => 'Email sudah terdaftar',
                    'valid_email' => 'Email tidak valid'
                ]
            ],
            'edit_password' => [
                'rules' => 'required|regex_match[/^\S+$/]|min_length[5]',
                'errors' => [
                    'required' => 'Password harus diisi',
                    'regex_match' => 'Password tidak boleh menggunakan spasi',
                    'min_length' => 'Minimum panjang password adalah 5 karakter'
                ]
            ],
        ];

        $validasi->setRules($aturan);
        if ($validasi->withRequest($this->request)->run()) {
            $nama_lengkap = ucwords($this->request->getPost('edit_nama_lengkap'));
            $username = $this->request->getPost('edit_username');
            $email = $this->request->getPost('edit_email');
            $password = $this->request->getPost('edit_password');
            $role = $this->request->getPost('edit_role');

            $data = [
                'id' => $id,
                'nama_lengkap' => $nama_lengkap,
                'username' => $username,
                'email' => $email,
                'password' => $password,
                'role' => $role,
            ];
            $user = new UserModel();
            $user->save($data);

            notif_swal('success', 'Berhasil Edit User');
            $hasil['success'] = true;
        } else {
            $hasil = [
                'error' => [
                    'edit_nama_lengkap' => $validasi->getError('edit_nama_lengkap'),
                    'edit_username' => $validasi->getError('edit_username'),
                    'edit_email' => $validasi->getError('edit_email'),
                    'edit_password' => $validasi->getError('edit_password'),
                    'edit_role' => $validasi->getError('edit_role'),
                ],
            ];
        }
        return json_encode($hasil);
    }

    public function destroy($id)
    {
        $user = new UserModel();
        $data = $user->delete($id);

        notif_swal('success', 'Berhasil Hapus User');
        return redirect()->back();
    }
}