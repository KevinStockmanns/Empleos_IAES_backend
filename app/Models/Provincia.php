<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    use HasFactory;

    public function pais(){
        return $this->belongsTo(Pais::class);
    }
    public function localidades(){
        return $this->hasMany(Localidad::class);
    }
}
