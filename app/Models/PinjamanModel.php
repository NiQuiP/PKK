<?php

namespace App\Models;

use CodeIgniter\Model;

class PinjamanModel extends Model
{
    protected $table = 'pinjaman';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nama_barang',
        'merk_barang',
        'nama_lengkap',
        'kode_barang',
        'kondisi_barang',
        'ruangan',
        'jumlah_barang',
        'foto_barang',
        'email',
        'status_pinjaman',
        'status_pengembalian',
        'tanggal_pinjaman'
    ];
}