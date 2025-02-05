<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasantia extends Model
{
    use HasFactory;

    public $table = 'pasantias';
    public $timestamps = false;

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_final' => 'date',
    ];

    public function empresa(){
        return $this->belongsTo(Empresa::class);
    }
    public function usuario(){
        return $this->belongsTo(Usuario::class);
    }
}
