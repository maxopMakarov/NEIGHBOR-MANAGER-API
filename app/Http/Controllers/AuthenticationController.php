<?php

namespace App\Http\Controllers;

use App\Domain\Commands\CreateUserCommand;
use App\Domain\Commands\Handlers\CreateUserHandler;
use App\Http\Controllers\Requests\CreateUserRequest;
use App\Http\Controllers\Requests\LoginUserRequest;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function login(LoginUserRequest $request) 
    {

        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();
        $request->session()->regenerate();

        return response()->json([
            'message' => 'Login successful', 
            'user' => $user
        ]);
    }
    
    public function register(CreateUserRequest $request, CreateUserHandler $handler)
    {
        $command = new CreateUserCommand(...$request->validated());
        $user = $handler($command);
        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
    }
}
