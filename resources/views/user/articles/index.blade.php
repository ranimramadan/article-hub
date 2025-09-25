<x-user-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-blue-600 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15"/>
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">مقالاتي</h2>
            </div>
            <a href="{{ route('user.articles.create') }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                مقالة جديدة
            </a>
        </div>
    </x-slot>

    <div class="max-w-xl mx-auto px-4 py-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-4 border-b border-gray-100">
                <form method="GET" class="flex items-center gap-4">
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="search" name="q" value="{{ request('q') }}"
                               class="block w-full pr-10 rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                               placeholder="بحث في المقالات..."/>
                    </div>
                    <select name="status"
                            class="rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">كل الحالات</option>
                        <option value="draft" @selected(request('status')==='draft')>مسودة</option>
                        <option value="pending" @selected(request('status')==='pending')>قيد المراجعة</option>
                        <option value="published" @selected(request('status')==='published')>منشور</option>
                        <option value="rejected" @selected(request('status')==='rejected')>مرفوض</option>
                    </select>
                    <button type="submit" class="inline-flex items-center px-3 py-2 bg-gray-800 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                        <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        تصفية
                    </button>
                </form>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($items as $a)
                    <div class="p-4 hover:bg-gray-50/50 transition-colors">
                        <div class="flex items-center justify-between">
                            <h3 class="font-medium text-gray-900">{{ $a->title }}</h3>
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium
                                @class([
                                    'bg-gray-100 text-gray-800' => $a->status === 'draft',
                                    'bg-yellow-100 text-yellow-800' => $a->status === 'pending',
                                    'bg-emerald-100 text-emerald-800' => $a->status === 'published',
                                    'bg-red-100 text-red-800' => $a->status === 'rejected',
                                ])">
                                {{ __($a->status) }}
                            </span>
                        </div>

                        <p class="mt-1 text-sm text-gray-500 line-clamp-2">{{ $a->excerpt }}</p>

                        <div class="mt-3 flex items-center gap-3 flex-wrap">
                            
                            @can('update', $a)
                                <a href="{{ route('user.articles.edit', $a) }}"
                                   class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    تعديل
                                </a>
                            @endcan

                           
                            @can('submit', $a)
                                <form action="{{ route('user.articles.submit', $a) }}" method="POST" class="inline-flex items-center gap-2">
                                    @csrf
                                    <input type="text" name="note"
                                           class="text-sm rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                                           placeholder="ملاحظة للمراجعة (اختياري)">
                                    <button type="submit" class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                                        <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        طلب نشر
                                    </button>
                                </form>
                            @endcan

                            {{-- سجل الحالة: لو يقدر يشوف مقاله (مالك) --}}
                            @can('view', $a)
                                <a href="{{ route('user.articles.transitions', $a) }}"
                                   class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    سجل الحالة
                                </a>
                            @endcan

                            {{-- حذف: فقط لو policy تسمح (draft/rejected حسب إعدادك) --}}
                            @can('delete', $a)
                                <form action="{{ route('user.articles.destroy', $a) }}"
                                      method="POST"
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا المقال؟');"
                                      class="ms-auto">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-600 hover:text-white hover:bg-red-600 rounded-lg transition-all duration-200">
                                        <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        حذف
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="mt-2">لا توجد مقالات.</p>
                        <a href="{{ route('user.articles.create') }}" class="mt-3 inline-flex items-center text-sm text-blue-600 hover:text-blue-700">
                            <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            إنشاء مقالة جديدة
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="mt-4">
            {{ $items->withQueryString()->links() }}
        </div>
    </div>
</x-user-layout>
