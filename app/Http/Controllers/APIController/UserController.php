<?php

namespace App\Http\Controllers\APIController;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    // show all users
    function showUsers(Request $request)
    {
        $user = Auth::user();
        $existing_user = User::where('id', $user->id);

        if ($user && $existing_user->exists()) {
            if ($user->role_id == 1) {
                return response()->json([
                    'data' => User::get(),
                    'status' => 200
                ]);
            } else if ($user->role_id == 2) {
                // Fetch communities the user moderates
                $data = DB::table('community_users')
                    ->join('users', 'users.id', 'community_users.user_id')
                    ->get();

                return response()->json([
                    'data' => $data,
                    'status' => 200
                ]);
            }
        } else {
            return response()->json([
                'message' => 'Please login first!',
                'status' => 400,
            ]);
        }
    }
}
