<x-app-layout>
    <x-slot name="header" class="flex items-center justify-between">
        <span>المستخدمون</span>
        <a href="{{ route('admin.users.create') }}"
           class="rounded-lg bg-slate-900 text-white px-4 py-1.5">+ مستخدم</a>
    </x-slot>

    @include('admin.partials.flash')

    <form method="GET" class="mb-4">
        <input name="q" value="{{ request('q') }}" placeholder="بحث بالاسم أو الإيميل"
               class="w-full rounded-lg border-slate-300" />
    </form>

    <div class="rounded-xl border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-600">
                <tr>
                    <th class="p-3 text-start">#</th>
                    <th class="p-3 text-start">الاسم</th>
                    <th class="p-3 text-start">الإيميل</th>
                    <th class="p-3 text-start">الأدوار</th>
                    <th class="p-3 text-start">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($users as $u)
                <tr class="hover:bg-slate-50">
                    <td class="p-3">{{ $u->id }}</td>
                    <td class="p-3">{{ $u->name }}</td>
                    <td class="p-3">{{ $u->email }}</td>
                    <td class="p-3">
                        @forelse($u->roles as $r)
                            <span class="inline-block rounded-full bg-slate-100 text-slate-700 px-2 py-0.5 text-xs me-1">
                                {{ $r->name }}
                            </span>
                        @empty
                            <span class="text-xs text-slate-400">بدون دور</span>
                        @endforelse
                    </td>
                    <td class="p-3">
                        <div class="flex items-center gap-3">
                            <a class="text-slate-700 hover:text-slate-900" href="{{ route('admin.users.show', $u) }}">عرض</a>
                            <a class="text-slate-700 hover:text-slate-900" href="{{ route('admin.users.edit', $u) }}">تعديل</a>
                            <form method="POST" action="{{ route('admin.users.destroy', $u) }}"
                                  onsubmit="return confirm('حذف المستخدم؟');">
                                @csrf @method('DELETE')
                                <button class="text-rose-600 hover:text-rose-700">حذف</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $users->links() }}</div>
</x-app-layout>
