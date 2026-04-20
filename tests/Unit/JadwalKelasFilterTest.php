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

		Schema::dropIfExists('schedules');
		Schema::dropIfExists('classes');
		Schema::dropIfExists('coaches');
		Schema::dropIfExists('membership_packages');
		Schema::dropIfExists('users');

		Schema::create('users', function (Blueprint $table) {
			$table->increments('user_id');
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

		Schema::create('schedules', function (Blueprint $table) {
			$table->increments('schedule_id');
			$table->unsignedInteger('class_id');
			$table->unsignedInteger('coach_id');
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

		DB::table('schedules')->insert([
			[
				'schedule_id' => 101,
				'class_id' => 1,
				'coach_id' => 1,
				'schedule_date' => '2026-05-04',
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
				'schedule_date' => '2026-05-05',
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
				'schedule_date' => '2026-05-05',
				'start_time' => '11:00:00',
				'end_time' => '12:00:00',
				'available_slots' => 7,
				'capacity' => 10,
				'status' => 'upcoming',
				'created_at' => now(),
				'updated_at' => now(),
			],
			[
				'schedule_id' => 104,
				'class_id' => 2,
				'coach_id' => 2,
				'schedule_date' => '2026-05-04',
				'start_time' => '18:00:00',
				'end_time' => '19:00:00',
				'available_slots' => 9,
				'capacity' => 10,
				'status' => 'upcoming',
				'created_at' => now(),
				'updated_at' => now(),
			],
		]);
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

		$expectedRows = [
			['kelas' => 'Yoga', 'waktu' => Carbon::parse('2026-05-04')->translatedFormat('l'), 'coach' => 'Alya'],
			['kelas' => 'Yoga', 'waktu' => Carbon::parse('2026-05-05')->translatedFormat('l'), 'coach' => 'Bima'],
			['kelas' => 'Pilates', 'waktu' => Carbon::parse('2026-05-05')->translatedFormat('l'), 'coach' => 'Alya'],
			['kelas' => 'Pilates', 'waktu' => Carbon::parse('2026-05-04')->translatedFormat('l'), 'coach' => 'Bima'],
		];

		$this->assertEqualsCanonicalizing(['Yoga', 'Pilates'], $kelasValues);
		$this->assertEqualsCanonicalizing(array_values(array_unique(array_column($expectedRows, 'waktu'))), $waktuValues);
		$this->assertEqualsCanonicalizing(['Alya', 'Bima'], $coachValues);

		foreach ($kelasValues as $kelas) {
			foreach ($waktuValues as $waktu) {
				foreach ($coachValues as $coach) {
					$expectedCount = count(array_filter($expectedRows, fn($row) => $row['kelas'] === $kelas && $row['waktu'] === $waktu && $row['coach'] === $coach));
					$actualCount = count(array_filter($cards, fn($row) => $row['kelas'] === $kelas && $row['waktu'] === $waktu && $row['coach'] === $coach));

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
