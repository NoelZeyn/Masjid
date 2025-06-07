<?php

namespace App\Models;

use App\Models\Kurban\Kurban;
use Illuminate\Database\Eloquent\Model;

class RiwayatPenyembelihan extends Model
{
    protected $table = 'riwayat_penyembelihan';

    protected $fillable = [
        'kurban_id_fk',
        'tanggal',
        'catatan',
    ];
        public function kurban()
    {
        return $this->hasOne(Kurban::class);
    }
}
