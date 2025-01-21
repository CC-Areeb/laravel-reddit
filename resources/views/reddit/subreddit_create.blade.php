@extends('layout.master_layout')

@section('content')
    <div class="ml-4">
        <div class="my-2">
            <h1 class="text-2xl">
                Create a Subreddit
            </h1>
        </div>
        <hr>
        <div class="my-6">
            <form action="{{ route('subreddit.store') }}" method="POST">
                @csrf
                <div class="mb-5">
                    <label for="name" class="block mb-2 text-sm font-medium">Subreddit name</label>
                    <input type="text" name="name" id="name" class="w-full p-4 rounded-md border border-slate-700" required />
                </div>

                <div class="mb-5">
                    <label for="description" class="block mb-2 text-sm font-medium">Subreddit description</label>
                    <textarea name="description" id="description" class="w-full p-4 rounded-md border border-slate-700" cols="50"
                        rows="9" required placeholder="what is your subreddit about?"></textarea>
                </div>

                {{-- Subreddit type --}}
                <div class="mb-5">
                    <label for="privacy" class="block text-lg font-semibold mb-2">Is your subreddit public or
                        private?
                    </label>

                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <input type="radio" id="public" name="privacy" value="public"
                                class="form-radio h-5 w-5 text-blue-500" checked>
                            <label for="public" class="ml-2 text-sm">Public</label>
                        </div>

                        <div class="flex items-center">
                            <input type="radio" id="private" name="privacy" value="private"
                                class="form-radio h-5 w-5 text-red-500">
                            <label for="private" class="ml-2 text-sm">Private</label>
                        </div>
                    </div>
                </div>

                {{-- banner and theme color --}}
                <label class="block text-lg font-semibold mb-2" for="banner">Choose banner name(s) and color(s)</label>
                <div class="banner_container items-center mb-4">
                    <!-- Banner Input -->
                    <div class="flex space-x-6 mb-2">
                        <div class="flex items-center space-x-2">
                            <label for="banner" class="text-sm font-medium text-gray-700">Banner name</label>
                            <input type="text" name="banner"
                                class="banner_name px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm w-40"
                                placeholder="Enter banner name">
                        </div>

                        <!-- Theme Color Picker -->
                        <div class="flex items-center space-x-2">
                            <label for="theme" class="text-sm font-medium text-gray-700">Color</label>
                            <input type="color" name="theme" value="#ffffff"
                                class="theme_color w-10 h-10 p-0 border-none rounded-md cursor-pointer focus:outline-none">
                        </div>
                    </div>
                </div>
                <div class="items-center space-x-2 hidden" id="add_more_banner_div">
                    <button class="add_more_banner bg-green-700 p-3 rounded-lg text-white">Add another banner</button>
                </div>


                {{-- Subreddit Rules --}}
                <div class="my-5">
                    <label for="" class="block text-lg font-semibold mb-2">Subreddit rules</label>
                    <input type="rules" name="rules" id="rules" class="w-full p-4 rounded-md border border-slate-700" required />
                </div>

            </form>
        </div>
    </div>

    <script>
        let bannerNameInput = document.querySelector('.banner_name');
        let themeColorInput = document.querySelector('.theme_color');
        let addMoreBannerDiv = document.getElementById('add_more_banner_div');
        let addMoreBannerButton = document.querySelector('.add_more_banner');
        let bannersContainer = document.getElementById('banners-container');

        function checkFields() {
            if (bannerNameInput.value && themeColorInput.value) {
                addMoreBannerDiv.classList.remove('hidden');
            } else {
                addMoreBannerDiv.classList.add('hidden');
            }
        }

        bannerNameInput.addEventListener('input', checkFields);
        themeColorInput.addEventListener('input', checkFields);

        function addNewBanner() {
            // Find the existing banner_container div
            let bannerContainer = document.querySelector('.banner_container');

            // Create a new div to hold the new banner input and color picker (this ensures they are on a new line)
            let newBannerDiv = document.createElement('div');
            newBannerDiv.classList.add('flex', 'space-x-6', 'mb-4'); // Ensure space between banner name and color picker

            // Create the banner input div
            let bannerInputDiv = document.createElement('div');
            bannerInputDiv.classList.add('flex', 'items-center', 'space-x-2');

            // Create the banner name label
            let bannerLabel = document.createElement('label');
            bannerLabel.setAttribute('for', 'banner');
            bannerLabel.classList.add('text-sm', 'font-medium', 'text-gray-700');
            bannerLabel.textContent = 'Banner name';

            // Create the banner name input field
            let bannerInput = document.createElement('input');
            bannerInput.type = 'text';
            bannerInput.name = 'banner';
            bannerInput.classList.add('banner_name', 'px-3', 'py-2', 'border', 'border-gray-300', 'rounded-md', 'shadow-sm',
                'focus:outline-none', 'focus:ring-2', 'focus:ring-blue-500', 'focus:border-blue-500', 'text-sm', 'w-40');
            bannerInput.placeholder = 'Enter banner name';

            // Append the label and input to the banner input div
            bannerInputDiv.appendChild(bannerLabel);
            bannerInputDiv.appendChild(bannerInput);

            // Create the color picker div
            let colorPickerDiv = document.createElement('div');
            colorPickerDiv.classList.add('flex', 'items-center', 'space-x-2');

            // Create the color label
            let colorLabel = document.createElement('label');
            colorLabel.setAttribute('for', 'theme');
            colorLabel.classList.add('text-sm', 'font-medium', 'text-gray-700');
            colorLabel.textContent = 'Color';

            // Create the color picker input field
            let colorPicker = document.createElement('input');
            colorPicker.type = 'color';
            colorPicker.name = 'theme';
            colorPicker.value = '#ffffff';
            colorPicker.classList.add('theme_color', 'w-10', 'h-10', 'p-0', 'border-none', 'rounded-md', 'cursor-pointer',
                'focus:outline-none');

            // Append the label and input to the color picker div
            colorPickerDiv.appendChild(colorLabel);
            colorPickerDiv.appendChild(colorPicker);

            // Create the "Remove" button
            let removeButton = document.createElement('button');
            removeButton.classList.add('bg-red-500', 'text-white', 'px-3', 'py-1', 'rounded', 'hover:bg-red-600',
                'focus:outline-none', 'focus:ring-2', 'focus:ring-red-500');
            removeButton.textContent = 'Remove';

            // Add event listener to the remove button
            removeButton.addEventListener('click', function() {
                bannerContainer.removeChild(newBannerDiv); // Remove the new banner div
            });

            // Append the remove button to the newBannerDiv
            newBannerDiv.appendChild(bannerInputDiv);
            newBannerDiv.appendChild(colorPickerDiv);
            newBannerDiv.appendChild(removeButton);

            // Append the newBannerDiv to the banner_container
            bannerContainer.appendChild(newBannerDiv);
        }

        addMoreBannerButton.addEventListener('click', function(event) {
            event.preventDefault();
            addNewBanner();
        });
    </script>
@endsection
