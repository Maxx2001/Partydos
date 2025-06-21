<?php

namespace Domain\Galleries\Mail;

use Domain\Galleries\Models\GalleryInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // For queueing emails
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GalleryInvitationMail extends Mailable implements ShouldQueue // Added ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public GalleryInvitation $invitation)
    {
        // Eager load relationships needed for the email to prevent N+1 issues if sending many.
        // $this->invitation->loadMissing(['gallery', 'inviter']); // 'inviter' is the relationship method name
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You have been invited to the gallery: ' . $this->invitation->gallery->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Ensure inviter and gallery names are available.
        // Accessing $this->invitation->gallery->name and $this->invitation->inviter->name directly in `with`
        // relies on these relationships being loaded. It's safer to prepare them.
        $galleryName = $this->invitation->gallery ? $this->invitation->gallery->name : 'a gallery';
        $inviterName = $this->invitation->inviter ? $this->invitation->inviter->name : 'Someone';


        return new Content(
            markdown: 'emails.gallery.invitation',
            with: [
                'acceptUrl' => route('gallery.invitations.accept', ['token' => $this->invitation->token]),
                'rejectUrl' => route('gallery.invitations.reject', ['token' => $this->invitation->token]),
                'galleryName' => $galleryName,
                'inviterName' => $inviterName,
                'invitationToken' => $this->invitation->token, // For reference or direct link display
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
