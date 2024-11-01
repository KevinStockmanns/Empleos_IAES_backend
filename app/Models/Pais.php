<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    use HasFactory;

    protected $table = 'paises';
    public $timestamps = false;
    public $fillable = ["nombre"];


    public function provincias(){
        return $this->hasMany(Provincia::class);
    }


    public static function createOrFirst(array $attributes)
    {
        $instance = self::where('nombre', $attributes['nombre'])->first();

        if ($instance) {
            return $instance; 
        }

        return self::create($attributes); 
    }
}
