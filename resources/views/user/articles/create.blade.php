<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">إنشاء مقالة</h2></x-slot>

    <div class="py-6 max-w-4xl mx-auto px-4">
        @include('admin.partials.flash')

        <form method="post" action="{{ route('user.articles.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block mb-1">العنوان</label>
                <input name="title" type="text" class="w-full border rounded p-2" required value="{{ old('title') }}">
            </div>

            <div>
                <label class="block mb-1">المحتوى</label>
                <textarea name="body" rows="10" class="w-full border rounded p-2">{{ old('body') }}</textarea>
            </div>

            @if($categories->count())
                <div>
                    <label class="block mb-1">التصنيفات</label>
                    <select name="category_ids[]" multiple class="w-full border rounded p-2">
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            @if($tags->count())
                <div>
                    <label class="block mb-1">الوسوم</label>
                    <select name="tag_ids[]" multiple class="w-full border rounded p-2">
                        @foreach($tags as $t)
                            <option value="{{ $t->id }}">{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="flex gap-2">
                <button class="px-4 py-2 rounded bg-black text-white">حفظ</button>
                <a href="{{ route('user.articles.index') }}" class="px-4 py-2 rounded border">رجوع</a>
            </div>
        </form>
    </div>
</x-app-layout>
