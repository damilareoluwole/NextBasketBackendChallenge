<?php

namespace Tests\Unit;

use App\Events\UserCreated;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Event;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A test to assert user is created and event dispatched.
     */
    public function test_user_creation_and_event(): void
    {
        Event::fake();

        $userService = new UserService(); 
        $userData = User::factory()->make()->toArray();

        $user = $userService->createUser($userData);

        // Assertions
        Event::assertDispatched(UserCreated::class, function ($event) use ($user) {
            return $event->user->id === $user->id;
        });

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'firstName' => $user->firstName,
            'email' => $user->email,
            'lastName' => $user->lastName,
        ]);
    }
}
