<x-app-layout>
    <x-slot name="header" class="flex items-center justify-between">
        <span>الأدوار</span>
        <a href="{{ route('admin.roles.create') }}"
           class="rounded-lg bg-slate-900 text-white px-4 py-1.5">+ دور جديد</a>
    </x-slot>

    @include('admin.partials.flash')

    <div class="rounded-xl border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-600">
                <tr>
                    <th class="p-3 text-start">الاسم</th>
                    <th class="p-3 text-start"># صلاحيات</th>
                    <th class="p-3 text-start">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($roles as $r)
                <tr class="hover:bg-slate-50">
                    <td class="p-3 font-medium">{{ $r->name }}</td>
                    <td class="p-3">{{ $r->permissions_count ?? $r->permissions()->count() }}</td>
                    <td class="p-3">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.roles.edit', $r) }}" class="text-slate-700 hover:text-slate-900">
                                تعديل
                            </a>
                            <form method="POST" action="{{ route('admin.roles.destroy', $r) }}"
                                  onsubmit="return confirm('حذف الدور؟');">
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

    <div class="mt-4">{{ $roles->links() }}</div>
</x-app-layout>
