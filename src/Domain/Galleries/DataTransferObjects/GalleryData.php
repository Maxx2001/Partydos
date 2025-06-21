<?php

namespace Domain\Galleries\DataTransferObjects;

use Spatie\LaravelData\Data;

class GalleryData extends Data
{
    public function __construct(
        public string $name,
        public ?string $description
    ) {}
}
