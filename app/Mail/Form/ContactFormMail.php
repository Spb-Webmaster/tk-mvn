<?php

namespace App\Mail\Form;

use App\Support\SpamSignals;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    /** Признаки рассылки; заявку не блокируем, только предупреждаем получателя */
    public array $signals = [];

    public function __construct(public array $data)
    {
        $this->signals = SpamSignals::check($data);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: ($this->signals ? '[Возможно спам] ' : '') . 'Новый запрос с сайта',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'html.email.contact_form',
            with: ['signals' => $this->signals],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
