<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class JadwalKelasFilterTest extends TestCase
{
	protected function setUp(): void
	{
		parent::setUp();

		Carbon::setTestNow(Carbon::parse('2026-05-01 08:00:00'));
		Schema::dropIfExists('schedules');
		Schema::dropIfExists('classes');
		Schema::dropIfExists('coaches');
		Schema::dropIfExists('membership_packages');
		Schema::dropIfExists('users');
		Schema::dropIfExists('transactions');

		

		Schema::create('transactions', function (Blueprint $table) {
            $table->increments('transaction_id');
            $table->string('order_id')->nullable();
            $table->unsignedInteger('user_id');
            $table->string('item_type')->nullable();
            $table->unsignedInteger('item_id')->nullable();
            $table->unsignedInteger('booking_id')->nullable();
            $table->unsignedInteger('recorded_by')->nullable();
            $table->integer('amount');
            $table->string('payment_type')->nullable();
            $table->string('payment_channel')->nullable();
            $table->string('xendit_external_id')->nullable();
            $table->string('xendit_invoice_url')->nullable();
            $table->string('xendit_id')->nullable();
            $table->string('status')->nullable();
            $table->dateTime('transaction_date')->nullable();
            $table->dateTime('expiry_time')->nullable(); // Wajib ada untuk fungsi cleanup!
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });

		Schema::create('users', function (Blueprint $table) {
			$table->increments('user_id');
			$table->string('username')->nullable();
			$table->string('name');
			$table->string('email')->nullable();
			$table->string('phone_number')->nullable();
			$table->string('password_hash')->nullable();
			$table->string('role')->default('customer');
			$table->string('status')->default('active');
			$table->timestamp('created_at')->nullable();
			$table->timestamp('updated_at')->nullable();
		});

		Schema::create('coaches', function (Blueprint $table) {
			$table->increments('coach_id');
			$table->unsignedInteger('user_id');
			$table->string('specialization')->nullable();
			$table->string('profile_photo')->nullable();
			$table->integer('rate_per_class')->default(50000);
			$table->timestamp('created_at')->nullable();
			$table->timestamp('updated_at')->nullable();
		});

		Schema::create('classes', function (Blueprint $table) {
			$table->increments('class_id');
			$table->string('class_name');
			$table->text('description')->nullable();
			$table->timestamp('created_at')->nullable();
			$table->timestamp('updated_at')->nullable();
		});

		Schema::create('bookings', function (Blueprint $table) {
			$table->increments('booking_id');
			$table->unsignedInteger('user_id');
			$table->unsignedInteger('schedule_id');
			$table->dateTime('booking_date')->nullable();
			$table->string('status');
			$table->timestamp('created_at')->nullable();
			$table->timestamp('updated_at')->nullable();
		});

		Schema::create('schedules', function (Blueprint $table) {
			$table->increments('schedule_id');
			$table->unsignedInteger('class_id');
			$table->unsignedInteger('coach_id');
			$table->string('title')->nullable();
			$table->date('schedule_date');
			$table->time('start_time');
			$table->time('end_time');
			$table->integer('available_slots')->default(0);
			$table->integer('capacity')->default(0);
			$table->string('status')->default('upcoming');
			$table->timestamp('created_at')->nullable();
			$table->timestamp('updated_at')->nullable();
		});

		Schema::create('membership_packages', function (Blueprint $table) {
            $table->increments('package_id');
            $table->unsignedInteger('class_id')->nullable(); // 👇 Baris ini yang wajib ditambahkan
            $table->string('name');
            $table->integer('price');
            $table->integer('quota_amount');
            $table->boolean('is_active')->default(1);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });

		DB::table('users')->insert([
			['user_id' => 1, 'name' => 'Customer', 'role' => 'customer', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
			['user_id' => 2, 'name' => 'Alya', 'role' => 'coach', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
			['user_id' => 3, 'name' => 'Bima', 'role' => 'coach', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
		]);

		DB::table('coaches')->insert([
			['coach_id' => 1, 'user_id' => 2, 'specialization' => 'Yoga', 'rate_per_class' => 70000, 'created_at' => now(), 'updated_at' => now()],
			['coach_id' => 2, 'user_id' => 3, 'specialization' => 'Pilates', 'rate_per_class' => 80000, 'created_at' => now(), 'updated_at' => now()],
		]);

		DB::table('classes')->insert([
			['class_id' => 1, 'class_name' => 'Yoga', 'description' => 'Yoga class', 'created_at' => now(), 'updated_at' => now()],
			['class_id' => 2, 'class_name' => 'Pilates', 'description' => 'Pilates class', 'created_at' => now(), 'updated_at' => now()],
		]);

		// Bikin tanggal dinamis untuk Senin dan Selasa minggu depan
        $senin = now()->next('Monday')->toDateString();
        $selasa = now()->next('Tuesday')->toDateString();

        DB::table('schedules')->insert([
            [
                'schedule_id' => 101,
                'class_id' => 1,
                'coach_id' => 1,
                'schedule_date' => $senin,
                'start_time' => '09:00:00',
                'end_time' => '10:00:00',
                'available_slots' => 8,
                'capacity' => 10,
                'status' => 'upcoming',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'schedule_id' => 102,
                'class_id' => 1,
                'coach_id' => 2,
                'schedule_date' => $selasa,
                'start_time' => '15:00:00',
                'end_time' => '16:00:00',
                'available_slots' => 6,
                'capacity' => 10,
                'status' => 'upcoming',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'schedule_id' => 103,
                'class_id' => 2,
                'coach_id' => 1,
                'schedule_date' => $selasa,
                'start_time' => '11:00:00',
                'end_time' => '12:00:00',
                'available_slots' => 7,
                'capacity' => 10,
                'status' => 'upcoming',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 👇 Jadwal 104 dihapus dari sini!
        ]);

		DB::statement('DROP VIEW IF EXISTS vw_available_schedules');
		DB::statement(
			'CREATE VIEW vw_available_schedules AS
			 SELECT schedules.schedule_id,
			        schedules.class_id,
			        schedules.coach_id,
			        schedules.schedule_date,
			        schedules.start_time,
			        schedules.end_time,
			        schedules.available_slots,
			        schedules.capacity,
			        schedules.status,
			        classes.class_name,
			        users.name as coach_name,
			        coaches.rate_per_class
			 FROM schedules
			 INNER JOIN classes ON schedules.class_id = classes.class_id
			 INNER JOIN coaches ON schedules.coach_id = coaches.coach_id
			 INNER JOIN users ON coaches.user_id = users.user_id'
		);
	}

	public function test_home_filters_are_available_and_combination_data_is_correct(): void
	{
		$response = $this->withSession([
			'user_id' => 1,
			'user_name' => 'Customer',
			'user_role' => 'customer',
		])->get('/home');

		$response->assertStatus(200);
		$response->assertViewIs('pages.home');

		$html = $response->getContent();

		$cards = [];
		preg_match_all('/class="card-class"[^>]*data-kelas="([^"]*)"[^>]*data-waktu="([^"]*)"[^>]*data-coach="([^"]*)"/m', $html, $cardMatches, PREG_SET_ORDER);
		foreach ($cardMatches as $match) {
			$cards[] = [
				'kelas' => html_entity_decode($match[1], ENT_QUOTES),
				'waktu' => html_entity_decode($match[2], ENT_QUOTES),
				'coach' => html_entity_decode($match[3], ENT_QUOTES),
			];
		}

		$kelasValues = [];
		$waktuValues = [];
		$coachValues = [];

		preg_match_all('/class="filter-check"\s+data-type="(kelas|waktu|coach)"\s+value="([^"]*)"/m', $html, $filterMatches, PREG_SET_ORDER);
		foreach ($filterMatches as $match) {
			$type = $match[1];
			$value = html_entity_decode($match[2], ENT_QUOTES);

			if ($type === 'kelas') {
				$kelasValues[] = $value;
			}
			if ($type === 'waktu') {
				$waktuValues[] = $value;
			}
			if ($type === 'coach') {
				$coachValues[] = $value;
			}
		}


		$seninDate = now()->next('Monday')->toDateString();
        $selasaDate = now()->next('Tuesday')->toDateString();

				
		$expectedRows = [
			['kelas' => 'Yoga', 'waktu' => Carbon::parse($seninDate)->format('l'), 'coach' => 'Alya'],
			['kelas' => 'Yoga', 'waktu' => Carbon::parse($selasaDate)->format('l'), 'coach' => 'Bima'],
			['kelas' => 'Pilates', 'waktu' => Carbon::parse($selasaDate)->format('l'), 'coach' => 'Alya'],
		];

		$this->assertEqualsCanonicalizing(['Yoga', 'Pilates'], $kelasValues);
		$this->assertEqualsCanonicalizing(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'], $waktuValues);
		$this->assertEqualsCanonicalizing(['Alya', 'Bima'], $coachValues);

		foreach ($kelasValues as $kelas) {
            foreach ($waktuValues as $waktu) {
                foreach ($coachValues as $coach) {
                    $expectedCount = count(array_filter($expectedRows, fn($row) => $row['kelas'] === $kelas && $row['waktu'] === $waktu && $row['coach'] === $coach));
                    $actualCount = count(array_filter($cards, fn($row) => $row['kelas'] === $kelas && $row['waktu'] === $waktu && $row['coach'] === $coach));

                    // 👇 TAMBAHKAN INI UNTUK DEBUGGING
                    if ($expectedCount !== $actualCount) {
                        dump("Gagal di: $kelas, $waktu, $coach. Expected: $expectedCount, Actual: $actualCount");
                        dump("Daftar kartu yang terbaca:", $cards);
                    }

                    $this->assertSame(
                        $expectedCount,
                        $actualCount,
                        sprintf('Combination kelas=%s, waktu=%s, coach=%s does not match expected dataset.', $kelas, $waktu, $coach)
                    );
                }
            }
        }
	}
}
