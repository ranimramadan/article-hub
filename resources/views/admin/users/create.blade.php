<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-blue-600 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">إضافة مستخدم جديد</h2>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-3">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <form method="POST" action="{{ route('admin.users.store') }}" class="p-6 space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">الاسم</label>
                        <input name="name" value="{{ old('name') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">البريد الإلكتروني</label>
                        <input name="email" type="email" value="{{ old('email') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">كلمة المرور</label>
                        <input name="password" type="password" 
                               class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                        <p class="mt-1 text-sm text-gray-500">اتركها فارغة لتوليد كلمة مرور عشوائية</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">الأدوار</label>
                    <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        @foreach($roles as $r)
                            <label class="relative flex items-center">
                                <input type="checkbox" name="roles[]" value="{{ $r->id }}" 
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="mr-2 text-sm text-gray-700">{{ $r->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-4 border-t">
                    <button type="submit" 
                            class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                        <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        حفظ المستخدم
                    </button>
                    <a href="{{ route('admin.users.index') }}" 
                       class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">
                        <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
