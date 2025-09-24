<x-app-layout>
    <x-slot name="header">مقالات بانتظار المراجعة</x-slot>

    @include('admin.partials.flash')

    @if($items->count() === 0)
        <div class="rounded-xl border p-6 text-slate-600">لا توجد مقالات قيد الانتظار.</div>
    @else
        <div class="rounded-xl border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="p-3 text-start">العنوان</th>
                        <th class="p-3 text-start">الكاتب</th>
                        <th class="p-3 text-start">أنشئ في</th>
                        <th class="p-3 text-start">ملاحظة</th>
                        <th class="p-3 text-start">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($items as $a)
                        <tr class="hover:bg-slate-50">
                            <td class="p-3 font-medium text-slate-800">{{ $a->title }}</td>
                            <td class="p-3">{{ $a->author?->name ?? '—' }}</td>
                            <td class="p-3">{{ optional($a->created_at)->format('Y-m-d H:i') }}</td>
                            <td class="p-3">
                                <input form="pub-{{ $a->id }}" type="text" name="note" placeholder="ملاحظة (اختياري)"
                                       class="w-48 rounded-lg border-slate-300" />
                                <input form="rej-{{ $a->id }}" type="hidden" name="note" value="" />
                            </td>
                            <td class="p-3">
                                <div class="flex items-center gap-2">
                                    <form id="pub-{{ $a->id }}" method="POST" action="{{ route('admin.articles.publish', $a) }}">
                                        @csrf
                                        <button class="rounded-lg bg-emerald-600 text-white px-3 py-1.5 hover:bg-emerald-700">
                                            نشر
                                        </button>
                                    </form>

                                    <form id="rej-{{ $a->id }}" method="POST" action="{{ route('admin.articles.reject', $a) }}">
                                        @csrf
                                        <button class="rounded-lg bg-rose-600 text-white px-3 py-1.5 hover:bg-rose-700">
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

        <div class="mt-4">{{ $items->links() }}</div>
    @endif
</x-app-layout>
