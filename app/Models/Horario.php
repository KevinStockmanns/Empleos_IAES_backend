<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;
    
    public $table = 'horarios';
    public $timestamps = false;


    public function empresas(){
        return $this->hasMany(Empresa::class);
    }
}
