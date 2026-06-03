<?php

namespace App\Jobs\Form;

use App\Mail\Form\TrainingRegistrationMail;
use App\Models\MailLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Support\Traits\EmailAddressCollector;

class SendTrainingRegistrationJob implements ShouldQueue
{
    use Queueable;
    use EmailAddressCollector;

    public function __construct(public array $data) {}

    public function handle(): void
    {
        $recipients = $this->emails();

        if (empty($recipients)) {
            return;
        }

        $mailable = new TrainingRegistrationMail($this->data);
        $subject  = $mailable->envelope()->subject;

        $log = MailLog::create([
            'form'       => 'training_registration',
            'subject'    => $subject,
            'recipients' => $recipients,
            'payload'    => $this->data,
            'status'     => 'pending',
        ]);

        try {
            Mail::to($recipients)->send($mailable);
            $log->update(['status' => 'sent', 'sent_at' => now()]);
        } catch (\Throwable $e) {
            $log->update(['status' => 'failed', 'error' => $e->getMessage()]);
            throw $e;
        }
    }
}
