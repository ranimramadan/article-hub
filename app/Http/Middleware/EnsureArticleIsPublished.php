<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureArticleIsPublished
{
    public function handle(Request $request, Closure $next)
    {
        $article = $request->route('article');
        
        if (!$article || $article->status !== 'published') {
            abort(404);
        }

        return $next($request);
    }
}