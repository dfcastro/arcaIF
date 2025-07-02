<?php
// ARQUIVO: database/migrations/xxxx_xx_xx_xxxxxx_change_tipo_column_in_movimentacoes_table.php

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
            // Altera a coluna 'tipo' para string, que é mais flexível
            $table->string('tipo')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movimentacoes', function (Blueprint $table) {
            // Define o tipo original caso precise reverter
            // Nota: Isto pode falhar se já existirem dados que não correspondam ao enum.
            $table->enum('tipo', ['Pesagem', 'Vacinação', 'Medicação', 'Observação'])->change();
        });
    }
};