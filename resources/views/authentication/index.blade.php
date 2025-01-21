@extends('layout.master_layout')

@section('content')
    <div class="ml-4">

        <div class="my-2">
            <h1 class="text-2xl mb-4">Register</h1>
            <hr>
        </div>

        <div>
            <form action="{{ route('register.users') }}" method="post">
                @csrf
                <div class="mb-5">
                    <label for="name" class="block mb-2 text-sm font-medium">Your name</label>
                    <input type="name" name="name" id="name" class="w-full p-4 rounded-md border border-slate-700"
                        required />
                    @error('name')
                        <div class="text-red-600 text-sm">*{{ $message }}*</div>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="email" class="block mb-2 text-sm font-medium">Your email</label>
                    <input type="email" name="email" id="email"
                        class="w-full p-4 rounded-md border border-slate-700" placeholder="example@mail.com" required />
                    @error('email')
                        <div class="text-red-600 text-sm">*{{ $message }}*</div>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="password" class="block mb-2 text-sm font-medium">Your password</label>
                    <input type="password" name="password" id="password"
                        class="w-full p-4 rounded-md border border-slate-700" required />
                    @error('password')
                        <div class="text-red-600 text-sm">*{{ $message }}*</div>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="password_confirmation" class="block mb-2 text-sm font-medium">Confirm your password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full p-4 rounded-md border border-slate-700" required />
                    @error('password_confirmation')
                        <div class="text-red-600 text-sm">*{{ $message }}*</div>
                    @enderror
                </div>

                <button type="submit" class="bg-slate-800 p-3 rounded-md text-white hover:bg-blue-600 transition">
                    Register
                </button>
            </form>
        </div>
    </div>
@endsection
