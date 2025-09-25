<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-blue-600 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">مقالات بانتظار المراجعة</h2>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-3">
        @if($items->count() === 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="text-center text-gray-500">لا توجد مقالات قيد الانتظار.</div>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">العنوان</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الكاتب</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">أنشئ في</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ملاحظة</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($items as $a)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $a->title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $a->author?->name ?? '—' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ optional($a->created_at)->format('Y-m-d H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    <input form="pub-{{ $a->id }}" type="text" name="note" 
                                           placeholder="ملاحظة (اختياري)"
                                           class="w-48 rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500" />
                                    <input form="rej-{{ $a->id }}" type="hidden" name="note" value="" />
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <form id="pub-{{ $a->id }}" method="POST" action="{{ route('admin.articles.publish', $a) }}">
                                            @csrf
                                            <button class="inline-flex items-center px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-black text-sm font-medium rounded-lg transition-all duration-200">
                                                <svg class="w-5 h-5 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                نشر
                                            </button>
                                        </form>

                                        <form id="rej-{{ $a->id }}" method="POST" action="{{ route('admin.articles.reject', $a) }}">
                                            @csrf
                                            <button class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                                                <svg class="w-5 h-5 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                رفض
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-3">
                {{ $items->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
