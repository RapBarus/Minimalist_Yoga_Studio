<?php

namespace Tests\Unit;

use Tests\TestCase;

class RegisterTest extends TestCase
{
    //test register page can be rendered
	public function test_register_page_can_be_rendered(): void
	{
		$response = $this->get('/register');

		$response->assertStatus(200);
		$response->assertViewIs('auth.register');
	}

    //test register requires username, phone, and password (valid)
	public function test_register_requires_required_fields(): void
	{
		$response = $this->from('/register')->post('/register', []);

		$response->assertRedirect('/register');
		$response->assertSessionHasErrors(['username', 'phone', 'password']);
	}


	public function test_register_requires_minimum_password_length(): void
	{
		$payload = [
			'username' => 'newuser',
			'phone' => '08123456789',
			'password' => '12345',
		];

		$response = $this->from('/register')->post('/register', $payload);

		$response->assertRedirect('/register');
		$response->assertSessionHasErrors(['password']);
	}

    public function test_register_rejects_invalid_phone_format(): void
    {
        $payload = [
            'username' => 'newuser',
            'phone' => 'invalid-phone',
            'password' => 'secret123',
        ];

        $response = $this->from('/register')->post('/register', $payload);
        
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['phone']);
    }
}