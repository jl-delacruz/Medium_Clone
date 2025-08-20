<?php

namespace App\MediaLibrary;

use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AvatarPathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        $user = $media->model; // Assuming the media is attached directly to a User model
        $username = $user?->username ?? 'guest';

        return "avatars/{$username}/";
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
