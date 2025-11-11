<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Schedule;
use App\Models\Servant;
use App\Models\Category;
use Carbon\Carbon;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua pelayan dan kategori
        $servants = Servant::all();
        $categories = Category::all();

        if ($servants->isEmpty() || $categories->isEmpty()) {
            $this->command->warn('Tidak ada data pelayan atau kategori. Jalankan servant dan category seeder terlebih dahulu.');
            return;
        }

        // Generate jadwal untuk 3 bulan ke depan
        $startDate = now()->startOfMonth();
        $endDate = now()->addMonths(3)->endOfMonth();

        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            // Hanya generate untuk hari Minggu
            if ($currentDate->dayOfWeek === Carbon::SUNDAY) {
                // Generate untuk 3 sesi
                $sessions = ['KU1', 'KU2', 'KU3'];
                $times = ['08:00', '10:00', '16:00'];

                foreach ($sessions as $index => $session) {
                    // Untuk setiap kategori, assign random servant
                    foreach ($categories as $category) {
                        // Ambil pelayan dari kategori ini
                        $categoryServants = $servants->where('category_id', $category->id);

                        if ($categoryServants->isNotEmpty()) {
                            $servant = $categoryServants->random();

                            Schedule::create([
                                'servant_id' => $servant->id,
                                'category_id' => $category->id,
                                'service_date' => $currentDate->format('Y-m-d'),
                                'service_session' => $session,
                                'service_time' => $times[$index],
                                'notes' => null
                            ]);
                        }
                    }
                }
            }

            $currentDate->addDay();
        }

        $this->command->info('Schedule seeder berhasil dijalankan!');
    }
}
