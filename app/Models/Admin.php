<?php

namespace App\Models;

use App\Models\Kurban\Kurban;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admin';

    protected $fillable = [
        'nama_lengkap',
        'posisi',
        'email',
        'password',
        'tugas',
        'timestamp',
    ];

    public function kurban()
    {
        return $this->hasMany(Kurban::class, 'admin_id');
    }

    public function pengajuan()
    {
        return $this->hasMany(Pengajuan::class, 'admin_id');
    }

    public function softDelete()
    {
        return $this->hasMany(SoftDelete::class, 'admin_id');
    }

    public function logAktifitas()
    {
        return $this->hasMany(LogAktifitas::class, 'admin_id');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'admin_id');
    }

    public function dataDiri()
    {
        return $this->hasMany(DataDiri::class, 'admin_id');
    }
}
