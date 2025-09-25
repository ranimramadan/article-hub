<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">تعديل: {{ $article->title }}</h2></x-slot>

    <div class="py-6 max-w-4xl mx-auto px-4">
        @include('admin.partials.flash')

        <form method="post" action="{{ route('user.articles.update',$article) }}" class="space-y-4">
            @csrf @method('PUT')

            <div>
                <label class="block mb-1">العنوان</label>
                <input name="title" type="text" class="w-full border rounded p-2" required value="{{ old('title',$article->title) }}">
            </div>

            <div>
                <label class="block mb-1">المحتوى</label>
                <textarea name="body" rows="10" class="w-full border rounded p-2">{{ old('body',$article->body) }}</textarea>
            </div>

            @if($categories->count())
                <div>
                    <label class="block mb-1">التصنيفات</label>
                    <select name="category_ids[]" multiple class="w-full border rounded p-2">
                        @php $sel = $article->categories->pluck('id')->all(); @endphp
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" @selected(in_array($c->id,$sel))>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            @if($tags->count())
                <div>
                    <label class="block mb-1">الوسوم</label>
                    <select name="tag_ids[]" multiple class="w-full border rounded p-2">
                        @php $sel = $article->tags->pluck('id')->all(); @endphp
                        @foreach($tags as $t)
                            <option value="{{ $t->id }}" @selected(in_array($t->id,$sel))>{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="flex gap-2 items-center">
                <button class="px-4 py-2 rounded bg-black text-white">حفظ</button>

                @if($article->status==='draft')
                    <form method="post" action="{{ route('user.articles.submit',$article) }}" class="flex gap-2 items-center">
                        @csrf
                        <input name="note" type="text" class="border rounded p-2" placeholder="ملاحظة للمراجعة (اختياري)">
                        <button class="px-4 py-2 rounded bg-blue-600 text-white">طلب نشر</button>
                    </form>
                @endif

                <a href="{{ route('user.articles.transitions',$article) }}" class="underline">سجل الحالة</a>

                <form action="{{ route('user.articles.destroy',$article) }}" method="post" onsubmit="return confirm('حذف المقال؟');" class="ms-auto">
                    @csrf @method('DELETE')
                    <button class="px-3 py-2 rounded bg-red-600 text-white">حذف</button>
                </form>
            </div>
        </form>
    </div>
</x-app-layout>
