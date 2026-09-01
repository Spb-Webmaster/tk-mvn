<?php

namespace App\Http\Controllers\Pages;

use App\Models\Setting;
use Illuminate\Contracts\View\View;

class PrivacyController extends PageController
{
    public function index(): View
    {
        return view('pages.privacy', [
            'constants' => Setting::getGroup('constants')->data ?? [],
        ]);
    }
}
