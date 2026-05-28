<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class CustomEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        // public readonly string $subject,
        public readonly string $sbj,
        public readonly string $htmlBody,
        public readonly array  $attachmentPaths = [],
    ) {
            $this->subject = $sbj;
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->subject);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.compose.raw-html');
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return array_map(
            fn(string $path) => Attachment::fromStorageDisk('local', $path),
            $this->attachmentPaths
        );
    }
}
