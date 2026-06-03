@extends('html.email.layouts.layout_default_mail')

@section('title', 'Новый запрос с сайта')

@section('description', 'Пользователь оставил заявку. Свяжитесь с ним как можно скорее.')

@section('content')
    @foreach($data as $label => $value)
        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom:12px;">
            <tr>
                <td style="width:130px; padding:9px 16px 9px 0; vertical-align:top; border-bottom:1px solid #eeecea;">
                    <span style="font-family:Arial,Helvetica,sans-serif; font-size:11px; color:#aaaaaa; font-weight:700; text-transform:uppercase; letter-spacing:0.08em;">{{ $label }}</span>
                </td>
                <td style="padding:9px 0; vertical-align:top; border-bottom:1px solid #eeecea;">
                    <span style="font-family:Arial,Helvetica,sans-serif; font-size:15px; color:#282828; font-weight:500; word-break:break-word;">{{ $value }}</span>
                </td>
            </tr>
        </table>
    @endforeach
@endsection
