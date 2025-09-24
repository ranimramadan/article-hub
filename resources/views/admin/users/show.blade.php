<x-app-layout>
    <x-slot name="header">عرض مستخدم #{{ $user->id }}</x-slot>

    @include('admin.partials.flash')

    <div class="rounded-xl border p-4">
        <div class="text-slate-800 font-semibold text-lg">{{ $user->name }}</div>
        <div class="text-slate-500">{{ $user->email }}</div>

        <div class="mt-3">
            <div class="text-sm text-slate-600 mb-1">الأدوار:</div>
            @forelse($user->roles as $r)
                <span class="inline-block rounded-full bg-slate-100 text-slate-700 px-2 py-0.5 text-xs me-1">
                    {{ $r->name }}
                </span>
            @empty
                <span class="text-xs text-slate-400">بدون دور</span>
            @endforelse
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('admin.users.edit', $user) }}" class="rounded-lg bg-slate-900 text-white px-4 py-1.5">تعديل</a>
        <a href="{{ route('admin.users.index') }}" class="rounded-lg border px-4 py-1.5">رجوع</a>
    </div>
</x-app-layout>
