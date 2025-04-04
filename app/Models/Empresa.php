<?php

namespace App\Models;

use App\Enums\PasantiaEstadoEnum;
use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Empresa extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;
    public $table = 'empresas';
    public $timestamps = true;

    protected $guarded = ['id'];


    public function horarios(){
        return $this->belongsToMany(Horario::class, "empresa_horarios", "empresa_id", "horario_id");
    }


    public function pasantias(){
        return $this->hasMany(Pasantia::class);
    }
    public function getPasantiasPublicas(){
        return $this->pasantias()->where('estado', PasantiaEstadoEnum::APROBADA->value)
        ->whereExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('pasantias_usuarios') 
                ->whereColumn('pasantias_usuarios.pasantia_id', 'pasantias.id')
                ->whereNotNull('pasantias_usuarios.nota'); // Solo si tiene nota no nula
            })->get();
    }
    public function direccion(){
        return $this->belongsTo(Direccion::class);
    }
    public function experienciasLaborales(){
        return $this->hasMany(ExperienciaLaboral::class);
    }

    public function usuario(){
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id');
    }
}
