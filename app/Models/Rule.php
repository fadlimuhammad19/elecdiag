<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    protected $fillable = ['kerusakan_id', 'gejala_id', 'cf_pakar'];

    public function kerusakan()
    {
        return $this->belongsTo(Kerusakan::class);
    }

    public function gejala()
    {
        return $this->belongsTo(Gejala::class);
    }
}