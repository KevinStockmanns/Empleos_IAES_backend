<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LicenciaConducir extends Model
{
    use HasFactory;

    public $guarded = ['id'];
    public $table = 'licencias_conducir';
    public $timestamps = false;

    public function usuario(){
        return $this->belongsTo(Usuario::class);
    }
}
