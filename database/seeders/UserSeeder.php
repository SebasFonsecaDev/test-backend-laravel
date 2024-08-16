<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Crear 10 usuarios de ejemplo
        User::factory()->count(10)->create()->each(function ($user) {
            $user->account()->create([
                'balance' => rand(1000, 10000) // Asignar un saldo aleatorio
            ]);
        });
    }
}
