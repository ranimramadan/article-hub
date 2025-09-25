<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="fixed right-0 top-0 w-64 h-full bg-white border-l shadow-sm overflow-y-auto">
            <div class="p-4 border-b">
                <div class="flex items-center gap-3">
                    <div class="flex-1">
                        <div class="font-medium text-gray-900">{{ auth()->user()->name }}</div>
                        <div class="text-sm text-gray-500">{{ auth()->user()->email }}</div>
                    </div>
                </div>
            </div>
            
            <nav class="p-4">
                <div class="space-y-1">
             

                    <a href="{{ route('user.articles.index') }}" 
                       class="flex items-center gap-2 p-2 rounded-lg {{ request()->routeIs('user.articles.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15"/>
                        </svg>
                        <span>مقالاتي</span>
                    </a>
                </div>

                <hr class="my-4">

                <div class="space-y-1">
                    <a href="{{ route('profile.edit') }}" 
                       class="flex items-center gap-2 p-2 rounded-lg text-gray-700 hover:bg-gray-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>الملف الشخصي</span>
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="w-full flex items-center gap-2 p-2 rounded-lg text-gray-700 hover:bg-gray-50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span>تسجيل خروج</span>
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 mr-64">
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main class="py-6">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>