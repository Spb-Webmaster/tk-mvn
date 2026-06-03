<?php

namespace App\Http\Controllers\Schedule;

use App\Http\Controllers\Pages\PageController;
use App\Models\Setting;
use App\Models\Training;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Fluent;

class ScheduleController extends PageController
{
    public function index(): View
    {
        $page  = new Fluent(Setting::getGroup('schedule')->data ?? []);
        $today = Carbon::today();

        $upcoming = Training::where('published', 1)
            ->with('categories')
            ->whereNotNull('ev_date_from')
            ->where('ev_date_from', '>=', $today)
            ->orderBy('ev_date_from')
            ->get();

        $past = Training::where('published', 1)
            ->with('categories')
            ->whereNotNull('ev_date_from')
            ->where('ev_date_from', '<', $today)
            ->orderByDesc('ev_date_from')
            ->limit(5)
            ->get();

        return view('pages.schedule.list', compact('page', 'upcoming', 'past'));
    }
}

