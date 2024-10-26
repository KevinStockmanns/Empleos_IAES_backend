<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;

    protected $fillable = ['nombre'];

// Desactiva los timestamps automáticos
    public $timestamps = false;
    protected $table = 'roles';

    // Definir la relación: un rol tiene muchos usuarios
    public function users()
    {
        return $this->hasMany(Usuario::class);
    }
}
