@extends('layouts.layout')
<x-seo.meta
    title="{{ $page['metatitle'] ?? 'Контакты — Василий Никольский — бизнес-тренер, консультант, коуч' }}"
    description="{{ $page['description'] ?? '' }}"
    keywords="{{ $page['keywords'] ?? '' }}"
/>
@section('content')

    <div class="news-page-header">
        <div class="news-ph-inner">
            <div class="block_content__breadcrumbs"><div class="breadcrumb">{{ Breadcrumbs::render(Route::currentRouteName()) }}</div></div>
            <div class="ph-inner">
                <div>
                    <div class="page-eyebrow">Мастерская Василия Никольского</div>
                    <h1 class="page-title page-title-600">{{ $page['title'] ?? 'Контакты' }}</h1>
                </div>
                <x-modules.stats2 :items="$constants['stats2'] ?? []" />
            </div>
        </div>
    </div>

    <div class="contact-page">

        {{-- INFO COLUMN --}}
        <div class="info-col">

            {{-- Телефон --}}
            @if(!empty($page['phone']))
            <div class="contact-block">
                <div class="s-eye">{{ $page['phone_label'] ?? 'Телефон' }}</div>
                <a href="tel:{{ preg_replace('/\D/', '', $page['phone']) }}" class="contact-phone-link">{{ $page['phone'] }}</a>
                @if(!empty($page['phone_note']))
                <div class="contact-phone-note">{{ $page['phone_note'] }}</div>
                @endif
            </div>
            @endif

            {{-- Как связаться --}}
            <div class="contact-block">
                <div class="s-eye">Как связаться</div>
                <div class="contact-rows">

                    @if(!empty($page['email']))
                    <div class="cr">
                        <div class="cr-icon">
                            <svg width="16" height="16" viewBox="0 0 20 20" fill="none" stroke="var(--gold)" stroke-width="1.5" stroke-linecap="round"><path d="M3 5h14l-7 7-7-7z"/><rect x="3" y="5" width="14" height="10" rx="1"/></svg>
                        </div>
                        <div class="cr-body">
                            <div class="cr-lbl">Email</div>
                            <div class="cr-val"><a href="mailto:{{ $page['email'] }}">{{ $page['email'] }}</a></div>
                        </div>
                    </div>
                    @endif

                    @if(!empty($page['address']))
                    <div class="cr">
                        <div class="cr-icon">
                            <svg width="16" height="16" viewBox="0 0 20 20" fill="none" stroke="var(--gold)" stroke-width="1.5" stroke-linecap="round"><path d="M10 2C7 2 4.5 4.5 4.5 7.5c0 4 5.5 9 5.5 9s5.5-5 5.5-9C15.5 4.5 13 2 10 2z"/><circle cx="10" cy="7.5" r="2"/></svg>
                        </div>
                        <div class="cr-body">
                            <div class="cr-lbl">Адрес</div>
                            <div class="cr-val">
                                {{ $page['address'] }}
                                @if(!empty($page['address_note']))
                                <br><span style="font-size:13px;color:var(--muted)">{{ $page['address_note'] }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(!empty($page['vk_url']) || !empty($page['telegram_url']))
                    <div class="cr">
                        <div class="cr-icon">
                            <svg width="16" height="16" viewBox="0 0 20 20" fill="none" stroke="var(--gold)" stroke-width="1.5" stroke-linecap="round"><rect x="3" y="3" width="14" height="14" rx="2"/><circle cx="10" cy="10" r="3"/><circle cx="14.5" cy="5.5" r="0.8" fill="var(--gold)" stroke="none"/></svg>
                        </div>
                        <div class="cr-body">
                            <div class="cr-lbl">Социальные сети</div>
                            <div class="cr-val">
                                <div class="social-row">
                                    @if(!empty($page['vk_url']))
                                    <a href="{{ $page['vk_url'] }}" class="soc" target="_blank" rel="noopener">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M15.684 0H8.316C1.592 0 0 1.592 0 8.316v7.368C0 22.408 1.592 24 8.316 24h7.368C22.408 24 24 22.408 24 15.684V8.316C24 1.592 22.408 0 15.684 0zm3.692 17.123h-1.744c-.66 0-.862-.525-2.049-1.714-1.033-1-1.49-1.135-1.744-1.135-.356 0-.458.102-.458.593v1.566c0 .424-.135.678-1.253.678-1.846 0-3.896-1.118-5.335-3.202C5.051 11.565 4.36 9.55 4.36 9.149c0-.254.102-.491.593-.491h1.744c.44 0 .61.203.779.678.863 2.49 2.303 4.675 2.896 4.675.22 0 .322-.102.322-.66V10.98c-.068-1.186-.695-1.287-.695-1.71 0-.203.169-.406.44-.406h2.744c.373 0 .508.203.508.643v3.473c0 .372.169.508.271.508.22 0 .407-.136.813-.542 1.254-1.406 2.151-3.574 2.151-3.574.119-.254.339-.491.779-.491h1.744c.525 0 .644.271.525.643-.22 1.017-2.354 4.031-2.354 4.031-.186.305-.254.44 0 .78.186.254.796.779 1.203 1.253.745.847 1.32 1.558 1.473 2.049.17.474-.085.712-.593.712z"/></svg>
                                        ВКонтакте
                                    </a>
                                    @endif
                                    @if(!empty($page['telegram_url']))
                                    <a href="{{ $page['telegram_url'] }}" class="soc" target="_blank" rel="noopener">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.447 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.12l-6.871 4.326-2.962-.924c-.643-.204-.657-.643.136-.953l11.57-4.461c.537-.194 1.006.131.833.941z"/></svg>
                                        Telegram
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>

            {{-- Реквизиты --}}
            @if(!empty($page['company_name']))
            <div class="contact-block">
                <div class="s-eye">Реквизиты</div>
                <div class="requisites">
                    <div class="req-title">{{ $page['company_name'] }}</div>
                    <div class="req-list">
                        @if(!empty($page['inn']))<div class="req-row"><span class="req-key">ИНН</span><span class="req-val">{{ $page['inn'] }}</span></div>@endif
                        @if(!empty($page['kpp']))<div class="req-row"><span class="req-key">КПП</span><span class="req-val">{{ $page['kpp'] }}</span></div>@endif
                        @if(!empty($page['ogrn']))<div class="req-row"><span class="req-key">ОГРН</span><span class="req-val">{{ $page['ogrn'] }}</span></div>@endif
                        @if(!empty($page['okpo']))<div class="req-row"><span class="req-key">ОКПО</span><span class="req-val">{{ $page['okpo'] }}</span></div>@endif
                        @if(!empty($page['okved']))<div class="req-row"><span class="req-key">ОКВЭД</span><span class="req-val">{{ $page['okved'] }}</span></div>@endif
                    </div>

                    @if(!empty($page['legal_address']))
                    <div class="req-section">Юридический адрес</div>
                    <div class="req-addr">{{ $page['legal_address'] }}</div>
                    @endif

                    @if(!empty($page['actual_address']))
                    <div class="req-section">Фактический адрес</div>
                    <div class="req-addr">{{ $page['actual_address'] }}</div>
                    @endif

                    @if(!empty($page['bank_name']) || !empty($page['bik']))
                    <div class="req-section">Банковские реквизиты</div>
                    <div class="req-list">
                        @if(!empty($page['bank_name']))<div class="req-row"><span class="req-key">Банк</span><span class="req-val" style="font-size:14px">{{ $page['bank_name'] }}</span></div>@endif
                        @if(!empty($page['bik']))<div class="req-row"><span class="req-key">БИК</span><span class="req-val">{{ $page['bik'] }}</span></div>@endif
                        @if(!empty($page['cor_account']))<div class="req-row"><span class="req-key">Кор. счёт №</span><span class="req-val">{{ $page['cor_account'] }}</span></div>@endif
                        @if(!empty($page['settlement_account']))<div class="req-row"><span class="req-key">Расч. счёт №</span><span class="req-val">{{ $page['settlement_account'] }}</span></div>@endif
                    </div>
                    @endif

                    @if(!empty($page['director']))
                    <div class="req-section">Руководство</div>
                    <div class="req-list">
                        <div class="req-row">
                            <span class="req-key">Генеральный директор</span>
                            <span class="req-val">{{ $page['director'] }}</span>
                        </div>
                    </div>
                    @endif

                    @if(!empty($page['download_url']))
                    <a href="{{ $page['download_url'] }}" download class="req-download">
                        <svg width="20" height="20" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="48" height="48" rx="6" fill="#2B579A"/><path d="M28 10H14a2 2 0 0 0-2 2v24a2 2 0 0 0 2 2h20a2 2 0 0 0 2-2V18l-8-8z" fill="#fff" opacity="0.15"/><path d="M28 10v8h8" fill="none" stroke="#fff" stroke-width="1.5" stroke-linejoin="round" opacity="0.6"/><text x="8" y="36" font-family="Arial" font-weight="700" font-size="13" fill="#fff" letter-spacing="-0.5">W</text></svg>
                        Скачать реквизиты в Word
                    </a>
                    @endif
                </div>
            </div>
            @endif

            {{-- Карта --}}


        </div>

        {{-- FORM COLUMN --}}
        <div class="contact-form-col" id="form">
            <div class="contact-form-card">
                <div class="contact-form-eye">{{ $page['form_eyebrow'] ?? 'Обратная связь' }}</div>
                <div class="contact-form-title">{{ $page['form_title'] ?? 'Отправить запрос' }}</div>
                <div class="contact-form-sub">{{ $page['form_subtitle'] ?? 'Ответим в течение одного рабочего дня.' }}</div>

                <form action="{{ route('form.zapros') }}" method="POST" class="mz-form" data-form="zapros" id="contact-form" novalidate>
                    @csrf
                    <x-form.timestamp />
                    <div class="fgrid">
                        <x-form.input name="name"    label="Имя"      placeholder="Иван" />
                        <x-form.input name="surname" label="Фамилия"   placeholder="Иванов" />
                        <x-form.input name="phone"   label="Телефон"   :required="true" type="tel"   placeholder="+7 (___) ___-__-__" class="imask" />
                        <x-form.input name="email"   label="Email"     type="email" placeholder="mail@example.com" />
                        <x-form.textarea name="message" label="Сообщение" placeholder="Ваш вопрос или пожелание..." />
                    </div>
                    <button type="submit" class="btn-primary" style="width:100%;margin-top:14px">Отправить</button>
                    <div class="contact-form-note">* — обязательное поле. Нажимая кнопку, вы соглашаетесь с политикой конфиденциальности.</div>
                </form>
            </div>
        </div>

    </div>

<script>
(function () {
    function getPhoneDigits(input) {
        if (input._mask) return input._mask.unmaskedValue.replace(/\D/g, '');
        if (window.IMask && !input._mask) {
            input._mask = new window.IMask(input, { mask: '+{0}(000)000-00-00' });
            return input._mask.unmaskedValue.replace(/\D/g, '');
        }
        return input.value.replace(/\D/g, '');
    }

    function showError(input, msg) {
        const field = input.closest('.ff');
        input.classList.add('_error');
        let err = field.querySelector('.reg-error-msg');
        if (!err) {
            err = document.createElement('span');
            err.className = 'reg-error-msg';
            field.appendChild(err);
        }
        err.textContent = msg;
        input.addEventListener('input', function handler() {
            input.classList.remove('_error');
            err.remove();
            input.removeEventListener('input', handler);
        });
    }

    function validateForm(form) {
        form.querySelectorAll('._error').forEach(el => el.classList.remove('_error'));
        form.querySelectorAll('.reg-error-msg').forEach(el => el.remove());

        let valid = true;
        form.querySelectorAll('input[required]').forEach(function (input) {
            if (input.type === 'tel') {
                if (getPhoneDigits(input).length < 11) {
                    showError(input, 'Введите телефон');
                    valid = false;
                }
            } else if (input.type === 'email') {
                const val = input.value.trim();
                if (!val) {
                    showError(input, 'Введите email');
                    valid = false;
                } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
                    showError(input, 'Некорректный email');
                    valid = false;
                }
            } else {
                if (!input.value.trim()) {
                    showError(input, 'Обязательное поле');
                    valid = false;
                }
            }
        });
        return valid;
    }

    document.getElementById('contact-form').addEventListener('submit', function (e) {
        if (!validateForm(this)) e.preventDefault();
    });

    const msgArea = document.querySelector('#contact-form textarea[name="message"]');
    if (msgArea) {
        msgArea.addEventListener('input', function () {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    }
})();
</script>

@endsection
