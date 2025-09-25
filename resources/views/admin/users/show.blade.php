<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-blue-600 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">عرض المستخدم #{{ $user->id }}</h2>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.users.edit', $user) }}" 
                   class="inline-flex items-center px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                    <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    تعديل
                </a>
                <a href="{{ route('admin.users.index') }}" 
                   class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">
                    <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    رجوع
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-3">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">الاسم</h4>
                        <p class="text-lg font-semibold text-gray-900">{{ $user->name }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">البريد الإلكتروني</h4>
                        <p class="text-lg font-semibold text-gray-900">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="border-t pt-6">
                    <h4 class="text-sm font-medium text-gray-500 mb-3">الأدوار المسندة</h4>
                    <div class="flex flex-wrap gap-2">
                        @forelse($user->roles as $role)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                {{ $role->name }}
                            </span>
                        @empty
                            <span class="text-sm text-gray-400">لا توجد أدوار مسندة</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
