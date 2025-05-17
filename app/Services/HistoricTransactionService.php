<?php

namespace App\Services;

use App\Models\HistoricTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Exception;

class HistoricTransactionService
{
    public function createHistoric($data): HistoricTransaction
    {
        try{
            $user_id = $data['responsible_user'];

            $value = preg_replace('/[^\d,]/', '', $data['balance']);
            //Converte para centavos
            $centsValue = (int) round(
                floatval(
                    str_replace(',', '.', str_replace('.', '', $value))
                ) * 100
            );
            
            $data['balance'] = $centsValue;
            
            $historic = HistoricTransaction::create($data);
            return $historic;
        } catch (Exception $e) {
            \Log::error("Erro ao registrar histórico de transação na conta do usuário ($user_id); Message: " . $e->getMessage());
            throw $e;
        }
    }

    public function loadHistoricTransaction(): Collection
    {
        try{
            $user_id = Auth::id();
            $historic = Auth::user()->historicTransactions()->get();
            return $historic;
        } catch (Exception $e) {
            \Log::error("Erro ao carregar histórico de transação na conta do usuário ($user_id); Message: " . $e->getMessage());
            throw $e;
        }
    }

    public function revertTransaction($id): HistoricTransaction
    {
        try{
            DB::beginTransaction();
            $transaction = HistoricTransaction::with('responsibleUser.account')->findOrFail($id);
            if($transaction->getRawOriginal('type_transaction') == 0){
                $account = $transaction->responsibleUser->account;
                $new_balance = $account->balance - $transaction->getRawOriginal('balance');
                $account->balance = $new_balance;
                
                if($account->save()){
                    $transaction->status = 1;
                }
            }

            $transaction->save();
            DB::commit();
            return $transaction;
        } catch (Exception $e) {
            DB::rollBack();
            \Log::error("Erro ao reverter a transação ($id); Message: " . $e->getMessage());
            throw $e;
        }
    }
}
