<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class RegisterMail extends Mailable
{
    use Queueable, SerializesModels;

    public $msg;
    public $subj;
    public $verificationCode;

    /**
     * Create a new message instance.
     */
    public function __construct($verificationCode, $subj)
    {
        // $this->msg = $msg;
        $this->verificationCode = $verificationCode;
        $this->msg = "Your verification code is ";
        $this->subj = $subj;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subj,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail-template.hello',
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
