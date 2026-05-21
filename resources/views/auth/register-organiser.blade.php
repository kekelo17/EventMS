@extends('layouts.app')

@section('title', 'Register as Organiser')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 py-12">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl p-10">
        <h2 class="text-3xl font-bold text-center mb-2">Become an Organiser</h2>
        <p class="text-center text-gray-600 mb-8">Start hosting events today</p>

        <form method="POST" action="{{ route('register.organiser') }}">
            @csrf
            <!-- Same fields as register + required phone -->
            <div class="space-y-6">
                <div>
                    <label>Full Name</label>
                    <input type="text" name="name" required class="w-full px-4 py-3 border rounded-2xl">
                </div>
                <div>
                    <label>Email</label>
                    <input type="email" name="email" required class="w-full px-4 py-3 border rounded-2xl">
                </div>
                <div>
                    <label>Phone Number <span class="text-red-500">*</span></label>
                    <input type="tel" name="phone" required class="w-full px-4 py-3 border rounded-2xl">
                </div>
                <div>
                    <label>Password</label>
                    <input type="password" name="password" required class="w-full px-4 py-3 border rounded-2xl">
                </div>
                <div>
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" required class="w-full px-4 py-3 border rounded-2xl">
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-violet-600 to-indigo-600 text-white py-4 rounded-2xl font-semibold">
                    Register as Organiser
                </button>
            </div>
        </form>
    </div>
</div>
@endsection