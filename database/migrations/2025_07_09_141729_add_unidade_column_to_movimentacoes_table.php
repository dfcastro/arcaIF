<?php
// ARQUIVO: database/migrations/xxxx_xx_xx_xxxxxx_add_unidade_column_to_movimentacoes_table.php

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
            // Adiciona a nova coluna 'unidade' depois da coluna 'valor'
            $table->enum('unidade', ['Kg', '@ (Peso Vivo)', '@ (Carcaça)'])
                  ->default('Kg')
                  ->after('valor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movimentacoes', function (Blueprint $table) {
            // Remove a coluna caso precise de reverter
            $table->dropColumn('unidade');
        });
    }
};