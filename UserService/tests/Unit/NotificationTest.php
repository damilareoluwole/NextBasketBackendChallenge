<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Events\UserCreated;
use App\Listeners\ProcessNotification;
use App\Models\User;
use App\Services\BrookerService;
use Illuminate\Support\Facades\Event;
use Mockery;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A test to ensure notification is sent via broker when a user is created and an event dispatched.
    */
    public function test_broker_received_event_when_listener_is_triggered(): void
    {
        // Mock the BrookerService
        $brookerServiceMock = Mockery::mock(BrookerService::class);
        $this->app->instance(BrookerService::class, $brookerServiceMock);

        // Disable event listeners during the test
        Event::fake();

        // Create a UserCreated event
        $user = User::factory()->create();

        $event = new UserCreated($user);

        // Create an instance of the ProcessNotification listener
        $listener = new ProcessNotification($brookerServiceMock);

        // Act: Handle the event
        $listener->handle($event);

        // Assert: Verify that the BrookerService's sendQueue method was called with the expected arguments
        $brookerServiceMock->shouldHaveReceived('sendQueue')->with('notifications', json_encode($event))->once();
    }
}
