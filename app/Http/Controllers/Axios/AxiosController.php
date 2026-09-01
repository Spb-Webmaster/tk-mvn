<?php

namespace App\Http\Controllers\Axios;

use App\Http\Controllers\Controller;
use App\Jobs\Form\SendContactFormJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AxiosController extends Controller
{
    private const AGREE_MESSAGE = 'Необходимо согласие на обработку персональных данных.';

    /**
     * Проверяет чекбокс согласия. Возвращает ответ с ошибками либо null,
     * если согласие получено.
     */
    private function validateAgreement(Request $request, string $field): ?JsonResponse
    {
        $validator = Validator::make($request->all(), [
            $field => ['accepted'],
        ], [
            $field . '.accepted' => self::AGREE_MESSAGE,
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return null;
    }

    public function async(Request $request)
    {
        if ($request->template === 'call_me_blue') {
            return view('axios.forms.call_me_blue');
        }

        return view('axios.forms.error.error_form');
    }

    public function callMeBlue(Request $request)
    {
        if ($errors = $this->validateAgreement($request, 'Согласие')) {
            return $errors;
        }

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
        if ($errors = $this->validateAgreement($request, 'agree')) {
            return $errors;
        }

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
