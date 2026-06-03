<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\MailLog\Pages;

use App\MoonShine\Resources\MailLog\MailLogResource;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Preview;
use MoonShine\UI\Fields\Text;

/**
 * @extends DetailPage<MailLogResource>
 */
final class MailLogDetailPage extends DetailPage
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
                return "<span style=\"display:inline-block; padding:3px 12px; border-radius:20px;
                    background:{$color}22; color:{$color}; font-size:13px; font-weight:600;\">{$label}</span>";
            }),

            Preview::make('Получатели', 'recipients', fn($item) =>
                implode(', ', $item->recipients ?? [])
            ),

            Date::make('Отправлено', 'sent_at')->format('d.m.Y H:i:s'),
            Date::make('Создано', 'created_at')->format('d.m.Y H:i:s'),

            Preview::make('Ошибка', 'error', fn($item) =>
                $item->error
                    ? "<pre style=\"margin:0; font-size:12px; color:#ef4444; white-space:pre-wrap;\">{$item->error}</pre>"
                    : '—'
            ),

            Preview::make('Данные формы', 'payload', function ($item) {
                if (empty($item->payload)) {
                    return '—';
                }
                $rows = '';
                foreach ($item->payload as $key => $value) {
                    $rows .= sprintf(
                        '<tr>
                            <td style="padding:8px 16px 8px 0; width:140px; font-size:12px;
                                font-weight:700; color:inherit; opacity:0.45; text-transform:uppercase;
                                letter-spacing:0.06em; vertical-align:top; white-space:nowrap;">%s</td>
                            <td style="padding:8px 0; border-bottom:1px solid rgba(128,128,128,0.2);
                                font-size:14px; color:inherit; word-break:break-word;">%s</td>
                        </tr>',
                        e($key),
                        e($value)
                    );
                }
                return "<table style=\"width:100%; border-collapse:collapse;\">{$rows}</table>";
            }),
        ];
    }
}
