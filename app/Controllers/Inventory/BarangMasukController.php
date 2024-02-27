<?php

namespace App\Controllers\Inventory;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\BarangMasukModel;
use App\Models\InventoryModel;

class BarangMasukController extends BaseController
{
    protected $helpers = (['url', 'text', 'form', 'global_helper']);
    public function index()
    {
        // melarang user akses halaman ini
        if (session('redirected') != 'admin') {
            return redirect()->back();
        }

        $m_barang = new BarangMasukModel();
        $inventori = new InventoryModel();
        $data = [
            'inventori' => $inventori,
            'barang_masuk' => $m_barang->orderBy('id_barang', 'desc')->paginate(10, 'barang'),
            'pager' => $m_barang->pager,
            'aktif_transaksi' => 'aktif',
            'aktif_barang_masuk' => 'aktif',
        ];
        return view('Inventory/v_barang_masuk', $data);
    }

    public function store()
    {
        $nama_barang = ucwords($this->request->getPost('nama_barang'));
        $merk_barang = strtoupper($this->request->getPost('merk_barang'));
        $kondisi = ucwords($this->request->getPost('kondisi'));
        $jumlah = $this->request->getPost('jumlah');
        $kode_barang = random_string('numeric', 6);

        $b_masuk = new BarangMasukModel();
        $inventori = new InventoryModel();

        // mengecek jika barang sudah ada dan kondisi barang sama dengan input an user
        $cek_barang_ada = $inventori->where('nama_barang', $nama_barang)->where('merk_barang', $merk_barang)->where('kondisi_barang', $kondisi)->get()->getRowArray();
        // mengecek jika barang ada tapi kondisi barang berbeda
        $cek_barang_beda_kondisi = $inventori->where('nama_barang', $nama_barang)->where('merk_barang', $merk_barang)->get()->getRowArray();
        if ($cek_barang_ada) {
            $kode_barang = $cek_barang_ada['kode_barang'];
            $data = [
                'kode_barang' => $cek_barang_ada['kode_barang'],
                'nama_barang' => $nama_barang,
                'merk_barang' => $merk_barang,
                'kondisi_barang' => $kondisi,
                'jumlah_barang' => $cek_barang_ada['jumlah_barang'] + $jumlah,
            ];
            $inventori->where('kode_barang', $cek_barang_ada['kode_barang'])->set($data)->update();
        } else if ($cek_barang_beda_kondisi) {
            $data = [
                'kode_barang' => $cek_barang_beda_kondisi['kode_barang'],
                'nama_barang' => $nama_barang,
                'merk_barang' => $merk_barang,
                'kondisi_barang' => $kondisi,
                'jumlah_barang' => $jumlah,
            ];
            $inventori->insert($data);
        } else {
            $data = [
                'kode_barang' => $kode_barang,
                'nama_barang' => $nama_barang,
                'merk_barang' => $merk_barang,
                'kondisi_barang' => $kondisi,
                'jumlah_barang' => $jumlah,
            ];
            $inventori->insert($data);
        }
        $b_masuk->insert([
            'kode_barang' => $kode_barang,
            'nama_barang' => $nama_barang,
            'merk_barang' => $merk_barang,
            'kondisi_barang' => $kondisi,
            'jumlah_barang' => $jumlah,
            'tanggal_masuk' => date('Y-m-d')
        ]);
        notif_swal('success', 'Berhasil Tambah Barang');
        return redirect()->back();
    }

    public function destroy($id)
    {
        $inventori = new InventoryModel();
        $b_masuk = new BarangMasukModel();
        $cek = $b_masuk->find($id);
        if ($cek) {
            $data_inventori = $inventori->where('kode_barang', $cek['kode_barang'])->where('kondisi_barang', $cek['kondisi_barang'])->first();
            if ($data_inventori > 0) {
                if ($data_inventori['jumlah_barang'] == $cek['jumlah_barang']) {
                    $inventori->where('kode_barang', $cek['kode_barang'])->where('kondisi_barang', $cek['kondisi_barang'])->delete();
                } else if ($data_inventori['jumlah_barang'] > $cek['jumlah_barang']) {
                    $update = [
                        'jumlah_barang' => $data_inventori['jumlah_barang'] - $cek['jumlah_barang']
                    ];
                    $inventori->where('kode_barang', $cek['kode_barang'])->where('kondisi_barang', $cek['kondisi_barang'])->set($update)->update();
                }
            }
        }
        $b_masuk->delete($id);

        notif_swal('success', 'Berhasil Hapus Data');
        return redirect()->back();
    }

}