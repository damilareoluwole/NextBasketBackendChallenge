<?php

namespace App\Listeners;

use App\Contract\Interface\EventInterface;
use App\Events\UserCreated;
use App\Services\BrookerService;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ProcessNotification
{
    private $brooker;
    /**
     * Create the event listener.
     */
    public function __construct(BrookerService $brooker)
    {
        $this->brooker = $brooker;
    }

    /**
     * Handle the event.
     */
    public function handle(EventInterface $event): void
    {
        try {
            $this->brooker->sendQueue('notifications', json_encode($event));
        } catch (Exception $e) {
            logger($e);
        }
    }
}
