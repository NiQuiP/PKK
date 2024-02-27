<?php

namespace App\Controllers\Inventory;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\InventoryModel;
use App\Models\BarangMasukModel;

class DataBarangController extends BaseController
{
    protected $helpers = (['url', 'form', 'global_helper', 'text']);
    public function index()
    {
        $Barang = new InventoryModel();
        $data = [
            'dataBarang' => $Barang->paginate(10, 'barang'),
            'pager' => $Barang->pager,
            'aktif_data_barang' => 'active',
            'aktif_barang' => 'active'
        ];
        return view('Inventory/v_data_barang', $data);
    }

    public function store()
    {
        $validasi = \Config\Services::validation();
        $inventori = new InventoryModel();

        $aturan = [
            'nama_barang' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama Barang harus diisi !',
                ]
            ],
            'merk_barang' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Merk Barang harus diisi !'
                ]
            ],
            'kondisi' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kondisi harus diisi !'
                ]
            ],
            'jumlah' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Jumlah harus diisi !'
                ]
            ],
        ];
        $validasi->setRules($aturan);
        if ($validasi->withRequest($this->request)->run()) {
            $nama_barang = $this->request->getPost('nama_barang');
            $merk_barang = $this->request->getPost('merk_barang');
            $kondisi = $this->request->getPost('kondisi');
            $jumlah = $this->request->getPost('jumlah');
            $kode_barang = random_string('numeric', 6);

            // mengecek jika barang sudah ada dan kondisi barang sama dengan input an user
            $cek_barang_ada = $inventori->where('nama_barang', $nama_barang)->where('kondisi_barang', $kondisi)->get()->getRowArray();
            // mengecek jika barang ada tapi kondisi barang berbeda
            $cek_barang_beda_kondisi = $inventori->where('nama_barang', $nama_barang)->get()->getRowArray();
            if ($cek_barang_ada) {
                $kode_barang = $cek_barang_ada['kode_barang'];
                $data = [
                    'kode_barang' => $cek_barang_ada['kode_barang'],
                    'nama_barang' => $nama_barang,
                    'merk_barang' => $merk_barang,
                    'kondisi_barang' => $kondisi,
                    'jumlah_barang' => $cek_barang_ada['jumlah_barang'] + $jumlah,
                ];
                $inventori->save($data);
            } else if ($cek_barang_beda_kondisi) {
                $data = [
                    'kode_barang' => $kode_barang,
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
            notif_swal('success', 'Berhasil Tambah Barang');
            $hasil['success'] = true;
        } else {
            $hasil = [
                'error' => [
                    'nama_barang' => $validasi->getError('nama_barang'),
                    'merk_barang' => $validasi->getError('merk_barang'),
                    'kondisi' => $validasi->getError('kondisi'),
                    'jumlah' => $validasi->getError('jumlah'),
                ],
            ];
        }
        return json_encode($hasil);
    }

    public function destroy($id)
    {
        $inventori = new InventoryModel();
        $inventori->delete($id);
        notif_swal('success', 'Berhasil Hapus Data');
        return redirect()->back();
    }

    public function show($id)
    {
        $inventori = new InventoryModel();
        $data = $inventori->find($id);

        return json_encode($data);
    }

    public function edit()
    {
        $id_barang = $this->request->getPost('id_barang');
        $nama_barang = ucwords($this->request->getPost('nama_barang'));
        $merk_barang = strtoupper($this->request->getPost('merk_barang'));
        $kondisi_barang = ucwords($this->request->getPost('kondisi_barang'));
        $jumlah_barang = $this->request->getPost('jumlah_barang');
        $foto_barang = $this->request->getFile('foto_barang');

        $inventori = new InventoryModel();
        $cek_data = $inventori->find($id_barang);
        $foto_lama = FCPATH . 'img/' . $cek_data['foto_barang'];
        if (file_exists($foto_lama) && $cek_data['foto_barang'] != '') {
            unlink($foto_lama);
        }
        if ($foto_barang->isValid()) {
            $namaFile = date('Y-m-d') . ' . ' . date('H-i-sa') . '.jpg';
            $foto_barang->move(FCPATH . 'img', $namaFile);
        } else {
            $namaFile = null;
        }
        $data = [
            'nama_barang' => $nama_barang,
            'merk_barang' => $merk_barang,
            'kondisi_barang' => $kondisi_barang,
            'jumlah_barang' => $jumlah_barang,
            'foto_barang' => $namaFile,
        ];
        $inventori->where('id_barang', $id_barang)->set($data)->update();
        notif_swal('success', 'Berhasil Update Data');
        return redirect()->back();
    }
}