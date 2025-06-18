<?php

namespace App\Models;

use App\Models\Inventaris\Transaksi;
use App\Models\Kurban\Kurban;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable implements JWTSubject
{
    use Notifiable;
    protected $table = 'admin';

    protected $fillable = [
        'nama_lengkap',
        'posisi',
        'email',
        'status',
        'password',
        'tugas',
        'timestamp',
        'foto_ktp',
        'foto_profil',
    ];
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

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
        return $this->hasOne(DataDiri::class);
    }
}
