<?php

namespace App\Actions\Articles;

use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use DomainException;

class RejectArticleAction
{
    public function __invoke(User $actor, Article $article, ?string $note = null): Article
    {
        return DB::transaction(function () use ($actor, $article, $note) {
            $article->refresh();

            if ($article->status !== 'pending') {
                throw new DomainException('Only pending articles can be rejected.');
            }

            $from = $article->status;
            $article->status = 'rejected';
            $article->save();

            $article->transitions()->create([
                'from_status' => $from,
                'to_status'   => 'rejected',
                'acted_by'    => $actor->id,
                'note'        => $note,
            ]);

            // 

            return $article->fresh();
        });
    }
}
