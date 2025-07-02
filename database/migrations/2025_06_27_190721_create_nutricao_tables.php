<?php
// ARQUIVO: database/migrations/xxxx_xx_xx_xxxxxx_create_nutricao_tables.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabela para os nutrientes disponíveis
        Schema::create('nutrientes', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique(); // Ex: Proteína Bruta, Fósforo
            $table->string('unidade')->default('%'); // Ex: %, g/kg
            $table->timestamps();
        });

        // 2. Tabela para os planos nutricionais
        Schema::create('planos_nutricionais', function (Blueprint $table) {
            $table->id();
            $table->string('nome'); // Ex: Bovinos de Corte - Crescimento
            $table->text('descricao')->nullable();
            $table->foreignId('especie_id')->constrained('especies')->onDelete('cascade');
            
            // Critérios para aplicar o plano
            $table->string('fase_vida')->nullable(); // Ex: Crescimento, Lactação, Manutenção
            $table->integer('idade_min_meses')->nullable();
            $table->integer('idade_max_meses')->nullable();
            $table->decimal('peso_min_kg', 8, 2)->nullable();
            $table->decimal('peso_max_kg', 8, 2)->nullable();
            
            $table->timestamps();
        });

        // 3. Tabela de ligação (pivô) com os requisitos de cada plano
        Schema::create('plano_requisitos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plano_nutricional_id')->constrained('planos_nutricionais')->onDelete('cascade');
            $table->foreignId('nutriente_id')->constrained('nutrientes')->onDelete('cascade');
            
            $table->decimal('valor_minimo', 8, 2);
            $table->decimal('valor_maximo', 8, 2)->nullable();
            
            $table->unique(['plano_nutricional_id', 'nutriente_id']); // Garante que não há nutrientes repetidos no mesmo plano
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plano_requisitos');
        Schema::dropIfExists('planos_nutricionais');
        Schema::dropIfExists('nutrientes');
    }
};