<?php

namespace App\Models;

use CodeIgniter\Model;

class InventoryModel extends Model
{
    protected $table = 'inventories';
    protected $primaryKey = 'id_barang';
    protected $allowedFields = ['kode_barang', 'nama_barang', 'merk_barang', 'kondisi_barang', 'jumlah_barang', 'foto_barang'];

}