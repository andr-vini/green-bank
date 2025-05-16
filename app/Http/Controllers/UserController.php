<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Services\UserService;
use App\Services\AccountService;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    protected UserService $userService;
    protected AccountService $accountService;

    public function __construct(UserService $userService, AccountService $accountService)
    {
        $this->userService = $userService;
        $this->accountService = $accountService;
    }

    public function register(){
        return view('pages.guest.register');
    }

    public function store(UserRequest $request){
        try {

            //Garante a integridade dos dados
            DB::transaction(function () use ($request){
                $user = $this->userService->store($request->all());
                $this->accountService->newAccount($user->id);
            });
            
            return redirect()->route('login')->with('success', 'Usuário cadastrado com sucesso!');
        } catch (\Throwable $e) {
            \Log::error($e->getMessage());
            return redirect()->back()->withErrors(['Ocorreu um erro ao tentar criar usuário, entre em contato com o administrador']);
        }
        // dd($request->all());
    }
}
