<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = $this->userService->createUser($data);

        return response()->json([
            "status" => true,
            "statusCode" => "00",
            "message" => "User created successfully",
            "data" => [
                "user" => $user
            ]
        ], Response::HTTP_CREATED);
    }
}
