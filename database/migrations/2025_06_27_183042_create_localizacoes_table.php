<?php
// ARQUIVO: database/migrations/xxxx_xx_xx_xxxxxx_create_localizacoes_table.php

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
        // Tabela para guardar as localizações (Pasto 01, Curral, etc.)
        Schema::create('localizacoes', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique();
            $table->text('descricao')->nullable();
            $table->timestamps();
        });

        // Adiciona a coluna de localização na tabela de animais
        Schema::table('animais', function (Blueprint $table) {
            $table->foreignId('localizacao_id')->nullable()->after('raca_id')->constrained('localizacoes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove a coluna e a chave estrangeira da tabela de animais
        Schema::table('animais', function (Blueprint $table) {
            $table->dropForeign(['localizacao_id']);
            $table->dropColumn('localizacao_id');
        });

        Schema::dropIfExists('localizacoes');
    }
};