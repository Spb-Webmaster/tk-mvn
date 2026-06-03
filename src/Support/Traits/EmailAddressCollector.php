<?php

namespace Support\Traits;

use App\Models\Setting;

trait EmailAddressCollector
{
    public function emails(): array
    {
        $emails = [];

        // Основной адрес из .env / конфига
        $mailAdmin = env('MAIL_ADMIN', config('mail.from.address'));
        if ($mailAdmin) {
            $emails[] = $mailAdmin;
        }

        // Дополнительные адреса из админки (ConstantsPage → вкладка «E-mail адреса»)
        $extra = Setting::getGroup('constants')->data['emails'] ?? [];
        foreach ($extra as $row) {
            if (!empty($row['email'])) {
                $emails[] = trim($row['email']);
            }
        }

        return array_values(array_unique(array_filter($emails)));
    }
}
