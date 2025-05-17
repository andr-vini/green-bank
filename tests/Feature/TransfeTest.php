<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Account;
use Tests\TestCase;

class TransfeTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        // Cria um usuário e a conta relacionada
        $this->post('/register', [
            'cpf' => '729.327.740-77',
            'name' => 'Andre',
            'email' => 'andre@example.com',
            'password' => 'password',
        ]);

        // Cria um usuário e a conta relacionada
        $this->post('/register', [
            'cpf' => '049.315.620-86',
            'name' => 'Teste',
            'email' => 'teste@example.com',
            'password' => 'password',
        ]);

        $user_responsible = User::with('account')->where('email', 'andre@example.com')->firstOrFail();
        $account_responsible = $user_responsible->account;

        $this->actingAs($user_responsible);

        $teste = $this->post('/deposit', [
            'amount' => 'R$ 25,00',
        ]);

        $user_beneficiary = User::with('account')->where('email', 'teste@example.com')->firstOrFail();
        $account_beneficiary = $user_beneficiary->account;


        $accountTransfer = $account_beneficiary->number;
        $amountTransfer = 'R$ 10,00';

        $response = $this->post('/transfer', [
            'account' => $accountTransfer,
            'amount' => $amountTransfer
        ]);

        $response->assertStatus(200);

        $account_beneficiary->refresh();
        $account_responsible->refresh();

        $this->assertEquals(1500, $account_responsible->getRawOriginal('balance'));
        $this->assertEquals(1000, $account_beneficiary->getRawOriginal('balance'));
    }
}
