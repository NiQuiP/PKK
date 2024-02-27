<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;

class Auth extends BaseController
{
    protected $helpers = (['url', 'form', 'text', 'global_helper', 'cookie']);
    public function login()
    {
        if (get_cookie('cookie_username') && get_cookie('cookie_password')) {
            $username = get_cookie('cookie_username');
            $password = get_cookie('cookie_password');
            $user = new UserModel();

            $dataAkun = $user->where('username', $username)->orWhere('email', $username)->first();
            if ($dataAkun != null) {
                if ($password == $dataAkun['password']) {
                    $dataSesi = [
                        'logged_in' => true,
                        'member_id' => $dataAkun['id'],
                        'member_username' => $dataAkun['username'],
                        'member_password' => $dataAkun['password'],
                        'member_email' => $dataAkun['email'],
                        'member_nama_lengkap' => $dataAkun['nama_lengkap'],
                        'member_foto' => $dataAkun['foto'],
                    ];
                    session()->set($dataSesi);
                    if ($dataAkun['role'] == 'User') {
                        session()->set('redirected', 'user');
                    } else if ($dataAkun['role'] == 'Admin') {
                        session()->set('redirected', 'admin');
                    }
                    notif_swal('success', 'Selamat Datang');
                    return redirect()->to('inventory/dashboard');
                }
            }
        }
        $data = [
            'validation' => null
        ];
        return view('Auth/v_login', $data);
    }

    public function loginProcess()
    {
        helper('cookie');
        $fieldType = filter_var($this->request->getVar('login_id'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $username = $this->request->getVar('login_id');
        $password = $this->request->getVar('password');

        if ($fieldType == 'username') {
            $rules = ($this->validate([
                'login_id' => [
                    'rules' => 'required|is_not_unique[users.username]',
                    'errors' => [
                        'required' => 'Username atau Email harus diisi',
                        'is_not_unique' => 'Username yang dimasukkan tidak terdaftar',
                    ]
                ],
                'password' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Password harus diisi',
                    ]
                ],

            ]));
        } else {
            $rules = ($this->validate([
                'login_id' => [
                    'rules' => 'required|is_not_unique[users.email]',
                    'errors' => [
                        'required' => 'Username atau Email harus diisi',
                        'is_not_unique' => 'Email yang dimasukkan tidak terdaftar',
                    ]
                ],
                'password' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Password harus diisi',
                    ]
                ],

            ]));
        }

        if (!$rules) {
            return view('Auth/v_login', [
                'validation' => $this->validator->getErrors(),
            ]);
        } else {
            // verifikasi captcha
            $secret = getenv('SECRETKEY');

            $credential = array(
                'secret' => $secret,
                'response' => $this->request->getVar('g-recaptcha-response')
            );

            $verify = curl_init();
            curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
            curl_setopt($verify, CURLOPT_POST, true);
            curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($credential));
            curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($verify);
            curl_close($verify);

            $status = json_decode($response, true);
            if (!$status['success']) {
                return view('Auth/v_login', [
                    'captcha' => 'Verifikasi CAPTCHA!'
                ]);
            }

            $member = new UserModel();
            $memberInfo = $member->where($fieldType, $username)->first();
            $memberPassword = $memberInfo['password'];

            if (($password != $memberPassword)) {
                session()->setFlashdata('login_id', $username);
                session()->setFlashdata('password', $password);
                $err = 'Password yang anda masukkan salah';
                return redirect()->back()->with('error', $err);
            }
            if (empty($err)) {
                if ($memberInfo['is_verifikasi'] != 'yes') {
                    session()->set('member_email', $memberInfo['email']);
                    $err = 'Akun anda belum diverifikasi, silahkan dapatkan OTP untuk verifikasi';
                    session()->setFlashdata('error', $err);
                    return redirect()->to('/verifikasi');
                }
            }

            if (empty($err)) {
                // membuat data session
                $dataSesi = [
                    'logged_in' => true,
                    'member_id' => $memberInfo['id'],
                    'member_username' => $memberInfo['username'],
                    'member_password' => $memberInfo['password'],
                    'member_email' => $memberInfo['email'],
                    'member_nama_lengkap' => $memberInfo['nama_lengkap'],
                    'member_foto' => $memberInfo['foto'],
                ];
                session()->set($dataSesi);
                if ($memberInfo['role'] == 'User') {
                    session()->set('redirected', 'user');
                    notif_swal('success', 'Selamat Datang');
                    return redirect()->to('inventory/data-barang')->withCookies();
                } else if ($memberInfo['role'] == 'Admin') {
                    session()->set('redirected', 'admin');
                    notif_swal('success', 'Selamat Datang');
                    return redirect()->to('inventory/dashboard')->withCookies();
                }

                // membuat cookie login
                set_cookie('cookie_username', $username, 3600 * 24 * 30);
                set_cookie('cookie_password', $memberInfo['password'], 3600 * 24 * 30);

            }
        }
    }

    public function logout()
    {
        session()->destroy();
        delete_cookie('cookie_username');
        delete_cookie('cookie_password');
        /** Untuk session login */
        if (session()->get('logged_in') != '') {
            session()->setFlashdata('success', 'Berhasil Logout');
        }
        return view('Auth/v_login');
    }

    public function register()
    {
        $data = [
            'validation' => null
        ];
        return view('Auth/v_register', $data);
    }

    public function registerProcess()
    {
        $member = new UserModel();
        $nama_lengkap = ucwords($this->request->getPost('nama_lengkap'));
        $username = $this->request->getPost('username');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $rules = $this->validate([
            'nama_lengkap' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Username harus diisi',
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
                'rules' => 'required|is_unique[users.email]',
                'errors' => [
                    'required' => 'Email harus diisi',
                    'is_unique' => 'Email sudah terdaftar',
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[5]|regex_match[/^\S+$/]',
                'errors' => [
                    'required' => 'Password harus diisi',
                    'min_length' => 'Minimum panjang 5 karakter',
                    'regex_match' => 'Password tidak boleh menggunakan spasi'
                ]
            ],
            'konfirmasi_password' => [
                'rules' => 'matches[password]',
                'errors' => [
                    'matches' => 'Konfirmasi Password tidak sesuai',
                ]
            ]
        ]);
        if (!$rules) {
            return view('Auth/v_register', [
                'validation' => [
                    'nama_lengkap' => $this->validator->getError('nama_lengkap'),
                    'username' => $this->validator->getError('username'),
                    'email' => $this->validator->getError('email'),
                    'password' => $this->validator->getError('password'),
                    'konfirmasi_password' => $this->validator->getError('konfirmasi_password'),
                ]
            ]);
        } else {
            // verifikasi captcha
            $secret = getenv('SECRETKEY');

            $credential = array(
                'secret' => $secret,
                'response' => $this->request->getVar('g-recaptcha-response')
            );

            $verify = curl_init();
            curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
            curl_setopt($verify, CURLOPT_POST, true);
            curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($credential));
            curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($verify);
            curl_close($verify);

            $status = json_decode($response, true);
            if (!$status['success']) {
                // session()->setFlashdata('error', 'Verifikasi CAPTCHA!');
                return view('Auth/v_register', [
                    'captcha' => 'Verifikasi CAPTCHA!'
                ]);
            }

            /**Membuat function kirim email verifikasi menggunakan helpers */
            $token = random_string('numeric', 6);
            $link = site_url("verifikasi/?email=$email&token=$token");
            $attachment = "";
            $to = "$email";
            $title = "Verifikasi Akun";
            $uniq_id = uniqid();

            $message = ' <p>Berikut ini <a style="text-decoration: none; font-weight: bold;">' . $token . '</a> kode OTP untuk melakukan verifikasi akun anda, atau klik tombol di bawah ini :</p>
                    <div style="text-align: center;">
                        <a href="' . $link . '" style="display: inline-block; padding: 10px 20px; background-color: #3498db; border-radius: 5px; text-decoration: none; color: white;">Verifikasi</a>
                    </div>
                    <hr style="border-top: 2px solid ; margin-top: 2rem;">
                    <h3 style="margin-top: 1rem;">CATATAN : Kode OTP akan kadaluwarsa dalam 15 menit. Harap segera gunakkan</h3>
                    <div style="display: none;">' . $uniq_id . '</div>';
            kirim_email($attachment, $to, $title, $message);

            /** Mendaftarkan akun ke database */
            $dataUpdate = [
                'nama_lengkap' => $nama_lengkap,
                'username' => $username,
                'email' => $email,
                'password' => $password,
                'foto' => 'profile.png',
                'token' => $token,
                'is_verifikasi' => 'no',
                'tanggal_bergabung' => date('Y-m-d')
            ];
            $member->insert($dataUpdate);

            // Membuat session user
            $dataSesi = [
                'member_email' => $email,
                'member_password' => $password,
                'redirected' => 'user',
            ];
            session()->set($dataSesi);
            /**Pesan sukses */
            session()->setFlashdata("success", "Berhasil register, kode OTP sudah dikirim ke Email anda");
            return redirect()->to('/verifikasi');
        }
    }

    public function verifikasi()
    {
        $token = $this->request->getVar('token');
        if ($this->request->getVar('email') != '') {
            $email = $this->request->getVar('email');
        } else if (session()->get('member_email')) {
            $email = session()->get('member_email');
        } else {
            return redirect()->to('login');
        }

        $user = new UserModel();
        $dataAkun = $user->where('email', $email)->get()->getRowArray();

        if ($dataAkun['is_verifikasi'] == 'yes') {
            session()->set([
                'logged_in' => true,
                'member_id' => $dataAkun['id'],
                'member_username' => $dataAkun['username'],
                'member_password' => $dataAkun['password'],
                'member_email' => $dataAkun['email'],
                'member_nama_lengkap' => $dataAkun['nama_lengkap'],
                'member_foto' => $dataAkun['foto'],
            ]);
            if ($dataAkun['role'] == 'User') {
                session()->set('redirected', 'user');
            }
            session()->setFlashdata('sudah_verifikasi', true);
            return redirect()->to('login');
        }

        if ($token != '') {
            if ($dataAkun['token'] == $token) {
                $user->save([
                    'id' => $dataAkun['id'],
                    'token' => null,
                    'is_verifikasi' => 'yes'
                ]);
                session()->set([
                    'logged_in' => true,
                    'member_id' => $dataAkun['id'],
                    'member_username' => $dataAkun['username'],
                    'member_password' => $dataAkun['password'],
                    'member_email' => $dataAkun['email'],
                    'member_nama_lengkap' => $dataAkun['nama_lengkap'],
                    'member_foto' => $dataAkun['foto'],
                ]);
                if ($dataAkun['role'] == 'User') {
                    session()->set('redirected', 'user');
                    notif_swal('success', 'Selamat Datang');
                    return redirect()->to('inventory/data-barang');
                } else if ($dataAkun['role'] == 'Admin') {
                    session()->set('redirected', 'admin');
                    notif_swal('success', 'Selamat Datang');
                    return redirect()->to('inventory/dashboard');
                }
            }
        }
        return view('Auth/v_verifikasi');
    }

    public function verifikasiProcess()
    {
        if ($this->request->getVar('email') != '') {
            $email = $this->request->getVar('email');
        } else if (session()->get('member_email')) {
            $email = session()->get('member_email');
        }

        $token = $this->request->getVar('token');
        // if ($token == ''){
        //     session()->setFlashdata('error', 'Kode OTP tidak valid');
        //     return redirect()->back();
        // }

        $user = new UserModel();
        $dataUser = $user->where('email', $email)->get()->getRowArray();

        if ($dataUser['token'] != $token) {
            session()->setFlashdata('error', 'Kode OTP tidak valid');
            return redirect()->back();
        } else {
            $user->save([
                'id' => $dataUser['id'],
                'token' => null,
                'is_verifikasi' => 'yes'
            ]);
            session()->set([
                'logged_in' => true,
                'member_id' => $dataUser['id'],
                'member_username' => $dataUser['username'],
                'member_password' => $dataUser['password'],
                'member_email' => $dataUser['email'],
                'member_nama_lengkap' => $dataUser['nama_lengkap'],
                'member_foto' => $dataUser['foto'],
            ]);
            if ($dataUser['role'] == 'User') {
                session()->set('redirected', 'user');
                notif_swal('success', 'Selamat Datang');
                return redirect()->to('inventory/data-barang');
            } else if ($dataUser['role'] == 'Admin') {
                session()->set('redirected', 'admin');
                notif_swal('success', 'Selamat Datang');
                return redirect()->to('inventory/dashboard');
            }
        }
    }

    public function kirim_ulang()
    {
        if ($this->request->getVar('email') != '') {
            $email = $this->request->getVar('email');
        } else if (session()->get('member_email')) {
            $email = session()->get('member_email');
        } else {
            return redirect()->to('login');
        }


        $user = new UserModel();
        // mencari data user
        $dataUser = $user->where('email', $email)->get()->getRowArray();

        /**Membuat function kirim email verifikasi menggunakan helpers */
        $token = random_string('numeric', 6);
        $link = site_url("verifikasi/?email=$email&token=$token");
        $attachment = "";
        $to = "$email";
        $title = "Verifikasi Akun";
        $uniq_id = uniqid();

        $message = ' <p>Berikut ini <a style="text-decoration: none; font-weight: bold;">' . $token . '</a> kode OTP untuk melakukan verifikasi akun anda, atau klik tombol di bawah ini :</p>
                    <div style="text-align: center;">
                        <a href="' . $link . '" style="display: inline-block; padding: 10px 20px; background-color: #3498db; border-radius: 5px; text-decoration: none; color: white;">Verifikasi</a>
                    </div>
                    <hr style="border-top: 2px solid ; margin-top: 2rem;">
                    <h3 style="margin-top: 1rem;">CATATAN : Kode OTP akan kadaluwarsa dalam 15 menit. Harap segera gunakkan</h3>
                    <div style="display: none;">' . $uniq_id . '</div>';
        kirim_email($attachment, $to, $title, $message);

        // token sesuai dan lanjutkan proses verifikasi
        $user->save([
            'id' => $dataUser['id'],
            'token' => $token
        ]);
        session()->setFlashdata('success', 'Kode OTP telah dikirim, silahkan cek email anda');
        return redirect()->back();
    }

    public function forget_password()
    {
        if ($this->request->getMethod() == 'post') {
            $fieldType = filter_var($this->request->getVar('email'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            $userInput = $this->request->getVar('email');

            if ($userInput == '') {
                $err = 'Masukkan Username atau Email untuk melakukan reset password';
            }

            if (empty($err)) {
                if ($fieldType == 'email') {
                    $member = new UserModel();
                    $memberInfo = $member->where($fieldType, $userInput)->first();
                    if (!$memberInfo) {
                        $err = 'Email yang dimasukkan tidak terdaftar';
                    }
                }
            }

            if (empty($err)) {
                if ($fieldType == 'username') {
                    $member = new UserModel();
                    $memberInfo = $member->where($fieldType, $userInput)->first();
                    if (!$memberInfo) {
                        $err = 'Username yang dimasukkan tidak terdaftar';
                    }
                }
            }

            if (empty($err)) {
                $email = $memberInfo['email'];
                /**Membuat function kirim email verifikasi menggunakan helpers */
                $token = random_string('numeric', 6);
                $link = site_url("resetpassword/?email=$email&token=$token");
                $attachment = "";
                $to = "$email";
                $title = "Reset Password";
                $uniq_id = uniqid();

                $message = '<p>Berikut ini link untuk melakukan reset password anda, klik tombol di bawah ini :</p>
                    <div style="text-align: center;">
                        <a href="' . $link . '" style="display: inline-block; padding: 10px 20px; background-color: #3498db; border-radius: 5px; text-decoration: none; color: white;">Reset Password</a>
                    </div>
                    <hr style="border-top: 2px solid ; margin-top: 2rem;">
                    <h3 style="margin-top: 1rem;">CATATAN : Link Reset Password akan kadaluwarsa dalam 15 menit. Harap segera gunakkan</h3>
                    <div style="display: none;">' . $uniq_id . '</div>';
                kirim_email($attachment, $to, $title, $message);

                $dataUpdate = [
                    'email' => $memberInfo['email'],
                    'token' => $token
                ];
                $member->where('email', $email)->set($dataUpdate)->update();

                session()->set('email', $email);
                session()->setFlashdata('success', 'Link reset password telah dikirim ke email anda');
                return redirect()->back();
            }

            if ($err) {
                session()->setFlashdata('email', $userInput);
                session()->setFlashdata('invalid', 'is-invalid');
                session()->setFlashdata('error', $err);
                return redirect()->back();
            }
        }
        return view('Auth/v_forgetpassword');
    }
    public function reset_password()
    {
        $token = $this->request->getVar('token');
        $email = $this->request->getVar('email');
        if ($email == '' or $token == '') {
            // $err = 'Link reset password error, silahkan dapatkan link kembali';
            // session()->setFlashdata('error', $err);
            return redirect()->to('forgetpassword');
        }

        $data = [
            'validation' => null
        ];
        echo view('Auth/v_resetpassword', $data);
    }
    function reset_passwordProcess()
    {
        $token = $this->request->getVar('token');
        $email = $this->request->getVar('email');

        $member = new UserModel();
        $memberInfo = $member->where('email', $email)->first();
        $dataToken = $memberInfo['token'];

        if ($dataToken != $token) {
            $err = 'Link reset password error, silahkan dapatkan link kembali';
            session()->setFlashdata('error', $err);
            return redirect()->to('/forgetpassword');
        }

        $rules = $this->validate([
            'password' => [
                'rules' => 'required|min_length[5]',
                'errors' => [
                    'required' => 'Password harus diisi',
                    'min_length' => 'Minimum panjang untuk Password adalah 5 karakter'
                ]
            ],
            'konfirmasi_password' => [
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => 'Konfirmasi Password tidak sesuai',
                    'matches' => 'Konfirmasi Password harus sama dengan Password'
                ]
            ]
        ]);
        if (!$rules) {
            return view('Auth/v_resetpassword', [
                'validation' => $this->validator->getErrors()
            ]);
        } else {
            $member = new UserModel();
            $email = $this->request->getVar('email');
            $password = $this->request->getVar('password');
            $dataUser = $member->where('email', $email)->first();
            if ($password == $dataUser['password']) {
                session()->setFlashdata('error', 'Password sudah digunakkan');
                return redirect()->back();
            }
            $dataUpdate = [
                'password' => $password,
                'token' => null
            ];
            $member->where('email', $email)->set($dataUpdate)->update();
            session()->remove('email');
            session()->setFlashdata('success', 'Password berhasil direset, silahkan login');
            return redirect()->to('/login');
        }
    }
}
