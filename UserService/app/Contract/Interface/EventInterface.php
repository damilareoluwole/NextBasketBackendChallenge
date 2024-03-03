<?php

namespace App\Contract\Interface;

/**
 * This event is about a user and developer can know the user it happened to
 */
interface EventInterface
{
    public function eventName(): void;
}
