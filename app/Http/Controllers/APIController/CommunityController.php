<?php

namespace App\Http\Controllers\APIController;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddUserToCommunityRequest;
use App\Http\Requests\ApplySubredditRequest;
use App\Http\Requests\CommunityStoreRequest;
use App\Http\Requests\JoinSubredditRequest;
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

            // add information in pivot table
            CommunityUsers::create([
                'community_id' => $sub_reddit->id,
                'user_id' => $user->id,
                'community_moderator' => 1
            ]);

            return response()->json([
                'message' => "Successfully created r/" . $sub_reddit->name,
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

    // Join a public subreddit
    public function joinSubreddit(JoinSubredditRequest $request)
    {
        $subreddit = Community::where('type', 1)->findOrFail($request->subreddit_id);

        // join the subreddit
        CommunityUsers::create([
            'community_id' => $request->subreddit_id,
            'user_id' => Auth::user()->id,
            'community_moderator' => 0
        ]);

        return response()->json([
            'message' => 'Successfully joined r/' . $subreddit->name,
            'subreddit' => $subreddit,
            'status' => 200,
        ]);
    }

    public function pendingRequests()
    {
        $user = Auth::user();
        if ($user->role_id == 1) {
            return response()->json([
                'requests' => PendingCommunityRequests::with('user')->get(),
                'status' => 200
            ]);
        } else if ($user->role_id == 2) {
            // fetch data for subreddits you mod
            $request_list = PendingCommunityRequests::with('user')
                ->join('communities', 'communities.id', '=', 'pending_community_requests.community_id')
                ->join('community_users', 'community_users.community_id', '=', 'communities.id')
                ->where('community_users.user_id', $user->id)
                ->where('community_users.community_moderator', 1)
                ->get();

            return response()->json([
                'requests' => '',
                'status' => 200
            ]);
        }
    }

    public function applyToPrivateSubreddit(ApplySubredditRequest $request)
    {
        // Create a new pending request
        $pendingRequest = PendingCommunityRequests::create([
            'community_id' => $request->community_id,
            'user_id' => $request->user_id,
            'accepted' => 0,
        ]);

        $pendingRequest->with('community')->get();

        return response()->json([
            'message' => 'Request to join subreddit has been sent',
            'applied_subreddit' => $pendingRequest,
            'status' => 200,
        ]);
    }

    // for private subreddits
    public function addUsersToCommunities(AddUserToCommunityRequest $request)
    {
        $subreddit = Community::where('type', 2)->findOrFail($request->subreddit_id);

        // Find the pending request for the specified user and community
        $pending_request = PendingCommunityRequests::with('community')->where('accepted', 0)
            ->where('community_id', $request->subreddit_id)
            ->where('user_id', $request->user_id)
            ->first();

        if (!$pending_request) {
            return response()->json([
                'message' => 'No pending request found for the specified user.',
                'status' => 404,
            ]);
        }

        $pending_request->update([
            'accepted' => $request->is_accepted ? 1 : 0,
        ]);

        $message = $request->is_accepted
            ? 'You have been accepted to r/' . $pending_request->name
            : 'Your application has been rejected.';

        return response()->json([
            'message' => $message,
            'status' => 200,
        ]);
    }
}
