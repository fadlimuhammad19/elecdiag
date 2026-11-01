<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rekomendasi extends Model
{
    protected $fillable = ['kerusakan_id', 'urutan', 'langkah'];

    public function kerusakan()
    {
        return $this->belongsTo(Kerusakan::class);
    }
}