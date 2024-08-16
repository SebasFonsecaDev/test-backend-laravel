<?php

// database/seeders/TransactionSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        // Obtén una lista de todas las cuentas para asignar transacciones a cuentas existentes
        $accounts = DB::table('accounts')->pluck('id');

        foreach ($accounts as $accountId) {
            // Crear varias transacciones para cada cuenta
            for ($i = 0; $i < 5; $i++) {
                DB::table('transactions')->insert([
                    'account_id' => $accountId,
                    'amount' => rand(100, 1000), // Monto aleatorio entre 100 y 1000
                    'transaction_date' => Carbon::now()->subDays(rand(1, 30)), // Fecha aleatoria en los últimos 30 días
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
