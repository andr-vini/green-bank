<?php

namespace App\Services;

use App\Models\User;
use Exception;

class UserService
{
    public function store(array $data): User
    {
        try{
            $user = User::create($data);
            return $user;
        } catch (Exception $e) {
            \Log::error('Erro ao criar usuário; Message: ' . $e->getMessage());
        }
    }
}
