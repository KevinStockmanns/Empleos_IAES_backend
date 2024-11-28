<?php
namespace App\Validators;

class ValidatorHandler{
    private array $validators = [];

    public function addValidator(Validator $validator): void    {
        $this->validators[] = $validator;
    }

    public function validate(): void {
        foreach ($this->validators as $validator) {
            $validator->validate();
        }
    }
}