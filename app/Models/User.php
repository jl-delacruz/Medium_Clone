<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class User extends Authenticatable implements MustVerifyEmail, HasMedia
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'image',
        'bio',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('avatar')
            ->singleFile()
            ->useDisk('public')
            ->registerMediaConversions(function (Media $media = null) {
                $this
                    ->addMediaConversion('avatar')
                    ->width(128)
                    ->fit(Fit::Crop, 128, 128)
                    ->nonQueued(); // 👈 generate immediately instead of waiting for queue
            });
    }

    /**
     * Get the user's posts.
     */
    // user->userImageUrl()
    public function userImageUrl(): string
    {
        $media = $this->getFirstMedia('avatar');

        // If no media, return default avatar
        if (!$media) {
            return '/path/to/default/avatar.png';
        }

        // If conversion exists, return conversion URL
        if ($media->hasGeneratedConversion('avatar')) {
            return $media->getUrl('avatar'); // returns /conversions/...-avatar.jpg
        }

        // Fallback to original if conversion not yet generated
        return $media->getUrl();
    }


    /**
     * Get the posts for the user.
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get the users that the user is following.
     */
    public function following()
    {
        //
        return $this->belongsToMany(
            User::class,
            'followers',
            'follower_id',
            'user_id'
        );
    }

    /**
     * Get the followers of the user.
     */
    public function followers()
    {
        return $this->belongsToMany(
            User::class,
            'followers',
            'user_id',
            'follower_id'
        );
    }

    public function isFollowedBy(?User $user): bool
    {
        // Check if the user is null
        if (!$user) {
            return false;
        }
        return $this->followers()->where('follower_id', $user->id)->exists();
    }

    public function hasClapped(Post $post): bool
    {
        return $post->claps()->where('user_id', $this->id)->exists();
    }
}
