<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Article extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['title', 'body', 'status','published_at','user_id'];
    protected $casts = [
        'published_at' => 'datetime'
    ];
    
    protected $appends = ['excerpt'];

    public function author()      { return $this->belongsTo(User::class, 'user_id'); }
    public function categories()  { return $this->belongsToMany(Category::class, 'article_category')->withTimestamps(); }
    public function tags()        { return $this->belongsToMany(Tag::class, 'article_tag')->withTimestamps(); }
    public function bookmarks()   { return $this->hasMany(Bookmark::class); }
    public function likes()       { return $this->hasMany(Like::class); }
    public function transitions() { return $this->hasMany(ArticleTransition::class); }

    
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->whereNotNull('published_at');
    }
    public function scopePending($q)          { return $q->where('status','pending'); }
    public function scopeDraft($q)            { return $q->where('status','draft'); }
    public function scopeRejected($q)         { return $q->where('status','rejected'); }
    public function scopeOwnedBy($q, $user)   { $id = is_numeric($user)?$user:$user->id; return $q->where('user_id',$id); }
    public function scopeOrderLatestPub($q)   { return $q->orderByDesc('published_at'); }
    public function scopeOrderLatestCreated($q){ return $q->latest('created_at'); }
    public function scopeSearch($q, ?string $term) {
        $term = trim((string)$term);
        if ($term==='') return $q;
        return $q->where(fn($qq)=>$qq->where('title','like',"%{$term}%")->orWhere('body','like',"%{$term}%"));
    }

    
    public function registerMediaCollections(): void {
        $this->addMediaCollection('cover')->singleFile();
        $this->addMediaCollection('images');
    }

    public function getExcerptAttribute()
    {
        return \Illuminate\Support\Str::limit(strip_tags($this->body), 150);
    }
}
