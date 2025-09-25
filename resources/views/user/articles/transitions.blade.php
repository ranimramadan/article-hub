<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">سجل الحالة: {{ $article->title }}</h2></x-slot>

    <div class="py-6 max-w-4xl mx-auto px-4">
        <a href="{{ route('user.articles.edit',$article) }}" class="underline">رجوع للتعديل</a>

        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="border p-2">من</th>
                        <th class="border p-2">إلى</th>
                        <th class="border p-2">بواسطة</th>
                        <th class="border p-2">ملاحظة</th>
                        <th class="border p-2">التاريخ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $t)
                        <tr>
                            <td class="border p-2">{{ $t->from_status }}</td>
                            <td class="border p-2">{{ $t->to_status }}</td>
                            <td class="border p-2">{{ optional($t->actor)->name }}</td>
                            <td class="border p-2">{{ $t->note }}</td>
                            <td class="border p-2">{{ $t->created_at }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="border p-2">لا يوجد سجل.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
