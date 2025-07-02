<?php
// ARQUIVO: database/migrations/xxxx_xx_xx_xxxxxx_change_peso_columns_type_in_planos_nutricionais_table.php

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
        Schema::table('planos_nutricionais', function (Blueprint $table) {
            // Altera as colunas para um novo tipo que suporta valores maiores
            $table->decimal('peso_min_kg', 10, 2)->nullable()->change();
            $table->decimal('peso_max_kg', 10, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('planos_nutricionais', function (Blueprint $table) {
            // Reverte para o tipo original caso precise desfazer a migration
            $table->decimal('peso_min_kg', 8, 2)->nullable()->change();
            $table->decimal('peso_max_kg', 8, 2)->nullable()->change();
        });
    }
};