<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Account;
use Tests\TestCase;

class DepositoTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_deposito(): void
    {
        // Cria um usuário e a conta relacionada
        $this->post('/register', [
            'cpf' => '729.327.740-77',
            'name' => 'Andre',
            'email' => 'andre@example.com',
            'password' => 'password',
        ]);

        $user = User::with('account')->where('email', 'andre@example.com')->firstOrFail();
        $account = $user->account;

        $this->actingAs($user);

        // Valor do depósito como string formatada
        $depositAmount = 'R$ 10,00'; 

        $response = $this->post('/deposit', [
            'amount' => $depositAmount,
        ]);
        $response->assertStatus(200);

        // Atualiza a conta para pegar valor do banco
        $account->refresh();

        // O balance está em centavos, então R$ 10,00 = 1000 centavos
        $this->assertEquals(1000, $account->getRawOriginal('balance'));
    }
}
