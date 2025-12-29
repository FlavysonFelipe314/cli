<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Criar usuário padrão para testes
        User::firstOrCreate(
            ['email' => 'admin@clivus.com'],
            [
                'name' => 'Administrador',
                'password' => bcrypt('password'),
            ]
        );
    }
}
