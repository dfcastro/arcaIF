<?php
// ARQUIVO: database/migrations/xxxx_xx_xx_xxxxxx_create_categorias_animais_table_and_refactor_animais.php

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
        // 1. Cria a nova tabela principal para as categorias de animais
        Schema::create('categorias_animais', function (Blueprint $table) {
            $table->id();
            $table->string('nome'); // Ex: "Vacas em Lactação", "Bezerros em Crescimento"
            $table->foreignId('especie_id')->constrained('especies');
            $table->foreignId('formula_racao_id')->constrained('formulas_racoes');
            $table->decimal('consumo_diario_kg', 8, 2); // Consumo diário por cabeça
            $table->text('descricao')->nullable();
            $table->timestamps();
        });

        // 2. Modifica a tabela de animais para usar a nova categoria
        Schema::table('animais', function (Blueprint $table) {
            // Remove a coluna antiga se ela existir
            if (Schema::hasColumn('animais', 'formula_racao_id')) {
                // É preciso remover a chave estrangeira antes de remover a coluna
                $table->dropForeign(['formula_racao_id']);
                $table->dropColumn('formula_racao_id');
            }

            // Adiciona a nova coluna de categoria
            $table->foreignId('categoria_animal_id')->nullable()->after('localizacao_id')->constrained('categorias_animais')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('animais', function (Blueprint $table) {
            // Reverte as alterações na tabela animais
            $table->dropForeign(['categoria_animal_id']);
            $table->dropColumn('categoria_animal_id');
            
            // Adiciona de volta a coluna antiga (opcional, mas bom para reversão completa)
            $table->foreignId('formula_racao_id')->nullable()->after('localizacao_id')->constrained('formulas_racoes')->onDelete('set null');
        });

        Schema::dropIfExists('categorias_animais');
    }
};