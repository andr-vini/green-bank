<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DepositRequest;
use App\Services\AccountService;

class AccountController extends Controller
{
    protected AccountService $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function makeDeposit(DepositRequest $request){
        try {

            //Garante a integridade dos dados
            $this->accountService->deposit($request->input('amount'));
            
            return response()->json(['message' => 'Depósito concluído!'], 200);
        } catch (\Throwable $e) {
            \Log::error($e->getMessage());
            return response()->json(['message' => 'Ocorreu um erro, entre em contato com o administrador'], 500);
        }
    }
}
