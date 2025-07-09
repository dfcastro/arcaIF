<?php
// ARQUIVO: database/migrations/xxxx_xx_xx_xxxxxx_add_doacao_status_to_animais_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('animais', function (Blueprint $table) {
            // Atualiza a coluna 'status' para incluir o novo valor 'Doação'
            $table->enum('status', [
                'Ativo',
                'Vendido',
                'Óbito',
                'Doação' // <-- NOVO VALOR ADICIONADO
            ])->default('Ativo')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('animais', function (Blueprint $table) {
            // Reverte para a lista original caso precise desfazer a migration
            $table->enum('status', [
                'Ativo',
                'Vendido',
                'Óbito'
            ])->default('Ativo')->change();
        });
    }
};