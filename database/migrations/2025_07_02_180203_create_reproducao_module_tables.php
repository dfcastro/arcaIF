<?php
// ARQUIVO: database/migrations/xxxx_xx_xx_xxxxxx_create_reproducao_module_tables.php

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
        // 1. Adiciona as colunas de parentesco à tabela de animais
        Schema::table('animais', function (Blueprint $table) {
            // ID do pai (macho)
            $table->foreignId('pai_id')->nullable()->after('categoria_animal_id')->constrained('animais')->onDelete('set null');
            // ID da mãe (fêmea)
            $table->foreignId('mae_id')->nullable()->after('pai_id')->constrained('animais')->onDelete('set null');
        });

        // 2. Cria a nova tabela para os eventos reprodutivos
        Schema::create('eventos_reprodutivos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animal_id')->comment('Principalmente a fêmea')->constrained('animais')->onDelete('cascade');
            
            $table->enum('tipo', ['Cobrição', 'Inseminação', 'Diagnóstico de Gestação', 'Previsão de Parto', 'Parto', 'Aborto']);
            $table->date('data');
            $table->enum('status', ['Agendado', 'Realizado', 'Falhou'])->default('Realizado');

            // Para registar o macho usado na cobrição/inseminação
            $table->foreignId('animal_relacionado_id')->nullable()->comment('Ex: o touro ou carneiro usado')->constrained('animais')->onDelete('set null');
            
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos_reprodutivos');
        
        Schema::table('animais', function (Blueprint $table) {
            $table->dropForeign(['pai_id']);
            $table->dropForeign(['mae_id']);
            $table->dropColumn(['pai_id', 'mae_id']);
        });
    }
};