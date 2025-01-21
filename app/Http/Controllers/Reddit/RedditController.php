<?php

namespace App\Http\Controllers\Reddit;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommunityStoreRequest;
use App\Models\Community;
use App\Models\CommunityUsers;
use App\Models\RedditPosts;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RedditController extends Controller
{
    // home page
    public function home(): View {
        $posts = Community::get();
        return view('reddit.index', with([
            'posts' => $posts
        ]));
    }
    public function viewSubreddit($id): View {
        return view('reddit.single_subreddit', with([
            'community' => Community::findOrFail($id)
        ]));
    }

    public function createSubreddit(): RedirectResponse|View{
        if (Auth::user()){
            return view('reddit.subreddit_create');
        } else {
            return redirect()->route('home');
        }
    }

    // Create a subreddit
    public function storeSubreddit(CommunityStoreRequest $request) {
        dd(
            $request->all()
        );
        $user = Auth::user();
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
    }

    // Add more banners


    //make a reddit post
    // public function redditPost(Request $request) {
    //     $subreddit = Community::findOrFail($request->id);
    //     if ($subreddit) {
    //         $post = RedditPosts::create([
    //             'post' => $request->post
    //         ]);
    //     }
    // }
}
