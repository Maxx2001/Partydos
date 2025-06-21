<?php

namespace Domain\Galleries\Actions;

use Domain\Galleries\DataTransferObjects\GalleryData;
use Domain\Galleries\Models\Gallery;
use Domain\Users\Models\User;

class CreateGalleryAction
{
    public function execute(User $user, GalleryData $data): Gallery
    {
        return $user->galleries()->create([
            'name' => $data->name,
            'description' => $data->description,
        ]);
    }
}
