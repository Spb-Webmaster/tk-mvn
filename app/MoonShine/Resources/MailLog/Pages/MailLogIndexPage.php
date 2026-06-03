<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\MailLog\Pages;

use App\MoonShine\Resources\MailLog\MailLogResource;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Preview;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;

/**
 * @extends IndexPage<MailLogResource>
 */
final class MailLogIndexPage extends IndexPage
{
    private const FORM_LABELS = [
        'training_registration' => 'Запись на обучение',
    ];

    private const STATUS_COLORS = [
        'sent'    => '#22c55e',
        'failed'  => '#ef4444',
        'pending' => '#f59e0b',
    ];

    private const STATUS_LABELS = [
        'sent'    => 'Отправлено',
        'failed'  => 'Ошибка',
        'pending' => 'Ожидает',
    ];

    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),

            Preview::make('Форма', 'form', fn($item) =>
                self::FORM_LABELS[$item->form] ?? $item->form
            ),

            Text::make('Тема', 'subject'),

            Preview::make('Статус', 'status', function ($item) {
                $color = self::STATUS_COLORS[$item->status] ?? '#888';
                $label = self::STATUS_LABELS[$item->status] ?? $item->status;
                return "<span style=\"display:inline-block; padding:2px 10px; border-radius:20px;
                    background:{$color}22; color:{$color}; font-size:12px; font-weight:600;
                    letter-spacing:0.04em;\">{$label}</span>";
            }),

            Preview::make('Получателей', 'recipients', fn($item) =>
                count($item->recipients ?? [])
            ),

            Date::make('Отправлено', 'sent_at')->format('d.m.Y H:i')->sortable(),
            Date::make('Создано', 'created_at')->format('d.m.Y H:i')->sortable(),
        ];
    }

    protected function filters(): iterable
    {
        return [
            Select::make('Форма', 'form')
                ->options(self::FORM_LABELS)
                ->nullable(),

            Select::make('Статус', 'status')
                ->options(self::STATUS_LABELS)
                ->nullable(),
        ];
    }
}
