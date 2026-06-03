<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class FormTimestamp implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_numeric($value) || (now()->timestamp - (int) $value) < 3) {
            $fail('Форма заполнена слишком быстро. Попробуйте ещё раз.');
        }
    }
}
