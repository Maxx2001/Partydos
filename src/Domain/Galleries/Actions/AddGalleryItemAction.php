<?php

namespace Domain\Galleries\Actions;

use Domain\Galleries\DataTransferObjects\GalleryItemData;
use Domain\Galleries\Models\Gallery;
use Domain\Galleries\Models\GalleryItem;

class AddGalleryItemAction
{
    public function execute(Gallery $gallery, GalleryItemData $data): GalleryItem
    {
        return $gallery->items()->create([
            'external_photo_id' => $data->external_photo_id,
            'type' => $data->type,
            'metadata' => $data->metadata,
        ]);
    }
}
