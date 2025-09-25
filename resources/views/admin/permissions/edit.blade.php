<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-blue-600 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">تعديل الصلاحية: {{ $permission->name }}</h2>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-3">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <form method="POST" action="{{ route('admin.permissions.update', $permission) }}" class="p-6 space-y-6">
                @csrf 
                @method('PUT')
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">اسم الصلاحية</label>
                    <input name="name" value="{{ old('name', $permission->name) }}" 
                           class="block w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500" 
                           required />
                </div>

                <div class="flex items-center justify-end gap-2 pt-4 border-t">
                    <button type="submit" 
                            class="inline-flex items-center px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-black text-sm font-medium rounded-lg transition-all duration-200">
                        <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        تحديث الصلاحية
                    </button>
                    <a href="{{ route('admin.permissions.index') }}" 
                       class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">
                        <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        رجوع
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
