<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('plano_requisitos');
        Schema::dropIfExists('planos_nutricionais');
        Schema::dropIfExists('nutrientes');
    }

    public function down(): void
    {
        // Opcional: recriar as tabelas se precisar de reverter a migration
    }
};