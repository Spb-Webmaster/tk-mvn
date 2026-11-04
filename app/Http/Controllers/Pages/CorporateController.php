<?php

namespace App\Http\Controllers\Pages;

use App\Models\Response;
use App\Models\Setting;
use Illuminate\Contracts\View\View;

class CorporateController extends PageController
{
    public function index(): View
    {
        $corporate = Setting::getGroup('corporate')->data ?? [];

        // Те же письма, что в блоке на главной — отмеченные галочкой «На главной»
        $letters = Response::published()
            ->lettersOnHome()
            ->get();

        return view('pages.corporate', compact('corporate', 'letters'));
    }
}
