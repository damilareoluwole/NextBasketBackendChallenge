<?php

namespace App\Services;

use App\Events\UserCreated;
use App\Models\User;
use Illuminate\Support\Facades\Event;

class UserService
{
    public function createUser(array $data): User
    {
        $user = User::create($data);
        Event::dispatch(new UserCreated($user));
        return $user;
    }
}