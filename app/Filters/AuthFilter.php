<?php

namespace App\Filters;

use App\Models\UserModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {

        // Do something here
        $session = session();
        helper("global_helper");

        $user = new UserModel();
        $sesi_id = $session->get('member_id');
        $userRole = $user->where('id', $sesi_id)->first();

        if ($sesi_id != null) {
            if (!$userRole) {
                $dataSesi = [
                    'logged_in',
                    'member_id',
                    'member_username',
                    'member_password',
                    'member_email',
                    'member_nama_lengkap',
                    'member_foto'
                ];
                $session->remove($dataSesi);
                $session->setFlashdata('error', 'Akun anda telah dihapus');
                return redirect()->to('login');
            }
        }

        if ($session->get('logged_in')) {
            if ($userRole['role'] == 'Admin' && $session->get('redirected') != 'admin') {
                $session->set('redirected', 'admin');
                notif_swal('success', 'Anda Telah Menjadi Admin');
                return redirect()->to('inventory/dashboard');
            } else if ($userRole['role'] == 'User' && $session->get('redirected') != 'user') {
                $session->set('redirected', 'user');
                notif_swal('success', 'Anda Telah Menjadi User');
                return redirect()->to('inventory/dashboard');
            }
        } else {
            return redirect()->to('/login');
        }


    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}