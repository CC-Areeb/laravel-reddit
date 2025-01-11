<?php

namespace App\Http\Controllers\APIController;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // show all users
    function showUsers(Request $request)    {
        $user = Auth::user();
        $existing_user = User::where('id', $user->id);

        if ($user && $existing_user->exists()) {
            if ($user->role_id == 1) {
                return response()->json([
                    'data' => User::get(),
                    'status' => 200 
                ]);
            }

            else if ($user->role_id == 2) {
                // see users of your community
            }  
        } 
        
        else {
            return response()->json([
                'message' => 'Please login first!',
                'status' => 400,
            ]);
        }


    }
}
