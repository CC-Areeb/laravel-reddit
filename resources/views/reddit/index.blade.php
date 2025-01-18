@extends('layout.master_layout')

@section('content')
    <div class="ml-4">

        @foreach ($posts as $post)
            <div
                class="w-full p-4 my-4 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <div class="flex justify-between">
                    <h5 class="text-white">r/{{ $post->name }}</h5>
                    @auth
                        <a href="{{ route('view.single.subreddit', ['id' => $post->id]) }}"
                            class="text-white bg-blue-700 px-3 rounded-full">Checkout Subreddit</a>
                    @endauth
                </div>
                <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
                    {{ $post->description }}
                </p>
            </div>
        @endforeach

    </div>
@endsection
