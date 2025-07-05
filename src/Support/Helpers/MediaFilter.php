<?php

namespace Support\Helpers;

use Illuminate\Http\UploadedFile;

class MediaFilter
{
    /** 
     * @param array<int, mixed> $media 
     * @return array<int, UploadedFile>
     */
    public static function filterNewMedia(array $media): array
    {
        return array_filter($media, function ($item) {
            return $item instanceof UploadedFile;
        });
    }
}
