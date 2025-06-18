<?php

namespace App\Models\Inventaris;

use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    protected $table = 'detail_transaksi';

    protected $fillable = [
        'transaksi_id_fk',
        'barang_id_fk',
        'jumlah',
        'harga_satuan',
    ];


    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id_fk');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id_fk');
    }
}
