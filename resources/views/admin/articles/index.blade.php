<x-app-layout>
    <x-slot name="header">كل المقالات</x-slot>

    @include('admin.partials.flash')

    <form method="GET" class="mb-4 flex gap-2">
        <input name="q" value="{{ request('q') }}" placeholder="بحث بالعنوان"
               class="rounded-lg border-slate-300" />
        <select name="status" class="rounded-lg border-slate-300">
            <option value="">كل الحالات</option>
            @foreach (['draft'=>'مسودة','pending'=>'بانتظار','published'=>'منشور','rejected'=>'مرفوض'] as $k=>$v)
                <option value="{{ $k }}" @selected(request('status')===$k)>{{ $v }}</option>
            @endforeach
        </select>
        <button class="rounded-lg bg-slate-900 text-white px-4">تصفية</button>
    </form>

    <div class="rounded-xl border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-600">
                <tr>
                    <th class="p-3 text-start">#</th>
                    <th class="p-3 text-start">العنوان</th>
                    <th class="p-3 text-start">الكاتب</th>
                    <th class="p-3 text-start">الحالة</th>
                    <th class="p-3 text-start">أنشئ</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($items as $a)
                <tr class="hover:bg-slate-50">
                    <td class="p-3">{{ $a->id }}</td>
                    <td class="p-3">{{ $a->title }}</td>
                    <td class="p-3">{{ $a->author?->name ?? '—' }}</td>
                    <td class="p-3">
                        <span class="rounded-full px-2 py-0.5 text-xs
                            @class([
                                'bg-yellow-50 text-yellow-700' => $a->status==='pending',
                                'bg-emerald-50 text-emerald-700' => $a->status==='published',
                                'bg-slate-100 text-slate-700' => $a->status==='draft',
                                'bg-rose-50 text-rose-700' => $a->status==='rejected',
                            ])">
                            {{ $a->status }}
                        </span>
                    </td>
                    <td class="p-3">{{ optional($a->created_at)->format('Y-m-d H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $items->links() }}</div>
</x-app-layout>
