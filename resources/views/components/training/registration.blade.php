@props([
    'priceIndividual' => null,
    'priceLegal'      => null,
    'trainingId'      => null,
])

<div class="reg" id="reg">
    <div class="s-eye">Запись</div>
    <h2 class="s-ttl">Записаться на мероприятие</h2>
    <div class="rtabs">
        <div class="rtab active" onclick="swTab('fiz')">Физическое лицо</div>
        <div class="rtab" onclick="swTab('yur')">Юридическое лицо</div>
    </div>

    <div class="rform active" id="f-fiz">
        @if($priceIndividual)
            <div class="rprice">
                <div class="rprice-n">{{ price($priceIndividual) }}&thinsp;{{ config('site.currency') }}</div>
                <div class="rprice-l">Стоимость<br>для физических лиц</div>
            </div>
        @endif
        <form action="{{ route('training.register') }}" method="POST" novalidate>
            @csrf
            <x-form.timestamp />
            <input type="hidden" name="type" value="fiz">
            @if($trainingId)<input type="hidden" name="training_id" value="{{ $trainingId }}">@endif
            <div class="fgrid">
                <x-form.input name="first_name" label="Имя"     placeholder="Иван" />
                <x-form.input name="last_name"  label="Фамилия" placeholder="Иванов" />
                <x-form.input name="phone" label="Телефон" :required="true" type="tel" placeholder="+7(000)000-00-00" class="imask" />
                <x-form.input name="email" label="Email"   :required="true" type="email" placeholder="mail@example.com" />
                <x-form.textarea name="comment" label="Комментарий" placeholder="Ваш вопрос или пожелание..." />
            </div>
            <div class="fbtns">
                <button type="submit" class="btn-sub">Записаться</button>
                <p class="fnote" style="color: #f2f2f2">* — обязательные поля. Нажимая кнопку, вы соглашаетесь с политикой конфиденциальности.</p>
            </div>
        </form>
    </div>

    <div class="rform" id="f-yur">
        @if($priceLegal)
            <div class="rprice">
                <div class="rprice-n">{{ price($priceLegal) }}&thinsp;{{ config('site.currency') }}</div>
                <div class="rprice-l">Стоимость<br>для юридических лиц</div>
            </div>
        @endif
        <form action="{{ route('training.register') }}" method="POST" novalidate>
            @csrf
            <x-form.timestamp />
            <input type="hidden" name="type" value="yur">
            @if($trainingId)<input type="hidden" name="training_id" value="{{ $trainingId }}">@endif
            <div class="fgrid">
                <x-form.input name="first_name" label="Имя"     placeholder="Иван" />
                <x-form.input name="last_name"  label="Фамилия" placeholder="Иванов" />
                <x-form.input name="phone"    label="Телефон"             :required="true" type="tel"   placeholder="+7(000)000-00-00"      class="imask" />
                <x-form.input name="email"    label="Email"               :required="true" type="email" placeholder="mail@company.ru" />
                <x-form.input name="company"  label="Название организации" :required="true"              placeholder="ООО «Ваша компания»" class="fg-full-input" />
                <x-form.input name="inn"      label="ИНН"                  :required="true"              placeholder="7700000000" />
                <x-form.input name="position" label="Должность"                                          placeholder="Директор по развитию" />
                <x-form.textarea name="comment" label="Комментарий" placeholder="Количество участников, особые пожелания..." />
            </div>
            <div class="fbtns">
                <button type="submit" class="btn-sub">Записаться</button>
                <p class="fnote">* — обязательные поля. Предоплата 50% для бронирования места. НДС не облагается.</p>
            </div>
        </form>
    </div>
</div>

<script>
    function swTab(t) {
        document.querySelectorAll('.rtab').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.rform').forEach(el => el.classList.remove('active'));
        const idx = t === 'fiz' ? 0 : 1;
        document.querySelectorAll('.rtab')[idx].classList.add('active');
        document.getElementById('f-' + t).classList.add('active');
    }
    @if(old('type') === 'yur') swTab('yur'); @endif

    (function () {
        function getPhoneDigits(input) {
            if (input._regMask) return input._regMask.unmaskedValue.replace(/\D/g, '');
            if (window.IMask && !input._regMask) {
                input._regMask = new window.IMask(input, { mask: '+{0}(000)000-00-00' });
                return input._regMask.unmaskedValue.replace(/\D/g, '');
            }
            return input.value.replace(/\D/g, '');
        }

        function showRegError(input, msg) {
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

        function validateRegForm(form) {
            form.querySelectorAll('._error').forEach(el => el.classList.remove('_error'));
            form.querySelectorAll('.reg-error-msg').forEach(el => el.remove());

            let valid = true;
            form.querySelectorAll('input[required]').forEach(function (input) {
                if (input.type === 'tel') {
                    if (getPhoneDigits(input).length < 11) {
                        showRegError(input, 'Введите телефон');
                        valid = false;
                    }
                } else if (input.type === 'email') {
                    const val = input.value.trim();
                    if (!val) {
                        showRegError(input, 'Введите email');
                        valid = false;
                    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
                        showRegError(input, 'Некорректный email');
                        valid = false;
                    }
                } else {
                    if (!input.value.trim()) {
                        showRegError(input, 'Обязательное поле');
                        valid = false;
                    }
                }
            });
            return valid;
        }

        document.querySelectorAll('#reg form').forEach(function (form) {
            form.addEventListener('submit', function (e) {
                if (!validateRegForm(form)) e.preventDefault();
            });
        });
    })();
</script>
