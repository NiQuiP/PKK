<?php

namespace App\Controllers\Inventory;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;
use App\Models\InventoryModel;
use App\Models\PinjamanModel;

class DashboardController extends BaseController
{
    protected $helpers = (['url', 'form', 'global_helper', 'text']);

    public function index()
    {
        // melarang user akses halaman ini
        if (session('redirected') != 'admin') {
            return redirect()->back();
        }

        $user = new UserModel();
        $barang = new InventoryModel();
        $pinjaman = new PinjamanModel();
        $data = [
            'jumlahPinjaman' => $pinjaman->where('status_pinjaman', 'Diterima')->countAllResults(),
            'dataPinjaman' => $pinjaman->where("DATE_FORMAT(tanggal_pinjaman, '%Y-%m')")->where('status_pinjaman', 'Diterima')->paginate(9),
            'dataUser' => $user->findAll(),
            'jumlahUser' => $user->countAllResults(),
            'aktif_dashboard' => 'active',
            'jumlahBarang' => $barang->countAllResults(),
            'pager' => $barang->pager
        ];
        return view('Inventory/v_dashboard', $data);
    }
}