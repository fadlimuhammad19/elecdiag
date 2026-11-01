<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $fillable = ['kode', 'nama', 'deskripsi'];

    public function komponens()
    {
        return $this->hasMany(Komponen::class);
    }
}