<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name','slug'];

    public function articles() { return $this->belongsToMany(Article::class, 'article_tag')->withTimestamps(); }
    public function scopeBySlug($q, string $slug){ return $q->where('slug',$slug); }
}
