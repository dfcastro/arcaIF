<?php
// ARQUIVO: database/migrations/xxxx_xx_xx_xxxxxx_add_desmame_to_eventos_reprodutivos_table.php

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
        Schema::table('eventos_reprodutivos', function (Blueprint $table) {
            // Atualiza a coluna 'tipo' para incluir o novo valor 'Desmame'
            $table->enum('tipo', [
                'Cobrição',
                'Inseminação',
                'Diagnóstico de Gestação',
                'Previsão de Parto',
                'Parto',
                'Aborto',
                'Desmame' // <-- NOVO VALOR ADICIONADO
            ])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eventos_reprodutivos', function (Blueprint $table) {
            // Reverte para a lista original caso precise desfazer a migration
            $table->enum('tipo', [
                'Cobrição',
                'Inseminação',
                'Diagnóstico de Gestação',
                'Previsão de Parto',
                'Parto',
                'Aborto'
            ])->change();
        });
    }
};