<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|min:8|max:32|confirmed',
        ]);
        $user = User::create($validated);

        return response()->json([
            'data' => $user,
            'token' => $user->createToken('api_token')->plainTextToken,
            'token_type' => 'Bearer'
        ], 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|min:8|max:32',
        ]);
        // checks if user data[email and password] are correct
        if (!Auth::attempt($validated)) {
            return response()->json('Invalid Login Informations!', 401);
        }
        // fetch all user data
        $user = User::where('email', $validated['email'])->first();

        return response()->json([
            'data' => $user,
            'token' => $user->createToken('api_token')->plainTextToken,
            'token_type' => 'Bearer'
        ], 201);
    }
}
