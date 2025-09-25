<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;

class ArticlePolicy
{
    // عرض: المنشور للجميع؛ غير المنشور للمالك أو الأدمن
    public function view(?User $user, Article $article): bool
    {
        return $article->status === 'published'
            || ($user && ($article->user_id === $user->id || $user->hasRole('admin')));
    }

    // إنشاء: أي مستخدم مسجّل
    public function create(User $user): bool
    {
        return true;
    }

    // تعديل: المالك عندما Draft/Rejected، أو الأدمن
    public function update(User $user, Article $article): bool
    {
        if ($article->user_id === $user->id && in_array($article->status, ['draft','rejected'])) {
            return true;
        }
        return $user->hasRole('admin');
    }

    // حذف: المالك عندما Draft/Rejected، أو الأدمن
    public function delete(User $user, Article $article): bool
    {
        if ($article->user_id === $user->id && in_array($article->status, ['draft','rejected'])) {
            return true;
        }
        return $user->hasRole('admin');
    }

    // إرسال للمراجعة: المالك فقط عندما Draft
    public function submit(User $user, Article $article): bool
    {
        return $article->user_id === $user->id && $article->status === 'draft';
    }

    // مراجعة/نشر/رفض: الأدمن فقط، والمقال Pending
    public function publish(User $user, Article $article): bool
    {
        return $user->hasRole('admin') && $article->status === 'pending';
    }

    public function reject(User $user, Article $article): bool
    {
        return $user->hasRole('admin') && $article->status === 'pending';
    }
       // سحب الطلب: فقط من pending → draft
    // public function withdraw(User $user, Article $article): bool
    // {
    //     return $article->user_id === $user->id
    //         && $article->status === 'pending';
    // }
}

