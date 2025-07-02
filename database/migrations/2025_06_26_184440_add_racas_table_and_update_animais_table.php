<?php
// ARQUIVO: database/migrations/xxxx_xx_xx_xxxxxx_add_racas_table_and_update_animais_table.php

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
        // 1. Cria a nova tabela 'racas'
        Schema::create('racas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('especie_id')->constrained('especies')->onDelete('cascade');
            $table->string('nome');
            $table->timestamps();
        });

        // 2. Atualiza a tabela 'animais' para adicionar a coluna da raça
        Schema::table('animais', function (Blueprint $table) {
            $table->foreignId('raca_id')->nullable()->after('especie_id')->constrained('racas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Desfaz as operações na ordem inversa
        Schema::table('animais', function (Blueprint $table) {
            $table->dropForeign(['raca_id']);
            $table->dropColumn('raca_id');
        });

        Schema::dropIfExists('racas');
    }
};
