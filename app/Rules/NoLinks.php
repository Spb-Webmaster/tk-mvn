<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Запрещает ссылки и HTML-теги в тексте заявки.
 *
 * Смысл спам-рассылки — протащить ссылку: именно ради неё бот и заполняет
 * форму. Поэтому отсечь ссылку надёжнее, чем угадывать бота по поведению,
 * — выждать паузу и слать по одной заявке в час он умеет, а вот отказаться
 * от ссылки не может, рассылка теряет смысл.
 *
 * Живые клиенты в заявке ссылок почти не пишут, а если понадобится —
 * телефон и почта в форме остаются, по ним свяжутся.
 */
class NoLinks implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || trim($value) === '') {
            return;
        }

        $text = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        $patterns = [
            '~<\s*a\b~iu',                       // <a href=…> — разметка ссылки
            '~<\s*/?\s*[a-z][a-z0-9]*[^>]*>~iu', // любой HTML-тег
            '~\bhttps?\s*:\s*//~iu',             // http:// и https://, в том числе с пробелами
            '~\bwww\.[a-z0-9-]+\.[a-z]{2,}~iu',  // www.site.ru
            '~\[url[=\]]~iu',                    // BB-код [url]
            '~\b[a-z0-9-]+\.(ru|com|net|org|info|xyz|top|site|online|men|club|shop|store|biz)\b/~iu', // домен со слешем
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text)) {
                $fail('Уберите ссылки из текста — так заявку не получится отправить.');

                return;
            }
        }
    }
}
