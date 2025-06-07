<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    protected $table = 'pengeluaran';

    protected $fillable = [
        'keuangan_id_fk',
        'kategori_pengeluaran',
        'deskripsi',
        'jumlah',
        'tanggal',
    ];
    public function keuangan()
    {
        return $this->belongsTo(Keuangan::class, 'keuangan_id_fk');
    }
}
