<x-app-layout>
    <x-slot name="header">تعديل مستخدم: {{ $user->name }}</x-slot>

    @include('admin.partials.flash')

    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4">
        @csrf @method('PUT')

        <div>
            <label class="block text-sm mb-1">الاسم</label>
            <input name="name" value="{{ old('name', $user->name) }}" class="w-full rounded-lg border-slate-300" required />
        </div>

        <div>
            <label class="block text-sm mb-1">الإيميل</label>
            <input name="email" type="email" value="{{ old('email', $user->email) }}" class="w-full rounded-lg border-slate-300" required />
        </div>

        <div>
            <label class="block text-sm mb-1">كلمة مرور جديدة (اختياري)</label>
            <input name="password" type="password" class="w-full rounded-lg border-slate-300" />
        </div>

        <div>
            <label class="block text-sm mb-1">الأدوار</label>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-2 p-3 rounded-xl border max-h-72 overflow-auto">
                @foreach($roles as $r)
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="roles[]" value="{{ $r->id }}"
                               @checked(in_array($r->id, old('roles', $userRoles ?? [])))
                               class="rounded border-slate-300">
                        <span>{{ $r->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="flex gap-2">
            <button class="rounded-lg bg-slate-900 text-white px-4 py-1.5">تحديث</button>
            <a href="{{ route('admin.users.index') }}" class="rounded-lg border px-4 py-1.5">رجوع</a>
        </div>
    </form>
</x-app-layout>
