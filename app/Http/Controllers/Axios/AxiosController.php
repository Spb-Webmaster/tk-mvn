<?php

namespace App\Http\Controllers\Axios;

use App\Http\Controllers\Controller;
use App\Jobs\Form\SendContactFormJob;
use Illuminate\Http\Request;

class AxiosController extends Controller
{
    public function async(Request $request)
    {
        if ($request->template === 'call_me_blue') {
            return view('axios.forms.call_me_blue');
        }

        return view('axios.forms.error.error_form');
    }

    public function callMeBlue(Request $request)
    {
        $payload = array_filter([
            'ФИО'     => $request->input('ФИО'),
            'Телефон' => $request->input('Телефон'),
            'Email'   => $request->input('Email'),
        ]);

        SendContactFormJob::dispatch($payload);

        return response()->json(['response' => 'ok']);
    }

    public function sendRequest(Request $request)
    {
        $payload = array_filter([
            'Тип клиента' => $request->input('client_type'),
            'Имя'         => $request->input('name'),
            'Телефон'     => $request->input('phone'),
            'Email'       => $request->input('email'),
            'Запрос'      => $request->input('request'),
        ]);

        SendContactFormJob::dispatch($payload);

        return response()->json(['response' => 'ok']);
    }
}
