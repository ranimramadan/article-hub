<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class PublicArticlesController extends Controller
{
    
    public function index(Request $request)
    {
        $q = Article::with(['author:id,name','categories:id,name,slug','tags:id,name,slug'])
            ->published()
            ->search($request->query('q'))
            ->orderByDesc('published_at');

        if ($slug = $request->query('category')) {
            $q->whereHas('categories', fn($qq) => $qq->where('slug', $slug));
        }
        if ($slug = $request->query('tag')) {
            $q->whereHas('tags', fn($qq) => $qq->where('slug', $slug));
        }

        return view('articles.index', [
            'items' => $q->paginate(12),
            'q'     => $request->query('q'),
        ]);
    }

    public function show(Article $article)
    {
        abort_unless($article->status === 'published', 404);
        $article->load(['author:id,name','categories:id,name,slug','tags:id,name,slug','media']);
        return view('articles.show', compact('article'));
    }
}
