<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAktifitas extends Model
{
    protected $table = 'log_aktifitas';

    protected $fillable = [
        'admin_id',
        'aksi',
        'entitas',
        'entitas_id',
        'tanggal',
    ];

    
    public function admin()
    {
        return $this-> belongsTo(Admin::class, 'admin_id');
    }
}
