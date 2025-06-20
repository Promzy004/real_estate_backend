<?php

namespace App\Http\Controllers;

use App\Mail\VerificationMail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
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
            $user = User::create($validated);
            Mail::to($user->email)->send(new VerificationMail($user));

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
    
        // $validate = $request->validate([
        //     'otp' => 'required|digits:6',
        // ]);

        $validate = Validator::make($request->all(),[
            'otp' => 'required|digits:6'
        ]);

        if($validate->fails()){
            return response()->json([
                'error' => $validate->errors()
            ], 400);
        }

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
        } catch(ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
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
            $token = $user->createToken($user->firstname . 'auth_token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'user' => $user,
                'token_type' => 'Bearer',
                'message' => 'Logged in succefully'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Wrong credentials entered'
            ], 500);
        }

    }

    public function logout(Request $request) {
        $user = User::where('id', $request->user()->id)->first();
        if($user) {
            $user->tokens()->delete();
            return response()->json([
                'message' => 'Successfully logged out',
            ], 201);
        } else {
            return response()->json([
                'message' => 'user not found'
            ], 401);
        }
    }
}
