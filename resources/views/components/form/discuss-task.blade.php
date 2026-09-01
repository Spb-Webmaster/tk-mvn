<div class="section-eyebrow eyebrow">Связаться с нами</div>
<h2 class="section-title">Обсудим вашу задачу</h2>
<p class="cta-lead">Оставьте заявку — мы свяжемся в течение рабочего дня и подберём оптимальный формат.</p>
<form id="discuss-task-form" class="cta-form" action="{{ route('form.zapros') }}" method="POST" novalidate>
  @csrf
  <x-form.timestamp />
  <div class="cta-field">
    <input type="text" name="name" placeholder="Ваше имя">
  </div>
  <div class="cta-field">
    <input type="tel" name="phone" class="imask" placeholder="Телефон *">
  </div>
  <div class="cta-field full">
    <input type="email" name="email" placeholder="Email">
  </div>
  <div class="mz-select full" data-mz-select>
    <select name="represent">
      <option value="" disabled selected>Вы представляете...</option>
      <option value="company">Компанию (корпоративный запрос)</option>
      <option value="personal">Себя (частное лицо)</option>
    </select>
  </div>
  <div class="cta-field full">
    <textarea name="message" placeholder="Ваш запрос или комментарий" rows="4"></textarea>
  </div>
  <x-form.agree class="full" />
  <button class="b-send" type="submit">Отправить запрос</button>
</form>
<p class="cta-note">* — обязательные поля</p>
