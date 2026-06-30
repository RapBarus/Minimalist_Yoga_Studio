<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Carbon\Carbon;

class PerformanceTestSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $password = Hash::make('password123'); 
        $now = Carbon::now();

        $this->command->info('Membersihkan data lama (Truncate)...');
        
        // Mematikan foreign key checks sementara agar bisa truncate tabel yang berelasi
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('bookings')->truncate();
        DB::table('transactions')->truncate();
        DB::table('booking_groups')->truncate();
        DB::table('schedules')->truncate();
        DB::table('coaches')->truncate();
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('Memulai injeksi data masif...');

        // ==========================================
        // 1. SEEDING USERS (10.000 Data)
        // ==========================================
        $this->command->warn('Seeding Users...');
        for ($i = 0; $i < 10; $i++) { 
            $users = [];
            for ($j = 0; $j < 1000; $j++) { 
                $users[] = [
                    'name' => $faker->name,
                    'username' => $faker->userName . '_' . $i . $j . rand(10, 99), 
                    'password_hash' => $password,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            DB::table('users')->insert($users);
            $this->command->info('Inserted ' . (($i + 1) * 1000) . ' users.');
        }
        
        $userIds = DB::table('users')->pluck('user_id')->toArray();
        if (empty($userIds)) $userIds = DB::table('users')->pluck('id')->toArray();

        // ==========================================
        // 2. SEEDING COACHES (50 Data)
        // ==========================================
        $this->command->warn('Seeding Coaches...');
        $coaches = [];
        
        for ($i = 0; $i < 50; $i++) {
            $coaches[] = [
                'user_id' => $faker->randomElement($userIds),
                'class_id' => $faker->numberBetween(1, 6),
                'bio' => $faker->paragraph,
                'profile_photo' => null,
                'rate_per_class' => $faker->numberBetween(50000, 200000),
                'years_experience' => $faker->numberBetween(1, 10),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        DB::table('coaches')->insert($coaches);
        
        $coachIds = DB::table('coaches')->pluck('coach_id')->toArray();
        if(empty($coachIds)) $coachIds = DB::table('coaches')->pluck('id')->toArray();

        // ==========================================
        // 3. SEEDING SCHEDULES (1.000 Data)
        // ==========================================
        $this->command->warn('Seeding Schedules...');
        for ($i = 0; $i < 2; $i++) {
            $schedules = [];
            for ($j = 0; $j < 500; $j++) {
                $startTime = $faker->dateTimeBetween('06:00:00', '19:00:00');
                $endTime = (clone $startTime)->modify('+' . rand(1, 2) . ' hours');
                $capacity = $faker->numberBetween(10, 30);
                $availableSlots = $faker->numberBetween(0, $capacity);

                $schedules[] = [
                    'class_id' => $faker->numberBetween(1, 6),
                    'coach_id' => $faker->randomElement($coachIds),
                    'title' => null, 
                    'schedule_date' => $faker->dateTimeBetween('now', '+3 months')->format('Y-m-d'),
                    'start_time' => $startTime->format('H:i:s'),
                    'end_time' => $endTime->format('H:i:s'),
                    'capacity' => $capacity,
                    'available_slots' => $availableSlots,
                    'status' => $faker->randomElement(['scheduled', 'completed', 'cancelled']), 
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            DB::table('schedules')->insert($schedules);
        }
        
        $scheduleIds = DB::table('schedules')->pluck('schedule_id')->toArray();
        if(empty($scheduleIds)) $scheduleIds = DB::table('schedules')->pluck('id')->toArray();

        // ==========================================
        // 4. SEEDING BOOKING GROUPS, TRANSACTIONS, & BOOKINGS (20.000 Data)
        // ==========================================
        $this->command->warn('Seeding Booking Groups, Transactions, and Bookings...');
        
        // Array untuk melacak kombinasi unik user_id dan schedule_id yang sudah dipakai di booking
        $usedBookingCombinations = [];

        for ($i = 0; $i < 10; $i++) { 
            $bookingGroups = [];
            
            for ($j = 0; $j < 2000; $j++) { 
                $bookingGroups[] = [
                    'booked_by' => $faker->randomElement($userIds),
                    'created_at' => $faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d H:i:s'),
                    'updated_at' => $now,
                ];
            }
            
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('booking_groups')->insert($bookingGroups);
            
            $recentGroups = DB::table('booking_groups')
                ->orderBy('group_id', 'desc')
                ->take(2000)
                ->get(['group_id', 'booked_by', 'created_at']);
            
            $transactions = [];
            $bookings = [];

            foreach ($recentGroups as $group) {
                
                // Tambah user_id dan transaction_date sesuai struktur transactions
                $transactions[] = [
                    'group_id' => $group->group_id,
                    'user_id' => $group->booked_by, 
                    'amount' => $faker->randomElement([50000, 75000, 100000, 150000]),
                    'payment_type' => $faker->randomElement(['ewallet', 'bank_transfer']),
                    'payment_channel' => 'xendit',
                    'transaction_date' => $group->created_at,
                    'status' => $faker->randomElement(['pending', 'settlement', 'expired']), // Sesuaikan Xendit
                    'created_at' => $group->created_at,
                    'updated_at' => $now,
                ];

                $bookingStatus = $faker->randomElement(['pending', 'attended', 'cancelled']);
                
                // --- Logic untuk memastikan kombinasi user_id dan schedule_id unik ---
                $validCombinationFound = false;
                $attemptCount = 0;
                $maxAttempts = 100; // Mencegah infinite loop kalau misal pool id terbatas banget
                
                $selectedUserId = $group->booked_by;
                $selectedScheduleId = null;

                while (!$validCombinationFound && $attemptCount < $maxAttempts) {
                    // Random ulang schedule_id saja, karena kita mau nempel ke booked_by dari group
                    // atau bisa aja random user_id dan schedule_id lagi kalau emang di desain participant bebas, 
                    // tapi amannya ikutin booked_by aja untuk konsistensi sederhana ini.
                    $selectedScheduleId = $faker->randomElement($scheduleIds);
                    
                    // Bikin key unik kayak "user_id-schedule_id"
                    $comboKey = $selectedUserId . '-' . $selectedScheduleId;
                    
                    if (!isset($usedBookingCombinations[$comboKey])) {
                        // Kombinasi belum ada, gas pakai!
                        $usedBookingCombinations[$comboKey] = true;
                        $validCombinationFound = true;
                    }
                    $attemptCount++;
                }
                
                // Fallback darurat (meskipun dengan pool 10k user dan 1k schedule sangat jarang terjadi bentrok >100 kali):
                if (!$validCombinationFound) {
                     // Lanjut aja ke iterasi berikutnya atau log warning. 
                     // Di sini kita skip masukin datanya biar aman dari DB crash.
                     continue; 
                }
                // --- Selesai Logic Unik ---

                $bookings[] = [
                    'group_id' => $group->group_id,
                    'user_id' => $selectedUserId,
                    'schedule_id' => $selectedScheduleId,
                    'participant_name' => $faker->name,
                    'booking_date' => $group->created_at,
                    'status' => $bookingStatus,
                    'cancellation_date' => $bookingStatus === 'cancelled' ? $now : null,
                    'created_at' => $group->created_at,
                    'updated_at' => $now,
                ];
            }
            
            DB::table('transactions')->insert($transactions);
            
            // Karena pakai logic skip kalau bener-bener duplicate, count bisa jadi gak sama persis 2000, jadi insert apa adanya aja array yang keisi
            if (!empty($bookings)) {
                 DB::table('bookings')->insert($bookings);
            }
            
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
            $this->command->info('Inserted roughly ' . (($i + 1) * 2000) . ' transaction and booking sets.');
        }

        $this->command->info('Proses Seeding Selesai! Database super siap buat Load Test k6.');
    }
}