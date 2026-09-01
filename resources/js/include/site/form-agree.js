/**
 * Проверка чекбокса «Согласие на обработку персональных данных».
 * Единая функция для всех форм сайта — вёрстка блока задана
 * компонентом resources/views/components/form/agree.blade.php.
 *
 * Возвращает true, если согласие получено или чекбокса в форме нет.
 */
export function validateAgree(form) {
    const wrap = form.querySelector('.form-agree');
    if (!wrap) return true;

    const input = wrap.querySelector('input[type="checkbox"]');
    if (!input) return true;

    wrap.classList.remove('form-agree--error');
    wrap.querySelector('.form-agree-error')?.remove();

    if (input.checked) return true;

    wrap.classList.add('form-agree--error');

    const err = document.createElement('span');
    err.className = 'form-agree-error';
    err.textContent = 'Необходимо согласие на обработку персональных данных';
    wrap.appendChild(err);

    input.addEventListener('change', () => {
        wrap.classList.remove('form-agree--error');
        wrap.querySelector('.form-agree-error')?.remove();
    }, { once: true });

    return false;
}
