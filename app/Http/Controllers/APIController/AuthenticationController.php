<?php

namespace App\Http\Controllers\APIController;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    public function store(RegisterRequest $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => "User registered successfully",
            'status' => 200,
        ]);
    }

    public function login(LoginRequest $request)
    {
        // Login after registering
        $email = $request->email;
        $password = $request->password;
        $message = "";
        $status = 200;
        $token = "";

        // empty form
        if (!$email || !$password) {
            $message = "Email and Password are required fields!";
            $status = 400;
        }

        // incorrect data
        $user = User::where('email', $email)->first();
        if (!$user || !Hash::check($password, $user->password))
        {
            $message = "Invalid credentials!";
            $status = 400;
        }

        else {
            $message = "Welcome!";
            $status = 200;
            $token = $user->createToken('auth_token')->plainTextToken;
        }

        return response()->json([
            'message' => $message,
            'status' => $status,
            'token' => $token
        ]);
    }
}