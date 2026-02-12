<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Importante para a senha

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Cria o usuário Admin
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@arcaif.com',
            'password' => Hash::make('Yirq3008!'), // Defina a senha aqui
            'role' => 'administrador', // Defina o papel como 'admin'
        ]);

        // (Opcional) Cria usuários de teste adicionais
        // User::factory(10)->create();
    }
}