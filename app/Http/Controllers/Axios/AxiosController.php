<?php

namespace App\Http\Controllers\Axios;

use App\Http\Controllers\Controller;
use App\Jobs\Form\SendContactFormJob;
use App\Rules\FormTimestamp;
use App\Rules\Honeypot;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AxiosController extends Controller
{
    private const AGREE_MESSAGE = 'Необходимо согласие на обработку персональных данных.';

    private const PHONE_MESSAGE = 'Введите корректный номер телефона.';

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

    /**
     * Защита форм, уходящих через axios: ловушка для ботов, минимальное время
     * заполнения и обязательные поля. Раньше здесь проверялось только согласие,
     * и письмо уходило от пустого запроса с одним agree=1.
     *
     * @param  array<string, array<int, mixed>>  $fields  дополнительные правила полей формы
     */
    private function validateRequest(Request $request, array $fields): ?JsonResponse
    {
        $validator = Validator::make($request->all(), array_merge([
            'website'     => [new Honeypot()],
            '_form_time'  => ['required', new FormTimestamp()],
        ], $fields), [
            'phone.required' => self::PHONE_MESSAGE,
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

        if ($errors = $this->validateRequest($request, [
            'Телефон' => ['required', 'string', 'max:30'],
            'ФИО'     => ['nullable', 'string', 'max:150'],
            'Email'   => ['nullable', 'email', 'max:150'],
        ])) {
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

        if ($errors = $this->validateRequest($request, [
            'phone'   => ['required', 'string', 'max:30'],
            'name'    => ['nullable', 'string', 'max:150'],
            'email'   => ['nullable', 'email', 'max:150'],
            'company' => ['nullable', 'string', 'max:200'],
            'request' => ['nullable', 'string', 'max:3000'],
        ])) {
            return $errors;
        }

        $payload = array_filter([
            'Имя'         => $request->input('name'),
            'Телефон'     => $request->input('phone'),
            'Email'       => $request->input('email'),
            'Название компании' => $request->input('company'),
            'Запрос'      => $request->input('request'),
        ]);

        SendContactFormJob::dispatch($payload);

        return response()->json(['response' => 'ok']);
    }
}
