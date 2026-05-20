<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'EventMS - Event Management System')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', system-ui, sans-serif; }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Top Navigation -->
    <nav class="bg-white border-b shadow-sm">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-8">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-indigo-600">EventMS</a>
                    
                    <a href="{{ route('events.index') }}" 
                       class="text-gray-600 hover:text-gray-900 font-medium">Browse Events</a>
                </div>

                <div class="flex items-center gap-4">
                    @guest
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900">Login</a>
                        <a href="{{ route('register') }}" 
                           class="px-5 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition">
                            Register
                        </a>
                        <a href="{{ route('register.organiser') }}" 
                           class="px-5 py-2 border border-gray-300 rounded-xl hover:bg-gray-50 transition">
                            For Organisers
                        </a>
                    @else
                        <span class="text-sm text-gray-600">
                            Hi, {{ Auth::user()->name }}
                        </span>
                        
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="text-indigo-600 font-medium">Admin</a>
                        @elseif(Auth::user()->role === 'organiser')
                            <a href="{{ route('organiser.dashboard') }}" class="text-indigo-600 font-medium">Organiser</a>
                        @else
                            <a href="{{ route('client.dashboard') }}" class="text-indigo-600 font-medium">Dashboard</a>
                        @endif
                        
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-red-600">Logout</button>
                        </form>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-12 mt-20">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <p>&copy; {{ date('Y') }} EventMS. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>