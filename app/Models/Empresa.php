<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;
    public $table = 'empresas';
    public $timestamps = false;

    protected $guarded = ['id'];


    public function horarios(){
        return $this->belongsToMany(Horario::class, "empresa_horarios", "empresa_id", "horario_id");
    }


    public function pasantias(){
        return $this->hasMany(Pasantia::class);
    }
    public function direccion(){
        return $this->belongsTo(Direccion::class);
    }
    public function experienciasLaborales(){
        return $this->hasMany(ExperienciaLaboral::class);
    }
}
