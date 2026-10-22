<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    {{--  {!!   config('google.google_tag.head') !!}--}}
    <meta name="csrf-token" content="{{{ csrf_token() }}}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite([
    'resources/css/app.css',
    'resources/js/app.js',
    ])
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
    <link rel="manifest" href="/favicon/site.webmanifest">
    <title>@yield('title', config('seo.seo.title'))</title>
    <meta name="description" content="@yield('description',  config('seo.seo.description'))"/>
    <meta name="keywords" content="@yield('keywords',  config('seo.seo.keywords'))"/>
</head>
<body>
<div id="back-to-top"></div>
{{--  {!!  config('google.google_tag.body') !!}  --}}
<div class="content_  @yield('class')  {{ route_name() }}">
    <x-message.message/>
    <x-message.message_error/>
    @include('templates.axeld.header')
    <main>
        @yield('content')
    </main>
</div><!--.content_-->
@include('templates.axeld.footer')
@include('partials.cookie-notice')
@if(!empty($constants['yandex_metrika']))
    {!! $constants['yandex_metrika'] !!}

    @php
        // ID счётчика достаём из самого кода — отдельного поля в настройках нет
        preg_match('/ym\(\s*(\d+)/', $constants['yandex_metrika'], $ymMatch);
        $ymId = $ymMatch[1] ?? null;
    @endphp

    @if($ymId)
        <script>
            // Единая точка отправки целей Метрики: window.ymGoal('identifier')
            window.YM_ID = {{ $ymId }};
            window.ymGoal = function (goal) {
                if (typeof window.ym === 'function' && window.YM_ID) {
                    window.ym(window.YM_ID, 'reachGoal', goal);
                }
            };
        </script>

        {{-- Цель, выставленная контроллером перед редиректом (формы с перезагрузкой страницы) --}}
        @if(session('ym_goal'))
            <script>window.ymGoal(@json(session('ym_goal')));</script>
        @endif
    @endif
@endif
</body>
</html>

