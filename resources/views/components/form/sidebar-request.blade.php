{{-- Компактная форма запроса в сайдбаре программы — выводится вместо заглушки расписания --}}
<div class="sbf">
    <form class="sbf-form" novalidate>
        <div class="s-eye">Консультация</div>
        <div class="sbf-title">Для консультации или заказа заполните форму ниже</div>
        <p class="sbf-desc">Мы свяжемся с&nbsp;вами в&nbsp;течение рабочего дня.</p>

        <div class="sbf-field">
            <label for="sbf-name">Имя</label>
            <input type="text" id="sbf-name" name="name" placeholder="Ваше имя" autocomplete="given-name" required>
        </div>
        <div class="sbf-field">
            <label for="sbf-company">Название компании</label>
            <input type="text" id="sbf-company" name="company" placeholder="ООО «Компания»" autocomplete="organization">
        </div>
        <div class="sbf-field">
            <label for="sbf-phone">Телефон</label>
            <input type="tel" id="sbf-phone" name="phone" placeholder="+7(000)000-00-00" autocomplete="tel" class="imask" required>
        </div>

        <x-form.agree />

        <button type="submit" class="sbf-submit">Отправить запрос</button>
    </form>

    <div class="sbf-success">
        <div class="sbf-success__title">Запрос отправлен!</div>
        <p class="sbf-desc">Мы получили вашу заявку и&nbsp;свяжемся с&nbsp;вами в&nbsp;течение рабочего дня.</p>
    </div>
</div>
