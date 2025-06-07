<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoftDelete extends Model
{
    protected $table = 'soft_delete';

    protected $fillable = [
        'admin_id',
        'deskripsi',
        'aksi',
        'tanggal',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
