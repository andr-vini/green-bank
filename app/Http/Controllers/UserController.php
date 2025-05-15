<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Services\UserService;

class UserController extends Controller
{

    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(){
        return view('pages.guest.register');
    }

    public function store(UserRequest $request){
        try {
            $this->userService->store($request->all());
            return redirect()->route('login')->with('success', 'Usuário cadastrado com sucesso!');
        } catch (\Throwable $e) {
            \Log::error($e->getMessage());
            return redirect()->back()->withErrors(['Ocorreu um erro ao tentar criar usuário, entre em contato com o administrador']);
        }
        // dd($request->all());
    }
}
