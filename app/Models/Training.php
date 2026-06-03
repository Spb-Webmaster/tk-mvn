<?php

namespace App\Models;

use App\Enums\Resources\FullTemplate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Training extends Model
{
    protected $fillable = [
        'training_level_id',
        'title',
        'slug',
        'template',
        'subtitle',
        'short_desc',
        'img',
        'desc',
        'img2',
        'desc2',
        'html',
        'html2',
        'published',
        'params',
        'video',
        'gallery',
        'files',
        'metatitle',
        'description',
        'keywords',
        'script',
        'sorting',
        'faq',
        'custom_field',
        'custom_field2',
        'custom_field3',
        'ev_date_from',
        'ev_date_to',
        'ev_time',
        'ev_location',
        'ev_format',
        'ev_price_legal',
        'ev_price_individual',
        'ev_modules',
        'ev_goals',
        'ev_tasks',
        'ev_methods',
        'ev_results',
    ];
    public function trainingLevel(): BelongsTo
    {
        return $this->belongsTo(TrainingLevel::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(TrainingCategory::class, 'training_training_category');
    }
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('published', 1)->orderBy('sorting');
    }

    protected function casts(): array
    {
        return [
            'published' => 'integer',
            'sorting'   => 'integer',
            'template'  => FullTemplate::class,
            'video'     => 'array',
            'gallery'   => 'array',
            'files'     => 'array',
            'faq'                => 'array',
            'ev_price_legal'      => 'integer',
            'ev_price_individual' => 'integer',
            'ev_modules'          => 'array',
            'ev_goals'            => 'array',
            'ev_tasks'            => 'array',
            'ev_methods'          => 'array',
            'ev_results'          => 'array',
            'ev_date_from'        => 'date',
            'ev_date_to'          => 'date',
        ];
    }

    // День + месяц для сайдбара: "20–21 июня" или "30 мая–2 июня"
    protected function evDayMonth(): Attribute
    {
        return Attribute::get(function () {
            if (!$this->ev_date_from) return '';

            $from   = $this->ev_date_from;
            $to     = $this->ev_date_to;
            $months = ['', 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня',
                       'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];

            if (!$to || $from->isSameDay($to)) {
                return $from->day . ' ' . $months[$from->month];
            }

            if ($from->month === $to->month) {
                return $from->day . '–' . $to->day . ' ' . $months[$from->month];
            }

            return $from->day . ' ' . $months[$from->month]
                . '–' . $to->day . ' ' . $months[$to->month];
        });
    }

    // Диапазон дней: "20–21" или "30 мая–2 июня"
    protected function evDayRange(): Attribute
    {
        return Attribute::get(function () {
            if (!$this->ev_date_from) return '';

            $from = $this->ev_date_from;
            $to   = $this->ev_date_to;

            if (!$to || $from->isSameDay($to)) {
                return (string) $from->day;
            }

            if ($from->month === $to->month) {
                return $from->day . '–' . $to->day;
            }

            $abbr = ['', 'янв', 'фев', 'мар', 'апр', 'мая', 'июня',
                     'июля', 'авг', 'сен', 'окт', 'ноя', 'дек'];

            return $from->day . ' ' . $abbr[$from->month]
                . '–' . $to->day . ' ' . $abbr[$to->month];
        });
    }

    // Месяц и год: "июня 2026" или просто "2026" при разных месяцах
    protected function evMonthYear(): Attribute
    {
        return Attribute::get(function () {
            if (!$this->ev_date_from) return '';

            $from   = $this->ev_date_from;
            $to     = $this->ev_date_to;
            $months = ['', 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня',
                       'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];

            if ($to && $from->month !== $to->month) {
                return (string) $from->year;
            }

            return $months[$from->month] . ' ' . $from->year;
        });
    }

    // Количество дней: "1 день", "2 дня", "5 дней"
    protected function evDurationDays(): Attribute
    {
        return Attribute::get(function () {
            if (!$this->ev_date_from) return '';

            $days   = $this->ev_date_to
                ? (int) $this->ev_date_from->diffInDays($this->ev_date_to) + 1
                : 1;
            $mod100 = $days % 100;
            $mod10  = $days % 10;

            if ($mod100 >= 11 && $mod100 <= 19)          $word = 'дней';
            elseif ($mod10 === 1)                         $word = 'день';
            elseif ($mod10 >= 2 && $mod10 <= 4)          $word = 'дня';
            else                                          $word = 'дней';

            return $days . ' ' . $word;
        });
    }
}
