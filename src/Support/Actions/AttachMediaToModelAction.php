<?php

namespace Support\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Support\Helpers\MediaFilter;

class AttachMediaToModelAction
{
    public static function execute(array $media, Model $model, ?string $fileSystemExtension = null): void
    {
        if (empty($media)) {
            return;
        }

        $newMedia = MediaFilter::filterNewMedia($media);
        $modelName = strtolower(class_basename($model::class));

        if ($fileSystemExtension) {
            $modelName = $modelName . $fileSystemExtension;
        }

        foreach ($newMedia as $mediaItem) {
            $mediaPath = $mediaItem->store(options: $modelName);
            $model
                ->addMedia(Storage::disk($modelName)
                    ->path($mediaPath))
                ->toMediaCollection($modelName, $modelName);
        }
    }
}
