<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reddit</title>
</head>

<body>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    {{-- Navigation bar --}}
    <nav class="bg-white border-gray-200 dark:bg-gray-700 sticky top-0">
        <div class="max-w-screen flex flex-wrap items-center justify-between p-4">
            <div>
                <a href="{{ route('home') }}"
                    class="text-white flex items-center space-x-3 rtl:space-x-reverse hover:text-blue-300">
                    Reddit
                </a>
            </div>

            <div class="relative w-auto" id="navbar-default">
                <ul
                    class="font-medium flex flex-col p-4 md:p-0 mt-4 border md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 items-center">

                    @guest
                        <li>
                            <a href="{{ route('register') }}"
                                class="block py-2 px-3 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 hover:text-blue-300 md:p-0 dark:text-white">
                                Register
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('login') }}"
                                class="block py-2 px-3 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 hover:text-blue-300 md:p-0 dark:text-white">
                                Login
                            </a>
                        </li>
                    @endguest

                    @auth
                        <form action="{{ route('logout.users') }}" method="post">
                            @csrf
                            <button type="submit"
                                class="block py-2 px-3 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 hover:text-blue-300 md:p-0 dark:text-white">Logout</button>
                        </form>
                    @endauth

                    <li>
                        <button type="button" id="dropdownButton"
                            class="custom_dropdown p-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                            More
                        </button>
                        <!-- Custom Dropdown -->
                        <div id="dropdownMenu"
                            class="hidden absolute right-0 z-10 mt-4 w-full bg-white border border-gray-300 rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-600">
                            <ul class="py-1 text-sm text-gray-700 dark:text-gray-200">
                                <li>
                                    <a href="{{ route('contact') }}"
                                        class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        Contact
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        Stickers Shop
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>

        </div>
    </nav>

    {{-- sidebar --}}
    <button data-drawer-target="sidebar-multi-level-sidebar" data-drawer-toggle="sidebar-multi-level-sidebar"
        aria-controls="sidebar-multi-level-sidebar" type="button"
        class="side_menu_btn inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
        <span class="sr-only">Open sidebar</span>
        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg">
            <path clip-rule="evenodd" fill-rule="evenodd"
                d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
            </path>
        </svg>
    </button>

    <aside id="sidebar-multi-level-sidebar"
        class="fixed top-15 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0"
        aria-label="Sidebar">
        <div class="h-full px-3 py-4 overflow-y-auto bg-gray-50 dark:bg-gray-700">
            <ul class="space-y-2 font-medium">
                <li>
                    <a href="{{ route('home') }}"
                        class="flex items-center p-2 text-white hover:text-slate-900 hover:ease-in duration-300 hover:bg-gray-300 rounded-md">
                        <span class="ms-3">Home</span>
                    </a>
                </li>

                <li>
                    <a href="#"
                        class="flex items-center p-2 text-white hover:text-slate-900 hover:ease-in duration-300 hover:bg-gray-300 rounded-md">
                        <span class="ms-3">Popular</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>

    <div class="p-4 sm:ml-64">
        {{-- content will be injected here --}}
        @yield('content')
    </div>


    <script>
        document.getElementById('dropdownButton').addEventListener('click', function() {
            const dropdownMenu = document.getElementById('dropdownMenu');
            dropdownMenu.classList.toggle('hidden');
        });
    </script>


</body>

</html>
