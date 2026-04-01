<?php

namespace Tests\Unit;

use Tests\TestCase;

class AdminTest extends TestCase
{
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
}
