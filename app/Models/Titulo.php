<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Titulo extends Model
{
    use HasFactory;

    public $table = 'titulos';
    public $timestamps = false;
    public $guarded = ['id'];

    public function detalles(){
        return $this->hasMany(TituloDetalle::class);
    }
}
