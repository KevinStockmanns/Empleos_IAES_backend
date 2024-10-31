<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Direccion extends Model
{
    use HasFactory;
    protected $table="direcciones";
    protected $timestamps=false;

    public function localidad(){
        return $this->belongsTo(Localidad::class);
    }

    public function usuarios(){
        return $this->belongsToMany(Usuario::class);
    }
}
