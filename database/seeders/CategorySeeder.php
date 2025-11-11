<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Kolektan', 'description' => 'Petugas pengambil kolekte'],
            ['name' => 'Lektor', 'description' => 'Pembaca bacaan liturgi'],
            ['name' => 'Penyambut Jemaat', 'description' => 'Petugas penyambut di pintu masuk'],
            ['name' => 'Pengumban', 'description' => 'Pembawa persembahan'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
