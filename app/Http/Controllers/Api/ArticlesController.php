<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreArticleRequest;
use App\Http\Requests\V1\UpdateArticleRequest;
use App\Http\Resources\V1\ArticleResource;
use App\Models\Article;
use App\Models\ArticleTransition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticlesController extends Controller
{
    // عام: قائمة المنشور مع بحث/تصنيف/وسوم
    public function index(Request $request)
    {
        $q = Article::with(['author:id,name','categories:id,name,slug','tags:id,name,slug'])
            ->published()
            ->search($request->query('q'))
            ->orderLatestPub();

        if ($slug = $request->query('category')) {
            $q->whereHas('categories', fn($x) => $x->where('slug',$slug));
        }
        if ($slug = $request->query('tag')) {
            $q->whereHas('tags', fn($x) => $x->where('slug',$slug));
        }

        return ArticleResource::collection($q->paginate(12));
    }

    // عام: إظهار مقال منشور فقط
    public function show(Article $article)
    {
        abort_unless($article->status === 'published', 404);
        $article->load(['author:id,name','categories:id,name,slug','tags:id,name,slug','media']);
        return new ArticleResource($article);
    }

    // محمي: “مقالاتي”
    public function mine(Request $request)
    {
        $q = Article::with('categories:id,name,slug','tags:id,name,slug')
            ->ownedBy($request->user())
            ->when($request->query('status'), fn($qq,$s)=>$qq->status($s))
            ->orderLatestCreated();

        return ArticleResource::collection($q->paginate(12));
    }

    // محمي: إنشاء
    public function store(StoreArticleRequest $request)
    {
        $user = $request->user();
        $article = new Article($request->validated());
        $article->user_id = $user->id; // author
        $article->status = 'draft';
        $article->save();

        // ربط تصنيفات/وسوم (اختياري)
        if ($cats = $request->input('category_ids')) {
            $article->categories()->sync($cats);
        }
        if ($tags = $request->input('tag_ids')) {
            $article->tags()->sync($tags);
        }

        // ميديا (اختياري لاحقًا بالويب/رفع ملفات)
        return (new ArticleResource($article->load('categories','tags')))->response()->setStatusCode(201);
    }

    // محمي: تعديل (مسموح للمؤلف عندما draft/rejected)
    public function update(UpdateArticleRequest $request, Article $article)
    {
        $this->authorize('update', $article);

        $article->fill($request->validated());
        $article->save();

        if ($cats = $request->input('category_ids')) {
            $article->categories()->sync($cats);
        }
        if ($tags = $request->input('tag_ids')) {
            $article->tags()->sync($tags);
        }

        return new ArticleResource($article->load('categories','tags'));
    }

    // محمي: حذف (مسموح عندما draft/rejected)
    public function destroy(Request $request, Article $article)
    {
        $this->authorize('delete', $article);
        $article->delete();
        return response()->noContent();
    }

    // محمي: طلب نشر (draft -> pending)
    public function submit(Request $request, Article $article)
    {
        $this->authorize('submit', $article);

        $from = $article->status;
        $article->status = 'pending';
        $article->save();

        ArticleTransition::create([
            'article_id'  => $article->id,
            'from_status' => $from,
            'to_status'   => 'pending',
            'acted_by'    => $request->user()->id,
            'note'        => $request->input('note'),
        ]);

        return response()->json(['message' => 'Submitted for review.']);
    }
}
