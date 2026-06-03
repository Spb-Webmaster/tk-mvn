<?php

declare(strict_types=1);

namespace App\View\Components\Modules;

use App\Models\Setting;
use App\Models\TrainingCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Programs extends Component
{
    public Collection $categories;

    public array $icons = [
        '<svg width="36" height="36" viewBox="0 0 36 36" fill="none" stroke="var(--gold)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="18" r="15"/><path d="M12 18h12M18 12l6 6-6 6"/></svg>',
        '<svg width="36" height="36" viewBox="0 0 36 36" fill="none" stroke="var(--gold)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polyline points="6,26 13,18 19,22 30,10"/><polyline points="24,10 30,10 30,16"/></svg>',
        '<svg width="36" height="36" viewBox="0 0 36 36" fill="none" stroke="var(--gold)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 4l2.5 7.5H28l-6.5 4.7 2.5 7.5L18 19l-6 4.7 2.5-7.5L8 11.5h7.5z"/><line x1="18" y1="24" x2="18" y2="32"/><line x1="12" y1="32" x2="24" y2="32"/></svg>',
        '<svg width="36" height="36" viewBox="0 0 36 36" fill="none" stroke="var(--gold)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="10" width="28" height="20" rx="2"/><path d="M12 10V8a6 6 0 0112 0v2"/><line x1="18" y1="18" x2="18" y2="22"/><circle cx="18" cy="18" r="1.5" fill="var(--gold)"/></svg>',
        '<svg width="36" height="36" viewBox="0 0 36 36" fill="none" stroke="var(--gold)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="12" r="5"/><path d="M8 30c0-5.5 4.5-10 10-10s10 4.5 10 10"/><path d="M25 16l3 3-3 3"/></svg>',
        '<svg width="36" height="36" viewBox="0 0 36 36" fill="none" stroke="var(--gold)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="4" width="12" height="12" rx="1.5"/><rect x="20" y="4" width="12" height="12" rx="1.5"/><rect x="4" y="20" width="12" height="12" rx="1.5"/><rect x="20" y="20" width="12" height="12" rx="1.5"/></svg>',
    ];

    public function __construct(
        public string $eyebrow = '',
        public string $title = '',
        public string $lead = '',
        public string $headingTag = 'h2',
    ) {
        $home = Setting::getGroup('home')->data ?? [];

        if ($this->eyebrow === '') $this->eyebrow = $home['programs_eyebrow'] ?? '';
        if ($this->title   === '') $this->title   = $home['programs_title']   ?? '';
        if ($this->lead    === '') $this->lead    = $home['programs_lead']    ?? '';

        $this->categories = TrainingCategory::orderBy('sorting')->get();
    }

    public function render(): View
    {
        return view('components.modules.programs');
    }
}
