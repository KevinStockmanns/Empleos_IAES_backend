<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LicenciaConducir extends Model
{
    use HasFactory;

    public function usuario(){
        return $this->belongsTo(Usuario::class);
    }
}
