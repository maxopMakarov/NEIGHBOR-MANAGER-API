<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function login(Request $request) 
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if(! Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid login details'], 401);
        }
        $request->session()->regenerate();

        return response()->json(['message' => 'Login successful']);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:8',
        ]);

        
    }
}
