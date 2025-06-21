<?php

namespace Domain\Galleries\DataTransferObjects;

use Spatie\LaravelData\Data;

class GalleryItemData extends Data
{
    public function __construct(
        public string $external_photo_id,
        public string $type, // e.g., 'photo'
        public ?array $metadata = null
    ) {}
}
