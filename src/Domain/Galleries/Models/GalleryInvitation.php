<?php

namespace Domain\Galleries\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Domain\Users\Models\User;
// Assuming Gallery model is in the same namespace.
// use Domain\Galleries\Models\Gallery;

class GalleryInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'gallery_id',
        'inviter_user_id',
        'invited_email',
        'status',
        'token',
    ];

    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }

    public function inviter()
    {
        return $this->belongsTo(User::class, 'inviter_user_id');
    }

    public function invitedUser()
    {
        // This relationship links the invitation to a User model
        // if a user exists with the invited_email.
        return $this->belongsTo(User::class, 'invited_email', 'email');
    }
}
