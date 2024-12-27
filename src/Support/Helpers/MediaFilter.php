<?php

namespace Support\Helpers;

use Illuminate\Http\UploadedFile;

class MediaFilter
{
    public static function filterNewMedia(array $media): array
    {
        return array_filter($media, function ($item) {
            return $item instanceof UploadedFile;
        });
    }
}
