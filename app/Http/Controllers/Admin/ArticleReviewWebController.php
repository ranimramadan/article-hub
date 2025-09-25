<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Actions\Articles\PublishArticleAction;
use App\Actions\Articles\RejectArticleAction;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate; 

class ArticleReviewWebController extends Controller
{
    // قائمة الانتظار
    public function pending()
    {
        $items = Article::with('author:id,name')
            ->where('status', 'pending')
            ->latest('created_at')
            ->paginate(15);

        return view('admin.articles.pending', compact('items'));
    }

    // كل المقالات مع فلتر الحالة/بحث
    public function index(Request $request)
    {
        $q = Article::with('author:id,name')->latest('created_at');

        if ($s = $request->query('status')) {
            $q->where('status', $s);
        }
        if ($k = $request->query('q')) {
            $q->where('title', 'like', "%{$k}%");
        }

        $items = $q->paginate(20)->withQueryString();
        return view('admin.articles.index', compact('items'));
    }

    public function publish(Request $request, Article $article, PublishArticleAction $publish)
    {
        Gate::authorize('publish', $article); // بدل authorize()

        // idempotency
        if ($article->status === 'published') {
            return back()->with('ok', 'The article was previously published.');
        }

        $publish($request->user(), $article, $request->input('note'));
        return back()->with('ok', 'The article was published successfully.');
    }

    public function reject(Request $request, Article $article, RejectArticleAction $reject)
    {
        Gate::authorize('reject', $article); 
        

        if ($article->status === 'rejected') {
            return back()->with('ok', 'Article already rejected.');
        }

        $reject($request->user(), $article, $request->input('note'));
        return back()->with('ok', 'The article was rejected.');
    }
}
