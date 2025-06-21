<?php

namespace Domain\Galleries\Actions;

use Domain\Galleries\Models\GalleryInvitation;
use Illuminate\Database\Eloquent\ModelNotFoundException; // For explicit exception type

class RejectGalleryInvitationAction
{
    public function execute(string $token): GalleryInvitation
    {
        $invitation = GalleryInvitation::where('token', $token)
                                     ->where('status', 'pending')
                                     ->first();

        if (!$invitation) {
            throw new ModelNotFoundException('Gallery invitation not found or already processed.');
        }

        $invitation->status = 'rejected';
        $invitation->token = null; // Invalidate token after use
        $invitation->save();

        // Or, if you prefer to delete rejected invitations:
        // $invitation->delete();
        // return $invitation; // $invitation object would still hold data but be deleted from DB

        // Future considerations:
        // 1. Notify the inviter that the invitation was rejected.

        return $invitation;
    }
}
