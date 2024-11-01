<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Direccion extends Model
{
    use HasFactory;
    protected $table="direcciones";
    public $timestamps=false;
    public $fillable = ["barrio","calle", "numero", "piso", "localidad_id"];


    public function localidad(){
        return $this->belongsTo(Localidad::class);
    }

    public function usuarios(){
        return $this->hasMany(Usuario::class);
    }



    public static function createOrFirst($data){
        $dir = self::where('barrio', $data['barrio'])
            ->where('calle', $data['calle'])
            ->where('numero', $data['numero'])
            ->where('piso', $data['piso'])
            ->first();

        return $dir ? $dir : self::create($data);
    }
}
