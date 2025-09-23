<?php

namespace App\Actions\Articles;

use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use DomainException;

class PublishArticleAction
{
    public function __invoke(User $actor, Article $article, ?string $note = null): Article
    {
        return DB::transaction(function () use ($actor, $article, $note) {
            $article->refresh();

            if ($article->status !== 'pending') {
                throw new DomainException('Only pending articles can be published.');
            }

            $from = $article->status;
            $article->status = 'published';
            $article->published_at = now();
            $article->save();

            $article->transitions()->create([
                'from_status' => $from,
                'to_status'   => 'published',
                'acted_by'    => $actor->id,
                'note'        => $note,
            ]);

            event(new \App\Events\ArticlePublished($article, $actor, $note));

            return $article->fresh();
        });
    }
}
