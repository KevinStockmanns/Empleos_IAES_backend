<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Localidad extends Model
{
    use HasFactory;
    protected $table = 'localidades';
    public $timestamps = false;
    public $fillable = ["nombre", "provincia_id", "codigo_postal"];

    
    public function provincia(){
        return $this->belongsTo(Provincia::class);
    }

    public function localidades(){
        return $this->hasMany(Localidad::class);
    }


    public static function createOrFirst($data){
        $loc = self::where('nombre', $data['nombre'])->first();
        if($loc){
            return $loc;
        }
        return self::create($data);
    }
}
