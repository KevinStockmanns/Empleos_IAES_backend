<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfilProfesional extends Model
{
    use HasFactory;

    public $table = 'perfil_profesional';
    public $timestamps = false;
    public $guarded = ['id'];

    public function usuario(){
        return $this->belongsTo(Usuario::class);
    }
}
