<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\ContactFormRequest;
use App\Jobs\Form\SendContactFormJob;
use Illuminate\Http\RedirectResponse;

class ContactFormController extends Controller
{
    public function store(ContactFormRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $represent = match ($data['represent'] ?? null) {
            'company'  => 'Компания (корпоративный запрос)',
            'personal' => 'Частное лицо',
            default    => null,
        };

        $payload = array_filter([
            'Имя'             => $data['name'] ?? null,
            'Фамилия'         => $data['surname'] ?? null,
            'Телефон'         => $data['phone'],
            'Email'           => $data['email'] ?? null,
            'Представляет'    => $represent,
            'Сообщение'       => $data['message'] ?? null,
        ]);

        SendContactFormJob::dispatch($payload);

        flash()->info('Заявка отправлена. Мы свяжемся с вами в ближайшее время.');

        // Цель Яндекс.Метрики отправится из layout после редиректа.
        // Берём из белого списка, а не из запроса напрямую, чтобы
        // в счётчик нельзя было подсунуть произвольную цель.
        $goals = ['discuss_task' => 'discuss_task'];
        $goal  = $goals[$request->input('form_source')] ?? null;

        if ($goal) {
            session()->flash('ym_goal', $goal);
        }

        return redirect()->back();
    }
}
