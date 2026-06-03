<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function index(): View
    {
        $items = DB::table('t_zoo_item')
            ->where('application_id', 46)
            ->where('type', 'last-actions')
            ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(`params`, '$.\"config.primary_category\"')) = ?", ['1175'])
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        return view('test.index', compact('items'));
    }
}
