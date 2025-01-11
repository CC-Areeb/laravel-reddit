<?php

namespace App\Http\Controllers\APIController;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommunityStoreRequest;
use App\Models\Community;
use App\Models\CommunityUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunityController extends Controller
{
    // show subreddits
    public function showCommunities(){
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
    public function storeCommunity(CommunityStoreRequest $request) {
        $user = Auth::user();
        if ($user){
            $sub_reddit = Community::create([
                'name' => $request->name,
                'description' => $request->description,
                'banner' => $request->banner,
                'rules' => $request->rules,
                'theme' => $request->theme,
                'type' => $request->type,
                'creator_id' => $user->id,
            ]);
            $sub_reddit->users()->attach($request->user_ids);
    
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
    public function updateModeratorStatus(Request $request) {
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
    
}
