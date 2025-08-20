<?php

namespace App\MediaLibrary;

use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PostPathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        $post = $media->model;
        $user = $post->user;
        $post_id = $post->id;
        $username = $user?->username ?? 'guest';

        return "posts/{$username}/{$media->id}/{$post_id}/";
    }

    public function getPathForConversions(Media $media): string
    {
        return $this->getPath($media) . 'conversions/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getPath($media) . 'responsive/';
    }
}
