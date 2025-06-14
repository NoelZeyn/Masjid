<?php

namespace App\Models\Acara;

use Illuminate\Database\Eloquent\Model;

class KategoriAcara extends Model
{
    protected $table = 'kategori_acara';

    protected $fillable = ['nama'];

    public function acara()
    {
        return $this->hasMany(Acara::class, 'kategori_id');
    }
}
