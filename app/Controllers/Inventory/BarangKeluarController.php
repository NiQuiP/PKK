<?php

namespace App\Controllers\Inventory;

use App\Controllers\BaseController;
use App\Models\InventoryModel;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\BarangKeluarModel;

class BarangKeluarController extends BaseController
{
    protected $helpers = (['url', 'form', 'global_helper']);
    public function index()
    {
        // melarang user akses halaman ini
        if (session('redirected') != 'admin') {
            return redirect()->back();
        }

        $b_keluar = new BarangKeluarModel();
        $inventori = new InventoryModel();
        $data = [
            'inventori' => $inventori->findAll(),
            'barang_keluar' => $b_keluar->orderBy('id_barang', 'desc')->paginate(10, 'barang'),
            'pager' => $b_keluar->pager,
            'aktif_transaksi' => 'aktif',
            'aktif_barang_keluar' => 'aktif',
        ];
        return view('Inventory/v_barang_keluar', $data);
    }

    public function store()
    {
        $inventori = new InventoryModel();
        $m_BarangKeluar = new BarangKeluarModel();
        $id_barang = $this->request->getPost('barang_keluar');
        $jumlah = $this->request->getPost('jumlah');

        $data_barang = $inventori->where('id_barang', $id_barang)->first();
        if ($data_barang['jumlah_barang'] == $jumlah) {
            $inventori->where('id_barang', $id_barang)->delete();
        } else if ($data_barang['jumlah_barang'] > $jumlah) {
            $data = [
                'jumlah_barang' => $data_barang['jumlah_barang'] - $jumlah
            ];
            $inventori->where('id_barang', $id_barang)->set($data)->update();
        } else {
            notif_swal('error', 'Jumlah melebihi stock');
            return redirect()->back();
        }
        $data = [
            'kode_barang' => $data_barang['kode_barang'],
            'nama_barang' => $data_barang['nama_barang'],
            'merk_barang' => $data_barang['merk_barang'],
            'kondisi_barang' => $data_barang['kondisi_barang'],
            'jumlah_barang' => $jumlah,
            'tanggal_keluar' => date('Y-m-d'),
        ];
        $m_BarangKeluar->insert($data);
        notif_swal('success', 'Berhasil Tambah Data');
        return redirect()->back();
    }

    public function destroy($id)
    {
        $b_keluar = new BarangKeluarModel();
        $b_keluar->delete($id);
        notif_swal('success', 'Berhasil Hapus Data');
        return redirect()->back();
    }
}