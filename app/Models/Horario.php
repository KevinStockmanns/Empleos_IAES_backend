<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;
    
    public $table = 'horarios';
    public $timestamps = false;
    public $guarded = ['id'];



    public function empresas(){
        return $this->belongsToMany(Empresa::class,"empresa_horarios", "horario_id", "empresa_id");
    }
}
