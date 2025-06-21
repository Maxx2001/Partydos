<?php

namespace Domain\Galleries\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Will add Gallery once it's defined in a way that can be imported, or confirm path.
// use Domain\Galleries\Models\Gallery;

class GalleryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'gallery_id',
        'external_photo_id',
        'type',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function gallery()
    {
        // Assuming Gallery model is in the same namespace.
        return $this->belongsTo(Gallery::class);
    }
}
