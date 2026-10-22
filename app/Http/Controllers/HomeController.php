<?php

namespace App\Http\Controllers;

use App\Models\Response;
use App\Models\Setting;
use App\Models\Training;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index():View
    {
        $home = Setting::getGroup('home')->data;

        $events = Training::published()
            ->upcoming()
            ->with(['categories', 'trainingLevel'])
            ->orderBy('ev_date_from')
            ->get();

        // Благодарственные письма, отмеченные галочкой «На главной»
        $letters = Response::published()
            ->lettersOnHome()
            ->get();

        if(auth()->check()) {
            $user = auth()->user();
        } else {
            $user = false;
        }

        return view('home', [
                'user'   => $user,
                'home'   => $home,
                'events' => $events,
                'letters' => $letters,
            ]
        );
    }
}
