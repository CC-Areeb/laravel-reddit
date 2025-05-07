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
    public function home(): View
    {
        $posts = Community::get();
        return view('reddit.index', with([
            'posts' => $posts
        ]));
    }

    public function viewSubreddit($id): View
    {
        $community = Community::with(['posts' => function ($query) {
            $query->select('id', 'title', 'post', 'community_id', 'user_id')
            ->with('user:id,name')
            ->orderBy('id', 'desc');
        }])->findOrFail($id);

        return view('reddit.single_subreddit', [
            'community' => $community,
            'posts' => $community->posts,
        ]);
    }

    public function createSubreddit(): RedirectResponse|View
    {
        if (Auth::user()) {
            return view('reddit.subreddit_create');
        } else {
            return redirect()->route('home');
        }
    }

    // Create a subreddit
    public function storeSubreddit(CommunityStoreRequest $request)
    {
        $user = Auth::user();
        $rules = explode(',', $request->rules);
        $banners = $request->input('banner');
        $themes = $request->input('theme');
        $bannerThemeData = [];
        foreach ($banners as $key => $banner) {
            $bannerThemeData[$banner] = $themes[$key];
        }
        $sub_reddit = Community::create([
            'name' => $request->name,
            'description' => $request->description,
            'banner_theme' => json_encode($bannerThemeData),
            'rules' => json_encode(array_map('trim', $rules)),
            'type' => $request->type,
            'creator_id' => $user->id,
        ]);

        // add information in pivot table
        CommunityUsers::create([
            'community_id' => $sub_reddit->id,
            'user_id' => $user->id,
            'community_moderator' => 1
        ]);

        return redirect()->route('view.single.subreddit', ['id' => $sub_reddit->id])
            ->with('success', 'You have successfully created r/' . $sub_reddit->name);
    }

    // make a reddit post
    public function redditPost(Request $request) {
        $subreddit = Community::findOrFail($request->community_id);
        $post = '';
        if ($request->filled('text_post')) {
            $post = $request->text_post;
        } elseif ($request->filled('file_post')) {
            $post = $request->file_post;
        } elseif ($request->filled('link_post')) {
            $post = $request->link_post;
        } elseif ($request->filled('poll_heading')) {
            // If it's a poll, extract poll data
            $pollHeading = $request->input('poll_heading');
            $pollOptions = [];

            // Loop through the options and collect them
            for ($i = 1; $i <= 5; $i++) {
                $option = $request->input('option_' . $i);
                if ($option) {
                    $pollOptions[] = $option;
                }
            }

            // Join the poll options into a single string with comma separation
            $pollOptionsString = implode(',', $pollOptions);

            // Combine the heading and options into a single string
            $post = $pollHeading . " | " . $pollOptionsString;
        }

        if ($subreddit) {
            RedditPosts::create([
                'post' => $post,
                'community_id' => $request->community_id,
                'title' => $request->title,
                'user_id' => Auth::user()->id
            ]);
        return redirect()->back()->with('success', 'Post has been made successfully!');
        } else {
            return redirect()->back()->with('error', 'Sorry but no such subreddit found!');
        }
    }
}
