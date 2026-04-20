<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class FilterMembershipCustomerTest extends TestCase
{
	protected function setUp(): void
	{
		parent::setUp();

		Schema::dropIfExists('membership_packages');

		Schema::create('membership_packages', function (Blueprint $table) {
			$table->increments('package_id');
			$table->string('name');
			$table->integer('price');
			$table->integer('quota_amount');
			$table->boolean('is_active')->default(1);
			$table->string('coach_name')->nullable();
			$table->unsignedInteger('coach_id')->nullable();
			$table->date('schedule_date')->nullable();
			$table->time('start_time')->nullable();
			$table->time('end_time')->nullable();
			$table->timestamp('created_at')->nullable();
			$table->timestamp('updated_at')->nullable();
		});

		DB::table('membership_packages')->insert([
			[
				'package_id' => 1,
				'name' => 'Yoga Starter',
				'price' => 250000,
				'quota_amount' => 4,
				'is_active' => 1,
				'coach_name' => 'Alya',
				'schedule_date' => '2026-05-04',
				'start_time' => '09:00:00',
				'end_time' => '10:00:00',
				'created_at' => now(),
				'updated_at' => now(),
			],
			[
				'package_id' => 2,
				'name' => 'Yoga Advanced',
				'price' => 300000,
				'quota_amount' => 6,
				'is_active' => 1,
				'coach_name' => 'Bima',
				'schedule_date' => '2026-05-05',
				'start_time' => '15:00:00',
				'end_time' => '16:00:00',
				'created_at' => now(),
				'updated_at' => now(),
			],
			[
				'package_id' => 3,
				'name' => 'Pilates Core',
				'price' => 280000,
				'quota_amount' => 5,
				'is_active' => 1,
				'coach_name' => 'Alya',
				'schedule_date' => '2026-05-05',
				'start_time' => '10:00:00',
				'end_time' => '11:00:00',
				'created_at' => now(),
				'updated_at' => now(),
			],
			[
				'package_id' => 4,
				'name' => 'Pilates Flex',
				'price' => 320000,
				'quota_amount' => 8,
				'is_active' => 1,
				'coach_name' => 'Bima',
				'schedule_date' => '2026-05-04',
				'start_time' => '18:00:00',
				'end_time' => '19:00:00',
				'created_at' => now(),
				'updated_at' => now(),
			],
		]);
	}

	public function test_member_filters_are_available_and_combination_data_is_correct(): void
	{
		$response = $this->withSession([
			'user_id' => 1,
			'user_name' => 'customer_one',
			'user_role' => 'customer',
		])->get('/member');

		$response->assertStatus(200);
		$response->assertViewIs('pages.member');

		$html = $response->getContent();

		$cards = [];
		preg_match_all('/class="card-member"[^>]*data-kelas="([^"]*)"[^>]*data-waktu="([^"]*)"[^>]*data-coach="([^"]*)"/m', $html, $cardMatches, PREG_SET_ORDER);
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
			['kelas' => 'Yoga Starter', 'waktu' => Carbon::parse('2026-05-04')->translatedFormat('l'), 'coach' => 'Alya'],
			['kelas' => 'Yoga Advanced', 'waktu' => Carbon::parse('2026-05-05')->translatedFormat('l'), 'coach' => 'Bima'],
			['kelas' => 'Pilates Core', 'waktu' => Carbon::parse('2026-05-05')->translatedFormat('l'), 'coach' => 'Alya'],
			['kelas' => 'Pilates Flex', 'waktu' => Carbon::parse('2026-05-04')->translatedFormat('l'), 'coach' => 'Bima'],
		];

		$this->assertEqualsCanonicalizing(['Yoga Starter', 'Yoga Advanced', 'Pilates Core', 'Pilates Flex'], $kelasValues);
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
