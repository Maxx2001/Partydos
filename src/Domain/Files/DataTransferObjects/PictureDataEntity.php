<?php

namespace Domain\Files\DataTransferObjects;

use Illuminate\Support\Facades\Storage;
use Spatie\LaravelData\Data;
use App\Web\Files\Actions\GenerateFileUrl;

class PictureDataEntity extends Data
{
    public function __construct(
        public int           $id,
        public string        $model_type,
        public int           $model_id,
        public string        $uuid,
        public string        $collection_name,
        public string        $name,
        public string        $file_name,
        public string        $mime_type,
        public string        $disk,
        public string        $conversions_disk,
        public int           $size,
        public array         $manipulations,
        public array         $custom_properties,
        public array         $generated_conversions,
        public array         $responsive_images,
        public ?int          $order_column,
        public string        $created_at,
        public string        $updated_at,
        public ?string       $url,
        public ?string $filePath,
    ) {
        $this->url = $this->generateFullUrl();
        $this->filePath = $this->generateRelativePath();
    }

    private function generateFullUrl(): ?string
    {
        return (new GenerateFileUrl())->execute($this->model_type, $this->model_id, $this->id, $this->file_name);
    }

    private function generateRelativePath(): ?string
    {
        if ($this->file_name && $this->disk) {
            return Storage::disk($this->disk)->path($this->id . "/" . $this->file_name);
        }

        return null;
    }
}
