<?php

namespace App\Http\Controllers\APIController;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    public function store(RegisterRequest $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 3
        ]);

        return response()->json([
            'message' => "User registered successfully",
            'status' => 200,
        ]);
    }

    public function login(LoginRequest $request)
    {
        $email = $request->email;
        $password = $request->password;
        $message = "";
        $status = "";
        $token = "";
    
        // Empty form check
        if (!$email || !$password) {
            $message = "Email and Password are required fields!";
            $status = 400;
            return response()->json([
                'message' => $message,
                'status' => $status,
                'token' => $token
            ]);
        }
    
        // Check if the user already has an active token
        $user = User::where('email', $email)->first();
        if ($user && $user->tokens->count() > 0) {
            // If user already has an active token
            $message = "You are already logged in.";
            $status = 400;
            return response()->json([
                'message' => $message,
                'status' => $status,
                'token' => $token
            ]);
        }
    
        // Check credentials
        if (!$user || !Hash::check($password, $user->password)) {
            $message = "Invalid credentials!";
            $status = 400;
            return response()->json([
                'message' => $message,
                'status' => $status,
                'token' => $token
            ]);
        }
    
        // Successful login
        $message = "Welcome " . $user->name . "!";
        $status = 200;
        $token = $user->createToken('auth_token')->plainTextToken;
    
        return response()->json([
            'message' => $message,
            'user_info' => $user,
            'status' => $status,
            'token' => $token
        ]);
    }
    

    public function logout(Request $request)
    {
        $accessToken = $request->user()->currentAccessToken();
    
        if (!$accessToken) {
            return response()->json([
                'message' => 'You are already logged out.',
                'status' => 200,
            ]);
        }
    
        // Revoke the current access token
        $accessToken->delete();
        return response()->json([
            'message' => 'Logged out successfully.',
            'status' => 200,
        ]);
    }

    function change_password(Request $request)
    {
        $old_password = $request->old_password;
        $new_password = $request->new_password;
        $user = Auth::user();

        // Check if the user exists
        if (!$user) {
            return response()->json([
                'message' => "User not found",
                'status' => 404,
            ]);
        }

        // Check if the old password matches the stored password
        if (!Hash::check($old_password, hashedValue: $user->password)) {
            return response()->json([
                'message' => "Old password is incorrect",
                'status' => 400,
            ]);
        }

        if ($old_password == $new_password)
        {
            return response()->json([
                'message' => "New password cannot be same as old password",
                'status' => 400,
            ]);
        }
        
        else {
            $user->update([
                'password' => Hash::make($new_password)
            ]);
            return response()->json([
                'message' => 'Password has been updated successfully!',
                'status' => 200
            ]);
        }
    }

    function forgot_password(Request $request)
    {
        $auth_user = User::where('email', $request->email)->first();
        if (!$auth_user)
        {
            return response()->json([
                'message' => 'No user with given email present!',
                'status' => 400,
            ]);
        } else {
            $response = Password::sendResetLink(
                ['email' => $request->email]
            );
            if ($response == Password::RESET_LINK_SENT) {
                return response()->json([
                    'message' => 'Password reset link has been sent to your email.',
                    'status' => 200,
                ]);
            } else {
                return response()->json([
                    'message' => 'Failed to send password reset link.',
                    'status' => 400,
                ]);
            }
        }
    }
}