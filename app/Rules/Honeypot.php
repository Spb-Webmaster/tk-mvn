<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Поле-ловушка из <x-form.honeypot /> — человек его не видит и не заполняет,
 * бот проходит по всем input и вписывает значение.
 *
 * Непустое значение означает автоматическую отправку: отклоняем.
 * Сообщение намеренно обычное, чтобы по тексту ошибки нельзя было понять,
 * что сработала именно ловушка.
 */
class Honeypot implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_string($value) && trim($value) !== '') {
            $fail('Не удалось отправить форму. Попробуйте ещё раз.');
        }
    }
}
