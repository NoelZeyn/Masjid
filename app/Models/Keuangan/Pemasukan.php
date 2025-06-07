<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Model;

class Pemasukan extends Model
{
    protected $table = 'pemasukan';

    protected $fillable = [
        'keuangan_id_fk',
        'sumber',
        'kategori_pemasukan',
        'deskripsi',
        'jumlah',
        'tanggal',
    ];

    public function keuangan()
    {
        return $this->belongsTo(Keuangan::class, 'keuangan_id_fk');
    }
}
