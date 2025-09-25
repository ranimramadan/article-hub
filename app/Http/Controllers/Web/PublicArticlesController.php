<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class PublicArticlesController extends Controller
{
    public function index(Request $request)
    {
        $articles = Article::query()
            ->published()
            ->when($request->search, function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('excerpt', 'like', "%{$search}%")
                      ->orWhere('content', 'like', "%{$search}%");
                });
            })
            ->with('author')
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        return view('articles.index', compact('articles'));
    }

    public function show(Article $article)
    {
        abort_if($article->status !== 'published', 404);
        
        return view('articles.show', compact('article'));
    }
}
