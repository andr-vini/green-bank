<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Tests\TestCase;

class CadastroUsuarioTest extends TestCase
{

    /**
     * A basic feature test example.
     */
    public function test_usuario_criado_e_conta_associada(): void
    {
        $response = $this->post('/register', [
            'cpf' => '729.327.740-77',
            'name' => 'Andre',
            'email' => 'andre@example.com',
            'password' => 'password',
        ]);

        // Verifica redirecionamento e mensagem de sucesso
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success', 'Usuário cadastrado com sucesso!');

        // Verifica que o usuário foi criado no banco
        $this->assertDatabaseHas('users', [
            'email' => 'andre@example.com'
        ]);

        // Verifica que a conta foi criada e associada ao usuário
        $user = User::where('email', 'andre@example.com')->first();
        $this->assertNotNull($user);
        $this->assertDatabaseHas('accounts', [
            'user_id' => $user->id
        ]);
    }
}
