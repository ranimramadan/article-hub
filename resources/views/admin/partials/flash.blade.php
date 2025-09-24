@if (session('ok'))
    <div class="mb-4 rounded-xl bg-green-50 text-green-800 px-4 py-3">
        {{ session('ok') }}
    </div>
@endif

@if ($errors->any())
    <div class="mb-4 rounded-xl bg-red-50 text-red-800 px-4 py-3">
        <div class="font-semibold mb-1">حدثت أخطاء:</div>
        <ul class="list-disc ms-5 text-sm">
            @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif
