<?php

namespace App\Http\Requests\Contact;

use App\Rules\FormTimestamp;
use Illuminate\Foundation\Http\FormRequest;

class ContactFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'    => ['nullable', 'string', 'max:100'],
            'surname' => ['nullable', 'string', 'max:100'],
            // 11 цифр — российский формат с кодом страны
            'phone'   => ['required', 'string', 'max:30', function ($attribute, $value, $fail) {
                if (strlen(preg_replace('/\D/', '', $value)) < 11) {
                    $fail('Введите корректный номер телефона.');
                }
            }],
            'email'   => ['nullable', 'email', 'max:150'],
            'represent'  => ['nullable', 'string', 'in:company,personal'],
            'message'    => ['nullable', 'string', 'max:3000'],
            '_form_time' => ['required', new FormTimestamp()],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required' => 'Укажите номер телефона.',
            'email.email'    => 'Некорректный формат email.',
        ];
    }
}
