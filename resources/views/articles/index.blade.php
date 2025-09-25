<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>المقالات - {{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-xl font-semibold text-gray-900">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>
                
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900">لوحة التحكم</a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900">تسجيل الدخول</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                                إنشاء حساب
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-900 sm:text-4xl">المقالات المنشورة</h1>
                <p class="mt-3 text-xl text-gray-500">استكشف مجموعة متنوعة من المقالات المميزة</p>
            </div>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <form method="GET" class="flex gap-4">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="search" name="search" value="{{ request('search') }}" 
                       class="block w-full pr-10 rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                       placeholder="ابحث في المقالات..."/>
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">بحث</button>
        </form>
    </div>

    <!-- Articles List -->
    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($articles as $article)
                <article class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all duration-200">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-900">
                            <a href="{{ route('articles.show', $article) }}" class="hover:text-blue-600">
                                {{ $article->title }}
                            </a>
                        </h2>
                        
                        <p class="mt-3 text-gray-500 line-clamp-3">
                            {{ $article->excerpt }}
                        </p>

                        <div class="mt-4 flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="text-sm text-gray-500">
                                    <span>{{ $article->author->name }}</span>
                                    <span class="mx-1">•</span>
                                    <time datetime="{{ $article->published_at->format('Y-m-d') }}">
                                        {{ $article->published_at->format('Y/m/d') }}
                                    </time>
                                </div>
                            </div>
                            <a href="{{ route('articles.show', $article) }}" 
                               class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-700">
                                قراءة المزيد
                                <svg class="w-4 h-4 me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full text-center py-12">
                    <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="mt-4 text-gray-500">لا توجد مقالات منشورة حالياً</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $articles->links() }}
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-12">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-gray-500">جميع الحقوق محفوظة © {{ date('Y') }}</p>
        </div>
    </footer>
</body>
</html>