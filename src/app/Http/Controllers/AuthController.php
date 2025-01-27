<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validate input data
        $data = $request->validate([
            'name' => ['required', 'string'],
            'studentId' => ['required', 'string', 'unique:users'],
            'isAdmin' => ['required', 'integer'],
            'password' => ['required', 'min:8']
        ]);

        // Create a new user
        $user = User::create([
            'name' => $data['name'],
            'studentId' => $data['studentId'],
            'isAdmin' => $data['isAdmin'],
            'password' => Hash::make($data['password'])
        ]);

        // Generate an API token for the user
        $token = $user->createToken('auth_token')->plainTextToken;

        // Return the user and token
        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function login(Request $request)
    {
        try {
            // Validate input data
            $data = $request->validate([
                'studentId' => ['required', 'string', 'exists:users'],
                'password' => ['required']
            ]);

            // Retrieve the user based on the studentId
            $user = User::where('studentId', $data['studentId'])->first();

            // Check if user exists and if password matches
            if (!$user || !Hash::check($data['password'], $user->password)) {
                return response()->json([
                    'message' => 'Bad credentials'
                ], 401);
            }

            // Generate the authentication token
            $token = $user->createToken('auth_token')->plainTextToken;

            // Return the user, token, and isAdmin status in the response
            return response()->json([
                'user' => $user,
                'token' => $token,
                'isAdmin' => $user->isAdmin
            ]);

        } catch (\Exception $e) {
            // If there's an exception, return it as a JSON response
            return response()->json([
                'message' => 'An error occurred.',
                'error' => $e->getMessage()
            ], 500);  // Return a 500 Internal Server Error status code
        }
    }

}
