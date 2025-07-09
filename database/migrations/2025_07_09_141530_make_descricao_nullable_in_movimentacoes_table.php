<?php
// ARQUIVO: database/migrations/xxxx_xx_xx_xxxxxx_make_descricao_nullable_in_movimentacoes_table.php

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
        Schema::table('movimentacoes', function (Blueprint $table) {
            // Altera a coluna 'descricao' para permitir valores nulos
            $table->string('descricao')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movimentacoes', function (Blueprint $table) {
            // Reverte para o estado original (não nulo)
            // Nota: Isto pode falhar se já existirem dados nulos na coluna.
            $table->string('descricao')->nullable(false)->change();
        });
    }
};