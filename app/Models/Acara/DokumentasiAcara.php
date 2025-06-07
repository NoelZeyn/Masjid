<?php

namespace App\Models\Acara;

use Illuminate\Database\Eloquent\Model;

class DokumentasiAcara extends Model
{
    protected $table = 'dokumentasi_acara';

    protected $fillable = [
        'acara_id_fk',
        'tipe',
        'catatan',
        'uploaded_at',
    ];

    public function acara()
    {
        return $this->belongsTo(Acara::class, 'acara_id_fk');
    }
}
