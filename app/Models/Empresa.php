<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresa extends Model
{
    use HasFactory, SoftDeletes;
    public $table = 'empresas';
    public $timestamps = true;

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

    public function usuario(){
        return $this->belongsTo(Usuario::class);
    }
}
