<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;

class ArticlePolicy
{
    public function view(?User $user, Article $article): bool
    {
        return $article->status === 'published' || ($user && $article->user_id === $user->id);
    }

    public function create(User $user): bool
    {
        return true; 
    }

    public function update(User $user, Article $article): bool
    {
        if ($article->user_id === $user->id && in_array($article->status, ['draft','rejected'])) {
            return true;
        }
        return $user->hasRole('admin'); 
    }

    public function delete(User $user, Article $article): bool
    {
        if ($article->user_id === $user->id && in_array($article->status, ['draft','rejected'])) {
            return true;
        }
        return $user->hasRole('admin');
    }

    public function submit(User $user, Article $article): bool
    {
        return $article->user_id === $user->id && $article->status === 'draft';
    }
}
