<?php

namespace App\Http\Controllers\APIController;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddUserToCommunityRequest;
use App\Http\Requests\CommunityStoreRequest;
use App\Models\Community;
use App\Models\CommunityUsers;
use App\Models\PendingCommunityRequests;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunityController extends Controller
{
    // show subreddits
    public function showCommunities()
    {
        if (count(Community::get()) > 0) {
            return response()->json([
                'data' => Community::get(),
                'status' => 200
            ]);
        } else {
            return response()->json([
                'message' => "No subreddit found",
                'status' => 400
            ]);
        }
    }

    // create subreddit
    public function storeCommunity(CommunityStoreRequest $request)
    {
        $user = Auth::user();
        if ($user) {
            $rules = explode(',', $request->rules);
            $sub_reddit = Community::create([
                'name' => $request->name,
                'description' => $request->description,
                'banner' => $request->banner,
                'rules' => json_encode(array_map('trim', $rules)),
                'theme' => $request->theme,
                'type' => $request->type,
                'creator_id' => $user->id,
            ]);
            return response()->json([
                'message' => "Subreddit created successfully!",
                'data' => $sub_reddit,
                'status' => 200
            ]);
        } else {
            return response()->json([
                'message' => "Please login first!",
                'status' => 400,
            ]);
        }
    }

    // update subreddit moderator status
    public function updateModeratorStatus(Request $request)
    {
        // Check if subreddit exists
        $sub_reddit = Community::find($request->id);
        if (!$sub_reddit) {
            return response()->json([
                'message' => 'Subreddit not found!',
                'status' => 400,
            ]);
        }

        // Check if user is part of the subreddit
        $community_member = CommunityUsers::where('user_id', Auth::user()->id)
            ->where('community_id', $request->id)
            ->first();
        if (!$community_member) {
            return response()->json([
                'message' => 'User is not part of this community!',
                'status' => 400,
            ]);
        }

        // Handle moderator status update
        if (!in_array($request->mod_status, [0, 1])) {
            return response()->json([
                'message' => 'Invalid status',
                'status' => 400,
            ]);
        }

        // Update the community moderator status
        $community_member->update([
            'community_moderator' => $request->mod_status
        ]);

        $message = $request->mod_status == 1 ? 'This user is now a moderator' : 'This user is not a moderator';

        return response()->json([
            'message' => $message,
            'status' => 200,
        ]);
    }

    public function addUsersToCommunities(AddUserToCommunityRequest $request)
    {
        $existing_subreddit = Community::findOrFail($request->subreddit_id);

        $existing_users = CommunityUsers::where('community_id', $request->subreddit_id)
            ->where('user_id', $request->user_id)
            ->leftJoin('users', 'users.id', '=', 'community_users.user_id')
            ->select(
                'users.name',
                'users.email'
            )
            ->get();

        if ($existing_users->isNotEmpty()) {
            return response()->json([
                'message' => 'User is already added to the subreddit.',
                'existing_users' => $existing_users,
                'status' => 400
            ]);
        }

        // Prepare data for bulk insert
        if ($existing_subreddit->type == 1) {
            $community_users = CommunityUsers::create([
                'community_id' => $existing_subreddit->id,
                'user_id' => $request->user_id,
                'community_moderator' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            return response()->json([
                'message' => 'Users added to the community successfully!',
                'data' => $community_users,
                'status' => 200,
            ]);
        } elseif ($existing_subreddit->type == 2){
            if (PendingCommunityRequests::where('user_id', $request->user_id)->first()){
                return response()->json([
                    'message' => 'Request is in process!',
                    'status' => 400,
                ]);
            }

            $pending_request = PendingCommunityRequests::create([
                'community_id' => $existing_subreddit->id,
                'user_id' => $request->user_id,
                'accepted' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            return response()->json([
                'message' => 'Request to join subreddit has been sent',
                'data' => $pending_request,
                'status' => 200,
            ]);
        }
    }
}
