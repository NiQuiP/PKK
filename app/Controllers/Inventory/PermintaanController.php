<?php

namespace App\Controllers\Inventory;

use App\Controllers\BaseController;
use App\Models\InventoryModel;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PinjamanModel;

class PermintaanController extends BaseController
{
    protected $helpers = (['url', 'form', 'global_helper', 'text']);
    public function index()
    {
        // melarang user akses halaman ini
        if (session('redirected') != 'admin') {
            return redirect()->back();
        }

        $pinjaman = new PinjamanModel();
        $dataPermintaan = $pinjaman->where('status_pinjaman', 'Pending')->orderBy('id', 'desc')->paginate(10, 'permintaan');
        $jumlah_permintaan = $pinjaman->where('status_pinjaman', 'Pending')->countAllResults();
        $data = [
            'permintaan' => $dataPermintaan,
            'aktif_permintaan' => 'aktif',
            'jumlah_permintaan' => $jumlah_permintaan
        ];
        return view('Inventory/v_permintaan', $data);
    }

    public function terima($id)
    {
        $pinjaman = new PinjamanModel();

        $pinjaman->save([
            'id' => $id,
            'status_pinjaman' => 'Diterima'
        ]);
        notif_swal('success', 'Berhasil Menerima Permintaan');
        return redirect()->back();
    }

    public function tolak($id)
    {
        $inventory = new InventoryModel();
        $pinjaman = new PinjamanModel();
        $data_permintaan = $pinjaman->find($id);

        $cek_inventory = $inventory->where('kode_barang', $data_permintaan['kode_barang'])->where('kondisi_barang', $data_permintaan['kondisi_barang'])->first();
        if ($cek_inventory) {
            $inventory->save([
                'id_barang' => $cek_inventory['id_barang'],
                'jumlah_barang' => $cek_inventory['jumlah_barang'] + $data_permintaan['jumlah_barang']
            ]);
        } else {
            $inventory->insert([
                'nama_barang' => $data_permintaan['nama_barang'],
                'kode_barang' => $data_permintaan['kode_barang'],
                'merk_barang' => $data_permintaan['merk_barang'],
                'kondisi_barang' => $data_permintaan['kondisi_barang'],
                'jumlah_barang' => $data_permintaan['jumlah_barang'],
                'foto_barang' => $data_permintaan['foto_barang'],
            ]);
        }
        $pinjaman->delete($id);
        notif_swal('success', 'Berhasil hapus');
        return redirect()->back();
    }
}