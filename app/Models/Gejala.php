<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gejala extends Model
{
    protected $fillable = ['kode', 'nama', 'pertanyaan'];

    public function rules()
    {
        return $this->hasMany(Rule::class);
    }
}