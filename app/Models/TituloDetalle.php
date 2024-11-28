<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TituloDetalle extends Model
{
    use HasFactory;

    public $table= 'titulos_detalles';
    public $timestamps=false;
    public $guarded = ['id'];

    public function titulo(){
        return $this->belongsTo(Titulo::class);
    }
    public function usuario(){
        return $this->belongsTo(Usuario::class);
    }
}
