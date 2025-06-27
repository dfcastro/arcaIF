<?php
// ARQUIVO: database/migrations/xxxx_xx_xx_xxxxxx_create_movimentacoes_table.php

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
        Schema::create('movimentacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animal_id')->constrained('animais')->onDelete('cascade');
            $table->date('data');
            $table->enum('tipo', ['Pesagem', 'Vacinação', 'Medicação', 'Observação', 'Venda', 'Óbito']);
            $table->text('descricao');
            $table->string('valor')->nullable()->comment('Pode guardar o peso, nome da vacina, etc.');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimentacoes');
    }
};
