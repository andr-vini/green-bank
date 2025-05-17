<?php

namespace App\Services;

use App\Models\HistoricTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Exception;

class HistoricTransactionService
{
    /**
     * Registra o histórico de transações
     */
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

    /**
     * Carrega os dados de histórico de transações feitas pelo usuário autenticado
     */
    public function loadHistoricTransaction(): Collection
    {
        try{
            $user_id = Auth::id();
            $historic = Auth::user()->historicTransactions()->with('beneficiaryUser.account')->get();
            return $historic;
        } catch (Exception $e) {
            \Log::error("Erro ao carregar histórico de transação na conta do usuário ($user_id); Message: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Reverte as transações, tanto depósito quando transferências
     * Envolvido por uma transaction para garantir a integridade dos dados, caso lance um erro, executa rollback
     */
    public function revertTransaction($id): HistoricTransaction
    {
        try{
            DB::beginTransaction();
            $transaction = HistoricTransaction::with(['responsibleUser.account', 'beneficiaryUser.account'])->findOrFail($id);
            $account_responsible = $transaction->responsibleUser->account;
            if($transaction->getRawOriginal('type_transaction') == 0){
                $new_balance = $account_responsible->balance - $transaction->getRawOriginal('balance');
                $account_responsible->balance = $new_balance;
                
                if($account_responsible->save()){
                    $transaction->status = 1;
                }
            }elseif($transaction->getRawOriginal('type_transaction') == 1){
                $account_beneficiary = $transaction->beneficiaryUser->account;
                $account_responsible->balance += $transaction->getRawOriginal('balance');
                $account_beneficiary->balance -= $transaction->getRawOriginal('balance');

                if($account_responsible->save() && $account_beneficiary->save()){
                    $transaction->status = 1;
                }

            }
            $transaction->reverted_user = Auth::id();
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
