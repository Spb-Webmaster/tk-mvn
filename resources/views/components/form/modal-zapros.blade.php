<div class="mzr-modal">

    <div class="mzr-modal__head">
        <div class="mzr-modal__eyebrow">Обратная связь</div>
        <div class="mzr-modal__title">Отправить запрос</div>
        <button type="button" class="modal-close" aria-label="Закрыть">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                <path d="M2 2l12 12M14 2L2 14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
        </button>
    </div>

    <form class="mzr-modal__body" id="mzr-form" novalidate>

        <div class="mzr-radio-group">
            <div class="mzr-radio-label">Тип клиента</div>
            <div class="mzr-radio-row">
                <input type="radio" name="client_type" id="mzr-type-company" value="company" checked>
                <label for="mzr-type-company">Компания</label>
                <input type="radio" name="client_type" id="mzr-type-person" value="person">
                <label for="mzr-type-person">Частное лицо</label>
            </div>
        </div>

        <div class="mzr-divider"></div>

        <div class="mzr-field-row">
            <div class="mzr-field">
                <label for="mzr-name" class="mzr-label--required">Имя</label>
                <input type="text" id="mzr-name" name="name" placeholder="Василий" autocomplete="given-name" required>
            </div>
            <div class="mzr-field">
                <label for="mzr-phone" class="mzr-label--required">Телефон</label>
                <input type="tel" id="mzr-phone" name="phone" placeholder="+7(000)000-00-00" autocomplete="tel" class="imask" required>
            </div>
        </div>

        <div class="mzr-field">
            <label for="mzr-email">Почта</label>
            <input type="email" id="mzr-email" name="email" placeholder="example@company.ru" autocomplete="email">
        </div>

        <div class="mzr-field">
            <label for="mzr-request">Ваш запрос</label>
            <textarea id="mzr-request" name="request" placeholder="Опишите задачу, программу или формат обучения, который вас интересует…"></textarea>
        </div>

        <div class="mzr-divider"></div>

        <div class="mzr-modal__footer">
            <p class="mzr-modal__hint">Нажимая кнопку, вы соглашаетесь с&nbsp;политикой обработки персональных данных.</p>
            <button type="submit" class="mzr-btn-submit">Отправить</button>
        </div>

    </form>

    <div class="mzr-modal__success" id="mzr-success">
        <div class="mzr-success-icon">
            <svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                <path d="M4 11l5 5L18 6" stroke="#c4963a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div class="mzr-success-title">Запрос отправлен!</div>
        <p class="mzr-success-sub">Мы получили вашу заявку и свяжемся с вами в&nbsp;течение рабочего дня.</p>
    </div>

</div>
