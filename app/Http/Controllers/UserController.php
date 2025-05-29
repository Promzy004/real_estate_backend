<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    //

    public function register(Request $request) {
        try {
            $validated = $request->validate([
                'firstname' => 'required|string|max:50|min:3',
                'lastname' => 'required|string|max:50|min:3',
                'email' => 'required|email|unique:users,email',
                'phone_number' => 'required|string',
                'role' => 'required|in:agent,buyer,admin',
                'password' => 'required|string|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@,%$#:])[a-zA-Z0-9@,%$#:]{8,}$/i',
            ]);
            
            User::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Registered successfully'
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
                'errors' => $e->errors()
            ], 500);
        }
    }

    public function login(Request $request) {
        $info = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
    }
}
