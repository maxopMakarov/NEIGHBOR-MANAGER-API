<?php

namespace App\Http\Controllers;

use App\Domain\Commands\CreateUserCommand;
use App\Domain\Queries\GetUserByIdQuery;
use App\Domain\Commands\Handlers\CreateUserHandler;
use App\Domain\Queries\Handlers\GetUserByIdHandler;
use App\Http\Controllers\Requests\CreateUserRequest;
use App\Http\Controllers\Requests\LoginUserRequest;
use App\Http\Controllers\Requests\RegisterUserMetamaskRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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

    public function loginWeb3(RegisterUserMetamaskRequest $request, CreateUserHandler $handler, GetUserByIdHandler $getUserHandler) 
    {

        $credentials = $request->validated();
        $credentials['password'] = null;
        $credentials['name'] = Str::random(10);
        $credentials['email'] = Str::random(10).'@example.com';

        $query = new GetUserByIdQuery(null, $credentials['eth_address'], $credentials['message'], $credentials['signature']);
        $user = $getUserHandler($query);
        
        if(empty($user)) {
            $command = new CreateUserCommand(...$credentials);
            $user = $handler($command);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return response()->json(['message' => 'User Signed Up', 'user' => $user]);
    }
    
    public function register(CreateUserRequest $request, CreateUserHandler $handler)
    {
        $command = new CreateUserCommand(...$request->validated());
        $user = $handler($command);
        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
