<?php

namespace App\Models;

use Illuminate\Console\Concerns\InteractsWithIO;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;

class Post extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    // use HasSlug;

    protected $fillable = [
        'title',
        'content',
        'user_id',
        'category_id',
        'published_at',
        'slug',
    ];
    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->width(400);
    }

    // public function getSlugOptions(): SlugOptions
    // {
    //     return SlugOptions::create()
    //         ->generateSlugsFrom('title')
    //         ->saveSlugsTo('slug');
    // }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function readTime()
    {
        $wordCount = str_word_count(strip_tags($this->content));
        $readingSpeed = 200; // Average reading speed in words per minute
        $minutes = (int) ceil($wordCount / $readingSpeed);
        return max(1, $minutes);
    }

    
    public function imageUrl($conversionName = '')
    {
        //get original image
        $media = $this->getFirstMedia();
        if ($media->hasGeneratedConversion($conversionName)) {
            return $media->getUrl($conversionName);
        }
        return $media->getUrl();
    }


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function claps()
    {
        return $this->hasMany(Claps::class);
    }
}
