<aside class="w-64 bg-white shadow-lg min-h-screen border-e">
    <div class="p-4 border-b">
        <h2 class="text-lg font-semibold text-gray-800 flex items-center justify-center">
            <svg class="w-5 h-5 me-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            لوحة الأدمن
        </h2>
    </div>
    
    <nav class="p-2">
        <div class="mb-4">
            <h3 class="px-3 py-2 text-xs font-medium text-gray-500">
                إدارة المحتوى
            </h3>
            <div>
                <a href="{{ route('admin.articles.pending') }}" 
                   class="flex items-center px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-md transition-colors {{ request()->routeIs('admin.articles.pending') ? 'bg-gray-100 text-blue-600 font-medium' : '' }}">
                    <svg class="w-5 h-5 me-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    المقالات المعلّقة
                </a>
            </div>
        </div>

        <div>
            <h3 class="px-3 py-2 text-xs font-medium text-gray-500">
                إدارة النظام
            </h3>
            <div class="space-y-1">
                <a href="{{ route('admin.users.index') }}" 
                   class="flex items-center px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-md transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-gray-100 text-blue-600 font-medium' : '' }}">
                    <svg class="w-5 h-5 me-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    المستخدمون
                </a>

                <a href="{{ route('admin.roles.index') }}" 
                   class="flex items-center px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-md transition-colors {{ request()->routeIs('admin.roles.*') ? 'bg-gray-100 text-blue-600 font-medium' : '' }}">
                    <svg class="w-5 h-5 me-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    الأدوار
                </a>

                <a href="{{ route('admin.permissions.index') }}" 
                   class="flex items-center px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-md transition-colors {{ request()->routeIs('admin.permissions.*') ? 'bg-gray-100 text-blue-600 font-medium' : '' }}">
                    <svg class="w-5 h-5 me-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                    الصلاحيات
                </a>
            </div>
        </div>
    </nav>
</aside>