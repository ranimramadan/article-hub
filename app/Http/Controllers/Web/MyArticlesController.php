<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleTransition;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class MyArticlesController extends Controller
{
    
    public function index(Request $request)
    {
        $q = Article::query()
            ->with(['categories:id,name,slug','tags:id,name,slug'])
            ->where('user_id', $request->user()->id)
            ->when($request->query('status'), fn($qq, $s) => $qq->where('status', $s))
            ->latest('created_at');

        
        return view('user.articles.index', [
            'items'  => $q->paginate(12),
            'status' => $request->query('status')
        ]);
    }

    public function create()
    {
        [$categories, $tags] = $this->loadTaxonomies();
        return view('user.articles.create', compact('categories','tags'));
    }

    public function store(Request $request)
    {
        $data = $this->validateArticle($request);

        $article = new Article($data);
        $article->user_id = $request->user()->id;
        $article->status  = 'draft';
        $article->slug    = $this->uniqueSlug($data['title']);
        $article->save();

        if (!empty($data['category_ids'] ?? null)) $article->categories()->sync($data['category_ids']);
        if (!empty($data['tag_ids'] ?? null))       $article->tags()->sync($data['tag_ids']);

        return redirect()->route('user.articles.edit', $article)
            ->with('ok','The draft has been created. You can continue editing or request publishing.');
    }

    public function edit(Request $request, Article $article)
    {
        $this->authorize('update', $article);

        [$categories, $tags] = $this->loadTaxonomies();
        $article->load(['categories:id','tags:id']);

        return view('user.articles.edit', compact('article','categories','tags'));
    }

    public function update(Request $request, Article $article)
    {
        $this->authorize('update', $article);

        $data = $this->validateArticle($request, $article->id);

        
        $article->fill(collect($data)->except(['category_ids','tag_ids'])->toArray())->save();

        if (array_key_exists('category_ids', $data)) $article->categories()->sync($data['category_ids'] ?? []);
        if (array_key_exists('tag_ids', $data))       $article->tags()->sync($data['tag_ids'] ?? []);

        return back()->with('ok','The draft has been updated.');
    }

    public function destroy(Request $request, Article $article)
    {
        $this->authorize('delete', $article);
        $article->delete();
        return redirect()->route('user.articles.index')->with('ok','The article has been deleted.');
    }

    // طلب نشر: draft -> pending
    public function submit(Request $request, Article $article)
    {
        $this->authorize('submit', $article);

        DB::transaction(function () use ($request, $article) {
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
        });

        return back()->with('ok','The article has been sent for review.');
    }

    // عرض سجل تغيّر الحالة (للمالك)
    public function transitions(Request $request, Article $article)
    {
        $this->authorize('view', $article);

        $items = $article->transitions()
            ->with('actor:id,name')
            ->latest()
            ->get(['id','from_status','to_status','note','acted_by','created_at']);

        // TODO: اعملي Blade لاحقًا
        return view('user.articles.transitions', compact('article','items'));
    }

    // --------- Helpers ---------

    private function validateArticle(Request $request, ?int $articleId = null): array
    {
        $rules = [
            'title'        => ['required','string','max:200'],
            'excerpt'      => ['nullable','string','max:500'],
            'body'         => ['nullable','string'],
            'category_ids' => ['nullable','array'],
            'category_ids.*' => ['integer','exists:categories,id'],
            'tag_ids'      => ['nullable','array'],
            'tag_ids.*'    => ['integer','exists:tags,id'],        
        ];

        return $request->validate($rules);
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;

        while (Article::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }
        return $slug;
    }

    private function loadTaxonomies(): array
    {
        $categories = class_exists(\App\Models\Category::class)
            ? \App\Models\Category::query()->orderBy('name')->get(['id','name','slug'])
            : collect();

        $tags = class_exists(\App\Models\Tag::class)
            ? \App\Models\Tag::query()->orderBy('name')->get(['id','name','slug'])
            : collect();

        return [$categories, $tags];
    }
}
