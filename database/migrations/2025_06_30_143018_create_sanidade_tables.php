<?php
// ARQUIVO: database/migrations/xxxx_xx_xx_xxxxxx_create_sanidade_tables.php

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
        // 1. Tabela para os protocolos de saúde
        Schema::create('protocolos_sanitarios', function (Blueprint $table) {
            $table->id();
            $table->string('nome'); // Ex: "Ciclo de Vacinação de Bezerros"
            $table->foreignId('especie_id')->constrained('especies');
            $table->text('descricao')->nullable();
            $table->timestamps();
        });

        // 2. Tabela para as etapas/eventos de cada protocolo
        Schema::create('protocolo_eventos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('protocolo_sanitario_id')->constrained('protocolos_sanitarios')->onDelete('cascade');
            $table->string('nome_evento'); // Ex: "1ª Dose - Vacina XYZ", "Vermifugação"
            $table->enum('tipo', ['Vacina', 'Medicação', 'Vermifugo', 'Exame', 'Outro']);
            $table->integer('dias_apos_inicio')->comment('Dias após o início do protocolo ou nascimento para aplicar este evento');
            $table->text('instrucoes')->nullable(); // Ex: "Aplicar 5ml via subcutânea"
            $table->timestamps();
        });
        
        // 3. Tabela principal que funciona como a agenda de cada animal
        Schema::create('agenda_sanitaria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animal_id')->constrained('animais')->onDelete('cascade');
            $table->foreignId('protocolo_evento_id')->constrained('protocolo_eventos')->onDelete('cascade');
            
            $table->date('data_agendada');
            $table->date('data_conclusao')->nullable();
            $table->enum('status', ['Agendado', 'Concluído', 'Cancelado'])->default('Agendado');
            $table->text('observacoes')->nullable(); // Observações sobre a aplicação específica
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agenda_sanitaria');
        Schema::dropIfExists('protocolo_eventos');
        Schema::dropIfExists('protocolos_sanitarios');
    }
};