<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    // Register a new user
    public function register(Request $request)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',              // user name is required, max 255 chars
            'email' => 'required|string|email|unique:users,email', // email must be unique
            'password' => 'required|string|min:5|confirmed'  // password with confirmation
        ]);

        // Hash the password before saving
        $validatedData['password'] = Hash::make($validatedData['password']);

        // Create the user record in the database
        $user = User::create($validatedData);

        // Return success response with created user
        return response()->json([
            'message' => 'Registration successful!',
            'user' => $user,
        ], 201); // 201 = resource created
    }

    // Login a user and create a token
    public function login(Request $request)
    {
        // Validate login credentials
        $credentials = $request->validate([
            'email' => 'required|string|email',   // must be a valid email
            'password' => 'required|string'       // password required
        ]);

        // Find the user by email
        $user = User::where('email', $credentials['email'])->first();

        // Check if user exists and password is correct
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            // Throw validation error if credentials are wrong
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Create a personal access token for the user
        $token = $user->createToken('auth-token')->plainTextToken;

        // Return user info and token in response
        return response()->json([
            'message' => 'Login successful!',
            'user' => $user,
            'token' => $token,
        ], 200); // 200 = OK
    }

    // Logout the authenticated user
    public function logout(Request $request)
    {
        // Delete the current access token
        $request->user()->currentAccessToken()->delete();

        // Return success message
        return response()->json([
            'message' => 'Logout successful!',
        ], 200);
    }
}
