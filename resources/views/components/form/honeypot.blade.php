{{--
    Ловушка для ботов: поле, которого не видит человек, но которое заполняют
    автоматические рассыльщики — они проходят по всем input и вписывают данные.
    Если поле пришло непустым, заявка отклоняется.

    Прячем через position/opacity, а не display:none — часть ботов умеет
    пропускать скрытые display-ем поля. Имя «website» выбрано нарочно
    правдоподобным: такие поля боты заполняют охотнее всего.
    Для скринридеров поле скрыто через aria-hidden, из порядка табуляции
    убрано через tabindex, автозаполнение браузера отключено.
--}}
<div style="position:absolute;left:-9999px;top:auto;width:1px;height:1px;overflow:hidden;" aria-hidden="true">
    <label>
        Не заполняйте это поле
        <input type="text" name="website" value="" tabindex="-1" autocomplete="off">
    </label>
</div>
