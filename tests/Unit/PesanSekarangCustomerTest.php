<?php

namespace Tests\Unit;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PesanSekarangCustomerTest extends TestCase
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
			['user_id' => 2, 'name' => 'Coach One', 'role' => 'coach', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
		]);

		DB::table('coaches')->insert([
			['coach_id' => 1, 'user_id' => 2, 'specialization' => 'Yoga', 'rate_per_class' => 70000, 'created_at' => now(), 'updated_at' => now()],
		]);

		DB::table('classes')->insert([
			['class_id' => 1, 'class_name' => 'Yoga', 'description' => 'Yoga class', 'created_at' => now(), 'updated_at' => now()],
		]);

		DB::table('schedules')->insert([
			[
				'schedule_id' => 321,
				'class_id' => 1,
				'coach_id' => 1,
				'schedule_date' => '2026-05-06',
				'start_time' => '09:00:00',
				'end_time' => '10:00:00',
				'available_slots' => 7,
				'capacity' => 10,
				'status' => 'upcoming',
				'created_at' => now(),
				'updated_at' => now(),
			],
		]);
	}

	public function test_pesan_sekarang_from_home_navigates_user_to_payment_page(): void
	{
		$homeResponse = $this->withSession([
			'user_id' => 1,
			'user_name' => 'Customer',
			'user_role' => 'customer',
		])->get('/home');

		$homeResponse->assertStatus(200);
		$homeResponse->assertSee(route('payment.show', 321), false);

		$paymentResponse = $this->withSession([
			'user_id' => 1,
			'user_name' => 'Customer',
			'user_role' => 'customer',
		])->get(route('payment.show', 321));

		$paymentResponse->assertStatus(200);
		$paymentResponse->assertSeeText('Metode Pembayaran');
		$paymentResponse->assertSeeText('Bayar');
	}

	public function test_each_payment_method_redirects_user_back_to_home_for_now(): void
	{
		$methods = ['QRIS', 'GoPay', 'DANA', 'OVO', 'ShopeePay'];

		foreach ($methods as $method) {
			$response = $this->withSession([
				'user_id' => 1,
				'user_name' => 'Customer',
				'user_role' => 'customer',
			])->post('/payment/process', [
				'payment_method' => $method,
				'schedule_id' => 321,
			]);

			$response->assertRedirect('/home');
			$response->assertSessionHas('success', 'Pembayaran sedang diproses!');
		}
	}
}
