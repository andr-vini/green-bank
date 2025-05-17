<?php

namespace App\Services;

use App\Models\Account;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Exception;

class AccountService
{
    /**
     * Cria uma nova conta para um usuário
     */
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

    /**
     * Faz o depósito da quantia desejada
     */
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
    
    /**
     * Faz a transferência entre contas
     */
    public function transfer($data): Account
    {
        try{
            $user_responsible = User::with('account')->find(Auth::id());
            $account_responsible = $user_responsible->account;
            $account_beneficiary = Account::with('user')->where('number', $data['account'])->first();
            $user_beneficiary = $account_beneficiary->user;
            if($account_responsible->number == $account_beneficiary->number){
                throw new Exception("Transferência para si próprio", 1);
            }

            $value = preg_replace('/[^\d,]/', '', $data['amount']);
            //Converte para centavos
            $centsValue = (int) round(
                floatval(
                    str_replace(',', '.', str_replace('.', '', $value))
                ) * 100
            );

            if($account_responsible->getRawOriginal('balance') < $centsValue){
                throw new Exception("Saldo insuficiente", 1);
            }

            $account_responsible->balance = $account_responsible->getRawOriginal('balance') - $centsValue;
            $account_beneficiary->balance = $account_beneficiary->getRawOriginal('balance') + $centsValue;

            $account_responsible->save();
            $account_beneficiary->save();
            return $account_beneficiary;
            // $user_beneficiary 
        } catch (Exception $e) {
            \Log::error("Erro ao fazer transferência da conta do usuário ($user_responsible->id) para a conta ($user_beneficiary->id; Message: " . $e->getMessage());
            throw $e;
        }
    }
}
