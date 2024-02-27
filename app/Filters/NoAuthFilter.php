<?php

namespace App\Filters;

use App\Models\UserModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class NoAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Do something here
        $session = session();
        helper('global_helper');
        $user = new UserModel();
        $sesi_id = $session->get("member_id");
        $userRole = $user->where('id', $sesi_id)->first();

        if ($session->get("logged_in")) {
            if ($userRole['role'] == 'Admin') {
                return redirect()->to('inventory/dashboard');
            } else if ($userRole['role'] == 'User') {
                if ($session->getFlashdata('sudah_verifikasi')) {
                    notif_swal('success', 'Berhasil Verifikasi Akun');
                }
                return redirect()->to('inventory/dashboard');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}