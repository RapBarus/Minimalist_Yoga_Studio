<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Carbon\Carbon;

class AdminTest extends TestCase
{
	protected function setUp(): void
	{
		parent::setUp();

		Schema::dropIfExists('transactions');
		Schema::dropIfExists('bookings');
		Schema::dropIfExists('schedules');
		Schema::dropIfExists('classes');
		Schema::dropIfExists('coaches');
		Schema::dropIfExists('membership_packages');
		Schema::dropIfExists('users');

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
			$table->text('bio')->nullable();
			$table->integer('rate_per_class')->default(50000);
			$table->integer('years_experience')->default(0);
			$table->timestamp('created_at')->nullable();
			$table->timestamp('updated_at')->nullable();
		});

		Schema::create('classes', function (Blueprint $table) {
			$table->increments('class_id');
			$table->string('class_name');
			$table->text('description')->nullable();
			$table->string('level')->nullable();
			$table->integer('duration_minutes')->default(60);
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

		Schema::create('bookings', function (Blueprint $table) {
			$table->increments('booking_id');
			$table->unsignedInteger('user_id');
			$table->unsignedInteger('schedule_id');
			$table->dateTime('booking_date');
			$table->string('status');
			$table->timestamp('created_at')->nullable();
			$table->timestamp('updated_at')->nullable();
		});

		Schema::create('transactions', function (Blueprint $table) {
			$table->increments('transaction_id');
			$table->unsignedInteger('user_id');
			$table->unsignedInteger('booking_id');
			$table->unsignedInteger('recorded_by')->nullable();
			$table->integer('amount');
			$table->string('payment_type')->nullable();
			$table->string('payment_channel')->nullable();
			$table->string('xendit_external_id')->nullable();
			$table->string('xendit_invoice_url')->nullable();
			$table->string('xendit_id')->nullable();
			$table->string('status')->nullable();
			$table->dateTime('transaction_date')->nullable();
			$table->timestamp('created_at')->nullable();
			$table->timestamp('updated_at')->nullable();
		});

		Schema::create('membership_packages', function (Blueprint $table) {
			$table->increments('package_id');
			$table->string('name');
			$table->integer('price');
			$table->integer('quota_amount');
			$table->integer('validity_months')->default(1);
			$table->text('description')->nullable();
			$table->integer('original_price')->default(0);
			$table->boolean('is_active')->default(1);
			$table->timestamp('created_at')->nullable();
			$table->timestamp('updated_at')->nullable();
		});

		Schema::create('membership_quotas', function (Blueprint $table) {
			$table->increments('quota_id');
			$table->unsignedInteger('user_id');
			$table->unsignedInteger('package_id');
			$table->integer('remaining')->default(0);
			$table->timestamp('created_at')->nullable();
			$table->timestamp('updated_at')->nullable();
		});

		// seed admin user
		DB::table('users')->insert([
			'user_id' => 1,
			'username' => 'admin',
			'name' => 'Admin',
			'role' => 'admin',
			'status' => 'active',
			'created_at' => now(),
			'updated_at' => now(),
		]);
	}

	public function test_admin_dashboard_redirects_guest_to_login(): void
	{
		$response = $this->get('/admin/dashboard');

		$response->assertRedirect('/login');
	}

	public function test_admin_coaches_redirects_guest_to_login(): void
	{
		$response = $this->get('/admin/coaches');

		$response->assertRedirect('/login');
	}

	public function test_admin_dashboard_returns_forbidden_for_non_admin_user(): void
	{
		$response = $this->withSession([
			'user_id' => 99,
			'user_role' => 'customer',
		])->get('/admin/dashboard');

		$response->assertStatus(403);
	}

	public function test_admin_coaches_returns_forbidden_for_non_admin_user(): void
	{
		$response = $this->withSession([
			'user_id' => 100,
			'user_role' => 'coach',
		])->get('/admin/coaches');

		$response->assertStatus(403);
	}

	public function test_tambah_member_with_random_and_out_of_bounds(): void
	{
		// prepare: class, coach, schedule
		$classId = DB::table('classes')->insertGetId([
			'class_name' => 'TestClass ' . rand(1000, 9999),
			'description' => 'desc',
			'level' => 'beginner',
			'duration_minutes' => 60,
			'created_at' => now(),
			'updated_at' => now(),
		]);

		$userId = DB::table('users')->insertGetId([
			'name' => 'coach_for_member_' . rand(1000, 9999),
			'phone_number' => '+6281234567890',
			'email' => null,
			'password_hash' => Hash::make('Secret1'),
			'role' => 'coach',
			'status' => 'active',
			'created_at' => now(),
			'updated_at' => now(),
		]);

		$coachId = DB::table('coaches')->insertGetId([
			'user_id' => $userId,
			'specialization' => 'spec',
			'rate_per_class' => 50000,
			'years_experience' => 1,
			'created_at' => now(),
		]);

		$scheduleId = DB::table('schedules')->insertGetId([
			'class_id' => $classId,
			'coach_id' => $coachId,
			'schedule_date' => Carbon::now()->addDay()->toDateString(),
			'start_time' => '10:00',
			'end_time' => '11:00',
			'capacity' => 10,
			'available_slots' => 10,
			'status' => 'upcoming',
			'created_at' => now(),
		]);

		// valid payload: should create booking + transaction
		$payload = [
			'name' => 'WalkIn ' . rand(1000, 9999),
			'payment_type' => 'cash',
			'phone_number' => '081234567890',
			'amount' => 75000,
		];

		$response = $this->withSession(['user_id' => 1, 'user_role' => 'admin'])
			->post('/admin/schedules/' . $scheduleId . '/peserta', $payload);

		$response->assertRedirect('/admin/schedules/' . $scheduleId . '/view');

		$this->assertDatabaseHas('bookings', ['status' => 'confirmed']);
		$this->assertDatabaseHas('transactions', ['amount' => 75000]);

		// invalid payload: missing payment_type
		$bad = ['name' => '', 'payment_type' => 'invalid', 'phone_number' => 'bad', 'amount' => -1];
		$resp2 = $this->withSession(['user_id' => 1, 'user_role' => 'admin'])
			->post('/admin/schedules/' . $scheduleId . '/peserta', $bad);

		$resp2->assertSessionHasErrors();

		// cleanup
		DB::table('transactions')->where('amount', 75000)->delete();
		DB::table('bookings')->where('status', 'confirmed')->delete();
		DB::table('schedules')->where('schedule_id', $scheduleId)->delete();
		DB::table('coaches')->where('coach_id', $coachId)->delete();
		DB::table('users')->where('user_id', $userId)->delete();
		DB::table('classes')->where('class_id', $classId)->delete();
	}

	public function test_tambah_kelas_with_random_and_out_of_bounds(): void
	{
		$payload = [
			'class_name' => 'NewClass ' . rand(1000, 9999),
			'description' => 'desc',
			'level' => 'beginner',
			'duration_minutes' => 45,
		];

		$response = $this->withSession(['user_id' => 1, 'user_role' => 'admin'])
			->post('/admin/classes', $payload);

		$response->assertRedirect('/admin/classes');
		$this->assertDatabaseHas('classes', ['class_name' => $payload['class_name']]);

		// invalid payload: invalid level
		$bad = $payload;
		$bad['level'] = 'expert';
		$resp2 = $this->withSession(['user_id' => 1, 'user_role' => 'admin'])
			->post('/admin/classes', $bad);

		$resp2->assertSessionHasErrors();

		DB::table('classes')->where('class_name', $payload['class_name'])->delete();
	}

	public function test_jadwal_kelas_data_rendered_below_text(): void
	{
		// Create a class and schedule to ensure content
		$classId = DB::table('classes')->insertGetId([
			'class_name' => 'RenderClass ' . rand(1000, 9999),
			'description' => 'desc',
			'level' => 'beginner',
			'duration_minutes' => 60,
			'created_at' => now(),
			'updated_at' => now(),
		]);

		$userId = DB::table('users')->insertGetId([
			'name' => 'render_coach_' . rand(1000, 9999),
			'phone_number' => '+6281234567890',
			'email' => null,
			'password_hash' => Hash::make('Secret1'),
			'role' => 'coach',
			'status' => 'active',
			'created_at' => now(),
			'updated_at' => now(),
		]);

		$coachId = DB::table('coaches')->insertGetId([
			'user_id' => $userId,
			'specialization' => 'spec',
			'rate_per_class' => 50000,
			'years_experience' => 1,
			'created_at' => now(),
		]);

		DB::table('schedules')->insert([
			'class_id' => $classId,
			'coach_id' => $coachId,
			'schedule_date' => Carbon::now()->addDay()->toDateString(),
			'start_time' => '09:00',
			'end_time' => '10:00',
			'capacity' => 10,
			'available_slots' => 10,
			'status' => 'upcoming',
			'created_at' => now(),
		]);

		$response = $this->withSession(['user_id' => 1, 'user_role' => 'admin'])->get('/admin/schedules');

		$response->assertStatus(200);
		$response->assertSee('Jadwal Kelas');
		$response->assertSee('RenderClass');

		// cleanup
		DB::table('schedules')->where('class_id', $classId)->delete();
		DB::table('coaches')->where('coach_id', $coachId)->delete();
		DB::table('users')->where('user_id', $userId)->delete();
		DB::table('classes')->where('class_id', $classId)->delete();
	}

	public function test_view_jadwal_button_displays_input_aligned(): void
	{
		// create class, coach, schedule
		$classId = DB::table('classes')->insertGetId([
			'class_name' => 'ViewClass ' . rand(1000, 9999),
			'description' => 'desc',
			'level' => 'beginner',
			'duration_minutes' => 60,
			'created_at' => now(),
			'updated_at' => now(),
		]);

		$userId = DB::table('users')->insertGetId([
			'name' => 'view_coach_' . rand(1000, 9999),
			'phone_number' => '+6281234567890',
			'email' => null,
			'password_hash' => Hash::make('Secret1'),
			'role' => 'coach',
			'status' => 'active',
			'created_at' => now(),
			'updated_at' => now(),
		]);

		$coachId = DB::table('coaches')->insertGetId([
			'user_id' => $userId,
			'specialization' => 'spec',
			'rate_per_class' => 70000,
			'years_experience' => 2,
			'created_at' => now(),
		]);

		$scheduleId = DB::table('schedules')->insertGetId([
			'class_id' => $classId,
			'coach_id' => $coachId,
			'schedule_date' => Carbon::now()->addDay()->toDateString(),
			'start_time' => '14:00',
			'end_time' => '15:30',
			'capacity' => 20,
			'available_slots' => 20,
			'status' => 'upcoming',
			'created_at' => now(),
		]);

		$response = $this->withSession(['user_id' => 1, 'user_role' => 'admin'])
			->get('/admin/schedules/' . $scheduleId . '/view');

		$response->assertStatus(200);
		$response->assertSee('ViewClass');
		$response->assertSee('view_coach_');
		$response->assertSee('14:00');

		// cleanup
		DB::table('schedules')->where('schedule_id', $scheduleId)->delete();
		DB::table('coaches')->where('coach_id', $coachId)->delete();
		DB::table('users')->where('user_id', $userId)->delete();
		DB::table('classes')->where('class_id', $classId)->delete();
	}

	public function test_hapus_jadwal_requires_confirmation(): void
	{
		// schedule without bookings can be deleted
		$classId = DB::table('classes')->insertGetId([
			'class_name' => 'DelClass ' . rand(1000, 9999),
			'description' => 'desc',
			'level' => 'beginner',
			'duration_minutes' => 60,
			'created_at' => now(),
			'updated_at' => now(),
		]);

		$userId = DB::table('users')->insertGetId([
			'name' => 'del_coach_' . rand(1000, 9999),
			'phone_number' => '+6281234567890',
			'email' => null,
			'password_hash' => Hash::make('Secret1'),
			'role' => 'coach',
			'status' => 'active',
			'created_at' => now(),
			'updated_at' => now(),
		]);

		$coachId = DB::table('coaches')->insertGetId([
			'user_id' => $userId,
			'specialization' => 'spec',
			'rate_per_class' => 50000,
			'years_experience' => 1,
			'created_at' => now(),
		]);

		$scheduleId = DB::table('schedules')->insertGetId([
			'class_id' => $classId,
			'coach_id' => $coachId,
			'schedule_date' => Carbon::now()->addDay()->toDateString(),
			'start_time' => '08:00',
			'end_time' => '09:00',
			'capacity' => 5,
			'available_slots' => 5,
			'status' => 'upcoming',
			'created_at' => now(),
		]);

		$resp = $this->withSession(['user_id' => 1, 'user_role' => 'admin'])
			->delete('/admin/schedules/' . $scheduleId);

		$resp->assertRedirect('/admin/dashboard');

		// schedule with bookings cannot be deleted
		// create another schedule
		$scheduleId2 = DB::table('schedules')->insertGetId([
			'class_id' => $classId,
			'coach_id' => $coachId,
			'schedule_date' => Carbon::now()->addDay()->toDateString(),
			'start_time' => '12:00',
			'end_time' => '13:00',
			'capacity' => 5,
			'available_slots' => 5,
			'status' => 'upcoming',
			'created_at' => now(),
		]);

		// booking
		$userCust = DB::table('users')->insertGetId([
			'name' => 'walkin_' . rand(1000,9999),
			'phone_number' => '+628111111111',
			'email' => null,
			'password_hash' => Hash::make('pw'),
			'role' => 'customer',
			'status' => 'active',
			'created_at' => now(),
			'updated_at' => now(),
		]);

		$bookingId = DB::table('bookings')->insertGetId([
			'user_id' => $userCust,
			'schedule_id' => $scheduleId2,
			'booking_date' => now(),
			'status' => 'confirmed',
			'created_at' => now(),
			'updated_at' => now(),
		]);

		$resp2 = $this->withSession(['user_id' => 1, 'user_role' => 'admin'])
			->delete('/admin/schedules/' . $scheduleId2);

		$resp2->assertRedirect('/admin/dashboard');
		$resp2->assertSessionHasErrors();

		// cleanup
		DB::table('bookings')->where('booking_id', $bookingId)->delete();
		DB::table('users')->where('user_id', $userCust)->delete();
		DB::table('schedules')->where('schedule_id', $scheduleId2)->delete();
		DB::table('schedules')->where('schedule_id', $scheduleId)->delete();
		DB::table('coaches')->where('coach_id', $coachId)->delete();
		DB::table('users')->where('user_id', $userId)->delete();
		DB::table('classes')->where('class_id', $classId)->delete();
	}

	public function test_tambah_coach_random_and_out_of_bounds(): void
	{
		$name = 'coach_' . rand(1000, 9999);
		$payload = [
			'name' => $name,
			'phone' => '81234567890',
			'password' => 'Secret1',
			'specialization' => 'yoga',
			'rate_per_class' => 80000,
			'years_experience' => 3,
		];

		$response = $this->withSession(['user_id' => 1, 'user_role' => 'admin'])
			->post('/admin/coaches', $payload);

		$response->assertRedirect('/admin/coaches');
		$this->assertDatabaseHas('users', ['name' => $name, 'role' => 'coach']);

		// invalid phone
		$bad = $payload; $bad['phone'] = '12';
		$resp2 = $this->withSession(['user_id' => 1, 'user_role' => 'admin'])
			->post('/admin/coaches', $bad);
		$resp2->assertSessionHasErrors();

		// cleanup
		DB::table('coaches')->whereExists(function($q) use ($name) {
			$q->select(DB::raw(1))->from('users')->whereRaw('users.user_id = coaches.user_id')->where('users.name', $name);
		})->delete();
		DB::table('users')->where('name', $name)->delete();
	}

	public function test_login_with_new_coach_credentials(): void
	{
		// create coach
		$name = 'login_coach_' . rand(1000,9999);
		$password = 'Secret1';
		$userId = DB::table('users')->insertGetId([
			'username' => $name,
			'name' => $name,
			'phone_number' => '+6281234567890',
			'email' => null,
			'password_hash' => Hash::make($password),
			'role' => 'coach',
			'status' => 'active',
			'created_at' => now(),
			'updated_at' => now(),
		]);

		DB::table('coaches')->insert([
			'user_id' => $userId,
			'specialization' => 'spec',
			'rate_per_class' => 50000,
			'years_experience' => 1,
			'created_at' => now(),
		]);

		$resp = $this->post('/login', ['username' => $name . '@coach.com', 'password' => $password]);

		$resp->assertRedirect('/coach/dashboard');

		DB::table('coaches')->whereExists(function($q) use ($name) {
			$q->select(DB::raw(1))->from('users')->whereRaw('users.user_id = coaches.user_id')->where('users.name', $name);
		})->delete();
		DB::table('users')->where('name', $name)->delete();
	}

	public function test_tambah_kelas_with_new_coach_and_nonactive_check(): void
	{
		// create coach and class and schedule
		$name = 'nonactive_coach_' . rand(1000,9999);
		$userId = DB::table('users')->insertGetId([
			'username' => $name,
			'name' => $name,
			'phone_number' => '+6281234567890',
			'email' => null,
			'password_hash' => Hash::make('pw'),
			'role' => 'coach',
			'status' => 'active',
			'created_at' => now(),
			'updated_at' => now(),
		]);

		$coachId = DB::table('coaches')->insertGetId([
			'user_id' => $userId,
			'specialization' => 'spec',
			'rate_per_class' => 50000,
			'years_experience' => 1,
			'created_at' => now(),
		]);

		$classId = DB::table('classes')->insertGetId([
			'class_name' => 'CoachClass ' . rand(1000,9999),
			'description' => 'desc',
			'level' => 'beginner',
			'duration_minutes' => 60,
			'created_at' => now(),
			'updated_at' => now(),
		]);

		$scheduleId = DB::table('schedules')->insertGetId([
			'class_id' => $classId,
			'coach_id' => $coachId,
			'schedule_date' => Carbon::now()->addDay()->toDateString(),
			'start_time' => '10:00',
			'end_time' => '11:00',
			'capacity' => 10,
			'available_slots' => 10,
			'status' => 'upcoming',
			'created_at' => now(),
		]);

		$inactiveName = 'inactive_coach_' . rand(1000,9999);
		$inactiveUserId = DB::table('users')->insertGetId([
			'username' => $inactiveName,
			'name' => $inactiveName,
			'phone_number' => '+6281234567890',
			'email' => null,
			'password_hash' => Hash::make('pw'),
			'role' => 'coach',
			'status' => 'inactive',
			'created_at' => now(),
			'updated_at' => now(),
		]);

		DB::table('coaches')->insert([
			'user_id' => $inactiveUserId,
			'specialization' => 'spec',
			'rate_per_class' => 50000,
			'years_experience' => 1,
			'created_at' => now(),
		]);

		// as admin, view the schedule form and ensure inactive coaches are filtered out
		$response = $this->withSession(['user_id' => 1, 'user_role' => 'admin'])
			->get('/admin/schedules');

		$response->assertStatus(200);
		$html = $response->getContent();
		preg_match('/<select name="coach_id">(.*?)<\/select>/s', $html, $matches);
		$coachSelect = $matches[1] ?? '';

		$this->assertStringContainsString($name, $coachSelect);
		$this->assertStringNotContainsString($inactiveName, $coachSelect);

		// cleanup
		DB::table('schedules')->where('schedule_id', $scheduleId)->delete();
		DB::table('classes')->where('class_id', $classId)->delete();
		DB::table('coaches')->where('coach_id', $coachId)->delete();
		DB::table('users')->where('user_id', $userId)->delete();
		DB::table('coaches')->where('user_id', $inactiveUserId)->delete();
		DB::table('users')->where('user_id', $inactiveUserId)->delete();
	}

	public function test_keuangan_calendar_filter_by_date(): void
	{
		// create schedule, booking, transaction in specific date
		$classId = DB::table('classes')->insertGetId([
			'class_name' => 'FinanceClass ' . rand(1000,9999),
			'description' => 'desc',
			'level' => 'beginner',
			'duration_minutes' => 60,
			'created_at' => now(),
			'updated_at' => now(),
		]);

		$userId = DB::table('users')->insertGetId([
			'name' => 'finance_coach_' . rand(1000,9999),
			'phone_number' => '+6281234567890',
			'email' => null,
			'password_hash' => Hash::make('Secret1'),
			'role' => 'coach',
			'status' => 'active',
			'created_at' => now(),
			'updated_at' => now(),
		]);

		$coachId = DB::table('coaches')->insertGetId([
			'user_id' => $userId,
			'specialization' => 'spec',
			'rate_per_class' => 50000,
			'years_experience' => 1,
			'created_at' => now(),
		]);

		$date = Carbon::now()->subDays(3)->toDateString();

		$scheduleId = DB::table('schedules')->insertGetId([
			'class_id' => $classId,
			'coach_id' => $coachId,
			'schedule_date' => $date,
			'start_time' => '10:00',
			'end_time' => '11:00',
			'capacity' => 10,
			'available_slots' => 9,
			'status' => 'upcoming',
			'created_at' => now(),
		]);

		$userCust = DB::table('users')->insertGetId([
			'name' => 'finance_cust_' . rand(1000,9999),
			'phone_number' => '+628111111111',
			'email' => null,
			'password_hash' => Hash::make('pw'),
			'role' => 'customer',
			'status' => 'active',
			'created_at' => now(),
			'updated_at' => now(),
		]);

		$bookingId = DB::table('bookings')->insertGetId([
			'user_id' => $userCust,
			'schedule_id' => $scheduleId,
			'booking_date' => now(),
			'status' => 'confirmed',
			'created_at' => now(),
			'updated_at' => now(),
		]);

		DB::table('transactions')->insert([
			'user_id' => $userCust,
			'booking_id' => $bookingId,
			'recorded_by' => 1,
			'amount' => 90000,
			'payment_type' => 'cash',
			'status' => 'settlement',
			'transaction_date' => now(),
			'created_at' => now(),
			'updated_at' => now(),
		]);

		$from = Carbon::now()->subDays(5)->toDateString();
		$to = Carbon::now()->toDateString();

		$response = $this->withSession(['user_id' => 1, 'user_role' => 'admin'])
			->get('/admin/keuangan?from=' . $from . '&to=' . $to);

		$response->assertStatus(200);
		$response->assertSee('FinanceClass');

		// cleanup
		DB::table('transactions')->where('amount', 90000)->delete();
		DB::table('bookings')->where('booking_id', $bookingId)->delete();
		DB::table('users')->where('user_id', $userCust)->delete();
		DB::table('schedules')->where('schedule_id', $scheduleId)->delete();
		DB::table('classes')->where('class_id', $classId)->delete();
		DB::table('coaches')->where('coach_id', $coachId)->delete();
		DB::table('users')->where('user_id', $userId)->delete();
	}

	public function test_add_and_delete_membership_in_admin_membership(): void
	{
		$payload = [
			'name' => 'PromoPack ' . rand(1000,9999),
			'quota_amount' => 5,
			'price' => 150000,
			'validity_months' => 3,
			'description' => 'desc',
			'original_price' => 200000,
		];

		$response = $this->withSession(['user_id' => 1, 'user_role' => 'admin'])
			->post('/admin/membership', $payload);

		$response->assertRedirect('/admin/membership');

		$package = DB::table('membership_packages')->where('name', $payload['name'])->first();
		$this->assertNotNull($package);

		// delete
		$resp2 = $this->withSession(['user_id' => 1, 'user_role' => 'admin'])
			->delete('/admin/membership/' . $package->package_id);

		$resp2->assertRedirect('/admin/membership');
		$this->assertDatabaseMissing('membership_packages', ['name' => $payload['name']]);
	}
}
