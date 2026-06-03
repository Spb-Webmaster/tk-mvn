<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Training;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index():View
    {
        $home = Setting::getGroup('home')->data;

        $events = Training::published()
            ->whereNotNull('ev_date_from')
            ->where(function ($q) {
                $q->where('ev_date_to', '>=', today())
                  ->orWhere(function ($q) {
                      $q->whereNull('ev_date_to')->where('ev_date_from', '>=', today());
                  });
            })
            ->with(['categories', 'trainingLevel'])
            ->orderBy('ev_date_from')
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
            ]
        );
    }
}
