<?php

namespace App\Services;

class FactoryService
{
    public static function createService($eventType)
    {
        switch ($eventType) {
            case 'user-created':
                return new UserService();

            default:
                throw new \InvalidArgumentException("Unsupported event type: {$eventType}");
        }
    }
}