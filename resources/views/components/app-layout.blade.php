<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Penjadwalan') }} {{ isset($title) ? ' - ' . $title : '' }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800 antialiased relative min-h-screen flex flex-col overflow-x-hidden">
    <!-- Background Elements (Vibrant Glassmorphism) - Fixed agar tidak scrolling berantakan -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob"></div>
        <div class="absolute top-40 -left-10 w-72 h-72 bg-emerald-400 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-40 left-20 w-80 h-80 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-4000"></div>
    </div>

    <!-- Navigation -->
    <nav class="sticky top-0 z-50 bg-white/70 backdrop-blur-xl border-b border-white/50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 w-full">
                <!-- Left Side: Logo & Desktop Links -->
                <div class="flex items-center">
                    <div class="shrink-0 flex items-center gap-2">
                        <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg shadow-inner flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <span class="font-bold text-xl tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600">
                            JadwalKuliah
                        </span>
                    </div>
                    
                    <!-- Desktop Menu -->
                    <div class="hidden md:flex ml-10 space-x-6 items-center">
                        @auth
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('jadwal.index') }}" class="text-slate-600 hover:text-indigo-600 font-medium transition-colors">Jadwal</a>
                                <a href="{{ route('mata-kuliah.index') }}" class="text-slate-600 hover:text-indigo-600 font-medium transition-colors">Mata Kuliah</a>
                                <a href="{{ route('klaster.index') }}" class="text-slate-600 hover:text-indigo-600 font-medium transition-colors">Klaster</a>
                                <a href="{{ route('dosen.index') }}" class="text-slate-600 hover:text-indigo-600 font-medium transition-colors">Dosen</a>
                                <a href="{{ route('ruangan.index') }}" class="text-slate-600 hover:text-indigo-600 font-medium transition-colors">Ruangan</a>
                            @endif
                            <a href="{{ route('data.index') }}" class="text-slate-600 hover:text-indigo-600 font-medium transition-colors">Modul Data</a>
                        @endauth
                    </div>
                </div>

                <!-- Right Side: User Menu & Mobile Toggle -->
                <div class="flex items-center gap-4">
                    @auth
                        <div class="hidden md:flex items-center gap-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-md {{ auth()->user()->isAdmin() ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ strtoupper(auth()->user()->role) }}
                            </span>
                            <span class="text-sm font-medium text-slate-600">{{ auth()->user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="text-sm font-medium text-red-500 hover:text-red-700 transition-colors">Logout</button>
                            </form>
                        </div>
                    @else
                        <div class="hidden md:block">
                            <a href="{{ route('login') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors">Login</a>
                        </div>
                    @endauth

                    <!-- Mobile Menu Button -->
                    <div class="flex md:hidden items-center">
                        <button type="button" id="mobileMenuBtn" class="text-slate-500 hover:text-slate-700 focus:outline-none p-2 rounded-md hover:bg-slate-100/50 transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" id="menuIcon" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div id="mobileMenu" class="hidden md:hidden bg-white/95 backdrop-blur-xl border-t border-slate-100 shadow-lg absolute w-full left-0">
            <div class="px-4 pt-2 pb-4 space-y-1">
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('jadwal.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-indigo-600 hover:bg-indigo-50 transition-colors">Jadwal</a>
                        <a href="{{ route('mata-kuliah.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-indigo-600 hover:bg-indigo-50 transition-colors">Mata Kuliah</a>
                        <a href="{{ route('klaster.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-indigo-600 hover:bg-indigo-50 transition-colors">Klaster</a>
                        <a href="{{ route('dosen.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-indigo-600 hover:bg-indigo-50 transition-colors">Dosen</a>
                        <a href="{{ route('ruangan.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-indigo-600 hover:bg-indigo-50 transition-colors">Ruangan</a>
                    @endif
                    <a href="{{ route('data.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-indigo-600 hover:bg-indigo-50 transition-colors">Modul Data</a>
                    
                    <div class="border-t border-slate-200 mt-4 pt-4 pb-2">
                        <div class="flex items-center px-3 mb-3">
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-md {{ auth()->user()->isAdmin() ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' }} mr-2">
                                {{ strtoupper(auth()->user()->role) }}
                            </span>
                            <span class="text-sm font-medium text-slate-800">{{ auth()->user()->name }}</span>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-red-600 hover:bg-red-50 transition-colors">Logout</button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-blue-600 hover:bg-blue-50 transition-colors">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Page Content (Responsive Container) -->
    <main class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10 flex-grow">
        @if(session('success'))
            <div class="mb-6 bg-emerald-500/10 border border-emerald-500/20 backdrop-blur-md rounded-xl p-4 text-emerald-800 flex items-center gap-3">
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-500/10 border border-red-500/20 backdrop-blur-md rounded-xl p-4 text-red-800">
                <div class="flex items-center gap-3 mb-2">
                    <p class="font-bold">Terdapat kesalahan pada isian Anda:</p>
                </div>
                <ul class="list-disc list-inside space-y-1 text-sm ml-8">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{ $slot }}
    </main>

    @stack('scripts')
    
    <script>
        // Hamburger Menu Toggle Logic
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('mobileMenuBtn');
            const menu = document.getElementById('mobileMenu');
            const icon = document.getElementById('menuIcon');
            
            if(btn && menu && icon) {
                btn.addEventListener('click', () => {
                    menu.classList.toggle('hidden');
                    if(menu.classList.contains('hidden')){
                        icon.setAttribute('d', 'M4 6h16M4 12h16M4 18h16');
                    } else {
                        icon.setAttribute('d', 'M6 18L18 6M6 6l12 12');
                    }
                });
            }
        });
    </script>

    <style>
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
    </style>
</body>
</html>
