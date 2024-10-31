<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    use HasFactory;

    protected $table = 'paises';
    protected $timestamps = false;

    public function provincias(){
        return $this->hasMany(Provincia::class);
    }
}
