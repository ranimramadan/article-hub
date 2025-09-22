<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name','slug','parent_id'];

    public function parent()    { return $this->belongsTo(Category::class, 'parent_id'); }
    public function children()  { return $this->hasMany(Category::class, 'parent_id'); }
    public function articles()  { return $this->belongsToMany(Article::class, 'article_category')->withTimestamps(); }

    public function scopeBySlug($q, string $slug){ return $q->where('slug',$slug); }
}
