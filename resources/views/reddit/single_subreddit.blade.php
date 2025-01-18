@extends('layout.master_layout')

@section('content')
    <div>
        <h1 class="text-2xl mb-2">r/{{ $community->name }}</h1>
        <hr>

        <h5 class="my-3">
            {{ $community->description }}
        </h5>

        {{-- select post type --}}

        <div class="my-3 flex space-x-10">
            <button class="p-2 rounded-lg hover:ease-in duration-200 hover:bg-slate-700 hover:text-gray-300"
                id="text_btn">Text</button>
            <button class="p-2 rounded-lg hover:ease-in duration-200 hover:bg-slate-700 hover:text-gray-300"
                id="img_vid_btn">Images and Videos</button>
            <button class="p-2 rounded-lg hover:ease-in duration-200 hover:bg-slate-700 hover:text-gray-300"
                id="link_btn">Links</button>
            <button class="p-2 rounded-lg hover:ease-in duration-200 hover:bg-slate-700 hover:text-gray-300"
                id="poll_btn">Polls</button>
        </div>


        <form action="{{ route('post.submit') }}" method="post">
            @csrf
            <div class="mb-5">
                <label for="title" class="block mb-2 text-sm font-medium">Title</label>
                <input type="title" id="title" name="title" class="w-full p-4 rounded-md border border-slate-700"
                    required />
            </div>

            {{-- text --}}
            <div class="mb-5" id="text_box">
                <textarea name="text_post" id="" cols="30" rows="10"
                    class="w-full p-4 rounded-md border border-slate-700" placeholder="body..."></textarea>
            </div>

            {{-- images/videos --}}
            <div class="mb-5 hidden" id="image_video">
                <input type="file" name="file_post"
                    class="block w-full text-sm text-slate-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-full file:border-0
                    file:text-sm file:font-semibold
                    file:bg-violet-50 file:text-violet-700
                    hover:file:bg-violet-100
                    " />
            </div>

            {{-- links --}}
            <div class="mb-5 hidden" id="link">
                <input type="text" name="link_post" id="" class="w-full p-4 rounded-md border border-slate-700"
                    placeholder="Link URL">
            </div>

            {{-- polls --}}
            <div class="mb-5 hidden" id="poll">
                <textarea name="poll_heading" id="" cols="30" rows="10"
                    class="w-full p-4 rounded-md border border-slate-700" placeholder="Text (optional)">
                </textarea>
                <div id="poll_options" class="flex items-start space-x-12">
                    <!-- Poll Options -->
                    <div id="poll_options_div" class="flex flex-col space-y-2">
                        <input type="text" class="rounded-md border border-slate-700 p-2 w-[760px]" name="option_1" id="option_1"
                            placeholder="Option 1">
                        <input type="text" class="rounded-md border border-slate-700 p-2 w-[760px]" name="option_2" id="option_2"
                            placeholder="Option 2">

                        {{-- add more options --}}
                        <button id="add_more_options"
                            class="bg-slate-800 p-3 rounded-md text-white hover:bg-blue-600 transition">
                            More options
                        </button>
                    </div>

                    <!-- Tips -->
                    <div>
                        <span class="block font-semibold mb-2">Tips on Better Polls</span>
                        <ol class="list-decimal list-inside ml-4 text-sm text-gray-700">
                            <li>Suggest short clear options</li>
                            <li>The more options, the better</li>
                            <li>Choose the poll duration</li>
                            <li>Options can't be edited after post creation</li>
                        </ol>
                    </div>
                </div>

            </div>

            <button type="submit" class="bg-slate-800 p-3 rounded-md text-white hover:bg-blue-600 transition">
                Post
            </button>

            {{--
                ToDo : Drafting feature
                <button type="submit" class="bg-slate-800 p-3 rounded-md text-white hover:bg-blue-600 transition">
                    Save Draft
                </button>
            --}}
        </form>
    </div>

    <script>
        let text_btn = document.getElementById('text_btn');
        let img_vid_btn = document.getElementById('img_vid_btn');
        let link_btn = document.getElementById('link_btn');
        let poll_btn = document.getElementById('poll_btn');

        let text_box = document.getElementById('text_box');
        let image_video = document.getElementById('image_video');
        let link = document.getElementById('link');
        let poll = document.getElementById('poll');

        text_btn.onclick = function() {
            text_box.classList.remove('hidden');
            image_video.classList.add('hidden');
            link.classList.add('hidden');
            poll.classList.add('hidden');

            text_btn.classList.add('underline');
            img_vid_btn.classList.remove('underline');
            link_btn.classList.remove('underline');
            poll_btn.classList.remove('underline');
        };

        img_vid_btn.onclick = function() {
            text_box.classList.add('hidden');
            image_video.classList.remove('hidden');
            link.classList.add('hidden');
            poll.classList.add('hidden');

            text_btn.classList.remove('underline');
            img_vid_btn.classList.add('underline');
            link_btn.classList.remove('underline');
            poll_btn.classList.remove('underline');
        };

        link_btn.onclick = function() {
            text_box.classList.add('hidden');
            image_video.classList.add('hidden');
            link.classList.remove('hidden');
            poll.classList.add('hidden');

            text_btn.classList.remove('underline');
            img_vid_btn.classList.remove('underline');
            link_btn.classList.add('underline');
            poll_btn.classList.remove('underline');
        };

        poll_btn.onclick = function() {
            text_box.classList.add('hidden');
            image_video.classList.add('hidden');
            link.classList.add('hidden');
            poll.classList.remove('hidden');

            text_btn.classList.remove('underline');
            img_vid_btn.classList.remove('underline');
            link_btn.classList.remove('underline');
            poll_btn.classList.add('underline');
        };




        let addMoreOptionsBtn = document.getElementById('add_more_options');
        let pollOptionsDiv = document.getElementById('poll_options_div');
        let optionCounter = 3;

        addMoreOptionsBtn.addEventListener('click', function(event) {
            event.preventDefault();

            let optionDiv = document.createElement('div');
            optionDiv.classList.add('flex', 'items-center', 'space-x-2');

            let newInput = document.createElement('input');
            newInput.type = 'text';
            newInput.classList.add('rounded-md', 'border', 'border-slate-700', 'p-2', 'w-[760px]');
            newInput.name = 'option_' + optionCounter;
            newInput.id = 'option_' + optionCounter;
            newInput.placeholder = 'Option ' + optionCounter;

            let removeButton = document.createElement('button');
            removeButton.classList.add('text-red-600', 'hover:text-red-800', 'transition');
            removeButton.innerHTML = '&times;';

            removeButton.addEventListener('click', function() {
                pollOptionsDiv.removeChild(optionDiv);
            });

            optionDiv.appendChild(newInput);
            optionDiv.appendChild(removeButton);

            pollOptionsDiv.insertBefore(optionDiv, addMoreOptionsBtn);

            optionCounter++;
        });
    </script>
@endsection
