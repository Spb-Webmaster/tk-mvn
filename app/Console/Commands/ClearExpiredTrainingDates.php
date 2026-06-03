<?php

namespace App\Console\Commands;

use App\Models\Training;
use Illuminate\Console\Command;

class ClearExpiredTrainingDates extends Command
{
    protected $signature = 'training:clear-dates';

    protected $description = 'Очищает даты проведения у завершённых тренингов';

    public function handle(): void
    {
        $expired = Training::whereNotNull('ev_date_from')
            ->where(function ($q) {
                $q->whereNotNull('ev_date_to')
                  ->whereDate('ev_date_to', '<', today())
                  ->orWhere(function ($q) {
                      $q->whereNull('ev_date_to')
                        ->whereDate('ev_date_from', '<', today());
                  });
            })
            ->get();

        if ($expired->isEmpty()) {
            $this->info('Завершённых тренингов с датами не найдено.');
            return;
        }

        foreach ($expired as $training) {
            $this->line("Очищаю даты: [{$training->id}] {$training->title}");
        }

        Training::whereIn('id', $expired->pluck('id'))->update([
            'ev_date_from' => null,
            'ev_date_to'   => null,
        ]);

        $this->info("Очищено тренингов: {$expired->count()}");
    }
}
