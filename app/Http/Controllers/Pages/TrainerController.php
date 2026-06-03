<?php

namespace App\Http\Controllers\Pages;

use App\Models\Setting;
use Illuminate\Contracts\View\View;

class TrainerController extends PageController
{
    public function index(): View
    {
        $trainer = Setting::getGroup('trainer')->data ?? [];

        return view('pages.trainer', compact('trainer'));
    }
}
