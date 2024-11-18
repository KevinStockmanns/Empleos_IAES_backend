<?php
namespace App\Validators;

interface Validator{
    /**
     * Ejecuta la validación.
     *
     * @param mixed $value
     * @return bool
     */
    public function validate(): bool;

    /**
     * Mensaje de error para la validación fallida.
     *
     * @return string
     */
    public function message(): array;
}