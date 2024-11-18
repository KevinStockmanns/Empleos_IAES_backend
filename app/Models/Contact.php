<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    public $table = 'contactos';
    public $guarded =['id'];
    public $timestamps = false;

    public function usuario(){
        return $this->hasOne(Usuario::class);
    }
}
