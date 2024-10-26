<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfilProfesional extends Model
{
    use HasFactory;

    public function usuario(){
        $this->belongsTo(Usuario::class);
    }
}
