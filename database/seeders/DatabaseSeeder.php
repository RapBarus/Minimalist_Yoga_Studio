<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Memulai seeding...');

        DB::statement('SET @DISABLE_TRIGGERS = 1');

        // --- 1. CLASSES ---
        $classIds = [];
        foreach (['Hatha Yoga', 'Vinyasa Flow', 'Yin Yoga', 'Advanced Power'] as $name) {
            $classIds[] = DB::table('classes')->insertGetId(['class_name' => $name]);
        }

        // --- 2. COACHES ---
        $coachIds = DB::table('coaches')->pluck('coach_id')->toArray();
        if (empty($coachIds)) {
            $coachUserId = DB::table('users')->insertGetId([
                'username'      => 'coach_seed',
                'name'          => 'Seed Coach',
                'phone_number'  => '+6281234560000',
                'password_hash' => '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'role'          => 'coach',
                'status'        => 'active',
            ]);
            $coachIds[] = DB::table('coaches')->insertGetId([
                'user_id'          => $coachUserId,
                'class_id'         => $classIds[0],
                'rate_per_class'   => 200000,
                'years_experience' => 3,
            ]);
        }

        // --- 3. MEMBERSHIP PACKAGES ---
        // FIX: class_id is now required (CONS-11 migration)
        // Map: package_id => [quota, validity_months, price, class_id]
        $packages = [];

        $packages[] = [
            'id'      => DB::table('membership_packages')->insertGetId([
                'name'            => 'Starter Pack',
                'class_id'        => $classIds[0], // Hatha Yoga
                'price'           => 500000,
                'quota_amount'    => 8,
                'validity_months' => 1,
            ]),
            'class_id'        => $classIds[0],
            'price'           => 500000,
            'quota_amount'    => 8,
            'validity_months' => 1,
        ];

        $packages[] = [
            'id'      => DB::table('membership_packages')->insertGetId([
                'name'            => 'Vinyasa Monthly',
                'class_id'        => $classIds[1], // Vinyasa Flow
                'price'           => 800000,
                'quota_amount'    => 8,
                'validity_months' => 1,
            ]),
            'class_id'        => $classIds[1],
            'price'           => 800000,
            'quota_amount'    => 8,
            'validity_months' => 1,
        ];

        $packages[] = [
            'id'      => DB::table('membership_packages')->insertGetId([
                'name'            => 'Monthly Pro',
                'class_id'        => $classIds[0], // Hatha Yoga
                'price'           => 1200000,
                'quota_amount'    => 20,
                'validity_months' => 3,
            ]),
            'class_id'        => $classIds[0],
            'price'           => 1200000,
            'quota_amount'    => 20,
            'validity_months' => 3,
        ];

        // --- 4. CUSTOMERS ---
        $customerUserIds = [];
        for ($i = 0; $i < 30; $i++) {
            $name = fake()->name();
            $customerUserIds[] = DB::table('users')->insertGetId([
                'username'      => strtolower(str_replace([' ', '.', "'"], '', $name)) . rand(10, 99),
                'name'          => $name,
                'phone_number'  => '+628' . fake()->numerify('#########'),
                'password_hash' => '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'role'          => 'customer',
                'status'        => 'active',
            ]);
        }

        // --- 5. SCHEDULES ---
        $pastScheduleIds   = [];
        $futureScheduleIds = [];

        for ($d = -10; $d < 10; $d++) {
            $date    = Carbon::now()->addDays($d)->toDateString();
            $isPast  = $d < 0;
            $status  = $isPast ? 'completed' : 'upcoming';

            for ($s = 0; $s < 2; $s++) {
                $startHour      = $s === 0 ? '08:00:00' : '17:00:00';
                $endHour        = $s === 0 ? '09:00:00' : '18:00:00';
                $capacity       = 15;
                $bookedCount    = $isPast ? 8 : 0;
                $availableSlots = $capacity - $bookedCount;

                $schedId = DB::table('schedules')->insertGetId([
                    'class_id'        => fake()->randomElement($classIds),
                    'coach_id'        => fake()->randomElement($coachIds),
                    'schedule_date'   => $date,
                    'start_time'      => $startHour,
                    'end_time'        => $endHour,
                    'capacity'        => $capacity,
                    'available_slots' => $availableSlots,
                    'status'          => $status,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);

                $isPast ? ($pastScheduleIds[] = $schedId) : ($futureScheduleIds[] = $schedId);
            }
        }

        // --- 6. PAST: bookings + transactions + attendance + salary ---
        $adminUserId = DB::table('users')->where('role', 'admin')->value('user_id');

        foreach ($pastScheduleIds as $schedId) {
            $schedInfo = DB::table('schedules')
                ->join('coaches', 'schedules.coach_id', '=', 'coaches.coach_id')
                ->where('schedules.schedule_id', $schedId)
                ->select('schedules.coach_id', 'coaches.rate_per_class', 'schedules.schedule_date')
                ->first();

            $attendingCustomers = fake()->randomElements($customerUserIds, 8);

            foreach ($attendingCustomers as $uId) {
                DB::statement(
                    "INSERT INTO bookings (user_id, schedule_id, booking_date, status, created_at, updated_at)
                     VALUES (?, ?, NOW(), 'attended', NOW(), NOW())",
                    [$uId, $schedId]
                );
                $bookingId = DB::getPdo()->lastInsertId();

                DB::table('transactions')->insert([
                    'user_id'          => $uId,
                    'booking_id'       => $bookingId,
                    'quota_id'         => null,
                    'amount'           => 150000,
                    'payment_type'     => fake()->randomElement(['bank_transfer', 'ewallet']),
                    'payment_channel'  => 'xendit',
                    'status'           => fake()->randomElement(['settlement', 'paid']),
                    'transaction_date' => now(),
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);

                DB::table('attendance')->insert([
                    'booking_id'         => $bookingId,
                    'coach_verification' => 1,
                    'admin_verification' => 1,
                    'check_in_time'      => now(),
                    'coach_verified_at'  => now(),
                    'admin_verified_at'  => now(),
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ]);
            }

            $rate  = $schedInfo->rate_per_class ?? 200000;
            DB::table('salary_records')->insertOrIgnore([
                'coach_id'          => $schedInfo->coach_id,
                'schedule_id'       => $schedId,
                'recorded_by'       => $adminUserId,
                'session_date'      => $schedInfo->schedule_date,
                'participant_count' => count($attendingCustomers),
                'rate_per_class'    => $rate,
                'bonus_amount'      => 0,
                'total_amount'      => $rate,
                'status'            => 'pending',
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }

        // --- 7. FUTURE: bookings + transactions ---
        foreach ($futureScheduleIds as $schedId) {
            foreach (fake()->randomElements($customerUserIds, rand(3, 8)) as $uId) {
                $slots = DB::table('schedules')->where('schedule_id', $schedId)->value('available_slots');
                if ($slots <= 0) break;

                try {
                    $bookingId = DB::table('bookings')->insertGetId([
                        'user_id'      => $uId,
                        'schedule_id'  => $schedId,
                        'booking_date' => now(),
                        'status'       => 'confirmed',
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ]);

                    DB::table('transactions')->insert([
                        'user_id'          => $uId,
                        'booking_id'       => $bookingId,
                        'quota_id'         => null,
                        'amount'           => 150000,
                        'payment_type'     => 'bank_transfer',
                        'payment_channel'  => 'xendit',
                        'status'           => 'settlement',
                        'transaction_date' => now(),
                        'created_at'       => now(),
                        'updated_at'       => now(),
                    ]);
                } catch (\Exception $e) {
                    $this->command->warn("Skipped booking (schedule {$schedId}): " . $e->getMessage());
                }
            }
        }

        // --- 8. MEMBERSHIP QUOTAS ---
        // FIX 1: quota row now needs class_id (from the package)
        // FIX 2: TR-06 now blocks per class_id, not globally —
        //        check existing by (user_id, class_id) pair
        // FIX 3: used_quota = 0 always (TR-01 manages it in production)
        $membersWithQuota = fake()->randomElements($customerUserIds, 10);

        foreach ($membersWithQuota as $uId) {
            $pkg = fake()->randomElement($packages);

            // FIX 2: scope the duplicate check to same class
            $existing = DB::table('membership_quotas')
                ->where('user_id', $uId)
                ->where('class_id', $pkg['class_id'])
                ->where('is_active', 1)
                ->exists();

            if ($existing) continue;

            $quotaId = DB::table('membership_quotas')->insertGetId([
                'user_id'       => $uId,
                'package_id'    => $pkg['id'],
                'class_id'      => $pkg['class_id'], // FIX 1: required column
                'total_quota'   => $pkg['quota_amount'],
                'used_quota'    => 0,                // FIX 3: always 0
                'start_date'    => Carbon::now()->subDays(10)->toDateString(),
                'reset_date'    => Carbon::now()->addMonths($pkg['validity_months'])->toDateString(),
                'is_active'     => 1,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            DB::table('transactions')->insert([
                'user_id'          => $uId,
                'booking_id'       => null,
                'quota_id'         => $quotaId,
                'amount'           => $pkg['price'],
                'payment_type'     => 'bank_transfer',
                'payment_channel'  => 'xendit',
                'status'           => 'settlement',
                'transaction_date' => now(),
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        }

        DB::statement('SET @DISABLE_TRIGGERS = 0');
        $this->command->info('Seeding selesai!');
    }
}