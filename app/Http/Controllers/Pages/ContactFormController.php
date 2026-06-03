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

        $payload = array_filter([
            'Имя'       => $data['name'] ?? null,
            'Фамилия'   => $data['surname'] ?? null,
            'Телефон'   => $data['phone'],
            'Email'     => $data['email'] ?? null,
            'Сообщение' => $data['message'] ?? null,
        ]);

        SendContactFormJob::dispatch($payload);

        flash()->info('Заявка отправлена. Мы свяжемся с вами в ближайшее время.');

        return redirect()->back();
    }
}
