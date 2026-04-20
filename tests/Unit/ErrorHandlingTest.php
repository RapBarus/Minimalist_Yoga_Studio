<?php

namespace Tests\Unit;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ErrorHandlingTest extends TestCase
{
	protected function setUp(): void
	{
		parent::setUp();

		Schema::dropIfExists('bookings');
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

		Schema::create('bookings', function (Blueprint $table) {
			$table->increments('booking_id');
			$table->unsignedInteger('user_id');
			$table->string('status')->nullable();
			$table->timestamp('created_at')->nullable();
			$table->timestamp('updated_at')->nullable();
		});

		DB::table('users')->insert([
			'user_id' => 1,
			'name' => 'customer_one',
			'phone_number' => '+628123456789',
			'password_hash' => Hash::make('Password123'),
			'role' => 'customer',
			'status' => 'active',
			'created_at' => now(),
			'updated_at' => now(),
		]);
	}

	public function test_profile_update_shows_error_for_invalid_phone_format(): void
	{
		$response = $this->withSession([
			'user_id' => 1,
			'user_name' => 'customer_one',
			'user_role' => 'customer',
		])
			->from('/profile')
			->followingRedirects()
			->put('/profile/update', [
				'phone_number' => '0812-INVALID',
			]);

		$response->assertStatus(200);
		$response->assertSeeText('Nomor HP hanya boleh angka, 8–15 digit.');
	}

	public function test_profile_update_shows_password_error_when_null_input_is_submitted(): void
	{
		$before = DB::table('users')->where('user_id', 1)->value('password_hash');

		$response = $this->withSession([
			'user_id' => 1,
			'user_name' => 'customer_one',
			'user_role' => 'customer',
		])
			->from('/profile')
			->followingRedirects()
			->put('/profile/update', [
				'password' => '',
				'password_confirmation' => '',
			]);

		$after = DB::table('users')->where('user_id', 1)->value('password_hash');

		$response->assertStatus(200);
		$response->assertSeeText('Password tidak diubah karena input kosong.');
		$this->assertSame($before, $after);
	}
}
