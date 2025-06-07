<?php

namespace App\Models\Kurban;

use App\Models\Admin;
use App\Models\RiwayatPenyembelihan;
use App\Models\Warga;
use Illuminate\Database\Eloquent\Model;

class Kurban extends Model
{
    protected $table = 'kurban';

    protected $fillable = [
        'jenis_hewan',
        'jumlah',
        'status',
        'admin_id',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function warga()
    {
        return $this->belongsToMany(Warga::class, 'kurban_warga', 'kurban_id_fk', 'warga_id_fk');
    }

    public function riwayatPenyembelihan()
    {
        return $this->hasOne(RiwayatPenyembelihan::class);
    }
}
