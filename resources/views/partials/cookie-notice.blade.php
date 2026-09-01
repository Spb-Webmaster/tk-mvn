<div id="cookie-notice" hidden>
    <p>Мы используем файлы cookie и Яндекс.Метрику, чтобы сайт работал корректно и мы видели обезличенную статистику посещений. Оставаясь на сайте, вы соглашаетесь с <a href="{{ route('privacy') }}">политикой обработки персональных данных</a>.</p>
    <button type="button" id="cookie-notice-accept">Хорошо</button>
</div>
<script>
    (function () {
        try {
            if (localStorage.getItem('cookieConsent')) return;
        } catch (e) { return; }
        var notice = document.getElementById('cookie-notice');
        var accept = document.getElementById('cookie-notice-accept');
        if (!notice || !accept) return;
        notice.hidden = false;
        accept.addEventListener('click', function () {
            try { localStorage.setItem('cookieConsent', '1'); } catch (e) {}
            notice.hidden = true;
        });
    })();
</script>
