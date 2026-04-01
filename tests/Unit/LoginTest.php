<?php

namespace Tests\Unit;

use Tests\TestCase;

class LoginTest extends TestCase
{
	public function test_login_page_can_be_rendered(): void
	{
		$response = $this->get('/login');

		$response->assertStatus(200);
		$response->assertViewIs('auth.login');
	}

	public function test_login_requires_username_and_password(): void
	{
		$response = $this->from('/login')->post('/login', []);

		$response->assertRedirect('/login');
		$response->assertSessionHasErrors(['username', 'password']);
	}

	public function test_login_rejects_invalid_email_format_for_staff_login(): void
	{
		$payload = [
			'username' => 'someone@gmail.com',
			'password' => 'secret123',
		];

		$response = $this->from('/login')->post('/login', $payload);

		$response->assertRedirect('/login');
		$response->assertSessionHasErrors(['username']);
	}

	public function test_logout_flushes_session_and_redirects_to_welcome(): void
	{
		$response = $this->withSession([
			'user_id' => 1,
			'user_name' => 'tester',
			'user_role' => 'customer',
		])->post('/logout');

		$response->assertRedirect('/');
		$this->assertFalse(session()->has('user_id'));
		$this->assertFalse(session()->has('user_name'));
		$this->assertFalse(session()->has('user_role'));
	}
}
