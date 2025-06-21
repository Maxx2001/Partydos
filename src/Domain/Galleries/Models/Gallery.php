<?php

namespace Domain\Galleries\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Domain\Users\Models\User;
use Domain\Galleries\Models\GalleryItem;
use Domain\Galleries\Models\GalleryInvitation;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(GalleryItem::class);
    }

    public function invitations()
    {
        return $this->hasMany(GalleryInvitation::class);
    }
}
