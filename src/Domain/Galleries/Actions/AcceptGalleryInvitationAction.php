<?php

namespace Domain\Galleries\Actions;

use Domain\Galleries\Models\GalleryInvitation;
use Illuminate\Database\Eloquent\ModelNotFoundException; // For explicit exception type

class AcceptGalleryInvitationAction
{
    public function execute(string $token): GalleryInvitation
    {
        $invitation = GalleryInvitation::where('token', $token)
                                     ->where('status', 'pending')
                                     ->first();

        if (!$invitation) {
            throw new ModelNotFoundException('Gallery invitation not found or already processed.');
        }

        $invitation->status = 'accepted';
        $invitation->token = null; // Invalidate token after use
        $invitation->save();

        // Future considerations:
        // 1. If invited_email matches a registered user, associate them with the gallery.
        //    This might involve checking if a User exists with $invitation->invited_email.
        //    If so, potentially add an entry to a gallery_user pivot table.
        //    User::where('email', $invitation->invited_email)->first();
        //
        // 2. Notify the inviter that the invitation was accepted.

        return $invitation;
    }
}
