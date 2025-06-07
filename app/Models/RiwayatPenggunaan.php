<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatPenggunaan extends Model
{
    protected $table = 'riwayat_penggunaan';

    protected $fillable = [
        'barang_id_fk',
        'tanggal_pemijaman',
        'deskripsi_penggunaan',
        'tanggal_pengembalian',
    ];

    public function barang()
    {
        return $this->belongsTo(barang::class, 'barang_id_fk');
    }
}
