<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class UserCreationTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_user_creation()
    {
        Event::fake();

        $userData = User::factory()->make()->toArray();

        $response = $this->withHeader('Accept', 'application/json')->post(route('user.store'), [
            "email" => $userData["email"],
            "firstName" => $userData["firstName"],
            "lastName" => $userData["lastName"]
        ]);

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'status',
            'statusCode',
            'message',
            'data' => [
                'user'
            ]
        ]);

        $this->assertDatabaseHas('users', [
            'firstName' => $userData["firstName"],
            'email' => $userData["email"],
            'lastName' => $userData["lastName"],
        ]);
    }
}
