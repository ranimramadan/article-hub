<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArticleTransition extends Model
{
    use HasFactory;

    protected $fillable = ['article_id','from_status','to_status','acted_by','note'];

    public function article() { return $this->belongsTo(Article::class); }
    public function actor()   { return $this->belongsTo(User::class, 'acted_by'); }

    public function scopeForArticle($q,$articleId){ return $q->where('article_id',$articleId); }
    public function scopeLatestFirst($q){ return $q->orderByDesc('created_at'); }
}
