<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriPengeluaran extends Model
{
    protected $table = 'kategori_pengeluarans';

    protected $fillable = [
        'nama_kategori',
        'keterangan',
    ];

    public function pengeluarans()
    {
        return $this->hasMany(Pengeluarans::class, 'kategori_id');
    }
}
