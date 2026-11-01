<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kerusakan extends Model
{
    protected $fillable = ['kode', 'nama', 'prioritas', 'penyebab', 'solusi', 'komponen_id'];

    public function komponen()
    {
        return $this->belongsTo(Komponen::class);
    }

    public function rules()
    {
        return $this->hasMany(Rule::class);
    }

    public function rekomendasis()
    {
        return $this->hasMany(Rekomendasi::class)->orderBy('urutan');
    }
}