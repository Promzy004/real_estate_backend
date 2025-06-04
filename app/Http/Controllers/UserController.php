<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

            $verification_code = fake()->numberBetween(100000,999999);
            $validated['verification_code'] = $verification_code;
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

    public function verify(Request $request) {
        $validate = $request->validate([
            'otp' => 'required|integer',
        ]);
        $otp = $request->input('otp');
        $email = $request->input('email');

        try{
            $user = User::where('email', $email)->first();
            if($otp == $user->verification_code){
                $user->update([
                    'email_verified_at' => now(),
                    'verification_code' => null
                ]);

                return response()->json([
                    'message' => 'Successfully verified'
                ], 200);
            } else if ($otp !== $user->verification_code) {
                return response()->json([
                    'message' => 'Invalid code entered'
                ], 400);
            }
        } catch(\Exception $error) {
            return response()->json([
                'errors' => $error
            ]);
        }
    }

    public function login(Request $request) {
        $info = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if(Auth::attempt($info)){
            $user = User::where('email', $request->email)->first();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'user' => $user,
                'message' => 'Logged in succefully'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Wrong credentials entered'
            ], 500);
        }

    }
}
