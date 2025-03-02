<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExperienciaLaboral extends Model
{
    use HasFactory;
    public $table = 'experiencias_laborales';
    public $timestamps = false;
    public $guarded=['id'];
    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_terminacion' => 'datetime',
    ];

    public function usuario(){
        return $this->belongsTo(Usuario::class);
    }
    public function empresaModel(){
        return $this->belongsTo(Empresa::class, 'empresa_id' ,'id');
    }
}
