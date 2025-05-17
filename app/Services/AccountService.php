<?php

namespace App\Services;

use App\Models\Account;
use Illuminate\Support\Facades\Auth;
use Exception;

class AccountService
{
    public function newAccount($user_id): Account
    {
        try{
            //Gera um número aleatório para a conta
            do {
                $account_number = str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
            } while (Account::where('number', $account_number)->exists());

            $data = [
                'number' => $account_number,
                'user_id' => $user_id
            ];

            $account = Account::create($data);
            return $account;
        } catch (Exception $e) {
            \Log::error("Erro ao criar conta para o usuário id ($user_id); Message: " . $e->getMessage());
            throw $e;
        }
    }

    public function deposit($amount): Account
    {
        try{
            $user = Auth::user();
            $account = $user->account;
            
            $value = preg_replace('/[^\d,]/', '', $amount);
            //Converte para centavos
            $centsValue = (int) round(
                floatval(
                    str_replace(',', '.', str_replace('.', '', $value))
                ) * 100
            );

            $atualBalance = $account->balance;
            $account->balance = $atualBalance + $centsValue;

            if($account->save()){
                return $account;
            }
        } catch (Exception $e) {
            \Log::error("Erro ao fazer depósito na conta do usuário ($user->id); Message: " . $e->getMessage());
            throw $e;
        }
    }
    
}
