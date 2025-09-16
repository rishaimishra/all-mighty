<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Exception;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6|confirmed'
            ]);

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            return response()->json(['message' => 'User registered successfully'], 201);
        } catch (Exception $e) {
            // Log the error
            Log::error("User registration failed: " . $e->getMessage(), [
                'email' => $request->email,
                'trace' => $e->getTraceAsString(),
            ]);

            // Return a generic error response
            return response()->json([
                'message' => 'An error occurred during registration. Please try again later.'
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $data = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string'
            ]);

            $user = User::where('email', $data['email'])->first();

            if (!$user || !Hash::check($data['password'], $user->password)) {
                // Throw a validation exception for invalid credentials
                throw ValidationException::withMessages(['email' => ['Invalid credentials']]);
            }

            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ]
            ], 200);

        } catch (ValidationException $e) {
            // This block catches the validation exception thrown for invalid credentials
            // It's good practice to log this as a warning, not a critical error, as it's
            // a common user action.
            Log::warning('Failed login attempt for email: ' . $request->email, [
                'message' => $e->getMessage(),
            ]);
            return response()->json(['message' => 'Invalid credentials'], 401);

        } catch (Exception $e) {
            // This block catches any other unexpected errors during the process
            Log::error('Login error for email: ' . $request->email, [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['message' => 'An unexpected error occurred. Please try again later.'], 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}

