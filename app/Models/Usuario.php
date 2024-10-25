<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'apellido', 'nacimiento', 'dni', 'correo', 'clave'];

    // Definir la relación: un usuario tiene un rol
    public function rol()
    {
        return $this->belongsTo(Rol::class);
    }
    public function habilidad(){
        return $this->belongsTo(Habilidad::class);
    }
    public function licenciaConducir(){
        return $this->hasOne(LicenciaConducir::class);
    }
}
