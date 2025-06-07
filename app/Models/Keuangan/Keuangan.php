<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Model;

class Keuangan extends Model
{
    protected $table = 'keuangan';

    protected $fillable = [
        'tanggal_pelaporan',
        'bulan_ke',
        'minggu_ke',
        'saldo_akhir',
    ];

    public function pengeluaran()
    {
        return $this->hasMany(Pengeluaran::class, 'keuangan_id_fk');
    }
    public function pemasukan()
    {
        return $this->hasMany(Pemasukan::class, 'keuangan_id_fk');
    }
}
