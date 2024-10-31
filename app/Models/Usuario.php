<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    protected $table = 'usuarios';
    public $timestamps = false;

    protected $password = 'clave';


    protected $fillable = ['nombre', 'apellido', 'fecha_nacimiento', 'dni', 'correo', 'clave','estado'];

    public function rol()
    {
        return $this->belongsTo(Rol::class);
    }
    public function habilidades(){
        return $this->belongsToMany(Habilidad::class, 'habilidad_usuario');
    }
    public function licenciaConducir(){
        return $this->hasOne(LicenciaConducir::class);
    }
    public function perfilProfesional(){
        return $this->hasOne(PerfilProfesional::class);
    }
    public function experienciasLaborales(){
        return $this->hasMany(ExperienciaLaboral::class);
    }

    
    public function direcciones() {
        return $this->belongsToMany(Direccion::class, 'usuario_direccion');
    }
}
