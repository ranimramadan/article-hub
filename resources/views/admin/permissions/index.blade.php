<x-app-layout>
    <x-slot name="header" class="flex items-center justify-between">
        <span>الصلاحيات</span>
        <a href="{{ route('admin.permissions.create') }}"
           class="rounded-lg bg-slate-900 text-white px-4 py-1.5">+ صلاحية</a>
    </x-slot>

    @include('admin.partials.flash')

    <div class="rounded-xl border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-600">
                <tr>
                    <th class="p-3 text-start">الاسم</th>
                    <th class="p-3 text-start">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($permissions as $p)
                <tr class="hover:bg-slate-50">
                    <td class="p-3">{{ $p->name }}</td>
                    <td class="p-3">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.permissions.edit', $p) }}" class="text-slate-700 hover:text-slate-900">
                                تعديل
                            </a>
                            <form method="POST" action="{{ route('admin.permissions.destroy', $p) }}"
                                  onsubmit="return confirm('حذف الصلاحية؟');">
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

    <div class="mt-4">{{ $permissions->links() }}</div>
</x-app-layout>
