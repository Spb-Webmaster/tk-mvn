<?php

declare(strict_types=1);

namespace App\View\Components\Modules;

use App\Models\TrainingCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class MasterNav extends Component
{
    public Collection $trainings;

    public function __construct()
    {
        $this->trainings = TrainingCategory::where('slug', 'master-kommunikatsij')
            ->with(['trainings' => fn($q) => $q->published()->with('categories')->orderBy('sorting')])
            ->first()
            ?->trainings ?? collect();
    }

    public function render(): View
    {
        return view('components.modules.master-nav');
    }
}
