<?php
namespace App\Services;

use Hash;

class EncryptService{

    public function encryptPassword ($password){
        return Hash::make($password);
    }

    public function verifyPassword($password, $hash){
        return Hash::check($password, $hash);
    }
}