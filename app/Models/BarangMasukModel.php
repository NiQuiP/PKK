<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangMasukModel extends Model
{
    protected $table = 'barang_masuk';
    protected $primaryKey = 'id_barang';
    protected $allowedFields = ['kode_barang', 'nama_barang', 'merk_barang', 'kondisi_barang', 'jumlah_barang', 'tanggal_masuk'];


}