<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            // ServantSeeder::class, // Buat jika perlu
            // ScheduleSeeder::class, // Jalankan setelah ada data servant
        ]);
    }
}
