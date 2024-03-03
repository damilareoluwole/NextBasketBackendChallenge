<?php

namespace App\Services;
use App\Contract\Interface\ListenerServiceInterface;


class UserService implements ListenerServiceInterface
{
    public function handle($data){
        $this->logUser($data->user);
    }

    public function logUser($userData)
    {
        logger(json_encode($userData));
    }
}