<x-app-layout>
    <x-slot name="header">إنشاء مستخدم</x-slot>

    @include('admin.partials.flash')

    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm mb-1">الاسم</label>
            <input name="name" value="{{ old('name') }}" class="w-full rounded-lg border-slate-300" required />
        </div>

        <div>
            <label class="block text-sm mb-1">الإيميل</label>
            <input name="email" type="email" value="{{ old('email') }}" class="w-full rounded-lg border-slate-300" required />
        </div>

        <div>
            <label class="block text-sm mb-1">كلمة المرور (اتركها فارغة لتوليد عشوائي)</label>
            <input name="password" type="password" class="w-full rounded-lg border-slate-300" />
        </div>

        <div>
            <label class="block text-sm mb-1">الأدوار</label>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-2 p-3 rounded-xl border max-h-72 overflow-auto">
                @foreach($roles as $r)
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="roles[]" value="{{ $r->id }}" class="rounded border-slate-300">
                        <span>{{ $r->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="flex gap-2">
            <button class="rounded-lg bg-slate-900 text-white px-4 py-1.5">حفظ</button>
            <a href="{{ route('admin.users.index') }}" class="rounded-lg border px-4 py-1.5">إلغاء</a>
        </div>
    </form>
</x-app-layout>
