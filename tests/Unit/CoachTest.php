<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Coach;
use App\Models\Schedule;
use App\Models\Booking;
use App\Models\Class as ClassModel;
use Carbon\Carbon;

class CoachTest extends TestCase
{
	private $coach;
	private $coachUser;

	protected function setUp(): void
	{
		parent::setUp();
		
		// Create a coach user for testing
		$this->coachUser = User::factory()->create(['role' => 'coach']);
		$this->coach = Coach::factory()->create(['user_id' => $this->coachUser->id]);
	}

	public function test_coach_dashboard_redirects_guest_to_login(): void
	{
		$response = $this->get('/coach/dashboard');

		$response->assertRedirect('/login');
	}

	public function test_coach_dashboard_returns_forbidden_for_non_coach_user(): void
	{
		$response = $this->withSession([
			'user_id' => 88,
			'user_role' => 'customer',
		])->get('/coach/dashboard');

		$response->assertStatus(403);
	}

	public function test_coach_dashboard_returns_forbidden_for_admin_user(): void
	{
		$response = $this->withSession([
			'user_id' => 77,
			'user_role' => 'admin',
		])->get('/coach/dashboard');

		$response->assertStatus(403);
	}

	public function test_coach_can_view_schedules(): void
	{
		// Create a schedule for this coach
		$class = ClassModel::factory()->create();
		$schedule = Schedule::factory()->create([
			'coach_id' => $this->coach->id,
			'class_id' => $class->id,
			'schedule_date' => now()->addDays(5)->toDateString(),
		]);

		$response = $this->withSession([
			'user_id' => $this->coachUser->id,
			'user_role' => 'coach',
		])->get('/coach/dashboard');

		$response->assertStatus(200);
		$response->assertViewHas('schedules');
	}

	public function test_minggu_jadwal_filter_shows_only_this_weeks_schedule(): void
	{
		$class = ClassModel::factory()->create();
		
		// Create schedules for different time periods
		$thisWeekSchedule = Schedule::factory()->create([
			'coach_id' => $this->coach->id,
			'class_id' => $class->id,
			'schedule_date' => now()->addDays(2)->toDateString(),
		]);

		$nextWeekSchedule = Schedule::factory()->create([
			'coach_id' => $this->coach->id,
			'class_id' => $class->id,
			'schedule_date' => now()->addDays(10)->toDateString(),
		]);

		$response = $this->withSession([
			'user_id' => $this->coachUser->id,
			'user_role' => 'coach',
		])->get('/coach/dashboard?filter=week');

		$response->assertStatus(200);
		$schedules = $response->viewData('schedules');
		
		// Verify only this week's schedule is shown
		$this->assertTrue($schedules->contains('id', $thisWeekSchedule->id));
		$this->assertFalse($schedules->contains('id', $nextWeekSchedule->id));
	}

	public function test_bulan_ini_filter_shows_only_this_months_schedule(): void
	{
		$class = ClassModel::factory()->create();
		
		// Create schedules for different months
		$thisMonthSchedule = Schedule::factory()->create([
			'coach_id' => $this->coach->id,
			'class_id' => $class->id,
			'schedule_date' => now()->addDays(5)->toDateString(),
		]);

		$nextMonthSchedule = Schedule::factory()->create([
			'coach_id' => $this->coach->id,
			'class_id' => $class->id,
			'schedule_date' => now()->addMonth()->toDateString(),
		]);

		$response = $this->withSession([
			'user_id' => $this->coachUser->id,
			'user_role' => 'coach',
		])->get('/coach/dashboard?filter=month');

		$response->assertStatus(200);
		$schedules = $response->viewData('schedules');
		
		// Verify only this month's schedule is shown
		$this->assertTrue($schedules->contains('id', $thisMonthSchedule->id));
		$this->assertFalse($schedules->contains('id', $nextMonthSchedule->id));
	}

	public function test_coach_can_view_schedule_details_and_attendance(): void
	{
		$class = ClassModel::factory()->create();
		$schedule = Schedule::factory()->create([
			'coach_id' => $this->coach->id,
			'class_id' => $class->id,
			'schedule_date' => now()->toDateString(),
			'status' => 'upcoming',
		]);

		// Create some bookings for this schedule
		$customer1 = User::factory()->create(['role' => 'customer']);
		$customer2 = User::factory()->create(['role' => 'customer']);

		Booking::factory()->create([
			'user_id' => $customer1->id,
			'schedule_id' => $schedule->id,
			'status' => 'confirmed',
		]);

		Booking::factory()->create([
			'user_id' => $customer2->id,
			'schedule_id' => $schedule->id,
			'status' => 'confirmed',
		]);

		$response = $this->withSession([
			'user_id' => $this->coachUser->id,
			'user_role' => 'coach',
		])->get("/coach/schedule/{$schedule->id}");

		$response->assertStatus(200);
		$response->assertViewHas('schedule', $schedule);
		$response->assertViewHas('bookings');
	}

	public function test_coach_can_mark_attendance(): void
	{
		$class = ClassModel::factory()->create();
		$schedule = Schedule::factory()->create([
			'coach_id' => $this->coach->id,
			'class_id' => $class->id,
			'schedule_date' => now()->toDateString(),
			'status' => 'upcoming',
		]);

		$customer = User::factory()->create(['role' => 'customer']);
		$booking = Booking::factory()->create([
			'user_id' => $customer->id,
			'schedule_id' => $schedule->id,
			'status' => 'confirmed',
		]);

		$response = $this->withSession([
			'user_id' => $this->coachUser->id,
			'user_role' => 'coach',
		])->post("/coach/schedule/{$schedule->id}/update", [
			'attendees' => [$booking->id => 'attended'],
			'absences' => [],
		]);

		$response->assertRedirect();
		$this->assertDatabaseHas('bookings', [
			'id' => $booking->id,
			'status' => 'attended',
		]);
	}

	public function test_coach_can_mark_absence(): void
	{
		$class = ClassModel::factory()->create();
		$schedule = Schedule::factory()->create([
			'coach_id' => $this->coach->id,
			'class_id' => $class->id,
			'schedule_date' => now()->toDateString(),
			'status' => 'upcoming',
		]);

		$customer = User::factory()->create(['role' => 'customer']);
		$booking = Booking::factory()->create([
			'user_id' => $customer->id,
			'schedule_id' => $schedule->id,
			'status' => 'confirmed',
		]);

		$response = $this->withSession([
			'user_id' => $this->coachUser->id,
			'user_role' => 'coach',
		])->post("/coach/schedule/{$schedule->id}/update", [
			'attendees' => [],
			'absences' => [$booking->id],
		]);

		$response->assertRedirect();
		$this->assertDatabaseHas('bookings', [
			'id' => $booking->id,
			'status' => 'confirmed', // Status remains confirmed but marked as absent
		]);
	}

	public function test_marking_attendance_updates_schedule_status_to_completed(): void
	{
		$class = ClassModel::factory()->create();
		$schedule = Schedule::factory()->create([
			'coach_id' => $this->coach->id,
			'class_id' => $class->id,
			'schedule_date' => now()->toDateString(),
			'status' => 'upcoming',
		]);

		$customer = User::factory()->create(['role' => 'customer']);
		Booking::factory()->create([
			'user_id' => $customer->id,
			'schedule_id' => $schedule->id,
			'status' => 'confirmed',
		]);

		$response = $this->withSession([
			'user_id' => $this->coachUser->id,
			'user_role' => 'coach',
		])->post("/coach/schedule/{$schedule->id}/update", [
			'attendees' => [$customer->id => 'attended'],
		]);

		$response->assertRedirect();
		$this->assertDatabaseHas('schedules', [
			'id' => $schedule->id,
			'status' => 'completed',
		]);
	}
}
