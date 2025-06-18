<?php

namespace App\Models\Inventaris;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'supplier';

    protected $fillable = [
        'nama_supplier',
        'alamat',
        'kontak',
        'email',
    ];

        public function barang()
    {
        return $this->hasMany(Barang::class, 'supplier_id');
    }
}
