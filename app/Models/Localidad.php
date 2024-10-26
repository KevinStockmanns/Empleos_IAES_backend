<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Localidad extends Model
{
    use HasFactory;
    
    public function provincia(){
        return $this->belongsTo(Provincia::class);
    }

    public function localidades(){
        return $this->hasMany(Localidad::class);
    }
}