<?php

namespace App\Support;

/**
 * Разбирает заявку и называет признаки, по которым она похожа на рассылку.
 *
 * Ничего не блокирует: заявка уходит письмом в любом случае, а найденные
 * признаки добавляются в тему и в тело письма. Решение остаётся за человеком —
 * так не теряется настоящий клиент, который написал непривычно.
 *
 * Признаки взяты с разобранных писем: предложение «передавать клиентов» с
 * оплатой за лид и вторым телефоном прямо в тексте, где цифры местами заменены
 * буквами, а номер разорван скобками и дефисами ради обхода фильтров.
 */
class SpamSignals
{
    /** Слова, типичные для B2B-рассылок по чужим базам */
    private const PHRASES = [
        'рассылк', 'целевым базам', 'целевые базы', 'холодн', 'лидогенерац',
        'оплата за результат', 'за каждого клиента', 'за каждого заинтересованн',
        'передавать клиентов', 'передавать новых клиентов', 'поток клиентов',
        'продвижение сайта', 'seo-продвижение', 'набор подписчиков',
    ];

    /** Мессенджеры: сами по себе безобидны, но в связке с рассылками — признак */
    private const MESSENGERS = ['telegram', 'whatsapp', 'viber', 'вайбер', 'телеграм', 'ватсап', '(тел/tg)', 'тел/tg'];

    /**
     * @return list<string> причины; пустой массив — заявка выглядит обычной
     */
    public static function check(array $data): array
    {
        $reasons = [];

        $text = trim((string) ($data['Сообщение'] ?? $data['Запрос'] ?? $data['Комментарий'] ?? ''));
        $email = trim((string) ($data['Email'] ?? ''));
        $lower = mb_strtolower($text, 'UTF-8');

        if ($text !== '') {
            if (self::hasPhoneInside($text)) {
                $reasons[] = 'в тексте свой телефон';
            }

            if (self::hasLetterDigitSwap($text)) {
                $reasons[] = 'цифры подменены буквами';
            }

            $found = array_values(array_filter(
                self::PHRASES,
                static fn (string $p): bool => str_contains($lower, $p)
            ));

            if ($found !== []) {
                $reasons[] = 'лексика рассылок: ' . implode(', ', array_slice($found, 0, 3));
            }

            $messengers = array_values(array_filter(
                self::MESSENGERS,
                static fn (string $m): bool => str_contains($lower, $m)
            ));

            if (count($messengers) >= 2) {
                $reasons[] = 'перечислены мессенджеры';
            }
        }

        if ($email !== '' && self::isRandomMailbox($email)) {
            $reasons[] = 'адрес почты похож на случайный';
        }

        return $reasons;
    }

    /**
     * Телефон внутри текста: 10–11 цифр, между которыми могут стоять пробелы,
     * скобки и дефисы. У заявки есть отдельное поле для телефона, поэтому
     * второй номер в сообщении — признак рассылки, а разрывы в нём делаются
     * ради обхода фильтров.
     */
    private static function hasPhoneInside(string $text): bool
    {
        if (! preg_match_all('~[\d\s()\-+]{10,}~u', $text, $m)) {
            return false;
        }

        foreach ($m[0] as $candidate) {
            if (strlen(preg_replace('~\D~', '', $candidate)) >= 10) {
                return true;
            }
        }

        return false;
    }

    /** Кириллическая О или латинская O внутри числа: «5ОО рублей» вместо «500» */
    private static function hasLetterDigitSwap(string $text): bool
    {
        return (bool) preg_match('~\d[ОоOo]|[ОоOo]\d~u', $text);
    }

    /**
     * Ящик вида IoWlYmAwMxsSfpM — длинная строка без гласных-согласных чередования
     * и с хаотичным регистром. Простая эвристика: много смен регистра и нет
     * разделителей, какие обычно бывают в человеческих адресах.
     */
    private static function isRandomMailbox(string $email): bool
    {
        $box = strstr($email, '@', true) ?: '';

        if (strlen($box) < 12 || preg_match('~[._\-]~', $box)) {
            return false;
        }

        $switches = 0;
        for ($i = 1, $len = strlen($box); $i < $len; $i++) {
            $a = $box[$i - 1];
            $b = $box[$i];
            if (ctype_alpha($a) && ctype_alpha($b) && ctype_upper($a) !== ctype_upper($b)) {
                $switches++;
            }
        }

        return $switches >= 5;
    }
}
