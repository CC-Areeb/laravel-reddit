@extends('layout.master_layout')

@section('content')
    <div class="ml-4">
        <h1 class="text-2xl mb-4">Login</h1>
        <hr>
        <div class="my-2">
            <form action="{{ route('login.users') }}" method="post">
                @csrf

                <!-- Email Field -->
                <div class="mb-5">
                    <label for="email" class="block mb-2 text-sm font-medium">Your email</label>
                    <input type="email" name="email" id="email" class="w-full p-4 rounded-md border border-slate-700"
                        placeholder="example@mail.com" value="{{ old('email') }}" required />
                    @error('email')
                        <div class="text-red-600 text-sm mt-1">*{{ $message }}*</div>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="mb-5">
                    <label for="password" class="block mb-2 text-sm font-medium">Your password</label>
                    <input type="password" name="password" id="password"
                        class="w-full p-4 rounded-md border border-slate-700" required />
                    @error('password')
                        <div class="text-red-600 text-sm mt-1">*{{ $message }}*</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="bg-slate-800 p-3 rounded-md text-white hover:bg-blue-600 transition">
                    Login
                </button>
            </form>
        </div>
    </div>
@endsection
