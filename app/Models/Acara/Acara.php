<?php

namespace App\Models\Acara;

use App\Models\Warga;
use Illuminate\Database\Eloquent\Model;

class Acara extends Model
{
    protected $table = 'acara';

    protected $fillable = [
        'nama_acara',
        'deskripsi',
        'kategori_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'lokasi',
        'waktu',
        'status',
    ];

    public function dokumentasiAcara()
    {
        return $this->hasMany(DokumentasiAcara::class, 'acara_id_fk');
    }
    public function warga()
    {
        return $this->belongsToMany(Warga::class, 'peserta_acara', 'acara_id_fk', 'warga_id_fk')->withPivot('status_kehadiran');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriAcara::class, 'kategori_id');
    }
}
