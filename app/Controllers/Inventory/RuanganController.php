<?php

namespace App\Controllers\Inventory;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PinjamanModel;

class RuanganController extends BaseController
{
    public function index()
    {
        // melarang user akses halaman ini
        if (session('redirected') != 'admin') {
            return redirect()->back();
        }

        $pinjaman = new PinjamanModel();
        $data_ruangan = $pinjaman->select('ruangan')->distinct()->get()->getResult();
        $total_peminjam = [];
        foreach ($data_ruangan as $v) {
            $total_peminjam = $pinjaman->select('email')->where('ruangan', $v->ruangan)->distinct()->countAllResults();
        }
        $data = [
            'aktif_data_ruangan' => 'aktif',
            'data_ruangan' => $data_ruangan,
            'total_peminjam' => $total_peminjam
        ];
        return view('Inventory/v_ruangan', $data);
    }
}