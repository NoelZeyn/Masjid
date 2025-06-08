<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataDiri extends Model
{
    protected $table = 'data_diri';

    protected $fillable = [
        'admin_id',
        'kontak',
        'alamat',
    ];

    public function admin()
    {
        return $this->hasOne(Admin::class);
    }
}
