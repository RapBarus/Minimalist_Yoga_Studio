<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Memulai seeding dengan bypass Trigger logic...');

        // --- 1. DATA MASTER: CLASSES ---
        $classData = [
            ['class_name' => 'Hatha Yoga', 'description' => 'Fokus pada postur dasar.', 'level' => 'beginner', 'duration_minutes' => 60],
            ['class_name' => 'Vinyasa Flow', 'description' => 'Gerakan dinamis.', 'level' => 'intermediate', 'duration_minutes' => 75],
            ['class_name' => 'Yin Yoga', 'description' => 'Yoga meditatif.', 'level' => 'beginner', 'duration_minutes' => 90],
            ['class_name' => 'Advanced Power', 'description' => 'Intensitas sangat tinggi.', 'level' => 'advanced', 'duration_minutes' => 60],
        ];

        $classIds = [];
        foreach ($classData as $c) {
            $classIds[] = DB::table('classes')->insertGetId($c);
        }

        // --- 2. AMBIL COACH ---
        $coachIds = DB::table('coaches')->pluck('coach_id')->toArray();

        // --- 3. DATA MASTER: MEMBERSHIP PACKAGES ---
        $packageIds = [];
        $packageIds[] = DB::table('membership_packages')->insertGetId([
            'name' => 'Starter Pack', 'price' => 500000, 'quota_amount' => 4, 'validity_months' => 1
        ]);
        $packageIds[] = DB::table('membership_packages')->insertGetId([
            'name' => 'Monthly Pro', 'price' => 1200000, 'quota_amount' => 12, 'validity_months' => 3
        ]);

        // --- 4. GENERATE CUSTOMERS ---
        $customerUserIds = [];
        for ($i = 0; $i < 30; $i++) {
            $name = fake()->name();
            $customerUserIds[] = DB::table('users')->insertGetId([
                'username' => strtolower(str_replace(' ', '', $name)) . rand(10, 99),
                'name' => $name,
                'phone_number' => '+628' . fake()->numerify('#########'),
                'password_hash' => '$2y$12$I9jqz6SJKIDMnFpBe9GfOuL.B2jSgS.B2jSgS.B2jSgS.B2jSgS.',
                'role' => 'customer',
                'status' => 'active',
            ]);
        }

        // --- 5. GENERATE SCHEDULES ---
        $scheduleIds = [];
        $date = Carbon::now()->subDays(10);
        for ($d = 0; $d < 20; $d++) {
            for ($s = 0; $s < 2; $s++) {
                $start_time = Carbon::parse($date->format('Y-m-d') . ' ' . fake()->randomElement(['08:00', '17:00']));
                $capacity = 15;
                $scheduleIds[] = DB::table('schedules')->insertGetId([
                    'class_id' => fake()->randomElement($classIds),
                    'coach_id' => fake()->randomElement($coachIds),
                    'schedule_date' => $date->format('Y-m-d'),
                    'start_time' => $start_time->format('H:i:s'),
                    'end_time' => $start_time->copy()->addMinutes(60)->format('H:i:s'),
                    'capacity' => $capacity,
                    'available_slots' => $capacity, 
                    'status' => 'upcoming', // CRITICAL: Harus 'upcoming' agar Trigger mengizinkan booking
                    'created_at' => now(),
                ]);
            }
            $date->addDay();
        }

        // --- 6. GENERATE BOOKINGS & TRANSACTIONS ---
        foreach ($customerUserIds as $uId) {
            $randSchedules = fake()->randomElements($scheduleIds, rand(2, 4));
            foreach ($randSchedules as $schId) {
                $bookingId = DB::table('bookings')->insertGetId([
                    'user_id' => $uId,
                    'schedule_id' => $schId,
                    'booking_date' => now(),
                    'status' => 'confirmed',
                    'created_at' => now(),
                ]);

                DB::table('transactions')->insert([
                    'user_id' => $uId,
                    'booking_id' => $bookingId,
                    'amount' => 150000,
                    'payment_type' => 'bank_transfer',
                    'status' => 'settlement',
                    'transaction_date' => now(),
                ]);
            }
        }

        // --- 7. FINAL STEP: SINKRONISASI STATUS (Sangat Penting!) ---
        $this->command->info('Mengupdate status jadwal masa lalu menjadi completed...');
        
        // Update jadwal yang sudah lewat menjadi completed
        DB::table('schedules')
            ->where('schedule_date', '<', Carbon::now()->toDateString())
            ->update(['status' => 'completed']);

        // Update booking untuk jadwal yang sudah lewat menjadi attended (Simulasi kehadiran)
        DB::table('bookings')
            ->join('schedules', 'bookings.schedule_id', '=', 'schedules.schedule_id')
            ->where('schedules.status', 'completed')
            ->update(['bookings.status' => 'attended']);

        $this->command->info('Seeding berhasil diselesaikan!');
    }
}