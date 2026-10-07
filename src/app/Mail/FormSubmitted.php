<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FormSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array<string, array{label: string, value: string}>  $values
     */
    public function __construct(
        public string $title,
        public array $values,
        public ?string $replyToAddress = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "【{$this->title}】ホームページから送信がありました",
            replyTo: $this->replyToAddress ? [new Address($this->replyToAddress)] : [],
        );
    }

    public function content(): Content
    {
        return new Content(text: 'mail.form-submitted');
    }
}
