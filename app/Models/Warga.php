<?php

namespace App\Models;

use App\Models\Acara\Acara;
use App\Models\Kurban\Kurban;
use App\Models\Kurban\KurbanWarga;
use Illuminate\Database\Eloquent\Model;

class Warga extends Model
{
    protected $table = 'warga';

    protected $fillable = [
        'nama_lengkap',
        'kontak',
        'alamat',
    ];


    public function kurban()
    {
        return $this->belongsToMany(Kurban::class, 'kurban_warga', 'warga_id_fk', 'kurban_id_fk');
    }
    public function acara()
    {
        return $this->belongsToMany(Acara::class, 'peserta_acara', 'warga_id_fk', 'acara_id_fk')->withPivot('status_kehadiran');
    }

    public function kurbanWarga()
    {
        return $this->hasMany(KurbanWarga::class, 'warga_id_fk');
    }

    public function kritikSaran()
    {
        return $this->hasMany(KritikSaran::class, 'warga_id_fk');
    }

        public function infaq()
    {
        return $this->hasMany(Infaq::class, 'warga_id_fk');
    }
}
