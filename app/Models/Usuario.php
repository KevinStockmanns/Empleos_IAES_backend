<?php

namespace App\Models;

use App\Enums\PasantiaEstadoEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

use OwenIt\Auditing\Contracts\Auditable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Usuario extends Authenticatable implements JWTSubject, Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $table = 'usuarios';
    public $timestamps = true;

    // protected $password = 'clave';


    public $guarded = ['id'];


    // protected $auditExclude = ['ultimo_inicio'];


    public function isAdmin(): bool{
        return ($this->rol->nombre == "ADMIN" || $this->rol->nombre == "DEV");
    }
    public function isAlumn(): bool{
        return ($this->rol->nombre == "ALUMNO" || $this->rol->nombre == "EGRESADO");
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class);
    }
    public function habilidades(){
        return $this->belongsToMany(Habilidad::class, 'habilidad_usuario',
        'usuario_id',
        'habilidad_id'
    );
    }
    public function licenciaConducir(){
        return $this->hasOne(LicenciaConducir::class);
    }
    public function perfilProfesional(){
        return $this->hasOne(PerfilProfesional::class);
    }
    public function experienciasLaborales(){
        return $this->hasMany(ExperienciaLaboral::class, 'usuario_id', 'id');
    }

    
    public function direccion() {
        return $this->belongsTo(Direccion::class);
    }
    public function pasantias(){
        return $this->belongsToMany(Pasantia::class, 'pasantias_usuarios')->withPivot('nota');
    }
    public function getPasantiasPublicas(){
        return $this->pasantias()->where('estado', PasantiaEstadoEnum::APROBADA->value)
            ->whereNotNull('pasantias_usuarios.nota')->get();
    }
    public function contacto(){
        return $this->belongsTo(Contact::class);
    }
    public function empresas(){
        return $this->hasMany(Empresa::class, 'usuario_id', 'id');
    }

    public function tituloDetalles(){
        return $this->hasMany(TituloDetalle::class)
            ->orderByRaw('ISNULL(fecha_final) DESC, fecha_final DESC')
            ->orderBy('fecha_inicio', 'DESC');
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
