<?php

declare(strict_types=1);

namespace App\Http\Controllers\FancyBox;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FancyBoxController extends Controller
{
    public function fancybox(Request $request)
    {
        if ($request->template === 'test') {
            return view('fancybox.forms.test');
        }

        if ($request->template === 'send_request') {
            return view('fancybox.forms.send_request');
        }

        if ($request->template === 'zapros') {
            return view('fancybox.forms.zapros');
        }

        return view('fancybox.forms.error.error_form');
    }
}
