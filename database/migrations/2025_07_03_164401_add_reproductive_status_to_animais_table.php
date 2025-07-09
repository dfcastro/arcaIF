<?php
// ARQUIVO: database/migrations/xxxx_xx_xx_xxxxxx_add_reproductive_status_to_animais_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('animais', function (Blueprint $table) {
            $table->string('status_reprodutivo')->after('status')->nullable()->default('Vazia');
        });
    }

    public function down(): void
    {
        Schema::table('animais', function (Blueprint $table) {
            $table->dropColumn('status_reprodutivo');
        });
    }
};