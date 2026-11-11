<?php

namespace App\Mail\Form;

use App\Support\SpamSignals;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TrainingRegistrationMail extends Mailable
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
            subject: ($this->signals ? '[Возможно спам] ' : '') . 'Новая заявка на обучение',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'html.email.training_registration',
            with: ['signals' => $this->signals],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
