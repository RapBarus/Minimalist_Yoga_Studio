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

		Schema::dropIfExists('classes');
		Schema::dropIfExists('membership_packages');

		Schema::create('classes', function (Blueprint $table) {
			$table->increments('class_id');
			$table->string('class_name');
			$table->text('description')->nullable();
			$table->string('level')->nullable();
			$table->integer('duration_minutes')->default(60);
			$table->timestamp('created_at')->nullable();
			$table->timestamp('updated_at')->nullable();
		});

		Schema::create('membership_packages', function (Blueprint $table) {
			$table->increments('package_id');
			$table->string('name');
			$table->unsignedInteger('class_id')->nullable();
			$table->integer('price');
			$table->integer('quota_amount');
			$table->integer('validity_months')->default(1);
			$table->text('description')->nullable();
			$table->integer('original_price')->default(0);
			$table->boolean('is_active')->default(1);
			$table->string('coach_name')->nullable();
			$table->unsignedInteger('coach_id')->nullable();
			$table->date('schedule_date')->nullable();
			$table->time('start_time')->nullable();
			$table->time('end_time')->nullable();
			$table->timestamp('created_at')->nullable();
			$table->timestamp('updated_at')->nullable();
		});

		DB::table('classes')->insert([
			['class_id' => 1, 'class_name' => 'Yoga Starter'],
			['class_id' => 2, 'class_name' => 'Yoga Advanced'],
			['class_id' => 3, 'class_name' => 'Pilates Core'],
			['class_id' => 4, 'class_name' => 'Pilates Flex'],
		]);

		DB::table('membership_packages')->insert([
			[
				'package_id' => 1,
				'name' => 'Yoga Starter',
				'class_id' => 1,
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
				'class_id' => 2,
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
				'class_id' => 3,
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
				'class_id' => 4,
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
        preg_match_all('/class="card-member"[^>]*data-kelas="([^"]*)"/m', $html, $cardMatches, PREG_SET_ORDER);
        foreach ($cardMatches as $match) {
            $cards[] = [
                'kelas' => html_entity_decode($match[1], ENT_QUOTES),
            ];
        }

		$kelasValues = [];

		preg_match_all('/class="filter-check"\s+data-type="kelas"\s+value="([^"]*)"/m', $html, $filterMatches, PREG_SET_ORDER);
        foreach ($filterMatches as $match) {
            $kelasValues[] = html_entity_decode($match[1], ENT_QUOTES);
        }

		$expectedRows = [
            ['kelas' => 'Yoga Starter'],
            ['kelas' => 'Yoga Advanced'],
            ['kelas' => 'Pilates Core'],
            ['kelas' => 'Pilates Flex'],
        ];

		$this->assertEqualsCanonicalizing(['Yoga Starter', 'Yoga Advanced', 'Pilates Core', 'Pilates Flex'], $kelasValues);

        // Validasi jumlah data berdasarkan kelas
        foreach ($kelasValues as $kelas) {
            $expectedCount = count(array_filter($expectedRows, fn($row) => $row['kelas'] === $kelas));
            $actualCount = count(array_filter($cards, fn($row) => $row['kelas'] === $kelas));

            $this->assertSame(
                $expectedCount,
                $actualCount,
                sprintf('Jumlah kartu untuk kelas %s tidak sesuai.', $kelas)
            );
        }
	}
}
