<x-app-layout>
    <x-slot name="header">تعديل صلاحية: {{ $permission->name }}</x-slot>

    @include('admin.partials.flash')

    <form method="POST" action="{{ route('admin.permissions.update', $permission) }}" class="space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="block text-sm mb-1">اسم الصلاحية</label>
            <input name="name" value="{{ old('name', $permission->name) }}" class="w-full rounded-lg border-slate-300" required />
        </div>

        <div class="flex gap-2">
            <button class="rounded-lg bg-slate-900 text-white px-4 py-1.5">تحديث</button>
            <a href="{{ route('admin.permissions.index') }}" class="rounded-lg border px-4 py-1.5">رجوع</a>
        </div>
    </form>
</x-app-layout>
