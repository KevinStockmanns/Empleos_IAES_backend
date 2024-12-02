<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExperienciaLaboral extends Model
{
    use HasFactory;
    public $table = 'experiencias_laborales';
    public $timestamps = false;
    public $guarded=['id'];

    public function usuario(){
        return $this->belongsTo(Usuario::class);
    }
    public function empresa(){
        return $this->belongsTo(Empresa::class);
    }
}
