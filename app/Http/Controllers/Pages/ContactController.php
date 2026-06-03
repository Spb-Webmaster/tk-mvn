<?php

namespace App\Http\Controllers\Pages;

use App\Models\Setting;
use Illuminate\Contracts\View\View;

class ContactController extends PageController
{
    public function index(): View
    {
        $page = Setting::getGroup('contact')->data ?? [];
      //  $contact = false;
        return view('pages.contact', compact('page'));
    }
}
