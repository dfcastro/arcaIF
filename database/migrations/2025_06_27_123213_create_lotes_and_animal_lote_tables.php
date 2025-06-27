<?php
// ARQUIVO: database/migrations/xxxx_xx_xx_xxxxxx_create_lotes_and_animal_lote_tables.php

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
        // Tabela para guardar os lotes
        Schema::create('lotes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->timestamps();
        });

        // Tabela de ligação (pivô) entre animais e lotes
        Schema::create('animal_lote', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animal_id')->constrained('animais')->onDelete('cascade');
            $table->foreignId('lote_id')->constrained('lotes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animal_lote');
        Schema::dropIfExists('lotes');
    }
};
