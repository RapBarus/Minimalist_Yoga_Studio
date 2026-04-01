<?php

namespace Tests\Unit;

use Tests\TestCase;

class CoachTest extends TestCase
{
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
}
