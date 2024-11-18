<?php
namespace App\Services;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Model;

class ContactoService{

    public function crearContacto($data): Contact|Model{
        return Contact::create([
            'numero_telefono'=> $data['telefono'],
            'telefono_fijo'=> $data['telefonoFijo'] ?? null,
            'linkedin'=> $data['linkedin'] ?? null,
            'pagina_web'=> $data['paginaWeb'] ?? null,
        ]);
    }

    public function actualizarContacto(Contact $contacto, $data): Contact{
        if(isset($data['telefono'])){
            $contacto->numero_telefono = $data['telefono'];
        }
        if(isset($data['telefonoFijo'])){
            $contacto->telefono_fijo = $data['telefonoFijo'];
        }
        if(isset($data['linkedin'])){
            $contacto->linkedin = $data['linkedin'];
        }
        if(isset($data['paginaWeb'])){
            $contacto->pagina_web = $data['paginaWeb'];
        }

        $contacto->save();
        return $contacto;
    }
}