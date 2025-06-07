<?php

namespace App\Models\Kurban;

use App\Models\Warga;
use Illuminate\Database\Eloquent\Model;

class KurbanWarga extends Model
{
    protected $table = 'kurban_warga';

    protected $fillable = [
        'kurban_id_fk',
        'warga_id_fk',
    ];

    public function kurban()
    {
        return $this->belongsTo(Kurban::class, 'kurban_id_fk');
    }

    // Relasi ke tabel Warga
    public function warga()
    {
        return $this->belongsTo(Warga::class, 'warga_id_fk');
    }
}
