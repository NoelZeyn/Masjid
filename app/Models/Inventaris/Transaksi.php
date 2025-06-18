<?php

namespace App\Models\Inventaris;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $fillable = [
        'admin_id',
        'tanggal_transaksi',
        'tipe_transaksi',
        'total_harga',
    ];


    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    
    public function barang()
    {
        return $this->belongsToMany(Barang::class, 'detail_transaksi', 'transaksi_id_fk', 'barang_id_fk');
    }
}

