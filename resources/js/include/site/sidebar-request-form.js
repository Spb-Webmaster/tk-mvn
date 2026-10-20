import {validateAgree} from './form-agree.js';

/**
 * Форма запроса в сайдбаре страницы программы (x-form.sidebar-request).
 * Отправляет те же поля, что и модалка «Отправить запрос», на /send-request.
 */
export function sidebarRequestForm() {
    document.querySelectorAll('.sbf').forEach(initForm);
}

function initForm(container) {
    const form      = container.querySelector('.sbf-form');
    const successEl = container.querySelector('.sbf-success');
    if (!form || !successEl) return;

    const csrf       = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    const nameInput  = form.querySelector('input[name="name"]');
    const phoneInput = form.querySelector('input[name="phone"]');

    function showError(input, msg) {
        const field = input.closest('.sbf-field');
        field.classList.add('sbf-field--error');
        const err = document.createElement('span');
        err.className = 'sbf-error-msg';
        err.textContent = msg;
        field.appendChild(err);
        input.addEventListener('input', () => {
            field.classList.remove('sbf-field--error');
            field.querySelector('.sbf-error-msg')?.remove();
        }, { once: true });
    }

    function validate() {
        form.querySelectorAll('.sbf-field--error').forEach(f => f.classList.remove('sbf-field--error'));
        form.querySelectorAll('.sbf-error-msg').forEach(e => e.remove());

        let valid = true;

        if (!nameInput.value.trim()) {
            showError(nameInput, 'Введите имя');
            valid = false;
        }

        if (phoneInput.value.replace(/\D/g, '').length < 11) {
            showError(phoneInput, 'Введите телефон');
            valid = false;
        }

        if (!validateAgree(form)) {
            valid = false;
        }

        return valid;
    }

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        if (!validate()) return;

        const submitBtn = form.querySelector('.sbf-submit');
        const data      = {};
        new FormData(form).forEach((val, key) => { data[key] = val; });

        submitBtn.disabled = true;

        try {
            const response = await fetch('/send-request', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': csrf,
                },
                body: JSON.stringify(data),
            });
            const result = await response.json();
            if (!result.errors) {
                form.style.display = 'none';
                successEl.classList.add('show');
            }
        } catch (err) {
            console.error('Sidebar request form error:', err);
        } finally {
            submitBtn.disabled = false;
        }
    });
}
