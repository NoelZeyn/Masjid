<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Infaq extends Model
{
    protected $table = 'infaq';

    protected $fillable = [
        'nama_infaq',
        'warga_id_fk',
        'tanggal_pemberian',
        'kategori_infaq',
        'jumlah',
        'satuan',
    ];
        public function warga()
    {
        return $this->belongsTo(Warga::class, 'warga_id_fk');
    }
}
