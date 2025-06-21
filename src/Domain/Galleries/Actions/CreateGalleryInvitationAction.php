<?php

namespace Domain\Galleries\Actions;

// Not using GalleryInvitationData as per current action signature
// use Domain\Galleries\DataTransferObjects\GalleryInvitationData;
use Domain\Galleries\Mail\GalleryInvitationMail;
use Domain\Galleries\Models\Gallery;
use Domain\Galleries\Models\GalleryInvitation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CreateGalleryInvitationAction
{
    public function execute(Gallery $gallery, string $invitedEmail, int $inviterUserId): GalleryInvitation
    {
        // 1. Ensure inviter owns the gallery
        if ($gallery->user_id !== $inviterUserId) {
            throw ValidationException::withMessages(['gallery' => 'You do not own this gallery.']);
        }

        // 2. Check for existing pending invitation for this email to this gallery
        $existing = GalleryInvitation::where('gallery_id', $gallery->id)
                                   ->where('invited_email', $invitedEmail)
                                   ->where('status', 'pending')
                                   ->first();
        if ($existing) {
            // Optional: Resend email or just return existing? For now, throw.
            // Consider if resending the email for an existing pending invitation is better UX.
            // Mail::to($invitedEmail)->send(new GalleryInvitationMail($existing));
            // return $existing;
            throw ValidationException::withMessages(['invited_email' => 'An invitation has already been sent to this email address for this gallery and is pending.']);
        }

        // 3. Create invitation
        $invitation = GalleryInvitation::create([
            'gallery_id' => $gallery->id,
            'inviter_user_id' => $inviterUserId,
            'invited_email' => $invitedEmail,
            'token' => Str::uuid()->toString(), // Using UUID for token for better uniqueness
            'status' => 'pending',
        ]);

        // 4. Send email (ensure GalleryInvitationMail is created and inviter relationship loads)
        // The inviter relationship on GalleryInvitation model should be set up to eager load 'inviter.name' if needed by mail
        // Or pass inviter explicitly if mail needs it and it's not easily available on $invitation just after create.
        // $invitation->load('gallery', 'inviter'); // Load relationships if not automatically loaded
        Mail::to($invitedEmail)->send(new GalleryInvitationMail($invitation));

        return $invitation;
    }
}
