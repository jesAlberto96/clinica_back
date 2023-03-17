<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDataBase;
use Illuminate\Support\Str;

class AuthTest extends TestCase
{

    use RefreshDataBase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_auth_success()
    {
        $user = User::factory()->create([
            'name' => 'Agent',
            'email' => 'agent@agent.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);

        $payload = ['email' => 'agent@agent.com', 'password' => 'password', 'device' => 'windows'];

        $this->json('POST', 'api/auth', $payload)
            ->assertStatus(200)
            ->assertJsonStructure([
                'meta' => [
                    'success',
                    'errors',
                ],
                'data' => [
                    'token',
                    'minutes_to_expire',
                ],
            ]);
    }
    
    public function test_auth_unauthorized()
    {
        $user = User::factory()->create([
            'name' => 'Agent',
            'email' => 'agent@agent.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);

        $payload = ['email' => 'agent@agent.com', 'password' => 'password123', 'device' => 'windows'];

        $this->json('POST', 'api/auth', $payload)
            ->assertStatus(401)
            ->assertJsonStructure([
                'meta' => [
                    'success',
                    'errors',
                ]
            ]);
    }
}
