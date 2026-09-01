<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Http\Requests\Training\TrainingRegistrationRequest;
use App\Jobs\Form\SendTrainingRegistrationJob;
use App\Models\Training;
use Illuminate\Http\RedirectResponse;

class TrainingRegistrationController extends Controller
{
    public function store(TrainingRegistrationRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // поле приходит не из каждой формы — компонент рендерит его только при известном тренинге
        $training = !empty($data['training_id']) ? Training::find($data['training_id']) : null;

        $payload = array_filter([
            'Обучение'    => $training?->title,
            'Тип'         => $data['type'] === 'fiz' ? 'Физическое лицо' : 'Юридическое лицо',
            'Имя'         => $data['first_name'] ?? null,
            'Фамилия'     => $data['last_name'] ?? null,
            'Телефон'     => $data['phone'],
            'Email'       => $data['email'],
            'Должность'   => $data['position'] ?? null,
            'Организация' => $data['company'] ?? null,
            'ИНН'         => $data['inn'] ?? null,
            'Комментарий' => $data['comment'] ?? null,
        ]);

        SendTrainingRegistrationJob::dispatch($payload);

        flash()->info('Заявка отправлена. Мы свяжемся с вами в ближайшее время.');

        return redirect()->back();
    }
}
