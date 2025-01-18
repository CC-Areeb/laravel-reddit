<?php

namespace App\Http\Controllers\Reddit;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\RedditPosts;
use Illuminate\Http\Request;

class RedditController extends Controller
{
    // home page
    public function home() {

        $posts = Community::get();
        return view('reddit.index', with([
            'posts' => $posts
        ]));
    }

    public function contact() {
        return view('reddit.contact');
    }

    public function viewSubreddit($id) {
        return view('reddit.single_subreddit', with([
            'community' => Community::findOrFail($id)
        ]));
    }

    // make a reddit post
    public function redditPost(Request $request) {
        dd(
            $request->all()
        );
        $subreddit = Community::findOrFail($request->id);
        if ($subreddit) {
            $post = RedditPosts::create([
                'post' => $request->post
            ]);
        }
    }
}
