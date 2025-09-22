<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Like extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','article_id'];

    public function user()    { return $this->belongsTo(User::class); }
    public function article() { return $this->belongsTo(Article::class); }

    public function scopeForUser($q, $userId){ return $q->where('user_id',$userId); }
    public function scopeForArticle($q,$articleId){ return $q->where('article_id',$articleId); }
}
