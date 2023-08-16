<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    /**
     * Test registering a new user.
     *
     * @return void
     */
    public function testRegistration()
    {
        $data = [
            'name' => 'John Doe',
            'phone' => '48459434536',
            'password' => '123456789',
        ];

        $response = $this->json('POST', '/api/register', $data);

        $response->assertStatus(200);
        $response->assertJson([
            "success" => true,
            "message" => "Waiting for OTP Verification"
        ]);
    }
}
