<?php

namespace App\Controllers\Inventory;

use App\Controllers\BaseController;
use App\Models\InventoryModel;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PinjamanModel;

class DataPinjamanController extends BaseController
{
    protected $helpers = (['url', 'form', 'text', 'global_helper']);
    public function index()
    {
        // melarang user akses halaman ini
        if (session('redirected') != 'admin') {
            return redirect()->back();
        }

        $pinjaman = new PinjamanModel();
        $dataPinjaman = $pinjaman->where('status_pinjaman', 'Diterima')->orderBy('id', 'desc')->paginate(10, 'pinjaman');
        $data = [
            'aktif_pinjaman' => 'aktif',
            'dataPinjaman' => $dataPinjaman,
            'pager' => $pinjaman->pager,
        ];
        return view('Inventory/v_data_peminjam', $data);
    }

    public function pinjaman()
    {
        // melarang admin akses halaman ini
        if (session('redirected') != 'user') {
            return redirect()->back();
        }

        $sesi_email = session()->get('member_email');
        $pinjaman = new PinjamanModel();
        $inventori = new InventoryModel();
        $dataPinjaman = $pinjaman->where('email', $sesi_email)->orderBy('id', 'desc')->paginate(10, 'pinjaman');
        $data_barang = $pinjaman->where('email', $sesi_email)->orderBy('id', 'desc')->findAll();
        $data = [
            'aktif_pinjaman' => 'aktif',
            'pinjaman' => $dataPinjaman,
            'pager' => $pinjaman->pager,
            'inventori' => $inventori->findAll(),
            'data_barang' => $data_barang
        ];
        return view('Inventory/v_data_pinjaman', $data);
    }

    public function store()
    {
        $id_barang = $this->request->getVar('pinjam_barang');
        $jumlah = $this->request->getVar('jumlah');
        $ruangan = strtoupper($this->request->getVar('ruangan_barang'));
        $pinjaman = new PinjamanModel();
        $inventori = new InventoryModel();
        $data_barang = $inventori->find($id_barang);
        $sesi_email = session()->get('member_email');
        $sesi_nama = session()->get('member_nama_lengkap');

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
            'nama_lengkap' => $sesi_nama,
            'nama_barang' => $data_barang['nama_barang'],
            'merk_barang' => $data_barang['merk_barang'],
            'kode_barang' => $data_barang['kode_barang'],
            'kondisi_barang' => $data_barang['kondisi_barang'],
            'jumlah_barang' => $jumlah,
            'foto_barang' => $data_barang['foto_barang'],
            'ruangan' => $ruangan,
            'email' => $sesi_email,
            'tanggal_pinjaman' => date('Y-m-d')
        ];
        $pinjaman->insert($data);
        notif_swal('success', 'Berhasil Pinjam Barang');
        return redirect()->back();
    }

    public function kembalikan()
    {
        $id_barang = $this->request->getVar('kembalikan_barang');
        $jumlah = $this->request->getVar('jumlah');
        $pinjaman = new PinjamanModel();
        $inventori = new InventoryModel();

        $cek_data = $pinjaman->where('id', $id_barang)->first();
        $cek_inventori = $inventori->where('kode_barang', $cek_data['kode_barang'])->where('kondisi_barang', $cek_data['kondisi_barang'])->first();
        if ($jumlah > $cek_data['jumlah_barang']) {
            notif_swal('error', 'Jumlah melebihi stock');
            return redirect()->back();
        } else if ($jumlah == $cek_data['jumlah_barang']) {
            if ($cek_inventori) {
                $update = [
                    'jumlah_barang' => $cek_inventori['jumlah_barang'] + $jumlah,
                ];
                $inventori->where('kode_barang', $cek_data['kode_barang'])->where('kondisi_barang', $cek_data['kondisi_barang'])->set($update)->update();
            } else {
                $inventori->insert([
                    'kode_barang' => $cek_data['kode_barang'],
                    'nama_barang' => $cek_data['nama_barang'],
                    'merk_barang' => $cek_data['merk_barang'],
                    'kondisi_barang' => $cek_data['kondisi_barang'],
                    'jumlah_barang' => $cek_data['jumlah_barang'],
                    'foto_barang' => $cek_data['foto_barang'],
                ]);
            }
            $pinjaman->delete($id_barang);
        } else if ($jumlah < $cek_data['jumlah_barang']) {
            $update = [
                'jumlah_barang' => $cek_inventori['jumlah_barang'] + $jumlah,
            ];
            $inventori->where('kode_barang', $cek_data['kode_barang'])->where('kondisi_barang', $cek_data['kondisi_barang'])->set($update)->update();
            $pinjaman->save([
                'id' => $id_barang,
                'jumlah_barang' => $cek_data['jumlah_barang'] - $jumlah
            ]);
        }
        notif_swal('success', 'Berhasil Retur Barang');
        return redirect()->back();
    }
}