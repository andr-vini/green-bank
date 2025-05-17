<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DepositRequest;
use App\Http\Requests\TransferRequest;
use App\Services\AccountService;
use Illuminate\Support\Facades\Auth;
use App\Services\HistoricTransactionService;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    protected AccountService $accountService;
    protected HistoricTransactionService $historicTransactionService;

    public function __construct(AccountService $accountService, HistoricTransactionService $historicTransactionService)
    {
        $this->accountService = $accountService;
        $this->historicTransactionService = $historicTransactionService;
    }

    /**
     * Faz depósito, circundado por transaction para garantir a integridade dos dados
     */
    public function makeDeposit(DepositRequest $request){
        try {

            //Garante a integridade dos dados
            DB::transaction(function () use ($request){
                $account = $this->accountService->deposit($request->input('amount'));
                $this->historicTransactionService->createHistoric([
                    'type_transaction' => 0,
                    'status' => 0,
                    'balance' => $request->input('amount'),
                    'responsible_user' => $account->user_id
                ]);
            });
            
            return response()->json(['message' => 'Depósito concluído!'], 200);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Ocorreu um erro, entre em contato com o administrador'], 500);
        }
    }

    /**
     * Faz transferência, circundado por transaction para garantir a integridade dos dados
     */
    public function makeTransfer(TransferRequest $request){
        try {
            DB::transaction(function () use ($request){
                $account_beneficiary = $this->accountService->transfer($request->all());
                $this->historicTransactionService->createHistoric([
                    'type_transaction' => 1,
                    'status' => 0,
                    'balance' => $request->input('amount'),
                    'responsible_user' => Auth::id(),
                    'beneficiary_user' => $account_beneficiary->user_id,
                ]);
            });
            return response()->json(['message' => 'Transferência concluída'], 200);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Carrega o histórico de transações
     */
    public function loadHistoricTransactions(){
        try{
            $historicTransactions = $this->historicTransactionService->loadHistoricTransaction();
            return response()->json(['historicTransactions' => $historicTransactions]);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Ocorreu um erro, entre em contato com o administrador'], 500);
        }
    }

    /**
     * Reverte uma transação
     */
    public function revertTransaction(Request $request){
        $request->validate([
            'id' => 'required'
        ]);

        try{
            $historicTransactions = $this->historicTransactionService->revertTransaction($request->input('id'));
            return response()->json(['historicTransactions' => $historicTransactions], 200);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Ocorreu um erro, entre em contato com o administrador'], 500);
        }
    }
}
