<?php

namespace App\Actions\Articles;

use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use DomainException;

class SubmitArticleAction
{
    public function __invoke(User $actor, Article $article, ?string $note = null): Article
    {
        return DB::transaction(function () use ($actor, $article, $note) {
            $article->refresh();

            if ($article->status !== 'draft' || $article->user_id !== $actor->id) {
                throw new DomainException('Only owner can submit a draft article.');
            }

            $from = $article->status;
            $article->status = 'pending';
            $article->save();

            $article->transitions()->create([
                'from_status' => $from,
                'to_status'   => 'pending',
                'acted_by'    => $actor->id,
                'note'        => $note,
            ]);

            

            return $article->fresh();
        });
    }
}
