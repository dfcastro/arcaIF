<?php
// ARQUIVO: database/migrations/xxxx_xx_xx_xxxxxx_create_formulacao_racoes_tables.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabela principal de Ingredientes, baseada na sua planilha "COTAÇÃO"
        Schema::create('ingredientes', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique();
            $table->decimal('preco_por_kg', 10, 2);
            
            // Nutrientes do ingrediente (pode adicionar mais conforme necessário)
            $table->decimal('proteina_bruta', 8, 2)->default(0);      // PB
            $table->decimal('extrato_etereo', 8, 2)->default(0);     // EE
            $table->decimal('fibra_bruta', 8, 2)->default(0);          // FB
            $table->decimal('materia_mineral', 8, 2)->default(0);   // MM
            $table->decimal('calcio', 8, 2)->default(0);               // Ca
            $table->decimal('fosforo', 8, 2)->default(0);              // P
            
            $table->timestamps();
        });

        // Tabela para as "Fórmulas de Ração"
        Schema::create('formulas_racoes', function (Blueprint $table) {
            $table->id();
            $table->string('nome_formula'); // Ex: "Ração para Vacas em Lactação 18% PB"
            $table->foreignId('especie_id')->constrained('especies');
            $table->text('descricao')->nullable();
            $table->timestamps();
        });

        // Tabela de ligação (pivô) para dizer quais ingredientes e em que % compõem uma fórmula
        Schema::create('formula_ingredientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formula_racao_id')->constrained('formulas_racoes')->onDelete('cascade');
            $table->foreignId('ingrediente_id')->constrained('ingredientes')->onDelete('cascade');
            $table->decimal('percentual_inclusao', 8, 4); // % de inclusão do ingrediente na fórmula
            
            $table->unique(['formula_racao_id', 'ingrediente_id']);
        });
        
        // Vamos associar um animal a uma fórmula de ração, e não a um plano.
        Schema::table('animais', function (Blueprint $table) {
            $table->foreignId('formula_racao_id')->nullable()->after('localizacao_id')->constrained('formulas_racoes')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('animais', function (Blueprint $table) {
            $table->dropForeign(['formula_racao_id']);
            $table->dropColumn('formula_racao_id');
        });
        Schema::dropIfExists('formula_ingredientes');
        Schema::dropIfExists('formulas_racoes');
        Schema::dropIfExists('ingredientes');
    }
};