<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;
    public $table = 'empresas';
    public $timestamps = false;


    public function horario(){
        return $this->belongsTo(Horario::class);
    }


    public function pasantias(){
        return $this->hasMany(Pasantia::class);
    }
}
