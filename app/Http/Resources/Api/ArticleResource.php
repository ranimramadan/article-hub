<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'body'         => $this->body,
            'status'       => $this->status,
            'published_at' => optional($this->published_at)?->toISOString(),

            'author' => $this->whenLoaded('author', fn() => [
                'id' => $this->author->id,
                'name' => $this->author->name,
            ]),

            'categories' => $this->whenLoaded('categories', fn() =>
                $this->categories->map(fn($c) => [
                    'id' => $c->id, 'name' => $c->name, 'slug' => $c->slug
                ])
            ),

            'tags' => $this->whenLoaded('tags', fn() =>
                $this->tags->map(fn($t) => [
                    'id' => $t->id, 'name' => $t->name, 'slug' => $t->slug
                ])
            ),
        ];
    }
}
