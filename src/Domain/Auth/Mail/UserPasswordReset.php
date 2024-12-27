<?php

namespace Domain\Auth\Mail;

use Domain\Users\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserPasswordReset extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public string $url, public int $durationPasswordValidMinutes = 60)
    {
        $this->durationPasswordValidMinutes = config('auth.passwords.users.expire');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('users.mail.crm.password'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.user.password-reset',
            with: [
                'user'                         => $this->user,
                'url'                          => $this->url,
                'durationPasswordValidMinutes' => $this->durationPasswordValidMinutes,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
