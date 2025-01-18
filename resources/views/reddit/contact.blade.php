@extends('layout.master_layout')

@section('content')
    <form class="max-w mx-auto">
        <div class="mb-5">
            <label for="email" class="block mb-2 text-sm font-medium">Your email</label>
            <input type="email" id="email" class="w-full p-4 rounded-md border border-slate-700" placeholder="example@mail.com" required />
        </div>

        <div class="mb-5">
            <label for="email" class="block mb-2 text-sm font-medium">Your thoughts</label>
            <textarea name="" id="" class="w-full p-4 rounded-md border border-slate-700" cols="50" rows="9" required></textarea>
        </div>

        <button type="submit" class="bg-slate-800 p-3 rounded-md text-white hover:bg-blue-600 transition">
            Submit
        </button>
    </form>
@endsection
