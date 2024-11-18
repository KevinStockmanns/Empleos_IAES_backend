<?php
namespace App\DTO\Contacto;

use App\Models\Contact;

class ContactoRespuestaDTO{
    public $telefono = null;
    public $telefonoFijo = null;
    public $linkedin = null;
    public $paginaWeb = null;

    public function __construct(Contact $contacto) {
        $this->telefono = $contacto->numero_telefono;
        $this->telefonoFijo = $contacto->telefono_fijo;
        $this->linkedin = $contacto->linkedin;
        $this->paginaWeb = $contacto->pagina_web;
    }
}