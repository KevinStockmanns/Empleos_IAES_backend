<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TituloDetalle extends Model
{
    use HasFactory;

    public function titulo(){
        return $this->belongsTo(Titulo::class);
    }
}
