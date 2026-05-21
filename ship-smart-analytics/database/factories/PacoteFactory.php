<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PacoteFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nome'                  => $this->faker->words(3, true),
            'codigo'                => 'SSA-' . strtoupper($this->faker->bothify('??####')),
            'fabricante_fornecedor' => $this->faker->company(),
            'preco'                 => $this->faker->randomFloat(2, 5, 500),
            'quantidade'            => $this->faker->numberBetween(1, 50),
            'destinatario'          => $this->faker->name(),
            'peso'                  => $this->faker->randomFloat(3, 0.1, 30),
            'status'                => $this->faker->randomElement(['Pendente', 'Em Rota', 'Entregue']),
        ];
    }
}