<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diagnosa extends Model
{
    protected $fillable = ['user_id', 'kerusakan_id', 'nilai_cf', 'hasil_semua'];

    protected $casts = [
        'hasil_semua' => 'array',
    ];

    public function kerusakan()
    {
        return $this->belongsTo(Kerusakan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gejalas()
    {
        return $this->belongsToMany(Gejala::class, 'diagnosa_gejalas')
            ->withPivot('cf_user')
            ->withTimestamps();
    }
}