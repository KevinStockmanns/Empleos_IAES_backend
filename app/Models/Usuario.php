<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Tymon\JWTAuth\Contracts\JWTSubject;

class Usuario extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $table = 'usuarios';
    public $timestamps = false;

    // protected $password = 'clave';


    protected $fillable = ['nombre', 'apellido', 'fecha_nacimiento', 'dni', 'correo', 'clave','estado', 'direccion_id'];


    public function isAdmin(): bool{
        return ($this->rol->nombre == "ADMIN" || $this->rol->nombre == "DEV");
    }

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

    
    public function direccion() {
        return $this->belongsTo(Direccion::class);
    }
    public function pasantias(){
        return $this->hasMany(Pasantia::class);
    }
    public function contacto(){
        return $this->belongsTo(Contact::class);
    }



    /**
     * Obtiene el identificador que se almacenará en el token JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Normalmente el ID del usuario
    }

    /**
     * Devuelve cualquier reclamo personalizado que quieras añadir al token JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
