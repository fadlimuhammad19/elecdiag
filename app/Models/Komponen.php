<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Komponen extends Model
{
    protected $fillable = ['kategori_id', 'kode', 'nama', 'jenis_arus', 'deskripsi'];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function kerusakans()
    {
        return $this->hasMany(Kerusakan::class);
    }
}