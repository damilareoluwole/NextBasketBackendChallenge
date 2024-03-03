<?php

namespace App\Console\Commands;

use App\Services\BrookerService;
use App\Services\FactoryService;
use App\Services\UserService;
use Illuminate\Console\Command;
use Stomp\Transport\Frame;

class QueueNotificationListener extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Queue-Notification:Listener';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Listen to user creation event';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $broker = new BrookerService();

        $this->comment('Connected to broker, listening for messages...');

        $broker->subscribeQueue('notifications');

        while (true) {

            $message = $broker->read();
            if ($message instanceof Frame) {
                if ($message['type'] === 'terminate') {
                    $this->comment('Received shutdown command');
                    return Command::SUCCESS;
                }

                $this->info("Message received, check today's log");

                $res = json_decode($message->getBody());

                try {
                    $service = FactoryService::createService($res->event);
                    $service->handle($res);
                } catch (\InvalidArgumentException $exception) {
                    logger($exception);
                }

                $broker->ack($message);
            }

            usleep(100000);
        }
    }
}
