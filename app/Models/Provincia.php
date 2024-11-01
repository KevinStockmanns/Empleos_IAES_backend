<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    use HasFactory;

    protected $table = "provincias";
    public $timestamps = false;
    public $fillable = ["nombre", "pais_id"];


    public function pais(){
        return $this->belongsTo(Pais::class);
    }
    public function localidades(){
        return $this->hasMany(Localidad::class);
    }



    public static function createOrFirst(array $data){
        $provincia = self::where('nombre', $data['nombre'])->first();
        if ($provincia){
            return $provincia;
        }
        return self::create($data);
    }
}
