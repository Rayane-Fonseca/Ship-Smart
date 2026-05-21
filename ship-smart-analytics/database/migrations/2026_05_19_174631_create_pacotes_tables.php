<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pacotes', function (Blueprint $table) {
            $table->id();
            // Campos obrigatórios (conforme briefing)
            $table->string('nome');
            $table->string('codigo')->unique();          // Código de rastreio
            $table->string('fabricante_fornecedor');     // Remetente
            $table->decimal('preco', 10, 2);
            $table->integer('quantidade');

            // Campos específicos Ship-Smart
            $table->string('destinatario');
            $table->decimal('peso', 8, 3);               // em kg
            $table->enum('status', ['Pendente', 'Em Rota', 'Entregue'])
                  ->default('Pendente');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pacotes');
    }
};