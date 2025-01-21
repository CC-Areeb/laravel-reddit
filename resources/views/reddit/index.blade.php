@extends('layout.master_layout')

@section('content')
    <div class="ml-4">

        @if (session('error'))
            <div id="error-message" class="text-2xl text-center bg-red-400 text-white p-4 rounded-md transition-opacity duration-300 opacity-100">
                {{ session('error') }}
            </div>
        @endif

        @foreach ($posts as $post)
            <div
                class="w-full p-4 my-4 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <div class="flex justify-between">
                    <h5 class="text-white">r/{{ $post->name }}</h5>
                    @auth
                        <a href="{{ route('view.single.subreddit', ['id' => $post->id]) }}"
                            class="text-white bg-blue-700 px-3 rounded-full">Checkout r/{{ $post->name }}</a>
                    @endauth
                </div>
                <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
                    {{ $post->description }}
                </p>
            </div>
        @endforeach

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const errorMessage = document.getElementById('error-message');
                if (errorMessage) {
                    setTimeout(function() {
                        errorMessage.classList.add('opacity-0'); // Fade out effect
                        setTimeout(function() {
                            errorMessage.classList.add('hidden'); // Fully hide it
                        }, 300); // Wait for the fade-out transition to complete
                    }, 2000); // 3 seconds delay before hiding
                }
            });
        </script>


    </div>
@endsection
