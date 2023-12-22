<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'image',
        'content',
        'published_at',
        'featured',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function author(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function categories(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function likes(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'post_like')->withTimestamps();
    }

    public  function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function contentCount(): string
    {
        return Str::limit(strip_tags($this->content), 100);
    }
    public function readingTime(): float
    {
        $count = round(str_word_count($this->content) / 250 );
        return ($count < 1 ) ? 1 : $count;
    }

    public function getThumbnailImage()
    {
       $isUrl = str_contains($this->image, 'http');
       return ($isUrl) ? $this->image : Storage::disk('public')->url($this->image);
    }

    public function scopeCategory($query , $category)
    {
        $query->whereHas('categories', function ($query) use($category){
            $query->where('slug', $category);
        });
    }
}
