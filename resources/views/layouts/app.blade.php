<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'EventMS') — Event Management Platform</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { 50:'#fdf4ff', 100:'#fae8ff', 200:'#f5d0fe', 300:'#f0abfc', 400:'#e879f9', 500:'#d946ef', 600:'#c026d3', 700:'#a21caf', 800:'#86198f', 900:'#701a75', 950:'#4a044e' },
                        gold:  { 400:'#fbbf24', 500:'#f59e0b', 600:'#d97706' },
                    },
                    fontFamily: {
                        display: ['Syne', 'sans-serif'],
                        body:    ['DM Sans', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <style>
        body { font-family: 'DM Sans', sans-serif; background: #0a0a0f; color: #e2e8f0; }
        h1,h2,h3,h4,h5,.font-display { font-family: 'Syne', sans-serif; }

        /* Glowing navbar */
        .navbar-glass {
            background: rgba(10,10,15,0.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(217,70,239,0.15);
        }

        /* Sidebar */
        .sidebar-link { transition: all .2s; border-left: 2px solid transparent; }
        .sidebar-link:hover, .sidebar-link.active {
            background: linear-gradient(90deg, rgba(217,70,239,0.12), transparent);
            border-left-color: #d946ef;
            color: #f0abfc;
        }

        /* Cards */
        .card-glass {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 1rem;
            backdrop-filter: blur(10px);
        }
        .card-glow:hover {
            border-color: rgba(217,70,239,0.3);
            box-shadow: 0 0 30px rgba(217,70,239,0.08);
            transition: all .3s;
        }

        /* Stat cards */
        .stat-purple { background: linear-gradient(135deg, #4a044e 0%, #701a75 100%); }
        .stat-gold    { background: linear-gradient(135deg, #78350f 0%, #b45309 100%); }
        .stat-teal    { background: linear-gradient(135deg, #134e4a 0%, #0f766e 100%); }
        .stat-blue    { background: linear-gradient(135deg, #1e3a5f 0%, #1d4ed8 100%); }
        .stat-red     { background: linear-gradient(135deg, #450a0a 0%, #b91c1c 100%); }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #c026d3, #7c3aed);
            border: none; color: white; transition: all .2s;
        }
        .btn-primary:hover { opacity: .9; transform: translateY(-1px); box-shadow: 0 8px 25px rgba(192,38,211,.35); }

        /* Badge pills */
        .badge-held     { background:rgba(245,158,11,.15); color:#fbbf24; border:1px solid rgba(245,158,11,.3); }
        .badge-released { background:rgba(20,184,166,.15); color:#2dd4bf; border:1px solid rgba(20,184,166,.3); }
        .badge-refunded { background:rgba(239,68,68,.15);  color:#f87171; border:1px solid rgba(239,68,68,.3); }
        .badge-pending  { background:rgba(251,146,60,.15); color:#fb923c; border:1px solid rgba(251,146,60,.3); }
        .badge-approved { background:rgba(34,197,94,.15);  color:#4ade80; border:1px solid rgba(34,197,94,.3); }
        .badge-rejected { background:rgba(239,68,68,.15);  color:#f87171; border:1px solid rgba(239,68,68,.3); }
        .badge-cancelled{ background:rgba(148,163,184,.15);color:#94a3b8; border:1px solid rgba(148,163,184,.3); }

        /* Table */
        .dark-table thead { background: rgba(255,255,255,0.05); }
        .dark-table tbody tr { border-bottom: 1px solid rgba(255,255,255,0.05); transition: background .15s; }
        .dark-table tbody tr:hover { background: rgba(217,70,239,0.05); }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: #0a0a0f; }
        ::-webkit-scrollbar-thumb { background: #701a75; border-radius: 99px; }

        /* Notification dot */
        .notif-dot { width:8px; height:8px; background:#d946ef; border-radius:50%; animation: pulse-dot 2s infinite; }
        @keyframes pulse-dot { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.5;transform:scale(.8)} }

        /* Alert variants */
        .alert-success { background:rgba(34,197,94,.1); border:1px solid rgba(34,197,94,.3); color:#4ade80; }
        .alert-error   { background:rgba(239,68,68,.1); border:1px solid rgba(239,68,68,.3);  color:#f87171; }
        .alert-info    { background:rgba(99,102,241,.1);border:1px solid rgba(99,102,241,.3);  color:#a5b4fc; }
        .alert-warning { background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.3);  color:#fbbf24; }

        /* Input styling */
        .input-dark {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.12);
            color: #e2e8f0;
            border-radius: .5rem;
            padding: .6rem 1rem;
            width: 100%;
            transition: border-color .2s;
        }
        .input-dark:focus { outline: none; border-color: #d946ef; box-shadow: 0 0 0 3px rgba(217,70,239,.15); }
        .input-dark::placeholder { color: #64748b; }
        .input-dark option { background: #1a1a2e; }

        /* Sidebar mobile */
        @media(max-width:768px) { .sidebar-desktop { display: none; } }
    </style>
    @stack('styles')
</head>
<body>

{{-- ── TOP NAVIGATION ──────────────────────────────────── --}}
<nav class="navbar-glass sticky top-0 z-50">
    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6">
        <div class="flex justify-between items-center h-16">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:linear-gradient(135deg,#c026d3,#7c3aed)">
                    <i class="bi bi-calendar-star-fill text-white text-sm"></i>
                </div>
                <span class="font-display font-800 text-xl text-white">Event<span class="text-fuchsia-400">MS</span></span>
            </a>

            {{-- Center links --}}
            <div class="hidden md:flex items-center gap-6">
                <a href="{{ route('events.index') }}" class="text-slate-400 hover:text-white transition text-sm font-medium">Browse Events</a>
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="text-slate-400 hover:text-white transition text-sm">Admin Panel</a>
                    @elseif(auth()->user()->role === 'organiser')
                        <a href="{{ route('organiser.dashboard') }}" class="text-slate-400 hover:text-white transition text-sm">My Events</a>
                        <a href="{{ route('organiser.wallet') }}" class="text-slate-400 hover:text-white transition text-sm">Wallet</a>
                    @else
                        <a href="{{ route('client.dashboard') }}" class="text-slate-400 hover:text-white transition text-sm">My Tickets</a>
                    @endif
                @endauth
            </div>

            {{-- Right side --}}
            <div class="flex items-center gap-3">
                @auth
                    {{-- Notifications --}}
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" class="relative p-2 text-slate-400 hover:text-white transition">
                            <i class="bi bi-bell text-lg"></i>
                            @php $unread = auth()->user()->unreadNotifications()->count(); @endphp
                            @if($unread > 0)
                                <span class="absolute top-1 right-1 notif-dot"></span>
                            @endif
                        </button>
                        <div x-show="open" x-transition
                             class="absolute right-0 mt-2 w-80 card-glass shadow-2xl overflow-hidden z-50"
                             style="display:none">
                            <div class="px-4 py-3 border-b border-white/10 flex justify-between items-center">
                                <span class="font-display font-600 text-sm text-white">Notifications</span>
                                @if($unread > 0)<span class="text-xs badge-pending px-2 py-0.5 rounded-full">{{ $unread }} new</span>@endif
                            </div>
                            <div class="max-h-72 overflow-y-auto">
                                @forelse(auth()->user()->notifications()->latest()->limit(6)->get() as $notif)
                                    <a href="{{ $notif->link ?? '#' }}"
                                       class="flex gap-3 px-4 py-3 hover:bg-white/5 transition border-b border-white/5 {{ $notif->is_read ? 'opacity-60' : '' }}">
                                        <div class="w-2 h-2 rounded-full mt-1.5 flex-shrink-0
                                            {{ $notif->type === 'success' ? 'bg-green-400' : ($notif->type === 'danger' ? 'bg-red-400' : ($notif->type === 'warning' ? 'bg-yellow-400' : 'bg-fuchsia-400')) }}">
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold text-slate-200">{{ $notif->title }}</p>
                                            <p class="text-xs text-slate-400 mt-0.5">{{ Str::limit($notif->message, 55) }}</p>
                                            <p class="text-xs text-slate-600 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                                        </div>
                                    </a>
                                @empty
                                    <div class="px-4 py-6 text-center text-slate-500 text-sm">No notifications yet</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- User menu --}}
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open"
                                class="flex items-center gap-2 px-3 py-1.5 rounded-lg hover:bg-white/5 transition">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold text-white"
                                 style="background:linear-gradient(135deg,#c026d3,#7c3aed)">
                                {{ strtoupper(substr(auth()->user()->name,0,1)) }}
                            </div>
                            <span class="hidden sm:block text-sm text-slate-300">{{ Str::limit(auth()->user()->name,15) }}</span>
                            <i class="bi bi-chevron-down text-xs text-slate-500"></i>
                        </button>
                        <div x-show="open" x-transition
                             class="absolute right-0 mt-2 w-52 card-glass shadow-2xl z-50 overflow-hidden"
                             style="display:none">
                            <div class="px-4 py-3 border-b border-white/10">
                                <p class="text-sm font-semibold text-white">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-slate-400 capitalize">{{ auth()->user()->role }}</p>
                            </div>
                            <div class="py-1">
                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-300 hover:bg-white/5 hover:text-white transition"><i class="bi bi-shield-check w-4"></i> Admin Panel</a>
                                @elseif(auth()->user()->role === 'organiser')
                                    <a href="{{ route('organiser.dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-300 hover:bg-white/5 hover:text-white transition"><i class="bi bi-grid w-4"></i> Dashboard</a>
                                    <a href="{{ route('organiser.wallet') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-300 hover:bg-white/5 hover:text-white transition"><i class="bi bi-wallet2 w-4"></i> My Wallet</a>
                                @else
                                    <a href="{{ route('client.dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-300 hover:bg-white/5 hover:text-white transition"><i class="bi bi-grid w-4"></i> Dashboard</a>
                                    <a href="{{ route('client.tickets') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-300 hover:bg-white/5 hover:text-white transition"><i class="bi bi-ticket-perforated w-4"></i> My Tickets</a>
                                @endif
                                <div class="border-t border-white/10 mt-1 pt-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="flex items-center gap-2 w-full px-4 py-2 text-sm text-red-400 hover:bg-red-500/10 transition">
                                            <i class="bi bi-box-arrow-right w-4"></i> Sign Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-slate-400 hover:text-white transition px-3 py-2">Sign In</a>
                    <a href="{{ route('register') }}" class="btn-primary text-sm px-4 py-2 rounded-lg font-medium">Get Started</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

{{-- ── FLASH MESSAGES ──────────────────────────────────── --}}
@foreach(['success','error','info','warning'] as $type)
    @if(session($type))
        <div class="max-w-screen-2xl mx-auto px-6 pt-4">
            <div class="alert-{{ $type === 'error' ? 'error' : $type }} px-4 py-3 rounded-xl flex items-center gap-3 text-sm">
                <i class="bi bi-{{ $type === 'success' ? 'check-circle-fill' : ($type === 'error' ? 'x-circle-fill' : 'info-circle-fill') }} text-lg flex-shrink-0"></i>
                <span>{{ session($type) }}</span>
            </div>
        </div>
    @endif
@endforeach

{{-- ── MAIN ────────────────────────────────────────────── --}}
<main>@yield('content')</main>

@unless(in_array(request()->route()->getName(), ['login', 'register', 'register.organiser','admin.dashboard']))        

{{-- ── FOOTER ──────────────────────────────────────────── --}}
    <footer class="border-t border-white/5 mt-20">
        <div class="max-w-7xl mx-auto px-6 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-10">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:linear-gradient(135deg,#c026d3,#7c3aed)">
                            <i class="bi bi-calendar-star-fill text-white text-sm"></i>
                        </div>
                        <span class="font-display font-800 text-xl text-white">Event<span class="text-fuchsia-400">MS</span></span>
                    </div>
                    <p class="text-slate-500 text-sm leading-relaxed max-w-xs">
                        The secure event ticketing platform with built-in escrow protection for organisers and clients alike.
                    </p>
                </div>
                <div>
                    <h6 class="font-display font-600 text-slate-300 text-sm mb-4 uppercase tracking-wider">Platform</h6>
                    <ul class="space-y-2 text-sm text-slate-500">
                        <li><a href="{{ route('events.index') }}" class="hover:text-fuchsia-400 transition">Browse Events</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-fuchsia-400 transition">Create Account</a></li>
                        <li><a href="{{ route('register.organiser') }}" class="hover:text-fuchsia-400 transition">Become Organiser</a></li>
                    </ul>
                </div>
                <div>
                    <h6 class="font-display font-600 text-slate-300 text-sm mb-4 uppercase tracking-wider">Trust</h6>
                    <ul class="space-y-2 text-sm text-slate-500">
                        <li class="flex items-center gap-2"><i class="bi bi-shield-check text-fuchsia-400"></i> Escrow Protected</li>
                        <li class="flex items-center gap-2"><i class="bi bi-lock text-fuchsia-400"></i> Secure Payments</li>
                        <li class="flex items-center gap-2"><i class="bi bi-arrow-counterclockwise text-fuchsia-400"></i> Refund Policy</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-white/5 pt-6 flex flex-col md:flex-row justify-between items-center gap-3">
                <p class="text-slate-600 text-sm">&copy; {{ date('Y') }} EventMS. All rights reserved.</p>
                <p class="text-slate-700 text-xs">Built with Laravel · MySQL · Escrow Technology</p>
            </div>
        </div>
    </footer>
@endunless
{{-- Alpine.js for dropdown/toggle --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@stack('scripts')
</body>
</html>