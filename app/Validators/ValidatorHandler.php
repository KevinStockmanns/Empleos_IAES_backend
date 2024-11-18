<?php
namespace App\Validators;

class ValidatorHandler{
    private array $validators = [];

    public function addValidator(Validator $validator): void    {
        $this->validators[] = $validator;
    }

    public function validate(): array {
        $errors = [];

        foreach ($this->validators as $validator) {
            if (!$validator->validate()) {
                $errors = array_merge($errors, $validator->message());
            }
        }

        return $errors;
    }
}