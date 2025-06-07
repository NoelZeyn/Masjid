<?php

namespace App\Models\Acara;

use App\Models\Warga;
use Illuminate\Database\Eloquent\Model;

class PesertaAcara extends Model
{
    protected $table = 'peserta_acara';

    protected $fillable = [
        'acara_id_fk',
        'warga_id_fk',
        'status_kehadiran',
    ];

    public function acara()
    {
        return $this->belongsTo(Acara::class, 'acara_id_fk');
    }

    public function warga()
    {
        return $this->belongsTo(Warga::class, 'warga_id_fk');
    }
}
