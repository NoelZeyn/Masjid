<?php

namespace App\Models\Inventaris;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';

    protected $fillable = [
        'supplier_id',
        'nama_barang',
        'deskripsi',
        'harga',
        'stock',
        'merk',
        'kategori',
        'kondisi',
        'keterangan',
    ];

    public function transaksi()
    {
        return $this->belongsToMany(Transaksi::class, 'detail_transaksi', 'barang_id_fk', 'transaksi_id_fk');
    }


    public function riwayatPenggunaan()
    {
        return $this->hasMany(RiwayatPenggunaan::class, 'barang_id_fk');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}
