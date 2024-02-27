<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangKeluarModel extends Model
{
    protected $table = 'barang_keluar';
    protected $primaryKey = 'id_barang';
    protected $allowedFields = ['kode_barang', 'nama_barang', 'merk_barang', 'kondisi_barang', 'jumlah_barang', 'tanggal_keluar'];

}