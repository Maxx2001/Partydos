<?php

namespace Domain\Auth\Mail;

use Domain\Users\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserDeleteRequest extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user)
    {

    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject:"Partydos | Account deletion request",
        );
    }

    public function content(): Content
    {
        $url = route('user-recovery', ['recovery_token' => $this->user->recovery_token]);

        return new Content(
            view: 'emails.user.user-deleted',
            with: [
                'user'         => $this->user,
                'url'          => $url,
                'deletionDate' => now()->addMonth()->format('Y-m-d'),
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
