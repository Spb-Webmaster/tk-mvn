<?php

namespace App\Http\Requests\Training;

use App\Rules\FormTimestamp;
use Illuminate\Foundation\Http\FormRequest;

class TrainingRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type'        => ['required', 'in:fiz,yur'],
            'training_id' => ['nullable', 'integer', 'exists:trainings,id'],
            'first_name'  => ['nullable', 'string', 'max:100'],
            'last_name'   => ['nullable', 'string', 'max:100'],
            'phone'       => ['required', 'string', 'max:30'],
            'email'       => ['required', 'email', 'max:150'],
            'comment'     => ['nullable', 'string', 'max:2000'],
            'company'     => ['required_if:type,yur', 'nullable', 'string', 'max:255'],
            'inn'         => ['required_if:type,yur', 'nullable', 'digits_between:5,12'],
            'position'    => ['nullable', 'string', 'max:100'],
            '_form_time'  => ['required', new FormTimestamp()],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required'          => 'Укажите номер телефона.',
            'email.required'          => 'Укажите email.',
            'email.email'             => 'Некорректный формат email.',
            'company.required_if'     => 'Укажите название организации.',
            'inn.required_if'         => 'Укажите ИНН организации.',
            'inn.digits_between'      => 'ИНН должен содержать от 5 до 12 цифр.',
        ];
    }
}
