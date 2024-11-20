<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habilidad extends Model
{
    use HasFactory;


    protected $guarded = ['id'];
    public $table = 'habilidades';
    public $timestamps=false;

    public function usuarios(){
        return $this->belongsToMany(Usuario::class, 
        'habilidad_usuario',
        'habilidad_id',
        'usuario_id'
    );
    }
}
