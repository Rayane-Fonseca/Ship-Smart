<?php

namespace Tests\Feature;

use App\Models\Pacote;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PacoteTest extends TestCase
{
    use RefreshDatabase;

    private function autenticar(): User
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        return $user;
    }

    public function test_dashboard_carrega_para_usuario_autenticado(): void
    {
        $this->autenticar();
        $this->get(route('dashboard'))->assertStatus(200);
    }

    public function test_listagem_de_pacotes(): void
    {
        $this->autenticar();
        Pacote::factory()->count(3)->create();
        $this->get(route('pacotes.index'))->assertStatus(200);
    }

    public function test_cadastro_de_pacote_com_dados_validos(): void
    {
        $this->autenticar();

        $response = $this->post(route('pacotes.store'), [
            'nome'                  => 'Caixa Teste',
            'codigo'                => 'SSA-00001',
            'fabricante_fornecedor' => 'Fornecedor Teste',
            'preco'                 => 99.90,
            'quantidade'            => 1,
            'destinatario'          => 'João Silva',
            'peso'                  => 2.500,
            'status'                => 'Pendente',
        ]);

        $response->assertRedirect(route('pacotes.index'));
        $this->assertDatabaseHas('pacotes', ['codigo' => 'SSA-00001']);
    }

    public function test_cadastro_falha_com_codigo_duplicado(): void
    {
        $this->autenticar();
        Pacote::factory()->create(['codigo' => 'SSA-DUPLO']);

        $this->post(route('pacotes.store'), [
            'nome'                  => 'Outro',
            'codigo'                => 'SSA-DUPLO',
            'fabricante_fornecedor' => 'Forn',
            'preco'                 => 10,
            'quantidade'            => 1,
            'destinatario'          => 'Maria',
            'peso'                  => 1.0,
            'status'                => 'Pendente',
        ])->assertSessionHasErrors('codigo');
    }

    public function test_regra_peso_minimo(): void
    {
        $this->autenticar();

        $this->post(route('pacotes.store'), [
            'nome'                  => 'Caixa',
            'codigo'                => 'SSA-00002',
            'fabricante_fornecedor' => 'Forn',
            'preco'                 => 10,
            'quantidade'            => 1,
            'destinatario'          => 'Carlos',
            'peso'                  => 0.00,        // Inválido — abaixo do mínimo
            'status'                => 'Pendente',
        ])->assertSessionHasErrors('peso');
    }

    public function test_atualizacao_de_status(): void
    {
        $this->autenticar();
        $pacote = Pacote::factory()->create(['status' => 'Pendente']);

        $this->put(route('pacotes.update', $pacote), array_merge(
            $pacote->toArray(),
            ['status' => 'Entregue']
        ));

        $this->assertDatabaseHas('pacotes', [
            'id'     => $pacote->id,
            'status' => 'Entregue',
        ]);
    }

    public function test_exclusao_de_pacote(): void
    {
        $this->autenticar();
        $pacote = Pacote::factory()->create();

        $this->delete(route('pacotes.destroy', $pacote))
             ->assertRedirect(route('pacotes.index'));

        $this->assertSoftDeleted('pacotes', ['id' => $pacote->id]);
    }
}