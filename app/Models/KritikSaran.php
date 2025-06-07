<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KritikSaran extends Model
{
    protected $table = 'kritik_saran';

    protected $fillable = [
        'warga_id_fk',
        'pesan',
        'tipe',
        'tanggal',
    ];

        public function warga()
    {
        return $this->belongsTo(Warga::class, 'warga_id_fk');
    }
}
