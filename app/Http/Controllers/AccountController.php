<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DepositRequest;
use App\Services\AccountService;
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

    public function loadHistoricTransactions(){
        try{
            $historicTransactions = $this->historicTransactionService->loadHistoricTransaction();
            return response()->json(['historicTransactions' => $historicTransactions]);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Ocorreu um erro, entre em contato com o administrador'], 500);
        }
    }

    public function revertTransaction(Request $request){
        $request->validate([
            'id' => 'required'
        ]);

        try{
            $historicTransactions = $this->historicTransactionService->revertTransaction($request->input('id'));
            return response()->json(['historicTransactions' => $historicTransactions], 200);
        } catch (\Throwable $e) {
            dd($e->getMessage());
            return response()->json(['message' => 'Ocorreu um erro, entre em contato com o administrador'], 500);
        }
    }
}
