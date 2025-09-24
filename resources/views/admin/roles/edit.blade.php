<x-app-layout>
    <x-slot name="header">تعديل دور: {{ $role->name }}</x-slot>

    @include('admin.partials.flash')

    <form method="POST" action="{{ route('admin.roles.update', $role) }}" class="space-y-4">
        @csrf @method('PUT')

        <div>
            <label class="block text-sm mb-1">اسم الدور</label>
            <input name="name" value="{{ old('name', $role->name) }}" class="w-full rounded-lg border-slate-300" required />
        </div>

        <div>
            <label class="block text-sm mb-1">الصلاحيات</label>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-2 p-3 rounded-xl border">
                @foreach($permissions as $p)
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="permissions[]" value="{{ $p->id }}"
                               @checked(in_array($p->id, old('permissions', $rolePermissions ?? [])))
                               class="rounded border-slate-300">
                        <span>{{ $p->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="flex gap-2">
            <button class="rounded-lg bg-slate-900 text-white px-4 py-1.5">تحديث</button>
            <a href="{{ route('admin.roles.index') }}" class="rounded-lg border px-4 py-1.5">رجوع</a>
        </div>
    </form>
</x-app-layout>
