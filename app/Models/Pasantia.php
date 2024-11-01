<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasantia extends Model
{
    use HasFactory;

    public $table = 'pasantias';
    public $timestamps = false;

    public function empresa(){
        return $this->belongsTo(Empresa::class);
    }
    public function usuario(){
        return $this->belongsTo(Usuario::class);
    }
}
