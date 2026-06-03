<?php

declare(strict_types=1);

namespace App\Enums\Pages;

enum PageTemplate: string
{
    case Default = 'default';
    case About = 'about';
    case Training = 'training';
    case News = 'news';
    case Photo = 'photo';
    case Video = 'video';
    case Response = 'response';


    public function label(): string
    {
        return match($this) {
            self::Default => 'Стандартный',
            self::About => 'О нас',
            self::Training => 'Обучение',
            self::News => 'Новости',
            self::Photo => 'Фото',
            self::Video => 'Видео',
            self::Response => 'Отзывы',
        };
    }

    public function view(string $section): string
    {
        $specific = "pages.{$section}.pages.templates.{$this->value}";
        $common   = "pages.common.pages.templates.{$this->value}";

        if (view()->exists($specific)) return $specific;

        return $common;
    }

    public static function toOptions(): array
    {
        return array_column(
            array_map(fn(self $case) => ['value' => $case->value, 'label' => $case->label()], self::cases()),
            'label',
            'value'
        );
    }
}
