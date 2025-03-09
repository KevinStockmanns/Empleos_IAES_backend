<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasantia extends Model
{
    use HasFactory;

    public $table = 'pasantias';
    public $timestamps = false;
    public $guarded = ['id'];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_final' => 'date',
    ];

    public function empresa(){
        return $this->belongsTo(Empresa::class);
    }
    public function usuarios(){
        return $this->belongsToMany(Usuario::class, 'pasantias_usuarios')->withPivot('nota');
    }



    public function isComplete(){
        return ($this->empresa != null && 
            $this->usuarios->count() > 0 && 
            $this->usuarios->contains(fn($user) => $user->pivot->nota != null) && 
            $this->fecha_inicio != null &&
            $this->titulo != null);
    }
}
