<?php

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
        // Tabela para as espécies (Bovino, Suíno, etc.)
        Schema::create('especies', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique();
            $table->timestamps();
        });

        // Tabela principal para os animais
        Schema::create('animais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('especie_id')->constrained('especies')->onDelete('cascade');
            $table->string('identificacao')->comment('Pode ser o número do brinco, nome, etc.');
            $table->date('data_nascimento')->nullable();
            $table->enum('sexo', ['Macho', 'Fêmea']);
            $table->enum('status', ['Ativo', 'Vendido', 'Óbito'])->default('Ativo');
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animais');
        Schema::dropIfExists('especies');
    }
};

